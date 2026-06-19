<?php

namespace App\Filament\Widgets;

use App\Models\Absensi;
use App\Models\JurusanSetting;
use Filament\Widgets\Widget;

class JurusanWarningWidget extends Widget
{
    protected string $view = 'filament.widgets.jurusan-warning-widget';

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return true;
    }

    public function getViewData(): array
    {
        $settings = JurusanSetting::all();
        $jurusanData = [];

        foreach ($settings as $setting) {
            $totalHadir = Absensi::whereHas('mahasiswa', fn ($q) => $q->where('jurusan', $setting->nama_jurusan))
                ->whereIn('status', ['Hadir', 'Izin', 'Sakit'])
                ->count();

            $jurusanData[] = [
                'nama' => $setting->nama_jurusan,
                'target' => $setting->jumlah_mahasiswa,
                'total' => $totalHadir,
                'peringatan' => $totalHadir < $setting->jumlah_mahasiswa,
            ];
        }

        return ['jurusanData' => $jurusanData];
    }
}
