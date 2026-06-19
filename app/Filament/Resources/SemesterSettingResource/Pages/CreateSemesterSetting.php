<?php

namespace App\Filament\Resources\SemesterSettingResource\Pages;

use App\Filament\Resources\SemesterSettingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSemesterSetting extends CreateRecord
{
    protected static string $resource = SemesterSettingResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
