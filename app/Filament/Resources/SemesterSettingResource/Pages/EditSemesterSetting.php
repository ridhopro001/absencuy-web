<?php

namespace App\Filament\Resources\SemesterSettingResource\Pages;

use App\Filament\Resources\SemesterSettingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSemesterSetting extends EditRecord
{
    protected static string $resource = SemesterSettingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
