<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'tanggal',
        'nis',

        'id_guru',
        'id_mapel',
        'id_kelas',
        'status'
    ];
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mapel');
    }

    public function santri()
    {
        return $this->belongsTo(Datasantri::class, 'nis', 'nis');
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'id_guru');
    }
}
