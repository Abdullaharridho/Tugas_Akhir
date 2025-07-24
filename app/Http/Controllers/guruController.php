<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Datasantri;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Pelanggaran;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class guruController extends Controller
{
   public function tampil()
{
    $daftarGuru = User::where('tipeuser', 'guru')
    ->with('mapel') // ambil relasi mapel
    ->get();
    $tanggalHariIni = now()->toDateString();
    $jumlahAbsensiHariIni = Absensi::whereDate('created_at', $tanggalHariIni)
        ->distinct(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d %H:%i')"))
        ->count();

    $totalKelas = Kelas::count();
    $mapel = auth()->user()->mapel()->pluck('nama')->toArray();

    // Step 1: Ambil data absensi per bulan (berdasarkan created_at, unik per tanggal+jam-menit)
    $absensiBulananRaw = Absensi::select(
            DB::raw("MONTH(created_at) as bulan"),
            DB::raw("COUNT(DISTINCT DATE_FORMAT(created_at, '%Y-%m-%d %H:%i')) as total")
        )
        ->whereYear('created_at', now()->year)
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get()
        ->keyBy('bulan');

    // Step 2: Buat daftar bulan lengkap Januari - Desember
    $bulanList = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
        7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
    ];

    // Step 3: Gabungkan data — isi 0 jika tidak ada data
    $labels = [];
    $data = [];

    foreach ($bulanList as $nomor => $nama) {
        $labels[] = "$nama " . now()->year;
        $data[] = $absensiBulananRaw[$nomor]->total ?? 0;
    }

    return view('userguru.tampil', compact('jumlahAbsensiHariIni', 'totalKelas', 'mapel', 'labels', 'data','daftarGuru'));
}
    public function index()
    {
        $rekapAbsensi = Absensi::with(['kelas', 'mapel', 'guru'])
            ->selectRaw('tanggal, id_kelas, id_mapel, id_guru,
        COUNT(CASE WHEN status = "Hadir" THEN 1 END) as hadir,
        COUNT(*) as total_siswa')
            ->groupBy('tanggal', 'id_kelas', 'id_mapel', 'id_guru')
            ->get();

        return view('userguru.index', compact('rekapAbsensi'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        $mapel = auth()->user()->mapel; // relasi many-to-many

        return view('userguru.tambah', compact('kelas', 'mapel'));
    }

    public function getSantriByKelas($kelasId)
    {
        $santri = Datasantri::where('kelas', $kelasId)->get(['nis', 'nama']);
        return response()->json($santri);
    }

    public function store(Request $request)
    {
        foreach ($request->nis as $index => $nis) {
            $status = $request->status[$nis] ?? 'Hadir';

            Absensi::create([
                'tanggal' => $request->tanggal,
                'nis' => $nis,
                'id_guru' => auth()->id(),
                'id_mapel' => $request->mapel_id,
                'id_kelas' => $request->kelas_id,
                'status' => $status,
            ]);

            if ($status === 'Alpha') {
                $jumlahRingan = Pelanggaran::where('nis', $nis)->where('Kategori', 'ringan')->count();
                $kategori = 'ringan';

                if ($jumlahRingan + 1 > 3) {
                    $jumlahSedang = Pelanggaran::where('nis', $nis)->where('Kategori', 'sedang')->count();
                    $kategori = $jumlahSedang + 1 > 3 ? 'berat' : 'sedang';
                }

                Pelanggaran::create([
                    'nis' => $nis,
                    'Kategori' => $kategori,
                    'keterangan' => 'Tidak menghadiri Kelas Madin Pada Tanggal ' . $request->tanggal,
                    'tindakan' => '-',
                    'id_pengurus' => auth()->id(),
                ]);
            }
        }

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil disimpan!');
    }

    public function edit(Request $request)
    {
        $tanggal = $request->tanggal;
        $kelasId = $request->kelas;   // id_kelas
        $mapelId = $request->mapel;   // id_mapel

        $absensi = Absensi::with('guru')->where([
            ['tanggal', '=', $tanggal],
            ['id_kelas', '=', $kelasId],
            ['id_mapel', '=', $mapelId],
        ])->get();

        $nisList = $absensi->pluck('nis')->toArray();

        $santriMap = Datasantri::whereIn('nis', $nisList)->pluck('nama', 'nis');

        return view('userguru.edit', [
            'absensi' => $absensi,
            'tanggal' => $tanggal,
            'kelas'   => $kelasId,
            'mapel'   => $mapelId,
            'santriMap' => $santriMap,
        ]);
    }

    public function update(Request $request)
    {
        foreach ($request->nis as $nis) {
            $absensi = Absensi::where('tanggal', $request->tanggal)
                ->where('id_kelas', $request->kelas)
                ->where('id_mapel', $request->mapel)
                ->where('nis', $nis)
                ->first();

            $statusSebelumnya = $absensi ? $absensi->status : 'Hadir';
            $statusBaru = $request->status[$nis] ?? 'Hadir';

            Absensi::where('tanggal', $request->tanggal)
                ->where('id_kelas', $request->kelas)
                ->where('id_mapel', $request->mapel)
                ->where('nis', $nis)
                ->update(['status' => $statusBaru]);

            if ($statusSebelumnya === 'Hadir' && $statusBaru === 'Alpha') {
                $jumlahRingan = Pelanggaran::where('nis', $nis)->where('Kategori', 'ringan')->count();
                $kategori = 'ringan';

                if ($jumlahRingan + 1 > 3) {
                    $jumlahSedang = Pelanggaran::where('nis', $nis)->where('Kategori', 'sedang')->count();
                    $kategori = $jumlahSedang + 1 > 3 ? 'berat' : 'sedang';
                }

                Pelanggaran::create([
                    'nis' => $nis,
                    'Kategori' => $kategori,
                    'keterangan' => 'Tidak menghadiri Kelas Madin Pada Tanggal ' . $request->tanggal,
                    'tindakan' => '-',
                    'id_pengurus' => auth()->id(),
                ]);
            }

            if ($statusSebelumnya === 'Alpha' && $statusBaru === 'Hadir') {
                $pelanggaranTerakhir = Pelanggaran::where('nis', $nis)
                    ->where('keterangan', 'like', 'Tidak menghadiri Kelas%')
                    ->latest()
                    ->first();

                if ($pelanggaranTerakhir) {
                    $pelanggaranTerakhir->delete();
                }
            }
        }

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil diperbarui!');
    }

    public function formPassword()
    {
        return view('userguru.password');
    }

    public function ubahPassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama salah']);
        }

        $user->password = Hash::make($request->password_baru);
        $user->save();

        return redirect()->route('userguru.tampil')->with('success', 'Password berhasil diubah');
    }
}
