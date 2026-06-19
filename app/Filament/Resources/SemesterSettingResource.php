<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SemesterSettingResource\Pages;
use App\Models\SemesterSetting;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SemesterSettingResource extends Resource
{
    protected static ?string $model = SemesterSetting::class;

    protected static ?string $pluralModelLabel = 'Pengaturan Semester';
    protected static ?string $modelLabel = 'Pengaturan Semester';

    public static function getNavigationIcon(): string | \BackedEnum | \Illuminate\Contracts\Support\Htmlable | null
    {
        return 'heroicon-o-calendar-days';
    }

    protected static ?string $navigationLabel = 'Pengaturan Semester';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('nama_semester')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Semester'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_semester')->label('Nama Semester'),
            ])
            ->actions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSemesterSettings::route('/'),
            'create' => Pages\CreateSemesterSetting::route('/create'),
            'edit' => Pages\EditSemesterSetting::route('/{record}/edit'),
        ];
    }
}
