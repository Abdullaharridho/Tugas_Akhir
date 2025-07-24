<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SlideController extends Controller
{
    public function tampil()
    {
        $slides = Slide::all();
        return view('admin.manageportal.index', compact('slides'));
    }

    public function tambah()
    {
        return view('admin.manageportal.tambah');
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'nama.required' => 'Nama slide wajib diisi.',
            'gambar.required' => 'Gambar wajib diunggah.',
            'gambar.image' => 'File yang diunggah harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $path = $request->file('gambar')->store('slides', 'public');
        Slide::create(['nama' => $request->nama, 'gambar' => $path]);

        return redirect()->route('admin.manageportal.index')->with('success', 'Slide berhasil ditambahkan.');
    }

    public function edit(Slide $slide, $id)
    {
        $slide = Slide::find($id);
        return view('admin.manageportal.edit', compact('slide'));
    }

    public function update(Request $request, $id)
    {
        $slide = Slide::findOrFail($id); // Ambil data berdasarkan ID

        $request->validate([
            'nama' => 'required',
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($slide->gambar) {
                Storage::delete('public/' . $slide->gambar);
            }

            // Simpan gambar baru
            $path = $request->file('gambar')->store('slide', 'public');
            $slide->gambar = $path;
        }

        // Update data slide
        $slide->nama = $request->nama;
        $slide->save();

        return redirect()->route('admin.manageportal.index')->with('success', 'slide berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $slide = Slide::find($id);
        if (Storage::exists('public/' . $slide->gambar)) {
            Storage::delete('public/' . $slide->gambar);
        }

        $slide->delete();

        return redirect()->route('admin.manageportal.index')->with('success', 'slide berhasil dihapus.');
    }
}
