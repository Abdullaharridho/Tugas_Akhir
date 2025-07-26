<?php

namespace App\Http\Controllers;

use App\Exports\SantriExport;
use App\Imports\SantriImport;
use App\Models\Datasantri;
use App\Models\Kamar;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class DataSantriController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $filterKelas = $request->input('kelas');

        $santris = Datasantri::query()
            ->with(['kelasData', 'kamarData'])
            ->when($query, function ($q) use ($query) {
                $q->where(function ($subQuery) use ($query) {
                    $subQuery->where('nis', 'like', "%$query%")
                        ->orWhere('nama', 'like', "%$query%");
                });
            })
            ->when($filterKelas, function ($q) use ($filterKelas) {
                $q->where('kelas', $filterKelas);
            })
            ->get();

        $kelas = Kelas::all();
        $kamar = Kamar::all();

        return view('admin.datasantri.index', compact('santris', 'kelas', 'kamar'));
    }

    public function tambah()
    {
         if (Auth::user()->email !== 'abdullaharridho03@gmail.com') {
            return back()
                ->with('error', 'Akses ditolak: hanya pengguna tertentu yang diizinkan.')
                ->withInput();
        }
        $kamar = Kamar::all();
        $kelas = Kelas::all();

        return view('admin.datasantri.tambah', compact('kelas', 'kamar'));
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'nik' => 'required|digits:16|unique:data_santri,nik',
            'nama' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'kelas' => 'required|exists:kelas,id',
            'kamar' => 'required|exists:kamar,id',
            'alamat' => 'required|string',
            'tgllahir' => 'required|string',
            'ortu' => 'required|string',
            'kontak' => 'required|string',
        ], [
            'nik.unique' => 'NIK sudah ada.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus 16 digit.',
        ]);


        $lastSantri = Datasantri::latest('nis')->first();
        $nis = $lastSantri ? $lastSantri->nis + 1 : 21214001;

        $santri = Datasantri::create([
            'nis' => $nis,
            'nik' => $request->nik,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tgllahir' => $request->tgllahir,
            'alamat' => $request->alamat,
            'ortu' => $request->ortu,
            'kelas' => $request->kelas,
            'kamar' => $request->kamar,
            'kontak' => $request->kontak,
        ]);

        User::create([
            'name' => $santri->nama,
            'email' => $nis,
            'password' => bcrypt($nis),
            'tipeuser' => 'santri',
        ]);

        return redirect()->route('datasantri.index')->with('success', 'Santri berhasil ditambahkan dan akun dibuat.');
    }

    public function edit($nis)
    {
         if (Auth::user()->email !== 'abdullaharridho03@gmail.com') {
            return back()
                ->with('error', 'Akses ditolak: hanya pengguna tertentu yang diizinkan.')
                ->withInput();
        }
        $santri = Datasantri::findOrFail($nis);
        $kelas = Kelas::all();
        $kamar = Kamar::all();

        return view('admin.datasantri.edit', compact('santri', 'kelas', 'kamar'));
    }

    public function update(Request $request, $nis)
    {
        $request->validate([
            'nik' => 'required|digits:16',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
            'tgllahir' => 'required|string',
            'ortu' => 'required|string|max:255',
            'kelas' => 'required|exists:kelas,id',
            'kamar' => 'required|exists:kamar,id',
            'kontak' => 'required|string|max:15',
        ]);

        $santri = Datasantri::findOrFail($nis);
        $santri->update([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'tgllahir' => $request->tgllahir,
            'ortu' => $request->ortu,
            'kelas' => $request->kelas,
            'kamar' => $request->kamar,
            'kontak' => $request->kontak,
        ]);

        return redirect()->route('datasantri.index')->with('success', 'Santri berhasil diperbarui.');
    }

    public function hapus($nis)
    {
         if (Auth::user()->email !== 'abdullaharridho03@gmail.com') {
            return back()
                ->with('error', 'Akses ditolak: hanya pengguna tertentu yang diizinkan.')
                ->withInput();
        }
        $santri = Datasantri::findOrFail($nis);
        $santri->delete();

        return redirect()->route('datasantri.index')->with('success', 'Santri berhasil dihapus.');
    }
    public function import(Request $request)
    {
         if (Auth::user()->email !== 'abdullaharridho03@gmail.com') {
            return back()
                ->with('error', 'Akses ditolak: hanya pengguna tertentu yang diizinkan.')
                ->withInput();
        }
        $import = new SantriImport();
        Excel::import($import, $request->file('file'));
        session()->flash('successCount', $import->successCount);
        session()->flash('failCount', $import->failCount);
        session()->flash('failDetails', $import->failDetails);

        $message = "{$import->successCount} data berhasil diimpor.";

        if ($import->failCount > 0) {
            $message .= " {$import->failCount} data gagal.";
            foreach ($import->failDetails as $fail) {
                $message .= " (Baris {$fail['baris']}: NIK {$fail['nik']} - {$fail['alasan']})";
            }
        }

        return redirect()->back()->with('status', $message);
    }

    public function export()
    {
        return Excel::download(new SantriExport, 'data_santri.xlsx');
    }
    public function detail($nis)
    {
        $santri = Datasantri::where('nis', $nis)->firstOrFail();
        return view('admin.datasantri.detail', compact('santri'));
    }
}
