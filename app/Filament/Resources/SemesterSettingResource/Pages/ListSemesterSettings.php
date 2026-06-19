<?php

namespace App\Filament\Resources\SemesterSettingResource\Pages;

use App\Filament\Resources\SemesterSettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSemesterSettings extends ListRecords
{
    protected static string $resource = SemesterSettingResource::class;

    protected static ?string $title = 'Pengaturan Semester';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
