<?php

namespace App\Filament\Resources\LokasiAbsensiResource\Pages;

use App\Filament\Resources\LokasiAbsensiResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLokasiAbsensi extends CreateRecord
{
    protected static string $resource = LokasiAbsensiResource::class;

    public function canCreateAnother(): bool
    {
        return false;
    }

    protected function getRedirectUrl(): string
    {
        return LokasiAbsensiResource::getUrl('index');
    }
}
