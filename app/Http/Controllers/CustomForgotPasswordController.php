<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordOtp;
use App\Services\BrevoMailer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CustomForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.costum-forgot');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if (!$user) return back()->withErrors(['email' => 'Email tidak ditemukan.']);

        $otp = rand(100000, 999999);
        User::updateOrCreate(
            ['email' => $request->email],
            [
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(10),
            ]
        );

        // Kirim email via Brevo
        $subject = 'Reset Password OTP';
        $htmlContent = "<p>Halo <strong>{$user->name}</strong>,</p>
                        <p>Kode OTP Anda untuk reset password adalah: <strong>$otp</strong></p>
                        <p>Kode ini berlaku selama 10 menit.</p>";

        $success = BrevoMailer::send($request->email, $subject, $htmlContent);

        if (!$success) {
            return back()->withErrors(['email' => 'Gagal mengirim email. Coba lagi nanti.']);
        }

        session(['otp_email' => $request->email]);

        return redirect()->route('password.otp.verify.form')->with('success', 'OTP berhasil dikirim.');
    }

    public function showVerifyForm()
    {
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);
        $email = session('otp_email');
        $record = User::where('email', $email)->first();

        if (!$record || $record->otp != $request->otp || Carbon::now()->greaterThan($record->expires_at)) {
            return back()->withErrors(['otp' => 'Kode OTP salah atau sudah kadaluarsa.']);
        }

        session(['reset_email' => $email]);
        return redirect()->route('password.reset.form');
    }

    public function showResetForm()
    {
        if (!session('reset_email')) abort(403);
        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:6'
        ]);

        $email = session('reset_email');
        $user = User::where('email', $email)->first();

        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));

        // Kosongkan kolom otp dan expires_at
        $user->otp = null;
        $user->expires_at = null;

        $user->save();

        session()->forget(['reset_email', 'otp_email']);

        return redirect('/login')->with('success', 'Password berhasil direset.');
    }
}
