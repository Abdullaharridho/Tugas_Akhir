<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggaran extends Model
{
    use HasFactory;

    protected $table = 'pelanggaran';

    protected $fillable = ['nis', 'id_pengurus', 'kategori','keterangan','tindakan','statuspesan'];
    public function santri()
    {
        return $this->belongsTo(Datasantri::class, 'nis', 'nis');
    }
    public function pengurus()
{
    return $this->belongsTo(User::class, 'id_pengurus');
}
}
