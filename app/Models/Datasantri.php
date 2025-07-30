<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Hash;

class Datasantri extends Model
{
    use HasFactory; // Tambahkan AuthenticatableTrait

    protected $table = 'data_santri';
    protected $primaryKey = 'nis';
    public $incrementing = false; // NIS manual
    protected $keyType = 'string'; // Pastikan NIS bertipe string

    protected $fillable = [
        'nis',
        'nama',
        'kelas',
        'kamar',
        'tgllahir',
        'kontak',
        'password',
        'alamat',
        'nik',
        'jenis_kelamin',
        'ortu'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($santri) {
            $lastSantri = self::orderByRaw("CAST(nis AS UNSIGNED) DESC")->first();
            $santri->nis = $lastSantri ? (intval($lastSantri->nis) + 1) : 21214001;
        });
    }


    public function kelasData()
    {
        return $this->belongsTo(Kelas::class, 'kelas', 'id');
    }

    public function kamarData()
    {
        return $this->belongsTo(Kamar::class, 'kamar', 'id');
    }

    public function latestTransaksi()
    {
        return $this->hasOne(\App\Models\TransaksiTabungan::class, 'nis', 'nis')->latestOfMany();
    }

    public function transaksi()
    {
        return $this->hasMany(TransaksiTabungan::class, 'nis', 'nis');
    }
    public function perizinan()
    {
        return $this->hasMany(Perizinan::class, 'nis', 'nis');
    }
    public function akun()
    {
        return $this->hasOne(User::class, 'email', 'nis');
    }
}
