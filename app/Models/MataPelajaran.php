<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'matapelajaran';
    protected $fillable = ['nama'];
    public function gurus()
    {
        return $this->belongsToMany(User::class, 'mapel_user', 'mata_pelajaran_id', 'id_guru');
    }
}
