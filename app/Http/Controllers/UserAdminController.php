<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAdminController extends Controller
{
    public function index()
    {
        $admins = User::where('tipeuser', 'admin')->get();
        return view('admin.useradmin.index', compact('admins'));
    }

    public function create()
    {
        
        return view('admin.useradmin.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->email !== 'abdullaharridho03@gmail.com') {
            return back()
                ->with('error', 'Akses ditolak: Anda Tidak Memiliki Wewenang Untuk Menambah Pengurus.')
                ->withInput();
        }


        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tipeuser' => 'admin',
        ]);

        return redirect()->route('useradmin.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function edit($id)
    {
        
        $admin = User::findOrFail($id);
        return view('admin.useradmin.edit', compact('admin'));
    }


    public function update(Request $request, $id)
    {
        if (Auth::user()->email !== 'abdullaharridho03@gmail.com') {
            return back()
                ->with('error', 'Akses ditolak: Anda Tidak Memiliki Wewenang Untuk Mengedit Akun')
                ->withInput();
        }

        $admin = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $admin->id,
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
        ]);


        $admin->update([
            'name' => $request->name,
            'email' => $request->email,


        ]);

        return redirect()->route('useradmin.index')->with('success', 'Pengurus berhasil Diperbaharui.');
    }

    public function destroy($id)
    {
        $currentUser = Auth::user();
        if (Auth::user()->email !== 'abdullaharridho03@gmail.com') {
            return back()
                ->with('error', 'Akses ditolak: hanya pengguna tertentu yang diizinkan.')
                ->withInput();
        }

        $admin = User::findOrFail($id);

        // Cegah agar tidak menghapus dirinya sendiri
        if ($currentUser->id == $admin->id) {
            return redirect()->route('useradmin.index')->withErrors(['error' => 'Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        $admin->delete();

        return redirect()->route('useradmin.index')->with('success', 'Admin berhasil dihapus');
    }
    public function showChangePasswordForm()
    {
        return view('admin.useradmin.password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }
    public function resetPassword($id)
    {
        if (Auth::user()->email !== 'abdullaharridho03@gmail.com') {
            return back()->with('error', 'Akses ditolak: hanya pengguna tertentu yang diizinkan.');
        }

        $admin = User::where('tipeuser', 'admin')->findOrFail($id);
        $admin->password = Hash::make('123456789');
        $admin->save();

        return back()->with('success', 'Password berhasil direset.');
    }
}
