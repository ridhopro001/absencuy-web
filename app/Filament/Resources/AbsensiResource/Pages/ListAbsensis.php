<?php

namespace App\Filament\Resources\AbsensiResource\Pages;

use App\Filament\Resources\AbsensiResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Writer;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListAbsensis extends ListRecords
{
    protected static string $resource = AbsensiResource::class;

    protected static ?string $title = 'Riwayat Absensi';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('export_excel')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->action('exportExcel'),
        ];
    }

    public function exportExcel(): StreamedResponse
    {
        $records = $this->getTableQueryForExport()->with('mahasiswa')->get();
        $grouped = $records->groupBy(fn ($r) => ($r->mahasiswa->jurusan ?? 'Tanpa Jurusan') . ' - ' . ($r->mahasiswa->semester ?? 'Tanpa Semester'));

        return response()->streamDownload(function () use ($grouped) {
            $writer = new Writer();
            $writer->openToFile('php://output');

            $headerStyle = (new Style())->setFontBold();

            $headerRow = Row::fromValues([
                'No', 'Nama', 'NIM', 'Jurusan', 'Semester', 'Status', 'Tanggal', 'Waktu',
                'Latitude', 'Longitude', 'Sumber Lokasi', 'Alasan'
            ], $headerStyle);

            $first = true;
            foreach ($grouped as $key => $items) {
                $sheetName = mb_substr($key, 0, 31);

                if ($first) {
                    $writer->getCurrentSheet()->setName($sheetName);
                    $first = false;
                } else {
                    $writer->addNewSheetAndMakeItCurrent()->setName($sheetName);
                }

                $writer->addRow($headerRow);

                $no = 1;
                foreach ($items as $absensi) {
                    $sumberLabel = match ($absensi->lokasi_sumber) {
                        'gps' => 'GPS',
                        'wifi' => 'WiFi',
                        'ip' => 'IP',
                        default => '-',
                    };

                    $row = Row::fromValues([
                        $no++,
                        $absensi->mahasiswa->nama ?? '-',
                        $absensi->mahasiswa->nim ?? '-',
                        $absensi->mahasiswa->jurusan ?? '-',
                        $absensi->mahasiswa->semester ?? '-',
                        $absensi->status,
                        $absensi->tanggal->format('Y-m-d'),
                        $absensi->waktu,
                        $absensi->latitude,
                        $absensi->longitude,
                        $sumberLabel,
                        $absensi->alasan ?? '-',
                    ]);
                    $writer->addRow($row);
                }
            }

            $writer->close();
        }, 'data_absensi_' . now()->format('Ymd_His') . '.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
