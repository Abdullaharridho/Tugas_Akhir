<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Perizinan;
use Carbon\Carbon;

class KirimNotifikasiPerizinan extends Command
{
    protected $signature = 'notifikasi:perizinan';
    protected $description = 'Kirim notifikasi WA H-1 dan H+1 tanggal kembali santri';

    public function handle()
    {
        $hariIni = Carbon::today();

        // H-1
        $hMinus1 = Perizinan::whereDate('tanggal_kembali', $hariIni->copy()->addDay())
            ->where('statuspesan', 'izin')
            ->with('santri') // ambil data santri
            ->get();

        foreach ($hMinus1 as $izin) {
            $pesan = "Anak anda harus kembali pada tanggal " . Carbon::parse($izin->tanggal_kembali)->format('d-m-Y');
            $this->kirimWA($izin->santri->kontak, $pesan);
        }

        // H+1
        $hPlus1 = Perizinan::whereDate('tanggal_kembali', $hariIni->copy()->subDay())
            ->where('statuspesan', 'izin')
            ->with('santri')
            ->get();

        foreach ($hPlus1 as $izin) {
            $pesan = "Anak anda terlambat";
            $this->kirimWA($izin->santri->kontak, $pesan);

            // update status agar tidak kirim ulang
            $izin->update(['statuspesan' => 'terlambat']);
        }

        $this->info("Notifikasi berhasil diproses.");
    }

    private function kirimWA($nomor, $pesan)
    {
        $token = 'Y3HuLi23xamuEeb5wDmo'; // langsung hardcode
        $kontak = $nomor;

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
            CURLOPT_POSTFIELDS => array(
                'target' => $kontak,
                'message' => $pesan
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $token
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error = curl_error($curl);
            curl_close($curl);
            \Log::error('Gagal kirim WA: ' . $error);
            return;
        }

        curl_close($curl);
        \Log::info('WA sent: ' . $response);
    }
}
