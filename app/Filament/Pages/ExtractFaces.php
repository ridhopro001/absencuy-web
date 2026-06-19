<?php

namespace App\Filament\Pages;

use App\Models\Mahasiswa;
use Filament\Pages\Page;

class ExtractFaces extends Page
{
    public static function getNavigationIcon(): string | \BackedEnum | \Illuminate\Contracts\Support\Htmlable | null
    {
        return 'heroicon-o-camera';
    }

    protected static ?string $navigationLabel = 'Ekstrak Wajah';

    protected static ?string $title = 'Ekstrak Wajah';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.extract-faces';

    public function getPendingPhotoCount(): int
    {
        $dir = public_path('storage/foto_mahasiswa');
        if (!is_dir($dir)) {
            return 0;
        }
        return count(glob($dir . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE));
    }

    public function getPhotos()
    {
        $dirs = [
            'foto_mahasiswa' => public_path('storage/foto_mahasiswa'),
            'foto_mahasiswa_tersimpan' => public_path('storage/foto_mahasiswa_tersimpan'),
        ];

        $photos = [];

        foreach ($dirs as $folder => $dir) {
            if (!is_dir($dir)) {
                continue;
            }

            $files = glob($dir . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
            sort($files);

            foreach ($files as $file) {
                $basename = basename($file);
                $nameOnly = pathinfo($basename, PATHINFO_FILENAME);

                $parts = explode(',', $nameOnly);
                if (count($parts) >= 3) {
                    $nim = trim($parts[1]);
                } else {
                    $nim = $nameOnly;
                }

                $semester = (count($parts) >= 4) ? trim($parts[3]) : '';

                $mahasiswa = Mahasiswa::where('nim', $nim)->first();

                $photos[] = [
                    'path' => asset('storage/' . $folder . '/' . $basename),
                    'folder' => $folder,
                    'filename' => $basename,
                    'nim' => $nim,
                    'semester' => $semester,
                    'mahasiswa' => $mahasiswa,
                    'has_descriptor' => $mahasiswa && $mahasiswa->face_descriptor,
                ];
            }
        }

        return $photos;
    }
}
