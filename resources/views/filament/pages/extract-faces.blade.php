<x-filament::page>
    <style>
        /* Container Utama */
        .extractor-container {
            font-family: system-ui, -apple-system, sans-serif;
            color: #1f2937;
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
        }

        /* Judul Utama Halaman */
        .page-main-title {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 10px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }

        /* Struktur Linear Tunggal (Satu Kolom Kebawah) */
        .extractor-linear-stack {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Panel Card */
        .extractor-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }

        .card-title {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
        }

        .card-body {
            padding: 20px;
        }

        .card-desc {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.5;
            margin-bottom: 16px;
            margin-top: 0;
        }

        /* Form & Input */
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .file-input {
            display: block;
            width: 100%;
            font-size: 13px;
            color: #4b5563;
            padding: 8px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: #fafafa;
            cursor: pointer;
        }

        /* Komponen Tombol (Teks <icon>) */
        .btn-flex-container {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-main {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px; /* Jarak antara teks dan icon */
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 600;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.15s ease;
            white-space: nowrap; /* Teks tombol tidak patah */
        }

        .btn-primary { background: #0284c7; }
        .btn-primary:hover { background: #0369a1; }
        .btn-warning { background: #d97706; }
        .btn-warning:hover { background: #b45309; }

        .status-container {
            padding: 0 20px 16px 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .code-highlight {
            font-family: monospace;
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 11px;
            color: #dc2626;
        }

        /* Alert & Status Realtime */
        .info-alert {
            display: flex;
            gap: 12px;
            padding: 14px 16px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 12px;
            font-size: 12px;
            color: #1e3a8a;
            line-height: 1.6;
        }

        .info-title {
            font-weight: 700;
            display: block;
            margin-bottom: 4px;
        }

        .status-bar {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #e5e7eb;
            background: #f3f4f6;
            color: #374151;
        }

        .list-section-title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #4b5563;
            margin-bottom: 12px;
            margin-top: 10px;
        }

        /* List Foto Menumpuk Tunggal Kebawah */
        .photo-linear-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        /* Card baris foto */
        .photo-row-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
            display: flex;
            flex-direction: column;
            gap: 16px;
            transition: border-color 0.2s ease;
        }
        @media (min-width: 640px) {
            .photo-row-card {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }
        .photo-row-card:hover {
            border-color: #38bdf8;
        }

        .photo-meta-box {
            display: flex;
            gap: 16px;
            align-items: center;
            min-width: 0;
            flex: 1;
        }

        .img-frame {
            width: 56px;
            height: 56px;
            flex-shrink: 0;
            overflow: hidden;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        .img-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .meta-details {
            flex: 1;
            min-width: 0;
        }

        .nim-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
        }

        .nim-text {
            font-size: 14px;
            font-weight: 700;
            font-family: monospace;
            color: #111827;
            white-space: nowrap; /* NIM anti-break */
        }

        .badge-status {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 9999px;
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            white-space: nowrap;
        }

        /* Menjaga Nama Tetap dalam Satu Baris Sempurna */
        .nama-text {
            font-size: 13px;
            font-weight: 600;
            color: #4b5563;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap; /* Nama anti-break */
            max-width: 100%;
        }

        .error-text {
            font-size: 11px;
            font-weight: 700;
            color: #b91c1c;
            background: #fef2f2;
            padding: 2px 8px;
            border-radius: 4px;
            display: inline-block;
            white-space: nowrap;
        }

        /* Footer Aksi di Baris Kanan */
        .card-action-footer {
            flex-shrink: 0;
        }

        .btn-group-row {
            display: flex;
            gap: 8px;
        }

        .btn-mini {
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 700;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px; /* Jarak teks ke icon di tombol kecil */
            white-space: nowrap;
        }

        .btn-mini-extract {
            background: #0284c7;
            color: #ffffff;
        }
        .btn-mini-extract:hover { background: #0369a1; }

        .btn-mini-all {
            background: #f0f9ff;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }
        .btn-mini-all:hover { background: #e0f2fe; }

        .extract-result {
            text-align: center;
            font-size: 11px;
            margin-top: 4px;
        }

        /* Empty State */
        .empty-box {
            text-align: center;
            padding: 40px 16px;
            background: #ffffff;
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            width: 100%;
        }

        .text-gray-sub { color: #9ca3af; font-size: 11px; }
        .hidden { display: none !important; }
        .animate-spin { animation: spin 1s linear infinite; }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

        /* Hasil response text JS */
        .text-success-600 { color: #16a34a !important; font-weight: bold; }
        .text-danger-600 { color: #dc2626 !important; font-weight: bold; }
        .text-gray-500 { color: #6b7280 !important; }
    </style>

    <div class="extractor-container">

        <div class="extractor-linear-stack">

            {{-- Upload Section --}}
            <div class="extractor-card">
                <div class="card-header">
                    <span class="card-title">Upload Foto Baru</span>
                    <svg style="width:18px; height:18px; color:#0284c7;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                </div>
                <div class="card-body">
                    <p class="card-desc">
                        Nama file harus format: <code class="code-highlight">Nama,NIM,Jurusan,Semester.jpg (max 3mb)</code>. Nama file tidak diubah.
                    </p>
                    <form id="uploadForm" class="form-group">
                        <div>
                            <input type="file" name="photos[]" id="photosInput" multiple accept="image/jpeg,image/png,image/webp" class="file-input">
                        </div>
                        <div style="display: flex; justify-content: flex-start;">
                            <button type="submit" id="uploadBtn" class="btn-main btn-primary">
                                <span>Upload</span>
                                <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            </button>
                        </div>
                    </form>
                </div>
                <div id="uploadStatus" class="status-container"></div>
            </div>

            {{-- Sync Section --}}
            <div class="extractor-card">
                <div class="card-header">
                    <span class="card-title">Sinkronkan Data Mahasiswa</span>
                    <svg style="width:18px; height:18px; color:#d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
                <div class="card-body">
                    <p class="card-desc" style="font-size:13px; color:#374151;">
                        Foto yang sudah diupload akan diproses: data mahasiswa dibuat berdasarkan nama file, lalu foto dipindahkan ke <code class="code-highlight">foto_mahasiswa_tersimpan/</code>.
                    </p>
                    @php $pendingCount = app(\App\Filament\Pages\ExtractFaces::class)->getPendingPhotoCount(); @endphp
                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px; padding:10px 14px; background:#fffbeb; border:1px solid #fde68a; border-radius:8px;">
                        <svg style="width:16px; height:16px; color:#d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span style="font-size:13px; color:#92400e;">
                            Terdapat <strong>{{ $pendingCount }}</strong> foto di <code class="code-highlight">foto_mahasiswa/</code> yang siap disinkronkan.
                        </span>
                    </div>
                    <div class="btn-flex-container">
                        <button type="button" id="syncBtn" class="btn-main btn-warning">
                            <span>Sinkronkan Data</span>
                            <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </button>
                        <span class="text-gray-sub">Memproses & memindahkan semua foto dari <code class="code-highlight">foto_mahasiswa/</code></span>
                    </div>
                </div>
                <div id="syncStatus" class="status-container"></div>
            </div>

            {{-- Model Info --}}
            <div class="info-alert">
                <svg style="width:18px; height:18px; color:#1d4ed8; flex-shrink:0; margin-top:2px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <span class="info-title">Model AI Engine:</span>
                    <code>face-api.js</code> (tinyFaceDetector + faceLandmark68Net + faceRecognitionNet).
                    Face descriptor berupa array 128 float yang disimpan di kolom <code style="font-weight: bold;">face_descriptor</code>.
                </div>
            </div>

            {{-- Status Realtime Engine --}}
            <div>
                <div id="extractStatus" class="status-bar">
                    <svg class="animate-spin" style="width:16px; height:16px; color:#0284c7;" fill="none" viewBox="0 0 24 24"><circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                    <span>Memuat model face-api.js...</span>
                </div>
                <div id="extractProgress" class="status-bar hidden" style="background:#eff6ff; color:#1e40af; border-color:#bfdbfe; font-weight:600; margin-top:8px;"></div>
            </div>

            {{-- Photo List Section --}}
            <div>
                <h3 class="list-section-title">Daftar Antrean Foto</h3>
                <div class="photo-linear-list" id="photoList">
                    @php $page = app(\App\Filament\Pages\ExtractFaces::class); $photos = $page->getPhotos(); @endphp
                    @forelse($photos as $photo)
                        <div class="photo-row-card" data-nim="{{ $photo['nim'] }}" data-path="{{ $photo['path'] }}" data-folder="{{ $photo['folder'] }}" data-filename="{{ $photo['filename'] }}">

                            <div class="photo-meta-box">
                                <div class="img-frame">
                                    <img src="{{ $photo['path'] }}" alt="{{ $photo['filename'] }}">
                                </div>
                                <div class="meta-details">
                                    <div class="nim-row">
                                        <span class="nim-text">{{ $photo['nim'] }}</span>
                                        @if($photo['mahasiswa'] && $photo['has_descriptor'])
                                            <span class="badge-status">✓ Terstruktur</span>
                                        @endif
                                    </div>
                                    @if($photo['mahasiswa'])
                                        <div class="nama-text" title="{{ $photo['mahasiswa']->nama }}">{{ $photo['mahasiswa']->nama }}</div>
                                        @if($photo['mahasiswa']->semester)
                                            <div style="font-size:11px; color:#6b7280; margin-top:2px;">{{ $photo['mahasiswa']->semester }}</div>
                                        @endif
                                    @else
                                        <div class="error-text">Tidak ada mahasiswa dengan NIM ini</div>
                                        @if($photo['semester'])
                                            <div style="font-size:11px; color:#6b7280; margin-top:2px;">{{ $photo['semester'] }}</div>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <div class="card-action-footer">
                                @if($photo['mahasiswa'])
                                    @if(!$photo['has_descriptor'])
                                        <div class="btn-group-row">
                                            <button type="button" class="extract-btn btn-mini btn-mini-extract">
                                                <span>Ekstrak Descriptor</span>
                                                <svg style="width:12px; height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                            </button>
                                            <button type="button" class="extract-all-btn btn-mini btn-mini-all">
                                                <span>Semua</span>
                                            </button>
                                        </div>
                                        <div class="extract-result"></div>
                                    @else
                                        <span style="font-size:12px; color:#16a34a; font-weight:600;">✓ Data Wajah Aman</span>
                                    @endif
                                @else
                                    <button type="button" class="delete-photo-btn btn-mini" style="background:#dc2626;color:#fff;">
                                        <span>Hapus Foto</span>
                                        <svg style="width:12px; height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                @endif
                            </div>

                        </div>
                    @empty
                        <div class="empty-box">
                            <svg style="width:40px; height:40px; color:#d1d5db; margin-bottom:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p style="font-size:14px; font-weight:600; color:#6b7280; margin:0;">Belum ada foto yang tersedia.</p>
                            <p style="font-size:12px; color:#9ca3af; margin:4px 0 0 0;">Upload foto dengan format nama <code class="code-highlight">Nama,NIM,Jurusan.jpg</code> lalu klik <strong>Sinkronkan Data</strong>.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <script>
    function getToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    }

    document.getElementById('uploadForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const input = document.getElementById('photosInput');
        const files = input.files;
        if (!files.length) return;

        const btn = document.getElementById('uploadBtn');
        const status = document.getElementById('uploadStatus');
        btn.disabled = true;
        btn.innerHTML = 'Mengupload...';
        status.innerHTML = '<span class="text-gray-500">Mengupload ' + files.length + ' file...</span>';

        const formData = new FormData();
        for (const file of files) {
            formData.append('photos[]', file);
        }

        try {
            const res = await fetch('{{ route("admin.extract-faces.upload") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': getToken() },
                body: formData,
            });
            const result = await res.json();
            if (result.success) {
                location.reload();
            } else {
                status.innerHTML = '<span class="text-danger-600">✗ ' + (result.message || 'Gagal upload') + '</span>';
            }
        } catch (err) {
            status.innerHTML = '<span class="text-danger-600">✗ Gagal terhubung ke server.</span>';
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<span>Upload</span><svg style="width:14px; height:14px; display:inline-block; vertical-align:middle; margin-left:8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>';
        }
    });

    document.getElementById('syncBtn').addEventListener('click', async function() {
        const btn = this;
        const status = document.getElementById('syncStatus');
        btn.disabled = true;
        btn.innerHTML = 'Memproses...';
        status.innerHTML = '<span class="text-gray-500">Menyinkronkan data...</span>';

        try {
            const res = await fetch('{{ route("admin.extract-faces.sync") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getToken(),
                },
            });
            const result = await res.json();
            if (result.success) {
                let html = '<span class="text-success-600">✓ ' + result.message + '</span>';
                if (result.errors && result.errors.length) {
                    html += '<div style="margin-top:4px; color:#dc2626;"><ul style="list-style-type:disc; padding-left:16px; margin:0;">';
                    for (const err of result.errors) {
                        html += '<li>' + err + '</li>';
                    }
                    html += '</ul></div>';
                }
                status.innerHTML = html;
                setTimeout(() => window.location.reload(), 1500);
            } else {
                status.innerHTML = '<span class="text-danger-600">✗ ' + (result.message || 'Gagal sinkron') + '</span>';
                btn.disabled = false;
                btn.innerHTML = '<span>Sinkronkan Data</span><svg style="width:14px; height:14px; display:inline-block; vertical-align:middle; margin-left:8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>';
            }
        } catch (err) {
            status.innerHTML = '<span class="text-danger-600">✗ Gagal terhubung ke server.</span>';
            btn.disabled = false;
            btn.innerHTML = '<span>Sinkronkan Data</span><svg style="width:14px; height:14px; display:inline-block; vertical-align:middle; margin-left:8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>';
        }
    });

    async function loadFaceAPI() {
        const statusEl = document.getElementById('extractStatus');
        const MODEL_CDN = 'https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js@master/weights/';
        const MODEL_LOCAL = '/models';

        const sources = [
            { url: MODEL_CDN, label: 'CDN global', isCDN: true },
            { url: MODEL_LOCAL, label: 'server lokal', isCDN: false },
        ];

        let loaded = false;
        for (const source of sources) {
            try {
                statusEl.innerHTML = '<svg class="animate-spin" style="width:16px; height:16px; color:#0284c7;" fill="none" viewBox="0 0 24 24"><circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg><span>Mengunduh model dari ' + source.label + '...</span>';

                if (source.isCDN) {
                    await Promise.all([
                        faceapi.nets.tinyFaceDetector.loadFromUri(source.url),
                        faceapi.nets.faceLandmark68Net.loadFromUri(source.url),
                        faceapi.nets.faceRecognitionNet.loadFromUri(source.url),
                    ]);
                } else {
                    statusEl.innerHTML = '<svg class="animate-spin" style="width:16px; height:16px; color:#0284c7;" fill="none" viewBox="0 0 24 24"><circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg><span>Memuat model detektor wajah...</span>';
                    await faceapi.nets.tinyFaceDetector.loadFromUri(source.url);
                    statusEl.innerHTML = '<svg class="animate-spin" style="width:16px; height:16px; color:#0284c7;" fill="none" viewBox="0 0 24 24"><circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg><span>Memuat model landmark wajah...</span>';
                    await faceapi.nets.faceLandmark68Net.loadFromUri(source.url);
                    statusEl.innerHTML = '<svg class="animate-spin" style="width:16px; height:16px; color:#0284c7;" fill="none" viewBox="0 0 24 24"><circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg><span>Memuat model pengenal wajah...</span>';
                    await faceapi.nets.faceRecognitionNet.loadFromUri(source.url);
                }

                loaded = true;
                break;
            } catch (err) {
                console.warn('Gagal memuat dari ' + source.label + ':', err);
            }
        }

        if (loaded) {
            statusEl.style.background = '#ecfdf5';
            statusEl.style.color = '#065f46';
            statusEl.style.borderColor = '#a7f3d0';
            statusEl.innerHTML = '<svg style="width:16px; height:16px; color:#059669; flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span style="font-weight:600">Model AI Siap. Silakan jalankan ekstrasi deskriptor wajah.</span>';
        } else {
            statusEl.style.background = '#fee2e2';
            statusEl.style.color = '#991b1b';
            statusEl.style.borderColor = '#fecaca';
            statusEl.innerHTML = '<svg style="width:16px; height:16px; color:#dc2626; flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg><span style="font-weight:600">Gagal memuat model face-api. Periksa koneksi internet.</span>';
        }
    }

    loadFaceAPI();

    function loadImage(src) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = () => resolve(img);
            img.onerror = reject;
            img.src = src;
        });
    }

    function resizeToCanvas(img, maxDim = 640) {
        let w = img.naturalWidth;
        let h = img.naturalHeight;
        if (w > maxDim || h > maxDim) {
            const scale = maxDim / Math.max(w, h);
            w = Math.round(w * scale);
            h = Math.round(h * scale);
        }
        const canvas = document.createElement('canvas');
        canvas.width = w;
        canvas.height = h;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0, w, h);
        return canvas;
    }

    async function extractDescriptor(imgPath) {
        const img = await loadImage(imgPath);
        const canvas = resizeToCanvas(img, 640);

        const detection = await faceapi.detectSingleFace(canvas, new faceapi.TinyFaceDetectorOptions({ inputSize: 320, scoreThreshold: 0.3 }))
            .withFaceLandmarks()
            .withFaceDescriptor();

        return detection ? Array.from(detection.descriptor) : null;
    }

    async function saveDescriptor(nim, descriptor, resultDiv, btn) {
        const res = await fetch('/admin/extract-faces/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getToken(),
            },
            body: JSON.stringify({ nim, face_descriptor: descriptor }),
        });
        const result = await res.json();
        if (result.success) {
            resultDiv.innerHTML = '<span class="text-success-600">✓ Face descriptor tersimpan</span>';
            if (btn) btn.remove();
            return true;
        } else {
            resultDiv.innerHTML = '<span class="text-danger-600">✗ Gagal menyimpan: ' + (result.message || 'unknown') + '</span>';
            if (btn) { btn.disabled = false; btn.innerHTML = '<span>Ekstrak Descriptor</span><svg style="width:12px; height:12px; margin-left:6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>'; }
            return false;
        }
    }

    document.addEventListener('click', async function(e) {
        const btn = e.target.closest('.extract-btn');
        if (!btn) return;

        const container = btn.closest('[data-nim]');
        const nim = container.dataset.nim;
        const imgPath = container.dataset.path;
        const resultDiv = container.querySelector('.extract-result');

        btn.disabled = true;
        btn.dataset.origHtml = btn.innerHTML;
        btn.innerHTML = 'Memproses...';
        resultDiv.textContent = '';

        try {
            const descriptor = await extractDescriptor(imgPath);
            if (descriptor) {
                await saveDescriptor(nim, descriptor, resultDiv, btn);
            } else {
                resultDiv.innerHTML = '<span class="text-danger-600">✗ Wajah tidak terdeteksi</span>';
                btn.disabled = false;
                btn.innerHTML = btn.dataset.origHtml;
            }
        } catch (err) {
            resultDiv.innerHTML = '<span class="text-danger-600">✗ Error: ' + err.message + '</span>';
            btn.disabled = false;
            btn.innerHTML = btn.dataset.origHtml;
        }
    });

    document.addEventListener('click', async function(e) {
        const btn = e.target.closest('.extract-all-btn');
        if (!btn) return;

        const btns = document.querySelectorAll('.extract-btn:not(:disabled)');
        if (btns.length === 0) {
            document.getElementById('extractProgress').textContent = 'Semua foto sudah diproses.';
            return;
        }

        document.getElementById('extractProgress').classList.remove('hidden');
        let success = 0;
        let fail = 0;

        for (const b of btns) {
            const container = b.closest('[data-nim]');
            const nim = container.dataset.nim;
            const imgPath = container.dataset.path;
            const resultDiv = container.querySelector('.extract-result');

            b.disabled = true;
            b.dataset.origHtml = b.innerHTML;
            b.innerHTML = 'Memproses...';
            resultDiv.textContent = '';
            document.getElementById('extractProgress').textContent = 'Memproses ' + nim + '...';

            try {
                const descriptor = await extractDescriptor(imgPath);
                if (descriptor) {
                    await saveDescriptor(nim, descriptor, resultDiv, b);
                    success++;
                } else {
                    resultDiv.innerHTML = '<span class="text-danger-600">✗ Tidak ada wajah terdeteksi</span>';
                    b.disabled = false;
                    b.innerHTML = b.dataset.origHtml;
                    fail++;
                }
            } catch (err) {
                resultDiv.innerHTML = '<span class="text-danger-600">✗ Error: ' + err.message + '</span>';
                b.disabled = false;
                b.innerHTML = b.dataset.origHtml;
                fail++;
            }
        }

        document.getElementById('extractProgress').textContent = 'Selesai. Berhasil: ' + success + ', Gagal: ' + fail + '.';
    });

    document.addEventListener('click', async function(e) {
        const btn = e.target.closest('.delete-photo-btn');
        if (!btn) return;

        const container = btn.closest('[data-nim]');
        const filename = container.dataset.filename;
        const folder = container.dataset.folder;

        if (!confirm('Hapus foto "' + filename + '"?')) return;

        btn.disabled = true;
        btn.innerHTML = 'Menghapus...';

        try {
            const res = await fetch('{{ route("admin.extract-faces.delete") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getToken(),
                },
                body: JSON.stringify({ filename, folder }),
            });
            const result = await res.json();
            if (result.success) {
                container.remove();
            } else {
                alert('Gagal: ' + (result.message || 'unknown'));
                btn.disabled = false;
                btn.innerHTML = 'Hapus Foto';
            }
        } catch (err) {
            alert('Gagal terhubung ke server.');
            btn.disabled = false;
            btn.innerHTML = 'Hapus Foto';
        }
    });
    </script>
</x-filament::page>
