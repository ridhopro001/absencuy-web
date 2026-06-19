<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JurusanSettingResource\Pages;
use App\Models\JurusanSetting;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class JurusanSettingResource extends Resource
{
    protected static ?string $model = JurusanSetting::class;

    protected static ?string $pluralModelLabel = 'Pengaturan Jurusan';
    protected static ?string $modelLabel = 'Pengaturan Jurusan';

    public static function getNavigationIcon(): string | \BackedEnum | \Illuminate\Contracts\Support\Htmlable | null
    {
        return 'heroicon-o-academic-cap';
    }

    protected static ?string $navigationLabel = 'Pengaturan Jurusan';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('nama_jurusan')
                    ->required()
                    ->maxLength(255),
                TextInput::make('jumlah_mahasiswa')
                    ->required()
                    ->numeric()
                    ->label('Jumlah Mahasiswa'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_jurusan'),
                TextColumn::make('jumlah_mahasiswa')->label('Jumlah Mahasiswa'),
            ])
            ->actions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJurusanSettings::route('/'),
            'create' => Pages\CreateJurusanSetting::route('/create'),
            'edit' => Pages\EditJurusanSetting::route('/{record}/edit'),
        ];
    }
}
