<?php

namespace Database\Seeders;

use App\Models\SemesterSetting;
use Illuminate\Database\Seeder;

class SemesterSettingSeeder extends Seeder
{
    public function run(): void
    {
        $semesters = ['Semester 1', 'Semester 2', 'Semester 3', 'Semester 4', 'Semester 5', 'Semester 6'];

        foreach ($semesters as $s) {
            SemesterSetting::create(['nama_semester' => $s]);
        }
    }
}
