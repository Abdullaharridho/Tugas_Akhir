<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $gallery = Gallery::all();
        return view('admin.manageportal.gallery.index', compact('gallery'));
    }

    public function tambah()
    {
        return view('admin.manageportal.gallery.tambah');
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'nama.required' => 'Nama gallery wajib diisi.',
            'deskripsi.required' => 'Deskripsi gallery wajib diisi.',
            'gambar.required' => 'Gambar wajib diunggah.',
            'gambar.image' => 'File yang diunggah harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $path = $request->file('gambar')->store('gallery', 'public');

        Gallery::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'gambar' => $path
        ]);

        return redirect()->route('admin.manageportal.gallery.index')
            ->with('success', 'Gallery berhasil ditambahkan.');
    }

    public function edit(Gallery $gallery, $id)
    {
        $gallery = Gallery::find($id);
        return view('admin.manageportal.gallery.edit', compact('gallery'));
    }

    public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'deskripsi' => 'required',
        ], [
            'nama.required' => 'Nama gallery wajib diisi.',
            'deskripsi.required' => 'Deskripsi gallery wajib diisi.',
        ]);

        if ($request->hasFile('gambar')) {
            if ($gallery->gambar) {
                Storage::delete('public/' . $gallery->gambar);
            }

            $path = $request->file('gambar')->store('gallery', 'public');
            $gallery->gambar = $path;
        }

        $gallery->nama = $request->nama;
        $gallery->deskripsi = $request->deskripsi;
        $gallery->save();

        return redirect()->route('admin.manageportal.gallery.index')->with('success', 'Gallery berhasil diperbarui.');
    }



    public function hapus($id)
    {
        $gallery = Gallery::find($id);
        if (Storage::exists('public/' . $gallery->gambar)) {
            Storage::delete('public/' . $gallery->gambar);
        }

        $gallery->delete();

        return redirect()->route('admin.manageportal.gallery.index')->with('success', 'Gallery berhasil dihapus.');
    }
}
