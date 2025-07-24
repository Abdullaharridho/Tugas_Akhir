<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Kegiatan;
use App\Models\Slide;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    function login()
    {
        return view('auth.login');
    }
    function gallery()
    {
        $galleries = Gallery::all();
        return view('portal.gallery',compact('galleries'));
    }
    function kegiatan()
    {
        $kegiatan = Kegiatan::all();
        return view('portal.kegiatan',compact('kegiatan'));
    }

    public function home()
    {
        $slides = Slide::all(); // Ambil semua data dari tabel slides
        $galleries = Gallery::all(); 
        $kegiatan = Kegiatan::all(); // Ambil semua data dari tabel kegiatan

        return view('portal.home', compact('slides', 'galleries', 'kegiatan'));
    }
   
}
