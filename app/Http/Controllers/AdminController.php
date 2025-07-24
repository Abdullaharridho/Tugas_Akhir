<?php

namespace App\Http\Controllers;

use App\Models\Datasantri;
use App\Models\Gallery;
use App\Models\Kamar;
use App\Models\Kegiatan;
use App\Models\Kelas;
use App\Models\Perizinan;
use App\Models\Slide;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
{
     $pengurus = User::where('tipeuser', 'admin')->get();
    $totalSantri = Datasantri::count();
    $totalKelas = Kelas::count();
    $totalKamar = Kamar::count();
    $userName = Auth::user()->nama;
    $totalIzin = Perizinan::where('keterangan', 'izin')->count();

    // Ambil jumlah santri per bulan (12 bulan terakhir)
    $santriPerBulan = Datasantri::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->whereYear('created_at', now()->year)
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->pluck('count', 'month')
        ->toArray();

    // Lengkapi semua bulan (Jan–Des) meskipun 0
    $dataBulanan = [];
    for ($i = 1; $i <= 12; $i++) {
        $dataBulanan[] = $santriPerBulan[$i] ?? 0;
    }

    return view('admin.dashboard', compact(
        'totalSantri',
        'totalKelas',
        'totalKamar',
        'totalIzin',
        'dataBulanan',
        'pengurus'
    ));
}
   
}
