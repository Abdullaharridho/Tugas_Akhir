<?php

namespace App\Observers;

use Carbon\Carbon;
use App\Models\Datasantri;
use App\Models\Pelanggaran;

class WAObserver
{
    public function created(Pelanggaran $pelanggaran): void
    {
        $this->cekDanKirimPesan($pelanggaran);
    }

    protected function cekDanKirimPesan(Pelanggaran $pelanggaran)
    {
        if ($pelanggaran->statuspesan === 'Sudah1' && $pelanggaran->kategori === 'berat') {
            $this->kirimPesanWhatsApp2($pelanggaran);
        } elseif ($pelanggaran->statuspesan === 'Sudah' && $pelanggaran->kategori === 'berat') {
            $this->kirimPesanWhatsApp1($pelanggaran);
        } elseif ($pelanggaran->statuspesan === 'Belum'&& $pelanggaran->kategori === 'berat') {
            $this->kirimPesanWhatsApp($pelanggaran);
        }
    
    }


    protected function kirimPesanWhatsApp(Pelanggaran $pelanggaran)
    {
        $tanggalHadir = Carbon::now()->addDays(3)->format('d-m-Y');
        $datasantri = $pelanggaran->santri;
        $nama = $pelanggaran->nama;
        $ortu = $datasantri->ortu;
        $nik = $datasantri->nik;
        $alamat = $datasantri->alamat;
        $pesan = "Assalamualaikum Wr. Wb.\n\nYth. Bapak/Ibu $ortu,\n\nKami informasikan bahwa santri atas nama *$nama* (NIK: $nik), yang berdomisili di *$alamat*, telah melebihi batas jumlah pelanggaran kategori *Berat 1 Kali*.\n\nDimohon kehadiran Bapak/Ibu atau wali santri ke pondok pada tanggal *$tanggalHadir* untuk melakukan klarifikasi dan pembinaan lebih lanjut.\n\nTerima kasih atas perhatian dan kerja samanya.\n\nWassalamualaikum Wr. Wb.";

        $token = 'Y3HuLi23xamuEeb5wDmo';

        $kontak = $datasantri->kontak;

        if (!$kontak) {
            return;
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('target' => $kontak, 'message' => $pesan),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $token
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error = curl_error($curl);
            curl_close($curl);
            return;
        }

        curl_close($curl);

        $pelanggaran->statuspesan = 'Sudah';
        $pelanggaran->save(); // Simpan perubahan ke database
    }
    protected function kirimPesanWhatsApp1(Pelanggaran $pelanggaran)
    {
        $tanggalHadir = Carbon::now()->addDays(3)->format('d-m-Y');
        $datasantri = $pelanggaran->santri;
        $nama = $pelanggaran->nama;
        $ortu = $datasantri->ortu;
        $nik = $datasantri->nik;
        $alamat = $datasantri->alamat;
        $pesan = "Assalamualaikum Wr. Wb.\n\nYth. Bapak/Ibu $ortu,\n\nKami informasikan bahwa santri atas nama *$nama* (NIK: $nik), yang berdomisili di *$alamat*, telah melebihi batas jumlah pelanggaran kategori *Berat 2 Kali*.\n\nDimohon kehadiran Bapak/Ibu atau wali santri ke pondok pada tanggal *$tanggalHadir* untuk melakukan klarifikasi dan pembinaan lebih lanjut.\n\nTerima kasih atas perhatian dan kerja samanya.\n\nWassalamualaikum Wr. Wb.";

        $token = 'Y3HuLi23xamuEeb5wDmo';

        $kontak = $datasantri->kontak;

        if (!$kontak) {
            return;
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('target' => $kontak, 'message' => $pesan),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $token
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error = curl_error($curl);
            curl_close($curl);
            return;
        }

        curl_close($curl);

        $pelanggaran->statuspesan = 'Sudah1';
        $pelanggaran->save(); // Simpan perubahan ke database
    }
    protected function kirimPesanWhatsApp2(Pelanggaran $pelanggaran)
    {
        $tanggalHadir = Carbon::now()->addDays(3)->format('d-m-Y');
        $datasantri = $pelanggaran->santri;
        $nama = $pelanggaran->nama;
        $ortu = $datasantri->ortu;
        $nik = $datasantri->nik;
        $alamat = $datasantri->alamat;
        $pesan = "Assalamualaikum Wr. Wb.\n\nYth. Bapak/Ibu $ortu,\n\nKami informasikan bahwa santri atas nama *$nama* (NIK: $nik), yang berdomisili di *$alamat*, telah melebihi batas jumlah pelanggaran kategori *Berat 3 Kali*.\n\nDimohon kehadiran Bapak/Ibu atau wali santri ke pondok pada tanggal *$tanggalHadir* untuk melakukan klarifikasi dan pembinaan lebih lanjut.\n\nTerima kasih atas perhatian dan kerja samanya.\n\nWassalamualaikum Wr. Wb.";

        $token = 'Y3HuLi23xamuEeb5wDmo';

        $kontak = $datasantri->kontak;

        if (!$kontak) {
            return;
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('target' => $kontak, 'message' => $pesan),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $token
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error = curl_error($curl);
            curl_close($curl);
            return;
        }

        curl_close($curl);

        $pelanggaran->statuspesan = 'Selesai';
        $pelanggaran->save(); // Simpan perubahan ke database
    }
}
