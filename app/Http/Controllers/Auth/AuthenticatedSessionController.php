<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Datasantri;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'identifier' => ['required'],
            'password' => ['required'],
        ]);

        $loginField = filter_var($credentials['identifier'], FILTER_VALIDATE_EMAIL) ? 'email' : 'email';

        if (Auth::attempt([$loginField => $credentials['identifier'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            switch (Auth::user()->tipeuser) {
                case 'admin':
                    return redirect()->to('/admin/dashboard')->with('success', 'Login berhasil sebagai admin!');
                case 'guru':
                    return redirect()->to('/guru/dashboard')->with('success', 'Login berhasil sebagai guru!');
                default:
                    return redirect()->to('/user/dashboard')->with('success', 'Login berhasil!');
            }
        }

        // Kirim pesan error via session agar modal tampil
        return back()->with('error', 'NIS atau password salah.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
