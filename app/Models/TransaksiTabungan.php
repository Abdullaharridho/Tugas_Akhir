<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiTabungan extends Model
{
    use HasFactory;
    
    protected $table = 'transaksi_tabungan';
    protected $fillable = ['nis', 'jenis','id_pengurus', 'jumlah', 'tanggal'];

    public function santri() {
        return $this->belongsTo(Datasantri::class, 'nis', 'nis');
    }
    public function pengurus()
{
    return $this->belongsTo(User::class, 'id_pengurus');
}
}
