<?php

namespace App\Filament\Resources\MahasiswaResource\Pages;

use App\Filament\Resources\MahasiswaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMahasiswas extends ListRecords
{
    protected static string $resource = MahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
