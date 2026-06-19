<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbsensiResource\Pages;
use App\Models\Absensi;
use App\Models\JurusanSetting;
use App\Models\Mahasiswa;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AbsensiResource extends Resource
{
    protected static ?string $model = Absensi::class;

    protected static ?string $pluralModelLabel = 'Riwayat Absensi';
    protected static ?string $modelLabel = 'Absensi';

    public static function getNavigationIcon(): string | \BackedEnum | \Illuminate\Contracts\Support\Htmlable | null
    {
        return 'heroicon-o-clipboard-document-list';
    }

    protected static ?string $navigationLabel = 'Riwayat Absensi';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('mahasiswa_id')
                    ->label('Mahasiswa')
                    ->options(Mahasiswa::all()->pluck('nama', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('status')
                    ->options([
                        'Hadir' => 'Hadir',
                        'Hadir (Lokasi Tidak Valid)' => 'Hadir (Lokasi Tidak Valid)',
                        'Izin' => 'Izin',
                        'Sakit' => 'Sakit',
                        'Alpa' => 'Alpa',
                    ])
                    ->required(),
                Select::make('lokasi_sumber')
                    ->label('Sumber Lokasi')
                    ->options([
                        'gps' => 'GPS',
                        'ip' => 'IP',
                    ]),
                TextInput::make('latitude'),
                TextInput::make('longitude'),
                DatePicker::make('tanggal')
                    ->required(),
                TimePicker::make('waktu')
                    ->required(),
                Textarea::make('alasan')
                    ->label('Keterangan')
                    ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('mahasiswa.nama')->searchable()->sortable()->label('Nama'),
                TextColumn::make('mahasiswa.nim')->searchable()->sortable()->label('NIM'),
                TextColumn::make('mahasiswa.jurusan')->sortable()->label('Jurusan'),
                TextColumn::make('mahasiswa.semester')->sortable()->label('Semester'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Hadir' => 'success',
                        'Hadir (Lokasi Tidak Valid)' => 'warning',
                        'Izin' => 'warning',
                        'Sakit' => 'danger',
                        'Alpa' => 'gray',
                    }),
                TextColumn::make('tanggal')->date()->sortable(),
                TextColumn::make('waktu')->sortable(),
                TextColumn::make('latitude')->label('Latitude')->sortable(),
                TextColumn::make('longitude')->label('Longitude')->sortable(),
                TextColumn::make('lokasi_sumber')
                    ->label('Sumber')
                    ->formatStateUsing(fn (string $state, $record): string => match ($state) {
                        'gps' => '<a href="https://www.google.com/maps?q=' . $record->latitude . ',' . $record->longitude . '" target="_blank" rel="noopener noreferrer" style="text-decoration:underline;">GPS</a>',
                        'ip' => 'IP (perkiraan)',
                        default => '-',
                    })
                    ->html(),
                TextColumn::make('alasan')->limit(30)->label('Keterangan'),
                TextColumn::make('file_pendukung')
                    ->label('File')
                    ->formatStateUsing(fn ($state) => $state
                        ? '<a href="' . route('download.file', $state) . '" target="_blank" rel="noopener noreferrer" style="text-decoration:underline;color:#2563eb;">Download</a>'
                        : '-')
                    ->html(),
            ])
            ->filters([
                SelectFilter::make('jurusan')
                    ->label('Jurusan')
                    ->options(fn () => JurusanSetting::pluck('nama_jurusan', 'nama_jurusan')->toArray())
                    ->query(fn (Builder $query, array $data) => $query->when($data['value'], fn ($q) => $q->whereHas('mahasiswa', fn ($q) => $q->where('jurusan', $data['value'])))),
                SelectFilter::make('status')
                    ->options([
                        'Hadir' => 'Hadir',
                        'Hadir (Lokasi Tidak Valid)' => 'Hadir (Lokasi Tidak Valid)',
                        'Izin' => 'Izin',
                        'Sakit' => 'Sakit',
                        'Alpa' => 'Alpa',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                ViewAction::make(),
                DeleteAction::make(),
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
            'index' => Pages\ListAbsensis::route('/'),
            'create' => Pages\CreateAbsensi::route('/create'),
            'view' => Pages\ViewAbsensi::route('/{record}'),
            'edit' => Pages\EditAbsensi::route('/{record}/edit'),
        ];
    }
}
