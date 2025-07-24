<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index()
    {
        $matapelajaran = MataPelajaran::all();
        return view('admin.mapel.index', compact('matapelajaran'));
    }

    public function tambah()
    {
        return view('admin.mapel.tambah');
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255'
        ]);

        MataPelajaran::create($request->all());
        return redirect()->route('mapel.index')->with('success', 'Mata Pelajaran berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $matapelajaran = MataPelajaran::findOrFail($id);
        return view('admin.mapel.edit', compact('matapelajaran'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255'
        ]);

        $matapelajaran = MataPelajaran::findOrFail($id);
        $matapelajaran->update($request->only('nama'));

        return redirect()->route('mapel.index')->with('success', 'Kelas berhasil diperbarui!');
    }

    public function hapus($id)
    {
        $matapelajaran = MataPelajaran::findOrFail($id);
        $matapelajaran->delete();

        return redirect()->route('mapel.index')->with('success', 'Kelas berhasil dihapus!');
    }
}
