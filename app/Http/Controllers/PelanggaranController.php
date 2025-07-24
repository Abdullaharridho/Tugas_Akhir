<?php

namespace App\Http\Controllers;

use App\Models\Datasantri;
use App\Models\Pelanggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PelanggaranController extends Controller
{
    public function index(Request $request)
    {
        $pelanggarans = Pelanggaran::with(['santri', 'pengurus'])->get();
        $query = $request->input('search');

        $santri = Datasantri::query()
            ->when($query, function ($q) use ($query) {
                return $q->where('nis', 'like', "%$query%")
                    ->orWhere('nama', 'like', "%$query%");
            })
            ->get();

        $pelanggaranGrouped = Pelanggaran::all()->groupBy('nis');

        $latestPelanggaran = Pelanggaran::select('pelanggaran.*')
            ->join(DB::raw('(SELECT nis, MAX(created_at) as latest FROM pelanggaran GROUP BY nis) as latest_data'), function ($join) {
                $join->on('pelanggaran.nis', '=', 'latest_data.nis')
                    ->on('pelanggaran.created_at', '=', 'latest_data.latest');
            })
            ->get()
            ->groupBy('nis');

        return view('admin.pelanggaran.index', compact('santri', 'pelanggaranGrouped', 'latestPelanggaran'));
    }

    public function tambah(Request $request)
    {
        $nis = $request->query('nis');
        return view('admin.pelanggaran.tambah', compact('nis'));
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'nis' => 'required|exists:data_santri,nis',
            'kategori' => 'required|in:ringan,sedang,berat',
            'keterangan' => 'required',
            'tindakan' => 'required',
        ]);

        $idPengurus = Auth::id();

        // Hitung jumlah pelanggaran berat sebelumnya
        $jumlahBerat = Pelanggaran::where('nis', $request->nis)
            ->where('kategori', 'berat')
            ->count();

        $statuspesan = 'Belum'; // default
        if ($request->kategori === 'berat') {
            $jumlahBerat++; // tambahkan yang baru akan disimpan ini

            if ($jumlahBerat === 2) {
                $statuspesan = 'Sudah';
            } elseif ($jumlahBerat >= 3) {
                $statuspesan = 'Sudah1';
            }
        }

        Pelanggaran::create([
            'nis' => $request->nis,
            'kategori' => $request->kategori, // perhatikan huruf kecil
            'keterangan' => $request->keterangan,
            'tindakan' => $request->tindakan,
            'id_pengurus' => $idPengurus,
            'statuspesan' => $statuspesan,
        ]);

        return redirect()->route('pelanggaran.index')->with('success', 'Pelanggaran berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $pelanggaran = Pelanggaran::findOrFail($id);
        return view('admin.pelanggaran.edit', compact('pelanggaran'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nis' => 'required|exists:data_santri,nis',
            'kategori' => 'required|in:ringan,sedang,berat',
            'keterangan' => 'required|string',
            'tindakan' => 'required|string',
        ]);

        $pelanggaran = Pelanggaran::findOrFail($id);
        $pelanggaran->update([
            'nis' => $request->nis,
            'kategori' => $request->kategori,
            'keterangan' => $request->keterangan,
            'tindakan' => $request->tindakan,
            // statuspesan tidak diubah saat update manual
        ]);

        return redirect()->route('pelanggaran.riwayat', $request->nis)->with('success', 'Data berhasil diperbarui!');
    }

    public function hapus($id)
    {
        Pelanggaran::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }

    public function updateTindakan(Request $request, $id)
    {
        $request->validate([
            'tindakan' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $pelanggaran = Pelanggaran::findOrFail($id);
        $pelanggaran->tindakan = $request->tindakan;
        $pelanggaran->keterangan = $request->keterangan;
        $pelanggaran->save();

        return redirect()->back()->with('success', 'Tindakan khusus berhasil diperbarui.');
    }
    public function riwayat($nis)
    {
        $santri = Datasantri::where('nis', $nis)->firstOrFail();
        $riwayat = Pelanggaran::where('nis', $nis)->orderBy('created_at', 'desc')->with('pengurus')->get();

        return view('admin.pelanggaran.riwayat', compact('santri', 'riwayat'));
    }
}
