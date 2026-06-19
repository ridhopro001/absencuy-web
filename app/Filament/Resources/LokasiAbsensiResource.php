<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LokasiAbsensiResource\Pages;
use App\Models\LokasiAbsensi;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LokasiAbsensiResource extends Resource
{
    protected static ?string $model = LokasiAbsensi::class;

    protected static ?string $pluralModelLabel = 'Lokasi Absensi';
    protected static ?string $modelLabel = 'Lokasi Absensi';

    public static function getNavigationIcon(): string | \BackedEnum | \Illuminate\Contracts\Support\Htmlable | null
    {
        return 'heroicon-o-map-pin';
    }

    protected static ?string $navigationLabel = 'Lokasi Absensi';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('latitude')
                    ->required()
                    ->numeric()
                    ->step(0.0000001)
                    ->formatStateUsing(fn ($state): ?string => $state !== null ? rtrim(rtrim($state, '0'), '.') : null),
                TextInput::make('longitude')
                    ->required()
                    ->numeric()
                    ->step(0.0000001)
                    ->formatStateUsing(fn ($state): ?string => $state !== null ? rtrim(rtrim($state, '0'), '.') : null),
                TextInput::make('radius_meter')
                    ->required()
                    ->numeric()
                    ->default(3000)
                    ->label('Radius (meter)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('latitude')
                    ->formatStateUsing(fn ($state): string => $state ? rtrim(rtrim($state, '0'), '.') : '-'),
                TextColumn::make('longitude')
                    ->formatStateUsing(fn ($state): string => $state ? rtrim(rtrim($state, '0'), '.') : '-'),
                TextColumn::make('radius_meter')->label('Radius (m)'),
            ])
            ->actions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLokasiAbsensis::route('/'),
            'create' => Pages\CreateLokasiAbsensi::route('/create'),
            'edit' => Pages\EditLokasiAbsensi::route('/{record}/edit'),
        ];
    }
}
