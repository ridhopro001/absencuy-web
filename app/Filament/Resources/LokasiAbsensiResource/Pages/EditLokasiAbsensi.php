<?php

namespace App\Filament\Resources\LokasiAbsensiResource\Pages;

use App\Filament\Resources\LokasiAbsensiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLokasiAbsensi extends EditRecord
{
    protected static string $resource = LokasiAbsensiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return LokasiAbsensiResource::getUrl('index');
    }
}
