<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perizinan extends Model
{
    use HasFactory;

    protected $table = 'perizinan'; // Nama tabel

    protected $fillable = [
        'nis',
        'id_pengurus',
        'tanggal',
        'keterangan',
        'tanggal_kembali',
        'statuspesan',
    ];
    public function santri()
    {
        return $this->belongsTo(Datasantri::class, 'nis', 'nis');
    }
    public function pengurus()
    {
        return $this->belongsTo(User::class, 'id_pengurus');
    }
}
