<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JurusanSetting extends Model
{
    protected $fillable = [
        'nama_jurusan',
        'jumlah_mahasiswa',
    ];
}
