<?php

namespace Database\Seeders;

use App\Models\JurusanSetting;
use App\Models\LokasiAbsensi;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SemesterSettingSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => 'admin123',
        ]);

        LokasiAbsensi::create([
            'latitude' => -6.2088,
            'longitude' => 106.8456,
            'radius_meter' => 3000,
        ]);

        $jurusan = ['TI', 'TS', 'BTP'];
        foreach ($jurusan as $j) {
            JurusanSetting::create([
                'nama_jurusan' => $j,
                'jumlah_mahasiswa' => 100,
            ]);
        }
    }
}
