# Task: Sistem Absensi Wajah & Lokasi (Laravel + face-api.js)

## 1. Overview
Aplikasi absensi mahasiswa berbasis pengenalan wajah (face recognition) dan validasi lokasi (geofencing).

Terdiri dari 2 sisi:
- **Mahasiswa** (tanpa login): registrasi wajah, absensi wajah + lokasi, form izin/sakit
- **Admin** (dengan login): dashboard riwayat, pengaturan lokasi, pengaturan jumlah mahasiswa per jurusan, manajemen rekap

## 2. Tech Stack
- Laravel (backend + view/blade)
- MySQL
- face-api.js (face detection & recognition, client-side)
- Geolocation API (browser) + formula Haversine (hitung jarak)
- Laravel Excel (maatwebsite/excel) untuk export rekap
- Filament

## 3. Database Schema

### Tabel `mahasiswas`
- id (PK)
- nama (string)
- nim (string, unique)
- jurusan (string) — salah satu dari: Teknik Informatika, Teknik Sipil, Teknik Budidaya Perkebunan
- semester (string, nullable) — contoh: Semester I, Semester II, dst
- face_descriptor (json) — array 128 float hasil ekstraksi face-api.js
- timestamps

### Tabel `lokasi_absensi`
- id (PK)
- latitude (decimal)
- longitude (decimal)
- radius_meter (integer, default 3000)
- timestamps

### Tabel `jurusan_settings`
- id (PK)
- nama_jurusan (string)
- jumlah_mahasiswa (integer) — diisi admin, jadi acuan label peringatan

### Tabel `absensis`
- id (PK)
- mahasiswa_id (FK -> mahasiswas)
- tanggal (date)
- waktu (time)
- status (enum: Hadir, Izin, Sakit, Alpa, Hadir (Lokasi Tidak Valid))
- latitude (decimal, nullable) — hanya diisi saat status Hadir
- longitude (decimal, nullable)
- lokasi_sumber (string, nullable) — gps/wifi/ip
- alasan (text, nullable) — wajib diisi jika status = Izin
- file_pendukung (string, nullable) — path file pdf/doc
- timestamps

### Tabel `users` (admin)
Default filament.

---

## 4. Modul Mahasiswa (Tanpa Login)

### 4.1 Registrasi Wajah
- Form input: nama, nim (unique), jurusan (dropdown 3 pilihan)
- Aktifkan webcam → capture wajah → ekstrak face descriptor via face-api.js
- Simpan nama + nim + jurusan + face_descriptor ke tabel `mahasiswas`

### 4.2 Absensi (Face Recognition + Validasi Lokasi)
1. Aktifkan webcam, scan wajah secara realtime dengan face-api.js
2. Bandingkan descriptor wajah dengan seluruh data di `mahasiswas` (euclidean distance, threshold sekitar 0.5–0.6)
3. Jika cocok → tampilkan popup berisi nama, nim, jurusan, dengan 2 tombol: **"Kirim Absensi"** dan **"Batal"**
4. Saat klik **Kirim Absensi**:
   - Ambil koordinat user via Geolocation API
   - Ambil titik lokasi & radius dari `lokasi_absensi`
   - Hitung jarak antar koordinat (Haversine formula)
   - **Jika jarak ≤ radius_meter** → simpan ke `absensis` (status = `Hadir`, isi latitude/longitude), masuk ke rekap aktif, tampil di dashboard admin sebagai "Hadir ✅"
   - **Jika jarak > radius_meter** → JANGAN simpan ke database. Tampilkan pesan **"Hadir tidak valid"** (di luar radius lokasi absensi). User dapat mencoba absen ulang.
5. Saat klik **Batal** → tutup popup, tidak ada data yang tersimpan

### 4.3 Form Izin / Sakit
- Input manual: nama, nim, jurusan (dropdown 3 pilihan: Teknik Informatika, Teknik Sipil, Teknik Budidaya Perkebunan)
- Pilih status: Izin atau Sakit
- Alasan (textarea, **wajib diisi jika status Izin**)
- Upload file PDF/Word (opsional)
- Simpan ke `absensis` dengan status sesuai pilihan, masuk ke rekap aktif, tampil di dashboard admin

---

## 5. Modul Admin (Login)

### 5.1 Login
- Auth standar
- Email : mediasosial8899@gmail.com
- Password : Admin9911

### 5.2 Dashboard & Riwayat Absensi
- Tabel riwayat: nama, nim, jurusan, status (Hadir ✅ / Izin / Sakit / Alpa), tanggal, waktu, alasan/keterangan, link file pendukung (jika ada)
- Filter berdasarkan status: Hadir, Izin, Sakit, Alpa
- Pagination: 10 data per halaman

### 5.3 Pengaturan Lokasi Absensi
- Form update latitude & longitude (titik acuan lokasi absensi)
- Form update radius toleransi (default 3000 m / 3 km)
- Disimpan/diupdate pada 1 baris di tabel `lokasi_absensi`

### 5.4 Pengaturan Jumlah Mahasiswa per Jurusan & Label Peringatan
- Form input jumlah mahasiswa untuk masing-masing dari 3 jurusan → simpan ke `jurusan_settings`
- Sistem menghitung otomatis: total (Hadir + Izin + Sakit) per jurusan (dari seluruh data absensi)
- Jika total tersebut < jumlah_mahasiswa yang diset admin → tampilkan label **"⚠️ Peringatan"** pada jurusan tersebut di dashboard

### 5.5 Input Data Alpa (Manual)
- Admin memilih mahasiswa (search berdasarkan nama/nim) dari tabel `mahasiswas`
- Simpan ke `absensis` dengan status = `Alpa`
- Muncul di riwayat absensi dan dapat difilter dengan status Alpa

### 5.6 Export Excel
- Tombol **"Export Excel"** di halaman Riwayat Absensi
- Data yang diexport sesuai dengan filter yang sedang aktif (status, jurusan, dll)
- File Excel langsung di-download tanpa disimpan di database

---

## 6. Catatan Teknis
- **Haversine formula**: untuk menghitung jarak antara 2 koordinat (hasil dalam meter/km). Sebaiknya dihitung di frontend (untuk respons cepat ke user) dan divalidasi ulang di backend (agar tidak mudah dimanipulasi via request langsung)
- **face-api.js**: load model `tinyFaceDetector` (atau `ssdMobilenetv1`) + `faceLandmark68Net` + `faceRecognitionNet`; descriptor wajah disimpan sebagai array JSON di kolom `face_descriptor`
- **File pendukung izin/sakit**: simpan menggunakan `Storage::disk('public')`, validasi tipe file (pdf, doc, docx) dan batas ukuran maksimal
- **Status "Hadir tidak valid"**: tidak perlu disimpan ke database — cukup ditangani sebagai notifikasi/error di sisi frontend setelah validasi jarak gagal
- **Export Excel**: langsung dari data `absensis` sesuai filter pada halaman riwayat, menggunakan OpenSpout Writer, tidak perlu menyimpan file di database
-
