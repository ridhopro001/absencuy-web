<?php

namespace App\Filament\Resources\JurusanSettingResource\Pages;

use App\Filament\Resources\JurusanSettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJurusanSettings extends ListRecords
{
    protected static string $resource = JurusanSettingResource::class;

    protected static ?string $title = 'Pengaturan Jurusan';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
