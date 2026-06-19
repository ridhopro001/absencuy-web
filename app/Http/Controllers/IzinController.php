<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\JurusanSetting;
use App\Models\Mahasiswa;
use App\Models\SemesterSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IzinController extends Controller
{
    public function index()
    {
        $jurusanList = JurusanSetting::pluck('nama_jurusan')->toArray();
        $semesterList = SemesterSetting::pluck('nama_semester')->toArray();
        return view('mahasiswa.izin', compact('jurusanList', 'semesterList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:255',
            'jurusan' => 'required|in:' . implode(',', JurusanSetting::pluck('nama_jurusan')->toArray()),
            'semester' => 'required|in:' . implode(',', SemesterSetting::pluck('nama_semester')->toArray()),
            'status' => 'required|in:Izin,Sakit',
            'alasan' => 'nullable|string',
            'file_pendukung' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp|max:2048',
        ]);

        $mahasiswa = Mahasiswa::whereRaw('LOWER(TRIM(nama)) = ?', [strtolower(trim($validated['nama']))])
            ->whereRaw('TRIM(nim) = ?', [trim($validated['nim'])])
            ->whereRaw('TRIM(jurusan) = ?', [trim($validated['jurusan'])])
            ->where('semester', trim($validated['semester']))
            ->first();

        if (!$mahasiswa) {
            return back()->withErrors(['error' => 'Maaf data anda tidak terdaftar, silahkan hubungi admin.'])->withInput();
        }

        $already = Absensi::where('mahasiswa_id', $mahasiswa->id)
            ->whereDate('tanggal', now())
            ->exists();

        if ($already) {
            return back()->withErrors(['error' => 'Anda sudah melakukan absensi/izin/sakit hari ini.'])->withInput();
        }

        $filePath = $request->file('file_pendukung')->store('file_pendukung', 'public_storage');

        Absensi::create([
            'mahasiswa_id' => $mahasiswa->id,
            'tanggal' => now(),
            'waktu' => now()->format('H:i:s'),
            'status' => $validated['status'],
            'alasan' => $validated['alasan'] ?? null,
            'file_pendukung' => $filePath,
        ]);

        return redirect()->back()->with('success', 'Pengajuan ' . $validated['status'] . ' berhasil dikirim.');
    }
}
