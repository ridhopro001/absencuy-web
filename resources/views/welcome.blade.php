@extends('mahasiswa.layout')

@section('title', 'Beranda')

@section('content')
<div class="card" style="text-align: center;">
    <h1 style="font-size: 2rem; margin-bottom: 0.5rem;">Sistem Absensi Wajah & Lokasi</h1>
    <p style="color: #666; font-size: 1.1rem; margin-bottom: 0.5rem;">Absensi berbasis pengenalan wajah (face-api.js) dan validasi lokasi (geofencing)</p>
    <p style="color: #666; font-size: 0.9rem;">Mahasiswa dapat melakukan absensi wajah dan pengajuan izin/sakit.</p>
</div>

<div class="menu-grid">
    <a href="{{ route('absensi.index') }}" class="menu-card">
        <div class="icon">✅</div>
        <h3>Absensi Wajah</h3>
        <p>Scan wajah untuk absensi dengan validasi lokasi</p>
    </a>
    <a href="{{ route('izin.index') }}" class="menu-card">
        <div class="icon">📝</div>
        <h3>Izin / Sakit</h3>
        <p>Ajukan izin atau sakit tanpa scan wajah</p>
    </a>
</div>

<div class="card" style="margin-top: 1.5rem;">
    <h3 style="margin-bottom: 1rem;">Cara Penggunaan</h3>
    <ol style="padding-left: 1.5rem; color: #666; line-height: 1.8;">
        <li><strong>Absensi</strong> — Scan wajah Anda, pastikan berada dalam radius lokasi yang ditentukan, lalu kirim absensi.</li>
        <li><strong>Izin/Sakit</strong> — Jika absensi wajah bermasalah, tidak bisa hadir, sakit, ajukan melalui form manual.</li>
    </ol>
</div>
@endsection
