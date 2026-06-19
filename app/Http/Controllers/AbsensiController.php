<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\LokasiAbsensi;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index()
    {
        return view('mahasiswa.absensi');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'lokasi_sumber' => 'nullable|string|in:gps,ip',
        ]);

        $alreadyAbsent = Absensi::where('mahasiswa_id', $validated['mahasiswa_id'])
            ->whereDate('tanggal', now())
            ->exists();

        if ($alreadyAbsent) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absensi hari ini.',
            ], 400);
        }

        $lokasi = LokasiAbsensi::first();
        $lokasiValid = false;
        $alasan = null;

        if ($lokasi) {
            $distance = $this->haversine(
                $validated['latitude'],
                $validated['longitude'],
                (float) $lokasi->latitude,
                (float) $lokasi->longitude
            );

            if ($distance <= $lokasi->radius_meter) {
                $lokasiValid = true;
            } else {
                $alasan = 'Lokasi tidak valid (jarak: ' . round($distance, 1) . ' m dari titik acuan)';
            }
        } else {
            $alasan = 'Lokasi absensi belum diatur oleh admin.';
        }

        $status = $lokasiValid ? 'Hadir' : 'Hadir (Lokasi Tidak Valid)';

        $absensi = Absensi::create([
            'mahasiswa_id' => $validated['mahasiswa_id'],
            'tanggal' => now(),
            'waktu' => now()->format('H:i:s'),
            'status' => $status,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'lokasi_sumber' => $validated['lokasi_sumber'] ?? null,
            'alasan' => $alasan,
        ]);

        return response()->json([
            'success' => true,
            'message' => $lokasiValid
                ? 'Absensi berhasil!'
                : 'Absensi tercatat tetapi lokasi Anda tidak valid.',
            'data' => $absensi->load('mahasiswa'),
        ]);
    }

    public function getMahasiswas()
    {
        return response()->json(Mahasiswa::select('id', 'nama', 'nim', 'jurusan', 'semester', 'face_descriptor')->get());
    }

    public function getLokasi()
    {
        return response()->json(LokasiAbsensi::first());
    }

    private function haversine($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
