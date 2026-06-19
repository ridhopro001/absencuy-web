<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiAbsensi extends Model
{
    protected $fillable = [
        'latitude',
        'longitude',
        'radius_meter',
    ];

    protected function casts(): array
    {
        return [
            'radius_meter' => 'integer',
        ];
    }
}
