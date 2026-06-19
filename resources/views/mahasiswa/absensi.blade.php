@extends('mahasiswa.layout')

@section('title', 'Absensi Wajah')

@section('content')
<div class="card">
    <h1>Absensi Wajah</h1>
    <p style="color: #666; margin-bottom: 1.5rem;">Scan wajah Anda untuk melakukan absensi. Pastikan Anda berada dalam radius lokasi yang ditentukan.</p>

    <div id="cancelAlert" style="display: none;" class="alert alert-warning">
        <strong>✗ Absensi Tidak Terkirim</strong> Anda membatalkan proses absensi.
    </div>
    <div id="cancelActions" style="display: none; margin-top: 1rem; gap: 0.75rem;">
        <button id="restartBtn2" class="btn btn-primary">Mulai Ulang Absensi</button>
        <a href="{{ route('home') }}" class="btn btn-secondary" style="text-decoration: none;">Kembali ke Beranda</a>
    </div>
    <div id="countdownText" style="display: none; margin-top: 0.75rem; text-align: center; font-size: 0.9rem; color: #666;"></div>
    <div id="statusMessage"></div>
    <div id="errorContainer" style="display: none;" class="alert alert-error"></div>

    <div class="video-container" id="videoContainer">
        <video id="video" width="640" height="480" autoplay muted playsinline></video>
        <canvas id="overlay" width="640" height="480"></canvas>
        <div id="scanOverlay" class="scan-overlay">
            <div class="scan-line"></div>
        </div>
    </div>
    <div id="faceStatus" class="face-status loading">Memuat model...</div>
    <div style="text-align: center; margin-top: 1.5rem;">
        <button id="startBtn" class="btn btn-primary" style="display: none; font-size: 1.1rem; padding: 1rem 2.5rem;" disabled>Mulai Absensi</button>
    </div>
    <div id="scanTimer" style="text-align: center; font-size: 0.85rem; color: #999; margin-top: 0.5rem;"></div>
    <div id="invalidLocation" style="display: none; margin-top: 1rem;" class="alert alert-error">
        Hadir tidak valid - Anda berada di luar radius lokasi absensi.
    </div>
</div>

<div id="recognizedModal" class="modal-overlay" style="display: none;">
    <div class="modal modal-recognized">
        <div class="modal-icon">&#10003;</div>
        <h2 style="color: #059669; margin-bottom: 0.5rem;">Wajah Dikenali!</h2>
        <p style="color: #666; margin-bottom: 1.5rem;">Data Anda telah cocok dengan sistem.</p>
        <table class="info-table">
            <tr><td class="label">Nama</td><td class="sep">:</td><td class="value" id="popupNama">-</td></tr>
            <tr><td class="label">NIM</td><td class="sep">:</td><td class="value" id="popupNim">-</td></tr>
            <tr><td class="label">Jurusan</td><td class="sep">:</td><td class="value" id="popupJurusan">-</td></tr>
            <tr><td class="label">Semester</td><td class="sep">:</td><td class="value" id="popupSemester">-</td></tr>
            <tr><td class="label">Lokasi</td><td class="sep">:</td><td class="value" id="popupLokasi">Memverifikasi...</td></tr>
        </table>
        <div class="btn-group" style="margin-top: 1.5rem; display: flex; gap: 1rem; justify-content: center;">
            <button id="submitAbsensiBtn" class="btn btn-success" disabled>Kirim Absensi</button>
            <button id="cancelBtn" class="btn btn-secondary">Batal</button>
        </div>
    </div>
</div>

<div id="processingModal" class="modal-overlay" style="display: none;">
    <div class="modal">
        <div class="loading">
            <div class="spinner"></div>
        </div>
        <h2>Memproses Absensi...</h2>
        <p style="color: #666; margin-top: 0.5rem;">Menyimpan kehadiran Anda.</p>
    </div>
</div>
@endsection

@push('styles')
<style>
    .video-container { position: relative; max-width: 640px; margin: 0 auto; overflow: hidden; border-radius: 8px; display: none; }
    #video { width: 100%; border-radius: 8px; background: #000; display: none; }
    #overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; }
    .scan-overlay {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none;
        border: 3px solid transparent; border-radius: 8px; transition: border-color 0.3s;
        box-sizing: border-box;
    }
    .scan-overlay.active { border-color: #3b82f6; }
    .scan-overlay.matched { border-color: #059669; }
    .scan-overlay.not-matched { border-color: #dc2626; }
    .scan-line {
        position: absolute; left: 10%; width: 80%; height: 2px; background: linear-gradient(90deg, transparent, #3b82f6, transparent);
        animation: scanMove 2s ease-in-out infinite; opacity: 0; transition: opacity 0.3s;
    }
    .scan-overlay.active .scan-line { opacity: 1; }
    @keyframes scanMove {
        0% { top: 5%; }
        50% { top: 95%; }
        100% { top: 5%; }
    }
    .face-status { text-align: center; padding: 1rem; margin-top: 1rem; border-radius: 8px; font-weight: 500; transition: all 0.3s; }
    .face-status.loading { background: #fef3c7; color: #92400e; }
    .face-status.success { background: #d1fae5; color: #065f46; }
    .face-status.error { background: #fee2e2; color: #991b1b; }
    .face-status.info { background: #dbeafe; color: #1e40af; }
    .modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);
        display: flex; align-items: center; justify-content: center; z-index: 1000;
    }
    .modal { background: white; border-radius: 12px; padding: 2rem; max-width: 420px; width: 90%; text-align: center; }
    .modal h2 { margin-bottom: 1rem; }
    .modal p { margin-bottom: 0.5rem; color: #666; }
    .modal .btn-group { margin-top: 1.5rem; }
    .modal-icon {
        width: 60px; height: 60px; border-radius: 50%; background: #d1fae5; color: #059669;
        display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;
        font-size: 1.8rem; font-weight: bold;
    }
    .info-table { width: 100%; margin: 0 auto; text-align: left; }
    .info-table td { padding: 0.4rem 0.3rem; vertical-align: top; }
    .info-table .label { font-weight: 500; color: #374151; white-space: nowrap; width: 80px; }
    .info-table .sep { color: #9ca3af; width: 12px; }
    .info-table .value { color: #111827; word-break: break-word; }
    .loading { text-align: center; padding: 1rem; }
    .loading .spinner { width: 40px; height: 40px; border: 4px solid #e5e7eb; border-top-color: #1a1a2e; border-radius: 50%; animation: spin 0.8s linear infinite; margin: 0 auto 1rem; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .btn:disabled { opacity: 0.5; cursor: not-allowed; }
    .loc-detail { font-size: 0.8rem; line-height: 1.6; }
</style>
@endpush

@push('scripts')
<script>
let allMahasiswas = [];
let matchedMahasiswa = null;
let currentPosition = null;
let videoStream = null;
let isModelLoaded = false;
let recognitionActive = true;
let scanCount = 0;
const SCAN_DURATION = 30;
let scanEndTime = null;
let scanCountdownInterval = null;

// Location acquisition state
let lokasiAbsensi = null;
let lokasiSumber = null;

const video = document.getElementById('video');
const overlay = document.getElementById('overlay');
const ctx = overlay.getContext('2d');
const faceStatus = document.getElementById('faceStatus');
const recognizedModal = document.getElementById('recognizedModal');
const processingModal = document.getElementById('processingModal');
const invalidLocation = document.getElementById('invalidLocation');
const scanOverlay = document.getElementById('scanOverlay');
const scanTimer = document.getElementById('scanTimer');
const statusMessage = document.getElementById('statusMessage');
const errorContainer = document.getElementById('errorContainer');

function setStatus(text, type = 'loading') {
    faceStatus.textContent = text;
    faceStatus.className = 'face-status ' + type;
}

function showError(msg) {
    errorContainer.textContent = msg;
    errorContainer.style.display = 'block';
}

function hideError() {
    errorContainer.style.display = 'none';
}

const MODEL_CDN = 'https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js@master/weights/';
const MODEL_LOCAL = '/models';

(async function initFaceApi() {
    const sources = [
        { url: MODEL_CDN, label: 'CDN global', isCDN: true },
        { url: MODEL_LOCAL, label: 'server lokal', isCDN: false },
    ];

    let loaded = false;
    for (const source of sources) {
        try {
            setStatus(`Mengunduh model dari ${source.label}...`, 'loading');

            if (source.isCDN) {
                // CDN: parallel download from global edge servers (bypasses ngrok)
                await Promise.all([
                    faceapi.nets.tinyFaceDetector.loadFromUri(source.url),
                    faceapi.nets.faceLandmark68Net.loadFromUri(source.url),
                    faceapi.nets.faceRecognitionNet.loadFromUri(source.url),
                ]);
            } else {
                // Local: sequential to avoid overwhelming single-threaded dev server
                setStatus('Memuat model detektor wajah...', 'loading');
                await faceapi.nets.tinyFaceDetector.loadFromUri(source.url);
                setStatus('Memuat model landmark wajah...', 'loading');
                await faceapi.nets.faceLandmark68Net.loadFromUri(source.url);
                setStatus('Memuat model pengenal wajah...', 'loading');
                await faceapi.nets.faceRecognitionNet.loadFromUri(source.url);
            }

            loaded = true;
            break;
        } catch (err) {
            console.warn(`Gagal memuat dari ${source.label}:`, err);
            if (source.isCDN) {
                setStatus(`CDN gagal, mencoba dari ${sources[1].label}...`, 'loading');
            }
        }
    }

    if (loaded) {
        isModelLoaded = true;
        setStatus('Model dimuat. Memuat data mahasiswa...', 'info');
        loadMahasiswas();
    } else {
        setStatus('Gagal memuat model face-api. Periksa koneksi internet.', 'error');
        showError('Model pengenalan wajah gagal dimuat. Pastikan koneksi internet stabil.');
    }
})();

async function loadMahasiswas() {
    try {
        const res = await fetch('{{ route("absensi.mahasiswas") }}');
        allMahasiswas = await res.json();
        const registered = allMahasiswas.filter(m => m.face_descriptor).length;
        setStatus('Data dimuat (' + registered + ' mahasiswa terdaftar). Tekan tombol Mulai untuk memindai.', 'info');
        const startBtn = document.getElementById('startBtn');
        startBtn.style.display = 'inline-block';
        startBtn.disabled = false;
    } catch (err) {
        setStatus('Gagal memuat data mahasiswa.', 'error');
        showError('Tidak dapat mengambil data mahasiswa dari server.');
    }
}

async function startVideo() {
    document.getElementById('startBtn').style.display = 'none';
    recognitionActive = true;
    document.getElementById('videoContainer').style.display = 'block';
    video.style.display = 'block';
    scanOverlay.style.display = 'block';
    faceStatus.style.display = 'block';

    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        setStatus('Kamera tidak tersedia.', 'error');
        document.getElementById('videoContainer').style.display = 'none';
        video.style.display = 'none';
        scanOverlay.style.display = 'none';
        faceStatus.style.display = 'none';
        recognitionActive = false;
        statusMessage.innerHTML =
            '<div class="alert alert-error" style="text-align:left;">Akses kamera membutuhkan koneksi HTTPS atau localhost. Akses halaman ini melalui localhost atau gunakan koneksi HTTPS.</div>' +
            '<div style="display: flex; gap: 8px; margin-top: 12px; justify-content: flex-start;">' +
            '<a href="{{ route("home") }}" class="btn btn-secondary" style="text-decoration:none;">Kembali ke Beranda</a>' +
            '</div>';
        document.getElementById('startBtn').style.display = 'none';
        return;
    }

    try {
        videoStream = await navigator.mediaDevices.getUserMedia({
            video: { width: 640, height: 480, facingMode: 'user' }
        });
        video.srcObject = videoStream;
        await video.play();
        setStatus('Kamera aktif. Memindai wajah...', 'loading');
        scanOverlay.className = 'scan-overlay active';
        scanCount = 0;
        hideError();
        startScanCountdown();
        scheduleNextDetection();
    } catch (err) {
        setStatus('Gagal mengakses kamera.', 'error');
        if (err.name === 'NotAllowedError') {
            showError('Akses kamera ditolak. Izinkan akses kamera di pengaturan browser Anda.');
        } else if (err.name === 'NotFoundError') {
            showError('Kamera tidak ditemukan. Sambungkan kamera dan coba lagi.');
        } else {
            showError('Gagal mengakses kamera: ' + err.message);
        }
    }
}

document.getElementById('startBtn').addEventListener('click', function() {
    startVideo();
});


function startScanCountdown() {
    clearInterval(scanCountdownInterval);
    scanEndTime = Date.now() + SCAN_DURATION * 1000;
    scanCountdownInterval = setInterval(() => {
        const remaining = Math.max(0, Math.round((scanEndTime - Date.now()) / 1000));
        scanTimer.textContent = 'Sisa waktu ' + remaining + ' detik (pemindaian ke-' + scanCount + ')';
        if (remaining <= 0) {
            clearInterval(scanCountdownInterval);
            scanCountdownInterval = null;
            handleScanTimeout();
        }
    }, 500);
}

function handleScanTimeout() {
    if (!recognitionActive) return;
    recognitionActive = false;
    scanOverlay.className = 'scan-overlay';
    stopCamera();
    setStatus('Waktu habis! Wajah tidak terdeteksi dalam ' + SCAN_DURATION + ' detik.', 'error');
    scanTimer.textContent = '';
    document.getElementById('videoContainer').style.display = 'none';
    video.style.display = 'none';
    scanOverlay.style.display = 'none';
    faceStatus.style.display = 'none';
    hideError();
    const homeBtn = '<a href="{{ route("home") }}" class="btn btn-secondary" style="text-decoration:none; margin-left:8px;">Kembali Ke Beranda</a>';
    statusMessage.innerHTML =
        '<div class="alert alert-warning">Wajah tidak terdeteksi dalam batas waktu. Silakan coba lagi.</div>' +
        '<div style="display: flex; gap: 8px; margin-top: 12px;">' +
        '<button type="button" class="btn btn-primary" onclick="startRestartCountdown(this)">Coba Lagi</button>' +
        homeBtn +
        '</div>';
}

let detectionRunning = false;

async function scheduleNextDetection() {
    if (!recognitionActive) return;
    if (detectionRunning) {
        setTimeout(scheduleNextDetection, 100);
        return;
    }
    detectionRunning = true;
    try {
        await runDetection();
    } catch (err) {
        console.error('Detection error:', err);
        if (recognitionActive) {
            setStatus('Kesalahan deteksi. Mencoba lagi...', 'error');
        }
    }
    detectionRunning = false;
    if (recognitionActive) {
        setTimeout(scheduleNextDetection, 200);
    }
}

async function runDetection() {
    scanCount++;

    const detection = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({
        inputSize: 320,
        scoreThreshold: 0.5
    })).withFaceLandmarks().withFaceDescriptor();

    ctx.clearRect(0, 0, overlay.width, overlay.height);

    if (detection) {
        const box = detection.detection.box;
        ctx.strokeStyle = '#00ff00';
        ctx.lineWidth = 3;
        ctx.strokeRect(box.x, box.y, box.width, box.height);

        const match = findBestMatch(Array.from(detection.descriptor));
        if (match) {
            matchedMahasiswa = match;
            recognitionActive = false;
            clearInterval(scanCountdownInterval);
            scanCountdownInterval = null;
            scanOverlay.className = 'scan-overlay matched';
            scanTimer.textContent = '';
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
            }
            displayRecognizedInfo(match);
            return;
        } else {
            scanOverlay.className = 'scan-overlay not-matched';
            setStatus('Wajah tidak dikenali. Hubungi admin untuk pendaftaran.', 'error');
        }
    } else {
        scanOverlay.className = 'scan-overlay active';
        setStatus('Pindai wajah ke kamera...', 'loading');
    }
}

function findBestMatch(descriptor) {
    let bestMatch = null;
    let bestDistance = Infinity;

    for (const m of allMahasiswas) {
        if (!m.face_descriptor) continue;
        const storedDescriptor = m.face_descriptor;

        if (!Array.isArray(storedDescriptor) || storedDescriptor.length !== 128) continue;

        const distance = euclideanDistance(descriptor, storedDescriptor);
        if (distance < bestDistance) {
            bestDistance = distance;
            bestMatch = m;
        }
    }

    const threshold = 0.55;
    return bestDistance < threshold ? bestMatch : null;
}

function euclideanDistance(a, b) {
    let sum = 0;
    for (let i = 0; i < 128; i++) {
        sum += (a[i] - b[i]) ** 2;
    }
    return Math.sqrt(sum);
}

function displayRecognizedInfo(mahasiswa) {
    document.getElementById('popupNama').textContent = mahasiswa.nama;
    document.getElementById('popupNim').textContent = mahasiswa.nim;
    document.getElementById('popupJurusan').textContent = mahasiswa.jurusan;
    document.getElementById('popupSemester').textContent = mahasiswa.semester || '-';
    recognizedModal.style.display = 'flex';
    setStatus('Wajah dikenali! ✓', 'success');
    hideError();
    startLocationAcquisition();
}

async function startLocationAcquisition() {
    document.getElementById('submitAbsensiBtn').disabled = true;
    const displayLokasi = document.getElementById('popupLokasi');

    try {
        const res = await fetch('{{ route("absensi.lokasi") }}');
        lokasiAbsensi = await res.json();
    } catch (e) {
        console.error('Gagal mengambil data lokasi absensi:', e);
    }

    if (!navigator.geolocation) {
        fallbackToIp();
        return;
    }

    displayLokasi.textContent = 'Mendapatkan lokasi (GPS)...';
    displayLokasi.style.color = '#f59e0b';

    try {
        const position = await getPosition({ enableHighAccuracy: true, timeout: 10000 });

        lokasiSumber = 'gps';
        applyLocation(position.coords.latitude, position.coords.longitude, position.coords.accuracy);
    } catch {
        fallbackToIp();
    }
}

function getPosition(options) {
    return new Promise((resolve, reject) => {
        navigator.geolocation.getCurrentPosition(resolve, reject, {
            ...options,
            maximumAge: 0,
        });
    });
}

function applyLocation(lat, lon, accuracy) {
    currentPosition = { latitude: lat, longitude: lon, accuracy };
    const displayLokasi = document.getElementById('popupLokasi');

    document.getElementById('submitAbsensiBtn').disabled = false;

    const sumberLabel = lokasiSumber === 'gps' ? 'GPS' : 'IP (perkiraan)';

    if (lokasiAbsensi && lokasiAbsensi.radius_meter != null) {
        const distance = haversineDistance(
            lat, lon,
            parseFloat(lokasiAbsensi.latitude),
            parseFloat(lokasiAbsensi.longitude)
        );

        const isWithinRadius = distance <= lokasiAbsensi.radius_meter;
        const statusIcon = isWithinRadius ? '✅' : '❌';
        const statusText = isWithinRadius ? 'Lokasi Valid' : 'Lokasi Tidak Valid';

        displayLokasi.innerHTML = statusIcon + ' ' + statusText +
            '<br><span class="loc-detail">Latitude: ' + lat.toFixed(6) + '</span>' +
            '<br><span class="loc-detail">Longitude: ' + lon.toFixed(6) + '</span>' +
            '<br><span class="loc-detail">Radius admin: ' + lokasiAbsensi.radius_meter + 'm | Jarak user: ' + Math.round(distance) + 'm</span>' +
            '<br><span class="loc-detail">Akurasi GPS: ' + Math.round(accuracy) + 'm</span>';
        displayLokasi.style.color = isWithinRadius ? '#059669' : '#d97706';
    } else {
        displayLokasi.textContent = 'Lokasi terverifikasi (' + lat.toFixed(4) + ', ' + lon.toFixed(4) + ') - ' + sumberLabel + ' (akurasi GPS ' + Math.round(accuracy) + 'm)';
        displayLokasi.style.color = '#059669';
    }
}

function fallbackToIp() {
    lokasiSumber = 'ip';
    const displayLokasi = document.getElementById('popupLokasi');
    displayLokasi.textContent = 'Mendapatkan lokasi melalui IP...';
    displayLokasi.style.color = '#f59e0b';

    fetch('https://ipapi.co/json/')
        .then(res => res.json())
        .then(data => {
            if (data.latitude && data.longitude) {
                applyLocation(data.latitude, data.longitude, 10000);
            } else {
                displayLokasi.textContent = 'Lokasi tidak tersedia. Anda tetap bisa melanjutkan.';
                displayLokasi.style.color = '#dc2626';
                document.getElementById('submitAbsensiBtn').disabled = false;
            }
        })
        .catch(() => {
            fetch('https://ip-api.com/json/')
                .then(res => res.json())
                .then(data => {
                    if (data.lat && data.lon) {
                        applyLocation(data.lat, data.lon, 10000);
                    } else {
                        displayLokasi.textContent = 'Lokasi tidak tersedia. Anda tetap bisa melanjutkan.';
                        displayLokasi.style.color = '#dc2626';
                        document.getElementById('submitAbsensiBtn').disabled = false;
                    }
                })
                .catch(() => {
                    displayLokasi.textContent = 'Gagal mendapatkan lokasi. Anda tetap bisa melanjutkan.';
                    displayLokasi.style.color = '#dc2626';
                    document.getElementById('submitAbsensiBtn').disabled = false;
                });
        });
}

function haversineDistance(lat1, lon1, lat2, lon2) {
    const R = 6371000;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}

document.getElementById('submitAbsensiBtn').addEventListener('click', async function() {
    if (!matchedMahasiswa) {
        showError('Data wajah belum tersedia. Silakan scan ulang.');
        return;
    }
    if (!currentPosition) {
        showError('Lokasi belum terverifikasi. Tunggu proses lokasi selesai.');
        document.getElementById('popupLokasi').textContent = 'Mendapatkan lokasi...';
        startLocationAcquisition();
        return;
    }

    recognizedModal.style.display = 'none';
    processingModal.style.display = 'flex';

    try {
        const res = await fetch('{{ route("absensi.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                mahasiswa_id: matchedMahasiswa.id,
                latitude: currentPosition.latitude,
                longitude: currentPosition.longitude,
                lokasi_sumber: lokasiSumber,
            }),
        });

        const result = await res.json();
        processingModal.style.display = 'none';

        if (result.success) {
            video.style.display = 'none';
            scanOverlay.style.display = 'none';
            faceStatus.style.display = 'none';
            scanTimer.style.display = 'none';
            document.getElementById('videoContainer').style.display = 'none';

            const homeBtn = '<a href="{{ route("home") }}" class="btn btn-primary" style="margin-top: 1rem; display: inline-block; text-decoration: none;">Kembali ke Beranda</a>';

            if (result.message.includes('lokasi')) {
                statusMessage.innerHTML = '<div class="alert alert-warning"><strong>⚠ Absensi Tercatat</strong> ' + result.message + '</div>' + homeBtn;
            } else {
                statusMessage.innerHTML = '<div class="alert alert-success"><strong>✓ Berhasil!</strong> Absensi Anda telah tercatat sebagai Hadir.</div>' + homeBtn;
            }
            recognizedModal.style.display = 'none';
            invalidLocation.style.display = 'none';
            hideError();
            resetScan();
        } else {
            video.style.display = 'none';
            scanOverlay.style.display = 'none';
            faceStatus.style.display = 'none';
            scanTimer.style.display = 'none';
            document.getElementById('videoContainer').style.display = 'none';
            const homeBtn = '<a href="{{ route("home") }}" class="btn btn-primary" style="margin-top: 1rem; display: inline-block; text-decoration: none;">Kembali ke Beranda</a>';
            statusMessage.innerHTML = '<div class="alert alert-warning">' + result.message + '</div>' + homeBtn;
            recognizedModal.style.display = 'none';
            resetScan();
        }
    } catch (err) {
        processingModal.style.display = 'none';
        statusMessage.innerHTML = '<div class="alert alert-error"><strong>✗ Kesalahan Koneksi</strong> Tidak dapat terhubung ke server. Periksa koneksi internet Anda.</div>';
        resetScan();
    }
});

function resetScan() {
    recognizedModal.style.display = 'none';
    invalidLocation.style.display = 'none';
    matchedMahasiswa = null;
    currentPosition = null;
    document.getElementById('submitAbsensiBtn').disabled = true;
    clearInterval(scanCountdownInterval);
    scanCountdownInterval = null;
}

function stopCamera() {
    if (videoStream) {
        videoStream.getTracks().forEach(track => track.stop());
        videoStream = null;
    }
}

document.getElementById('cancelBtn').addEventListener('click', function() {
    resetScan();
    stopCamera();
    hideError();

    document.getElementById('cancelAlert').style.display = 'block';
    document.getElementById('cancelActions').style.display = 'flex';
    video.style.display = 'none';
    scanOverlay.style.display = 'none';
    scanTimer.textContent = '';
    faceStatus.style.display = 'none';
    recognitionActive = true;
    scanOverlay.className = 'scan-overlay';
});

let restartCountdown = null;

function startRestartCountdown(btn) {
    const countEl = document.getElementById('countdownText');
    btn.disabled = true;
    btn.textContent = 'Memulai...';
    countEl.style.display = 'block';

    let detik = 3;
    countEl.textContent = 'Mulai dalam ' + detik + ' detik...';

    clearInterval(restartCountdown);
    restartCountdown = setInterval(function() {
        detik--;
        if (detik > 0) {
            countEl.textContent = 'Mulai dalam ' + detik + ' detik...';
        } else {
            clearInterval(restartCountdown);
            countEl.textContent = 'Mulai...';

            document.getElementById('cancelAlert').style.display = 'none';
            document.getElementById('cancelActions').style.display = 'none';
            statusMessage.innerHTML = '';
            video.style.display = 'block';
            scanOverlay.style.display = 'block';
            countEl.style.display = 'none';
            btn.disabled = false;
            btn.textContent = 'Mulai Ulang Absensi';

            setStatus('Memulai kamera...', 'loading');
            startVideo();
        }
    }, 1000);
}

document.getElementById('restartBtn2').addEventListener('click', function() {
    startRestartCountdown(this);
});
</script>
@endpush
