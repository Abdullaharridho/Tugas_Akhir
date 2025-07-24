<?php

namespace App\Imports;

use App\Models\Datasantri;
use App\Models\Kamar;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SantriImport implements ToCollection
{
    public $successCount = 0;
    public $failCount = 0;
    public $failDetails = [];

    public function collection(Collection $rows)
    {
        foreach ($rows->skip(1) as $index => $row) {
            $nik = $row[0];

            // Validasi panjang NIK
            if (strlen($nik) < 16) {
                $this->failCount++;
                $this->failDetails[] = [
                    'baris' => $index + 2,
                    'nik' => $nik,
                    'alasan' => 'NIK kurang dari 16 digit'
                ];
                continue;
            }
            if (strlen($nik) > 16) {
                $this->failCount++;
                $this->failDetails[] = [
                    'baris' => $index + 2,
                    'nik' => $nik,
                    'alasan' => 'NIK lebih dari 16 digit'
                ];
                continue;
            }
            if (Datasantri::where('nik', $nik)->exists()) {
                $this->failCount++;
                $this->failDetails[] = [
                    'baris' => $index + 2,
                    'nik' => $nik,
                    'alasan' => 'NIK sudah terdaftar'
                ];
                continue;
            }

            // Format tanggal lahir
            $rawTanggal = $row[2];
            if (is_numeric($rawTanggal)) {
                try {
                    $tgllahir = Date::excelToDateTimeObject($rawTanggal)->format('Y-m-d');
                } catch (\Exception $e) {
                    $this->failCount++;
                    $this->failDetails[] = [
                        'baris' => $index + 2,
                        'nik' => $nik,
                        'alasan' => 'Format tanggal tidak valid'
                    ];
                    continue;
                }
            } else {
                try {
                    $tgllahir = \Carbon\Carbon::parse($rawTanggal)->format('Y-m-d');
                } catch (\Exception $e) {
                    $this->failCount++;
                    $this->failDetails[] = [
                        'baris' => $index + 2,
                        'nik' => $nik,
                        'alasan' => 'Format tanggal tidak dikenali'
                    ];
                    continue;
                }
            }

            // Cari ID kelas berdasarkan nama kelas di kolom Excel
            $namaKelas = $row[6];
            $kelas = Kelas::whereRaw('LOWER(TRIM(nama)) = ?', [strtolower(trim($namaKelas))])->first();

            if (!$kelas) {
                $this->failCount++;
                $this->failDetails[] = [
                    'baris' => $index + 2,
                    'nik' => $nik,
                    'alasan' => "Kelas '{$namaKelas}' tidak ditemukan"
                ];
                continue;
            }
            $namaKamar = $row[7];
            $kamar = Kamar::whereRaw('LOWER(TRIM(nama)) = ?', [strtolower(trim($namaKamar))])->first();

            if (!$kamar) {
                $this->failCount++;
                $this->failDetails[] = [
                    'baris' => $index + 2,
                    'nik' => $nik,
                    'alasan' => "kamar '{$namaKamar}' tidak ditemukan"
                ];
                continue;
            }

            // Buat data santri
            $santri = Datasantri::create([
                'nik' => $nik,
                'nama' => $row[1],
                'tgllahir' => $tgllahir,
                'jenis_kelamin' => $row[3],
                'alamat' => $row[4],
                'ortu' => $row[5],
                'kelas' => $kelas->id, // <-- pakai ID hasil relasi
                'kamar' => $kamar->id,
                'kontak' => $row[8],
            ]);

            // Buat user jika belum ada
            if (!User::where('email', $santri->nis)->exists()) {
                User::create([
                    'name' => $row[1],
                    'email' => $santri->nis,
                    'password' => Hash::make($santri->nis),
                    'tipeuser' => 'user',
                ]);
            }

            $this->successCount++;
        }
    }
}
