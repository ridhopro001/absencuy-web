<?php

namespace App\Http\Controllers;

use App\Models\JurusanSetting;
use App\Models\Mahasiswa;
use App\Models\SemesterSetting;
use Illuminate\Http\Request;

class FaceExtractController extends Controller
{
    public function save(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|string',
            'face_descriptor' => 'required|array|size:128',
        ]);

        $mahasiswa = Mahasiswa::where('nim', $validated['nim'])->first();

        if (!$mahasiswa) {
            return response()->json(['success' => false, 'message' => 'Mahasiswa tidak ditemukan.'], 404);
        }

        $mahasiswa->update([
            'face_descriptor' => $validated['face_descriptor'],
        ]);

        return response()->json(['success' => true]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $dir = public_path('storage/foto_mahasiswa');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $count = 0;
        foreach ($request->file('photos') as $file) {
            $originalName = $file->getClientOriginalName();
            $file->move($dir, $originalName);
            $count++;
        }

        return response()->json([
            'success' => true,
            'message' => "Berhasil upload {$count} foto.",
        ]);
    }

    public function sync(Request $request)
    {
        $dir = public_path('storage/foto_mahasiswa');
        $savedDir = public_path('storage/foto_mahasiswa_tersimpan');

        if (!is_dir($dir)) {
            return response()->json(['success' => false, 'message' => 'Folder foto_mahasiswa tidak ditemukan.'], 400);
        }

        if (!is_dir($savedDir)) {
            mkdir($savedDir, 0755, true);
        }

        $files = glob($dir . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
        $count = 0;
        $errors = [];

        $validJurusan = JurusanSetting::pluck('nama_jurusan')->map(fn ($v) => strtolower(trim($v)))->toArray();
        $validSemester = SemesterSetting::pluck('nama_semester')->map(fn ($v) => strtolower(trim($v)))->toArray();

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

            try {
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
            } catch (\Exception $e) {
                $errors[] = "Gagal proses {$basename}: " . $e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Berhasil sinkron {$count} data mahasiswa dari foto.",
            'errors' => $errors,
        ]);
    }

    public function deletePhoto(Request $request)
    {
        $validated = $request->validate([
            'filename' => 'required|string',
            'folder' => 'required|in:foto_mahasiswa,foto_mahasiswa_tersimpan',
        ]);

        $filePath = public_path('storage/' . $validated['folder'] . '/' . $validated['filename']);

        if (!file_exists($filePath)) {
            return response()->json(['success' => false, 'message' => 'File tidak ditemukan.'], 404);
        }

        if (unlink($filePath)) {
            return response()->json(['success' => true, 'message' => 'File berhasil dihapus.']);
        }

        return response()->json(['success' => false, 'message' => 'Gagal menghapus file.'], 500);
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
