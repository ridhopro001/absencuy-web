<?php

namespace App\Console\Commands;

use App\Models\JurusanSetting;
use App\Models\Mahasiswa;
use App\Models\SemesterSetting;
use Illuminate\Console\Command;

class SyncMahasiswaFromPhotos extends Command
{
    protected $signature = 'mahasiswa:sync-photos';
    protected $description = 'Sinkron data mahasiswa dari file foto di public/storage/foto_mahasiswa/ lalu pindahkan ke foto_mahasiswa_tersimpan/';

    public function handle()
    {
        $dir = public_path('storage/foto_mahasiswa');
        $savedDir = public_path('storage/foto_mahasiswa_tersimpan');

        if (!is_dir($dir)) {
            $this->error('Direktori public/storage/foto_mahasiswa/ tidak ditemukan.');
            return 1;
        }

        if (!is_dir($savedDir)) {
            mkdir($savedDir, 0755, true);
            $this->info("Membuat direktori public/storage/foto_mahasiswa_tersimpan/");
        }

        $validJurusan = JurusanSetting::pluck('nama_jurusan')->map(fn ($v) => strtolower(trim($v)))->toArray();
        $validSemester = SemesterSetting::pluck('nama_semester')->map(fn ($v) => strtolower(trim($v)))->toArray();

        $files = glob($dir . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
        $count = 0;
        $errors = [];

        foreach ($files as $fullpath) {
            $basename = basename($fullpath);
            $ext = strtolower(pathinfo($basename, PATHINFO_EXTENSION));
            $nameOnly = pathinfo($basename, PATHINFO_FILENAME);

            $parts = explode(',', $nameOnly);
            if (count($parts) < 4) {
                $errors[] = "Lewati {$basename}: format nama tidak valid (harus: Nama,NIM,Jurusan,Semester)";
                continue;
            }

            $nama = trim($parts[0]);
            $nim = trim($parts[1]);
            $jurusan = trim($parts[2]);
            $semester = trim($parts[3] ?? '');

            if (empty($nama) || empty($nim) || empty($jurusan) || empty($semester)) {
                $errors[] = "Lewati {$basename}: field kosong";
                continue;
            }

            if (!in_array(strtolower($jurusan), $validJurusan)) {
                $errors[] = "Lewati {$basename}: jurusan '{$jurusan}' tidak terdaftar di Pengaturan Jurusan";
                continue;
            }

            if (!in_array(strtolower($semester), $validSemester)) {
                $errors[] = "Lewati {$basename}: semester '{$semester}' tidak terdaftar di Pengaturan Semester";
                continue;
            }

            Mahasiswa::updateOrCreate(
                ['nim' => $nim],
                [
                    'nama' => $nama,
                    'jurusan' => $jurusan,
                    'semester' => $semester ?: null,
                ]
            );

            $target = $savedDir . '/' . $nim . '.' . $ext;
            $this->resizeImage($fullpath, $target, 100);
            unlink($fullpath);

            $count++;
        }

        $this->info("Berhasil sinkron $count data mahasiswa dari foto.");
        foreach ($errors as $error) {
            $this->warn($error);
        }
        if (count($errors) > 0) {
            $this->warn("Total " . count($errors) . " file dilewati karena tidak valid.");
        }
        return 0;
    }

    private function resizeImage(string $src, string $dest, int $size): void
    {
        $info = getimagesize($src);
        if (!$info) return;

        $sw = $info[0];
        $sh = $info[1];
        $mime = $info['mime'];

        $srcImg = match ($mime) {
            'image/jpeg' => imagecreatefromjpeg($src),
            'image/png' => imagecreatefrompng($src),
            'image/webp' => imagecreatefromwebp($src),
            default => null,
        };

        if (!$srcImg) return;

        $dstImg = imagecreatetruecolor($size, $size);
        imagefill($dstImg, 0, 0, imagecolorallocate($dstImg, 255, 255, 255));

        $scale = min($size / $sw, $size / $sh);
        $dw = (int) round($sw * $scale);
        $dh = (int) round($sh * $scale);
        $dx = (int) round(($size - $dw) / 2);
        $dy = (int) round(($size - $dh) / 2);

        imagecopyresampled($dstImg, $srcImg, $dx, $dy, 0, 0, $dw, $dh, $sw, $sh);

        imagejpeg($dstImg, $dest, 85);
        imagedestroy($srcImg);
        imagedestroy($dstImg);
    }
}
