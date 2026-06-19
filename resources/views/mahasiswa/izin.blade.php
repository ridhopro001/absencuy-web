@extends('mahasiswa.layout')

@section('title', 'Form Izin / Sakit')

@section('content')
<div class="card">
    <h1>Form Izin / Sakit</h1>
    <p style="color: #666; margin-bottom: 1.5rem;">Ajukan izin atau sakit tanpa perlu melakukan absensi wajah.</p>

    <form action="{{ route('izin.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group" style="position:relative">
            <label for="nama">Nama Lengkap</label>
            <input type="text" name="nama" id="nama" required maxlength="255" value="{{ old('nama') }}" autocomplete="off">
            <div id="autocompleteList" class="autocomplete-list" style="display:none"></div>
        </div>

        <div class="form-group">
            <label for="nim">NIM</label>
            <input type="text" name="nim" id="nim" required maxlength="255" value="{{ old('nim') }}" readonly>
        </div>

        <div class="form-group">
            <label for="jurusan">Jurusan</label>
            <select name="jurusan" id="jurusan" required>
                <option value="">-- Pilih Jurusan --</option>
                @foreach($jurusanList as $j)
                    <option value="{{ $j }}" {{ old('jurusan') == $j ? 'selected' : '' }}>{{ $j }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="semester">Semester</label>
            <select name="semester" id="semester" required>
                <option value="">-- Pilih Semester --</option>
                @foreach($semesterList as $s)
                    <option value="{{ $s }}" {{ old('semester') == $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" required>
                <option value="">-- Pilih Status --</option>
                <option value="Izin" {{ old('status') == 'Izin' ? 'selected' : '' }}>Izin</option>
                <option value="Sakit" {{ old('status') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
            </select>
        </div>

        <div class="form-group" id="alasanGroup">
            <label for="alasan">Alasan <span style="color: #6b7280;">(opsional)</span></label>
            <textarea name="alasan" id="alasan">{{ old('alasan') }}</textarea>
        </div>

        <div class="form-group">
            <label for="file_pendukung">File Pendukung <span style="color: #dc2626;">(wajib diisi, PDF/DOC/DOCX/JPG/PNG, maks 2MB)</span></label>
            <input type="file" name="file_pendukung" id="file_pendukung" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp">
        </div>

        <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
    </form>
</div>
@endsection

@push('styles')
<style>
.autocomplete-list {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: #fff;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    max-height: 200px;
    overflow-y: auto;
    z-index: 999;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.autocomplete-item {
    padding: 10px 12px;
    cursor: pointer;
    border-bottom: 1px solid #f3f4f6;
    transition: background 0.15s;
}
.autocomplete-item:last-child {
    border-bottom: none;
}
.autocomplete-item:hover,
.autocomplete-item.active {
    background: #eff6ff;
}
.autocomplete-item .name {
    font-weight: 500;
    color: #111827;
}
.autocomplete-item .detail {
    font-size: 0.8rem;
    color: #6b7280;
    margin-top: 2px;
}
</style>
@endpush

@push('scripts')
<script>
const izinForm = document.querySelector('form');
const namaInput = document.getElementById('nama');
const nimInput = document.getElementById('nim');
const jurusanSelect = document.getElementById('jurusan');
const semesterSelect = document.getElementById('semester');
const autocompleteList = document.getElementById('autocompleteList');
let mahasiswaList = null;
let fetchFailed = false;
let selectedIndex = -1;
let currentFiltered = [];

fetch('{{ route("absensi.mahasiswas") }}')
    .then(res => {
        if (!res.ok) throw new Error('fetch failed');
        return res.json();
    })
    .then(data => { mahasiswaList = data; })
    .catch(() => { fetchFailed = true; });

function filterMahasiswas(query) {
    if (!mahasiswaList) return [];
    const q = query.toLowerCase().trim();
    if (!q) return [];
    return mahasiswaList.filter(m =>
        m.nama.toLowerCase().includes(q) ||
        m.nim.toLowerCase().includes(q)
    ).slice(0, 10);
}

function renderAutocomplete(items) {
    autocompleteList.innerHTML = '';
    if (items.length === 0) {
        autocompleteList.style.display = 'none';
        return;
    }
        items.forEach((m, i) => {
        const div = document.createElement('div');
        div.className = 'autocomplete-item' + (i === selectedIndex ? ' active' : '');
        const semesterDetail = m.semester ? ' — ' + m.semester : '';
        div.innerHTML = '<div class="name">' + m.nama + '</div><div class="detail">' + m.nim + ' — ' + m.jurusan + semesterDetail + '</div>';
        div.addEventListener('click', function() {
            selectMahasiswa(m);
        });
        div.addEventListener('mouseenter', function() {
            selectedIndex = i;
            highlightItem();
        });
        autocompleteList.appendChild(div);
    });
    autocompleteList.style.display = 'block';
}

function highlightItem() {
    const items = autocompleteList.querySelectorAll('.autocomplete-item');
    items.forEach((el, i) => {
        el.classList.toggle('active', i === selectedIndex);
    });
    const active = items[selectedIndex];
    if (active) {
        active.scrollIntoView({ block: 'nearest' });
    }
}

function selectMahasiswa(m) {
    namaInput.value = m.nama;
    nimInput.value = m.nim;
    jurusanSelect.value = m.jurusan;
    if (m.semester && semesterSelect.querySelector('option[value="' + m.semester + '"]')) {
        semesterSelect.value = m.semester;
    }
    autocompleteList.style.display = 'none';
    selectedIndex = -1;
}

namaInput.addEventListener('input', function() {
    selectedIndex = -1;
    const items = filterMahasiswas(this.value);
    currentFiltered = items;
    renderAutocomplete(items);
});

namaInput.addEventListener('keydown', function(e) {
    if (autocompleteList.style.display !== 'block') return;
    if (e.key === 'ArrowDown') {
        e.preventDefault();
        selectedIndex = Math.min(selectedIndex + 1, currentFiltered.length - 1);
        highlightItem();
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        selectedIndex = Math.max(selectedIndex - 1, 0);
        highlightItem();
    } else if (e.key === 'Enter' && selectedIndex >= 0) {
        e.preventDefault();
        selectMahasiswa(currentFiltered[selectedIndex]);
    } else if (e.key === 'Escape') {
        autocompleteList.style.display = 'none';
        selectedIndex = -1;
    }
});

document.addEventListener('click', function(e) {
    if (!e.target.closest('.form-group') || !e.target.closest('#nama')) {
        autocompleteList.style.display = 'none';
        selectedIndex = -1;
    }
});

izinForm.addEventListener('submit', function(e) {
    const nama = namaInput.value.trim();
    const nim = nimInput.value.trim();
    const jurusan = jurusanSelect.value.trim();
    const semester = semesterSelect.value.trim();

    if (!nama || !nim || !jurusan || !semester) return;

    if (!fetchFailed && mahasiswaList) {
        const found = mahasiswaList.some(m =>
            m.nama.toLowerCase() === nama.toLowerCase() &&
            m.nim === nim &&
            m.jurusan === jurusan &&
            m.semester === semester
        );

        if (!found) {
            e.preventDefault();
            const modalOverlay = document.createElement('div');
            modalOverlay.className = 'modal-overlay';
            modalOverlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.6);display:flex;align-items:center;justify-content:center;z-index:9999';
            modalOverlay.innerHTML = '<div class="modal" style="background:#fff;border-radius:12px;padding:2rem;max-width:400px;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,0.3)"><h2 style="color:#dc2626;margin-bottom:0.5rem">Data Tidak Terdaftar</h2><p style="color:#6b7280">Maaf data anda tidak terdaftar, silahkan hubungi admin.</p><div class="btn-group" style="margin-top:1.5rem"><button type="button" class="btn btn-primary" id="modalOkBtn">Tutup</button></div></div>';
            document.body.appendChild(modalOverlay);
            document.getElementById('modalOkBtn').addEventListener('click', function() {
                modalOverlay.remove();
            });
            return;
        }
    }

    const submitBtn = izinForm.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Mengirim...';
});
</script>
@endpush
