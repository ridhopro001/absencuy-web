<?php

namespace App\Filament\Resources\JurusanSettingResource\Pages;

use App\Filament\Resources\JurusanSettingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJurusanSetting extends EditRecord
{
    protected static string $resource = JurusanSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return JurusanSettingResource::getUrl('index');
    }
}
