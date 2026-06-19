<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'tanggal',
        'waktu',
        'status',
        'latitude',
        'longitude',
        'lokasi_sumber',
        'alasan',
        'file_pendukung',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'waktu' => 'string',
            'lokasi_sumber' => 'string',
        ];
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    protected static function booted(): void
    {
        static::deleting(function (Absensi $absensi) {
            if ($absensi->file_pendukung) {
                $path = public_path('storage/' . $absensi->file_pendukung);
                if (file_exists($path)) {
                    @unlink($path);
                }
            }
        });
    }
}
