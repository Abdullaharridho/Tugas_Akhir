<?php

namespace App\Http\Controllers;

use App\Models\Pelanggaran;
use App\Models\Perizinan;
use App\Models\TransaksiTabungan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $nis = Auth::user()->email;

        if (!$nis) {
            abort(403, 'NIS tidak ditemukan');
        }
        $keteranganAbsensi = Pelanggaran::where('nis', $nis)
            ->where('keterangan', 'like', '%Tidak menghadiri Kelas%')
            ->get(['keterangan', 'tindakan', 'created_at']);
        $tahun = now()->year;
        $dataGabungan = TransaksiTabungan::selectRaw("
        DATE_FORMAT(created_at, '%Y-%m') as bulan,
        SUM(CASE WHEN jenis = 'tabung' THEN jumlah ELSE 0 END) as total_tabung,
        SUM(CASE WHEN jenis = 'ambil' THEN jumlah ELSE 0 END) as total_ambil
    ")
            ->where('nis', $nis)
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->get()
            ->keyBy('bulan');
        $grafikBulan = collect(range(1, 12))->map(function ($bulan) use ($dataGabungan, $tahun) {
            $key = sprintf('%04d-%02d', $tahun, $bulan);
            $tanggal = Carbon::createFromDate($tahun, $bulan, 1);

            $data = $dataGabungan->get($key);

            return (object)[
                'bulan' => $tanggal->translatedFormat('M Y'),
                'total_tabung' => $data->total_tabung ?? 0,
                'total_ambil' => $data->total_ambil ?? 0,
            ];
        });
        // Ambil keterangan pelanggaran berdasarkan kategori
        $keteranganRingan = Pelanggaran::where('nis', $nis)
            ->where('kategori', 'ringan')
            ->get(['keterangan', 'tindakan']);

        $keteranganSedang = Pelanggaran::where('nis', $nis)
            ->where('kategori', 'sedang')
            ->get(['keterangan', 'tindakan']);

        $keteranganBerat = Pelanggaran::where('nis', $nis)
            ->where('kategori', 'berat')
            ->get(['keterangan', 'tindakan']);

        // Hitung jumlah pelanggaran tiap kategori
        $pelanggaran = (object)[
            'ringan' => Pelanggaran::where('nis', $nis)->where('kategori', 'ringan')->count(),
            'sedang' => Pelanggaran::where('nis', $nis)->where('kategori', 'sedang')->count(),
            'berat' => Pelanggaran::where('nis', $nis)->where('kategori', 'berat')->count(),
        ];

        // Hitung total tabungan berdasarkan transaksi (jenis: tabung/ambil)
        $totalTabung = TransaksiTabungan::where('nis', $nis)
            ->where('jenis', 'tabung')
            ->sum('jumlah');

        $totalAmbil = TransaksiTabungan::where('nis', $nis)
            ->where('jenis', 'ambil')
            ->sum('jumlah');

        $totalTabungan = $totalTabung - $totalAmbil;

        // Ambil semua riwayat transaksi (ada nama_pengurus)
        $transaksi = TransaksiTabungan::where('nis', $nis)
            ->orderBy('tanggal', 'desc')
            ->get();
        $perizinanTerbaru = Perizinan::with('pengurus')
            ->where('nis', $nis)
            ->orderByDesc('tanggal')
            ->first();

        $riwayatPerizinan = Perizinan::with('pengurus')
            ->where('nis', $nis)
            ->orderByDesc('tanggal')
            ->get();

        return view('user.dashboard', compact(
            'pelanggaran',
            'totalTabungan',
            'transaksi',
            'keteranganRingan',
            'keteranganSedang',
            'keteranganBerat',
            'grafikBulan',
            'keteranganAbsensi',
            'perizinanTerbaru',
            'riwayatPerizinan',
        ));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah.'])->withInput();
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}
