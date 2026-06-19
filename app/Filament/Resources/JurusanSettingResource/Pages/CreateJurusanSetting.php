<?php

namespace App\Filament\Resources\JurusanSettingResource\Pages;

use App\Filament\Resources\JurusanSettingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJurusanSetting extends CreateRecord
{
    protected static string $resource = JurusanSettingResource::class;

    public function canCreateAnother(): bool
    {
        return false;
    }

    protected function getRedirectUrl(): string
    {
        return JurusanSettingResource::getUrl('index');
    }
}
