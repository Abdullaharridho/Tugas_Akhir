<?php

namespace App\Http\Controllers;

use App\Models\Datasantri;
use App\Models\TransaksiTabungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TabunganController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');

        $santri = Datasantri::query()
            ->when($query, function ($q) use ($query) {
                $q->where('nis', 'like', "%$query%")
                    ->orWhere('nama', 'like', "%$query%");
            })
            ->get()
            ->map(function ($s) {
                // Hitung saldo & total tabung/ambil
                $tabung = TransaksiTabungan::where('nis', $s->nis)->where('jenis', 'tabung')->sum('jumlah');
                $ambil = TransaksiTabungan::where('nis', $s->nis)->where('jenis', 'ambil')->sum('jumlah');

                $s->saldo = $tabung - $ambil;
                $s->tabung = $tabung;
                $s->ambil = $ambil;

                // Ambil transaksi terakhir
                $s->latestTransaksi = TransaksiTabungan::with('pengurus')
                    ->where('nis', $s->nis)
                    ->orderByDesc('tanggal')
                    ->first();
                return $s;
            });

        return view('admin.tabungan.index', compact('santri'));
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'nis' => 'required|exists:data_santri,nis',
            'tabung' => 'nullable|numeric|min:0',
            'ambil' => 'nullable|numeric|min:0'
        ]);

        $jumlahTabung = $request->tabung ?? 0;
        $jumlahAmbil = $request->ambil ?? 0;

        $idPengurus = Auth::id(); // Ambil ID user yang login

        // Simpan transaksi tabung
        if ($jumlahTabung > 0) {
            TransaksiTabungan::create([
                'nis' => $request->nis,
                'id_pengurus' => $idPengurus,
                'jenis' => 'tabung',
                'jumlah' => $jumlahTabung,
                'tanggal' => now()
            ]);
        }

        // Cek saldo sebelum tarik
        if ($jumlahAmbil > 0) {
            $saldoSaatIni = TransaksiTabungan::where('nis', $request->nis)
                ->where('jenis', 'tabung')->sum('jumlah')
                - TransaksiTabungan::where('nis', $request->nis)
                ->where('jenis', 'ambil')->sum('jumlah');

            if ($jumlahAmbil > $saldoSaatIni) {
                return redirect()->back()->with('error', 'Saldo tidak mencukupi untuk penarikan!');
            }

            // Simpan transaksi ambil
            TransaksiTabungan::create([
                'nis' => $request->nis,
                'id_pengurus' => $idPengurus,
                'jenis' => 'ambil',
                'jumlah' => $jumlahAmbil,
                'tanggal' => now()
            ]);
        }

        return redirect()->back()->with('success', 'Transaksi berhasil dilakukan!');
    }

    public function riwayat($nis)
    {
        $santri = Datasantri::where('nis', $nis)->firstOrFail();
        $transaksi = TransaksiTabungan::where('nis', $nis)
            ->with('pengurus') // load nama pengurus
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.tabungan.riwayat', compact('santri', 'transaksi'));
    }
    public function edit($id)
    {
        $transaksi = TransaksiTabungan::with('pengurus')->findOrFail($id);
        return response()->json($transaksi);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|in:tabung,ambil',
            'jumlah' => 'required|numeric|min:0',
        ]);

        $transaksi = TransaksiTabungan::findOrFail($id);
        $transaksi->update([
            'tanggal' => $request->tanggal,
            'jenis' => $request->jenis,
            'jumlah' => $request->jumlah,
        ]);

        return redirect()->back()->with('success', 'Transaksi berhasil diperbarui.');
    }
    public function destroy($id)
    {
        $transaksi = TransaksiTabungan::findOrFail($id);
        $transaksi->delete();

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus.');
    }
}
