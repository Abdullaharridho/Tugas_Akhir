<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::all();
        return view('admin.kelas.index', compact('kelas'));
    }

    public function tambah()
    {
        return view('admin.kelas.tambah');
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255'
        ]);

        Kelas::create($request->only('nama'));
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        return view('admin.kelas.edit', compact('kelas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255'
        ]);

        $kelas = Kelas::findOrFail($id);
        $kelas->update($request->only('nama'));

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui!');
    }

    public function hapus($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus!');
    }
}