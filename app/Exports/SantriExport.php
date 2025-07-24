<?php
namespace App\Exports;

use App\Models\Datasantri;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SantriExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Datasantri::select('nis', 'nik', 'nama', 'jenis_kelamin', 'alamat', 'ortu', 'kelas', 'kamar', 'kontak')->get();
    }

    public function headings(): array
    {
        return ['NIS', 'NIK', 'Nama', 'Jenis Kelamin','tgllahir', 'Alamat', 'Ortu', 'Kelas ID', 'Kamar ID', 'Kontak'];
    }
}
