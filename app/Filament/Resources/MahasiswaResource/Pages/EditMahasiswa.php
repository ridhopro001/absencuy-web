<?php

namespace App\Filament\Resources\MahasiswaResource\Pages;

use App\Filament\Resources\MahasiswaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMahasiswa extends EditRecord
{
    protected static string $resource = MahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return MahasiswaResource::getUrl('index');
    }
}
