<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = Kegiatan::all();
        return view('admin.manageportal.kegiatan.index', compact('kegiatan'));
    }

    public function tambah()
    {
        return view('admin.manageportal.kegiatan.tambah');
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'judul.required' => 'Judul kegiatan wajib diisi.',
            'deskripsi.required' => 'Deskripsi kegiatan wajib diisi.',
            'gambar.required' => 'Gambar kegiatan wajib diunggah.',
            'gambar.image' => 'File yang diunggah harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $path = $request->file('gambar')->store('kegiatan', 'public');

        Kegiatan::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'gambar' => $path
        ]);

        return redirect()->route('admin.manageportal.kegiatan.index')
                         ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function edit(Kegiatan $kegiatan, $id)
    {
        $kegiatan = Kegiatan::find($id);
        return view('admin.manageportal.kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required'
        ], [
            'judul.required' => 'Judul kegiatan wajib diisi.',
            'deskripsi.required' => 'Deskripsi kegiatan wajib diisi.',
        ]);

        if ($request->hasFile('gambar')) {
            if ($kegiatan->gambar) {
                Storage::delete('public/' . $kegiatan->gambar);
            }

            $path = $request->file('gambar')->store('kegiatan', 'public');
            $kegiatan->gambar = $path;
        }

        $kegiatan->judul = $request->judul;
        $kegiatan->deskripsi = $request->deskripsi;
        $kegiatan->save();

        return redirect()->route('admin.manageportal.kegiatan.index')
                         ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $kegiatan = Kegiatan::find($id);
        if (Storage::exists('public/' . $kegiatan->gambar)) {
            Storage::delete('public/' . $kegiatan->gambar);
        }

        $kegiatan->delete();

        return redirect()->route('admin.manageportal.kegiatan.index')
                         ->with('success', 'Kegiatan berhasil dihapus.');
    }
}
