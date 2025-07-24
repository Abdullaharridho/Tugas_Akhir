<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    public function index()
    {
        $kamar = Kamar::all();
        return view('admin.kamar.index', compact('kamar'));
    }

    public function tambah()
    {
        return view('admin.kamar.tambah');
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255'
        ]);

        Kamar::create($request->only('nama'));
        return redirect()->route('kamar.index')->with('success', 'kamar berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kamar = Kamar::findOrFail($id);
        return view('admin.kamar.edit', compact('kamar'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255'
        ]);

        $kamar = Kamar::findOrFail($id);
        $kamar->update($request->only('nama'));

        return redirect()->route('kamar.index')->with('success', 'kamar berhasil diperbarui!');
    }

    public function hapus($id)
    {
        $kamar = Kamar::findOrFail($id);
        $kamar->delete();

        return redirect()->route('kamar.index')->with('success', 'kamar berhasil dihapus!');
    }
}
