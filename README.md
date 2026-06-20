<div align="center">
  <h1>📸 Absen Cuy</h1>
  <p><strong>Sistem Absensi Mahasiswa berbasis Pengenalan Wajah & Lokasi</strong></p>
  <p>Dibangun dengan Laravel 13 + Filament 5.6 + face-api.js</p>
</div>

---

## Panduan Clone & Install

```bash
git clone https://github.com/username/absencuy.git
cd absencuy
```

<p><b>Peringatan!</b> Sebelum menjalankan perintah di bawah, pastikan kamu sudah membuat folder "vendor/composer" secara manual</p>

```bash
composer install
cp .env.example .env
```

### 1. Generate Key

```bash
php artisan key:generate
```

### 2. Setting Database

Edit `.env`:
```
DB_DATABASE=absencuy
DB_USERNAME=root
DB_PASSWORD=
```

Lalu jalankan:

```bash
php artisan migrate
```

```bash
php artisan db:seed
```

### 3. Setting Email (OTP)

Wajib diisi agar fitur reset password via OTP berfungsi:

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=emailkamu@gmail.com
MAIL_PASSWORD=xxxx xxxx xxxx xxxx
MAIL_ENCRYPTION=tls
```

### 4. Install & Build

```bash
npm install
npm run build
php artisan serve
```


---

## ⚠️ Catatan Penting

- **Loading model face-api.js cukup lambat di awal** karena mengunduh file model (~5MB) dari CDN. Tunggu hingga muncul notifikasi "Model siap" sebelum melakukan absensi.
- **Format upload foto untuk ekstraksi wajah**: `Nama Mahasiswa,NIM,Jurusan,Semester.jpg`
  Contoh: `Budi Santoso,2021001,Teknik Informasi,Semester 2.jpg`

---

## Cara Penggunaan

### Admin

| URL | Fungsi |
|---|---|
| `/admin` | Login admin |
| `/admin/extract-faces` | Ekstraksi wajah dari foto |
| `/admin/mahasiswas` | Kelola data mahasiswa |
| `/admin/absensis` | Riwayat absensi |
| `/admin/lokasi-absensis` | Atur titik & radius absensi |
| `/admin/jurusan-settings` | Atur target jumlah jurusan |
| `/admin/semester-settings` | Atur target jumlah semester |


**Login default:**
- Email: `admin@gmail.com`
- Password: `admin123`

### Mahasiswa

| URL | Fungsi |
|---|---|
| `/` | Halaman depan |
| `/absensi` | Absensi wajah (wajib izin lokasi & kamera) |
| `/izin` | Form izin/sakit |

---

## Command Artisan

```bash
# Sinkronisasi foto mahasiswa (batch process)
php artisan mahasiswa:sync-photos
```

---

<div align="center">
  <p>Anda bebas mengembangkan website ini untuk menjadi lebih baik, Terima kasih~</b></p>
</div>
