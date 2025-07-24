<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Datasantri;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Pelanggaran;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index()
    {
        $rekapAbsensi = Absensi::selectRaw('tanggal, kelas, mapel, 
        COUNT(CASE WHEN status = "Hadir" THEN 1 END) as hadir, 
        COUNT(*) as total_siswa')
            ->groupBy('tanggal', 'kelas', 'mapel')
            ->get();


        return view('admin.absensi.index', compact('rekapAbsensi'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        $mapel = MataPelajaran::all();
        return view('admin.absensi.tambah', compact('kelas', 'mapel'));
    }

    public function getSantriByKelas($kelas)
    {
        $santri = DataSantri::where('kelas', $kelas)->get(['nis', 'nama']);
        return response()->json($santri);
    }

    public function store(Request $request)
    {
        foreach ($request->nis as $index => $nis) {
            $status = $request->status[$nis] ?? 'Hadir'; // Default ke Hadir jika tidak dipilih

            Absensi::create([
                'tanggal' => $request->tanggal,
                'nis' => $nis,
                'nama' => $request->nama[$index],
                'mapel' => MataPelajaran::find($request->mapel_id)->nama,
                'kelas' => Kelas::find($request->kelas_id)->nama,
                'status' => $status,

            ]);

            // Jika Alpha, tambahkan pelanggaran ringan
            if ($status === 'Alpha') {
            $last = Pelanggaran::where('nisn', $nis)->latest()->first();

            $ringan = ($last->ringan ?? 0) + 1;
            $sedang = $last->sedang ?? 0;
            $berat = $last->berat ?? 0;

            // Eskalasi jika lebih dari 2 Alpha
            if ($ringan > 2) {
                $ringan = 0;
                $sedang += 1;
            }

            Pelanggaran::create([
                'nisn' => $nis,
                'nama' => $request->nama[$index],
                'ringan' => $ringan,
                'sedang' => $sedang,
                'berat' => $berat,
                'keterangan' => 'Alpha pada absensi ' . $request->tanggal . $request->mapel,
                'tindakan' => 'Belum Ditindak',
                'statuspesan' => 'Belum',
            ]);
        }
        }

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil disimpan!');
    }


    public function edit($tanggal, $kelas, $mapel)
    {
        $absensi = Absensi::where('tanggal', $tanggal)
            ->where('kelas', $kelas)
            ->where('mapel', $mapel)
            ->get();

        return view('admin.absensi.edit', compact('absensi', 'tanggal', 'kelas', 'mapel'));
    }
    public function update(Request $request)
    {
        foreach ($request->nis as $nis) {
            $absensi = Absensi::where('tanggal', $request->tanggal)
                ->where('kelas', $request->kelas)
                ->where('mapel', $request->mapel)
                ->where('nis', $nis)
                ->first();

            $statusSebelumnya = $absensi ? $absensi->status : 'Hadir';
            $statusBaru = $request->status[$nis] ?? 'Hadir';

            // Update data absensi
            Absensi::where('tanggal', $request->tanggal)
                ->where('kelas', $request->kelas)
                ->where('mapel', $request->mapel)
                ->where('nis', $nis)
                ->update(['status' => $statusBaru]);

            // Koreksi pelanggaran karena Alpha dikoreksi jadi Hadir
            if ($statusSebelumnya === 'Alpha' && $statusBaru === 'Hadir') {
                $riwayat = Pelanggaran::where('nisn', $nis)->latest()->first();
                $ringan = $riwayat->ringan ?? 0;
                $sedang = $riwayat->sedang ?? 0;
                $berat = $riwayat->berat ?? 0;

                // Jika sebelumnya baru saja terjadi eskalasi
                if ($ringan === 0 && $sedang > 0) {
                    $ringan = 2; // rollback 1 alpha dari 3 jadi 2
                    $sedang -= 1;
                } else {
                    $ringan = max($ringan - 1, 0);
                }

                Pelanggaran::create([
                    'nisn' => $nis,
                    'nama' => $riwayat->nama ?? 'Tidak Diketahui',
                    'ringan' => $ringan,
                    'sedang' => $sedang,
                    'berat' => $berat,
                    'keterangan' => 'Koreksi pelanggaran karena absensi diubah ke Hadir',
                    'tindakan' => '-'
                ]);
            }
        }

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil diperbarui!');
    }
}
