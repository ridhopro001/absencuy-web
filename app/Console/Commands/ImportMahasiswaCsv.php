<?php

namespace App\Console\Commands;

use App\Models\Mahasiswa;
use Illuminate\Console\Command;

class ImportMahasiswaCsv extends Command
{
    protected $signature = 'mahasiswa:import-csv {file : Path ke file CSV}';
    protected $description = 'Import data mahasiswa dari file CSV (format: nama,nim,jurusan)';

    public function handle()
    {
        $file = $this->argument('file');

        if (!file_exists($file)) {
            $this->error("File '$file' tidak ditemukan.");
            return 1;
        }

        $handle = fopen($file, 'r');
        $count = 0;

        while (($line = fgetcsv($handle)) !== false) {
            $nama = trim($line[0] ?? '');
            $nim = trim($line[1] ?? '');
            $jurusan = trim($line[2] ?? '');
            $semester = trim($line[3] ?? '');

            if (empty($nama) || empty($nim) || empty($semester)) {
                continue;
            }

            Mahasiswa::updateOrCreate(
                ['nim' => $nim],
                [
                    'nama' => $nama,
                    'jurusan' => $jurusan,
                    'semester' => $semester,
                ]
            );

            $count++;
        }

        fclose($handle);

        $this->info("Berhasil import $count data mahasiswa.");
        return 0;
    }
}
