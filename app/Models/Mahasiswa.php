<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Mahasiswa extends Model
{
    protected $fillable = [
        'nama',
        'nim',
        'jurusan',
        'semester',
        'face_descriptor',
    ];

    protected function casts(): array
    {
        return [
            'face_descriptor' => 'array',
        ];
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    protected static function booted(): void
    {
        static::deleting(function (Mahasiswa $mahasiswa) {
            $files = DB::table('absensis')
                ->where('mahasiswa_id', $mahasiswa->id)
                ->whereNotNull('file_pendukung')
                ->pluck('file_pendukung');

            foreach ($files as $file) {
                $path = public_path('storage/' . $file);
                if (file_exists($path)) {
                    @unlink($path);
                }
            }

            $dir = public_path('storage/foto_mahasiswa_tersimpan');
            if (!is_dir($dir)) {
                return;
            }
            $photoFiles = glob($dir . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
            foreach ($photoFiles as $file) {
                if (str_contains(basename($file), $mahasiswa->nim)) {
                    @unlink($file);
                }
            }
        });
    }
}
