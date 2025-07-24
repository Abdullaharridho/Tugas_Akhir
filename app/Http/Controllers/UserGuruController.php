<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserGuruController extends Controller
{
    // Tampilkan daftar guru
    public function guru()
    {
        $gurus = User::where('tipeuser', 'guru')->with('mapel')->get();
        $mapels = MataPelajaran::all();
        return view('admin.guru.index', compact('gurus', 'mapels'));
    }

    // FORM CREATE
    public function create()
    {
        $mapels = MataPelajaran::all();
        return view('guru.create', compact('mapels'));
    }

    // STORE - Simpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:6',
            'mapel_ids'  => 'required|array|min:1',
            'mapel_ids.*' => 'exists:matapelajaran,id',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'mapel_ids.required' => 'Pilih minimal satu mata pelajaran.',
            'mapel_ids.*.exists' => 'Mata pelajaran yang dipilih tidak valid.',
        ]);

        $guru = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'tipeuser' => 'guru',
        ]);

        $guru->mapel()->sync($request->mapel_ids);

        return redirect()->route('guru.index')->with('success', 'Guru berhasil ditambahkan.');
    }

    // FORM EDIT
    public function edit($id)
    {
        $guru = User::where('tipeuser', 'guru')->with('mapel')->findOrFail($id);
        $mapels = MataPelajaran::all();
        return view('guru.edit', compact('guru', 'mapels'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $guru = User::where('tipeuser', 'guru')->findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $id,
            'mapel_ids'   => 'required|array|min:1',
            'mapel_ids.*' => 'exists:matapelajaran,id',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 255 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',

            'mapel_ids.required' => 'Minimal pilih satu mata pelajaran.',
            'mapel_ids.array' => 'Format mata pelajaran tidak sesuai.',
            'mapel_ids.min' => 'Minimal pilih satu mata pelajaran.',
            'mapel_ids.*.exists' => 'Mata pelajaran yang dipilih tidak valid.',
        ]);

        $guru->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        $guru->mapel()->sync($request->mapel_ids);

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $guru = User::where('tipeuser', 'guru')->findOrFail($id);
        $guru->delete();

        return redirect()->route('guru.index')->with('success', 'Guru berhasil dihapus.');
    }
}
