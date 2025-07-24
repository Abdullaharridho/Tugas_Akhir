<?php

namespace App\Http\Controllers;

use App\Models\Datasantri;
use Illuminate\Http\Request;
use App\Models\Perizinan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PerizinanController extends Controller
{
    // Menampilkan daftar perizinan
    public function tampil(Request $request)
    {
        $search = $request->input('search');

        // Ambil semua santri yang sesuai pencarian
        $santri = Datasantri::when($search, function ($query, $search) {
            $query->where('nis', 'like', "%$search%")
                ->orWhere('nama', 'like', "%$search%");
        })->get();

        // Ambil perizinan terbaru untuk setiap santri berdasarkan NIS
        $perizinan = Perizinan::orderByDesc('tanggal')
            ->get()
            ->keyBy('nis');

        return view('admin.perizinan.index', compact('santri', 'perizinan'));
    }

    // Menampilkan form tambah data
    public function tambah(Request $request)
    {
        $nis = $request->query('nis');
        $santri = $nis ? Datasantri::where('nis', $nis)->first() : null;

        return view('admin.perizinan.tambah', [
            'nis' => $nis,
            'santri' => $santri
        ]);
    }

    // Menyimpan data perizinan
    public function simpan(Request $request)
    {
        // Validasi terlebih dahulu
        $validated = $request->validate([
            'nis' => 'required|string|max:20',
            'jenis_keterangan' => 'required|in:izin,sakit',
            'alasan_izin' => 'required_if:jenis_keterangan,izin|nullable|string|max:255',
            'tanggal' => 'required|date',
            'tanggal_kembali' => [
                'required_if:jenis_keterangan,izin',
                'date',
                'after_or_equal:tanggal',
                'before_or_equal:' . now()->addDays(7)->toDateString(),
            ],
        ]);

        // Gabungkan keterangan
        $keteranganGabung = $validated['jenis_keterangan'] === 'izin'
            ? 'izin, ' . $validated['alasan_izin']
            : 'sakit';

        $izin = Perizinan::create([
            'nis' => $validated['nis'],
            'tanggal' => $validated['tanggal'],
            'keterangan' => $keteranganGabung,
            'tanggal_kembali' => $validated['tanggal_kembali'],
            'id_pengurus' => Auth::id(),
            'statuspesan' => 'izin', // benar sesuai pilihan
        ]);

        return redirect()->route('perizinan.tampil')->with([
            'success' => 'Data perizinan berhasil ditambahkan.',
            'cetak_id' => $izin->id
        ]);
    }
    // Menampilkan form edit
    public function edit($id)
    {
        $perizinan = Perizinan::with('santri')->findOrFail($id);
        return view('admin.perizinan.edit', compact('perizinan'));
    }

    // Update data perizinan
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nis' => 'required|string|max:20',
            'jenis_keterangan' => 'required|in:izin,sakit',
            'alasan_izin' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
            'tanggal_kembali' => 'required|date',
        ]);

        $tanggal = Carbon::parse($request->tanggal);
        $tanggalKembali = Carbon::parse($request->tanggal_kembali);

        if ($tanggalKembali->gt($tanggal->copy()->addDays(7))) {
            return back()->withErrors(['tanggal_kembali' => 'Tanggal kembali tidak boleh lebih dari 7 hari dari tanggal izin.'])->withInput();
        }

        $keterangan = $request->jenis_keterangan === 'izin'
            ? 'izin, ' . $request->alasan_izin
            : 'sakit';

        $izin = Perizinan::findOrFail($id);
        $izin->update([
            'nis' => $request->nis,
            'nama' => $request->nama ?? '',
            'tanggal' => $request->tanggal,
            'tanggal_kembali' => $request->tanggal_kembali,
            'keterangan' => $keterangan,
        ]);

        return redirect()->route('perizinan.tampil')->with('success', 'Data perizinan berhasil diperbarui.');
    }


    // Ubah status jadi kembali
    public function kembali($id)
    {
        $perizinan = Perizinan::findOrFail($id);
        $perizinan->update(['statuspesan' => 'kembali']);

        return redirect()->route('perizinan.tampil')->with('success', 'Status perizinan telah diperbarui menjadi kembali.');
    }

    // Menampilkan surat izin
    public function getsurat($id)
    {
        $izin = Perizinan::with('santri')->findOrFail($id);
        return view('admin.perizinan.surat', compact('izin'));
    }
    public function tandaiKembali($id)
    {
        $perizinan = Perizinan::findOrFail($id);

        $tanggalKembali = Carbon::parse($perizinan->tanggal_kembali);
        $tanggalHariIni = Carbon::today();

        // Cek apakah kembali tepat waktu atau terlambat
        $status = $tanggalHariIni->gt($tanggalKembali) ? 'terlambat' : 'kembali';

        $perizinan->update([
            'statuspesan' => $status,
        ]);

        return redirect()->route('perizinan.tampil')->with('success', 'Status perizinan telah diperbarui menjadi ' . ucfirst($status) . '.');
    }
    public function hapus($id)
    {
        $perizinan = Perizinan::findOrFail($id);
        $perizinan->delete();

        return redirect()->back()->with('success', 'Perizinan Santri Berhasil Di Hapus.');
    }
    public function riwayat($nis)
    {
        $santri = Datasantri::where('nis', $nis)->firstOrFail();

        $perizinan = $santri->perizinan()->orderBy('tanggal', 'desc')->get();

        return view('admin.perizinan.riwayat', [
            'santri' => $santri,
            'perizinan' => $perizinan,
        ]);
    }
    public function suratTerlambat($id)
{
    $izin = Perizinan::with('santri')->findOrFail($id);

    if ($izin->statuspesan !== 'terlambat') {
        abort(403, 'Surat keterlambatan hanya bisa dicetak untuk status terlambat.');
    }

    return view('admin.perizinan.surat_terlambat', compact('izin'));
}
}
