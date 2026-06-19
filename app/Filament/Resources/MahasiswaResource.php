<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MahasiswaResource\Pages;
use App\Models\JurusanSetting;
use App\Models\Mahasiswa;
use App\Models\SemesterSetting;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MahasiswaResource extends Resource
{
    protected static ?string $model = Mahasiswa::class;

    protected static ?string $pluralModelLabel = 'Mahasiswa';
    protected static ?string $modelLabel = 'Mahasiswa';
    protected static ?string $navigationLabel = 'Mahasiswa';

    protected static ?int $navigationSort = 1;

    public static function getNavigationIcon(): string | \BackedEnum | \Illuminate\Contracts\Support\Htmlable | null
    {
        return 'heroicon-o-users';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                TextInput::make('nim')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Select::make('jurusan')
                    ->required()
                    ->options(fn () => JurusanSetting::pluck('nama_jurusan', 'nama_jurusan')->toArray()),
                Select::make('semester')
                    ->label('Semester')
                    ->required()
                    ->options(fn () => SemesterSetting::pluck('nama_semester', 'nama_semester')->toArray()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->searchable()->sortable(),
                TextColumn::make('nim')->searchable()->sortable(),
                TextColumn::make('jurusan')->searchable()->sortable(),
                TextColumn::make('semester')->searchable()->sortable()->label('Semester'),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('jurusan')
                    ->options(fn () => JurusanSetting::pluck('nama_jurusan', 'nama_jurusan')->toArray()),
                SelectFilter::make('semester')
                    ->label('Semester')
                    ->options(fn () => SemesterSetting::pluck('nama_semester', 'nama_semester')->toArray()),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMahasiswas::route('/'),
            'create' => Pages\CreateMahasiswa::route('/create'),
            'edit' => Pages\EditMahasiswa::route('/{record}/edit'),
        ];
    }
}
