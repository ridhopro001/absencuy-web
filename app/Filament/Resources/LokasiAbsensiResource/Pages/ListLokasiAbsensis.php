<?php

namespace App\Filament\Resources\LokasiAbsensiResource\Pages;

use App\Filament\Resources\LokasiAbsensiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLokasiAbsensis extends ListRecords
{
    protected static string $resource = LokasiAbsensiResource::class;

    protected static ?string $title = 'Lokasi Absensi';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
