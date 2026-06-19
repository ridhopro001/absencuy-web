<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Absensi')</title>
    <!-- PWA  -->
    @pwaHead
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    @vite('resources/js/app.js')
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: system-ui, -apple-system, sans-serif; background: #f0f2f5; min-height: 100vh; }
        .navbar { background: #1a1a2e; color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 1000; }
        .navbar a { color: white; text-decoration: none; margin-left: 1rem; padding: 0.5rem 1rem; border-radius: 6px; transition: background 0.2s; }
        .navbar a:hover { background: rgba(255,255,255,0.1); }
        .navbar .logo { font-weight: 900; font-size: 1.4rem; letter-spacing: 2px; text-transform: uppercase; }
        .navbar .logo a { color: white; text-decoration: none; background: linear-gradient(135deg, #00c357, #00f08a); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; text-shadow: none; font-family: 'Segoe UI', 'Poppins', system-ui, sans-serif; }
        .container { max-width: 900px; margin: 2rem auto; padding: 0 1rem; }
        .card { background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 1.5rem; }
        .card h1 { margin-bottom: 1.5rem; color: #1a1a2e; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.4rem; font-weight: 500; color: #333; }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 0.7rem 1rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;
            transition: border-color 0.2s;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none; border-color: #1a1a2e;
        }
        .form-group textarea { min-height: 100px; resize: vertical; }
        .btn {
            padding: 0.7rem 1.5rem; border: none; border-radius: 8px; font-size: 1rem; cursor: pointer;
            transition: opacity 0.2s; font-weight: 500;
        }
        .btn:hover { opacity: 0.9; }
        .btn-primary { background: #1a1a2e; color: white; }
        .btn-success { background: #059669; color: white; }
        .btn-danger { background: #dc2626; color: white; }
        .btn-secondary { background: #6b7280; color: white; }
        .btn-warning { background: #f59e0b; color: white; }
        .video-container { position: relative; max-width: 640px; margin: 0 auto; }
        #video { width: 100%; border-radius: 8px; background: #000; }
        #overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
        .alert { padding: 1rem; border-radius: 8px; margin-bottom: 1rem; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-info { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
        .alert-warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);
            display: flex; align-items: center; justify-content: center; z-index: 1000;
        }
        .modal { background: white; border-radius: 12px; padding: 2rem; max-width: 400px; width: 90%; text-align: center; }
        .modal h2 { margin-bottom: 1rem; }
        .modal p { margin-bottom: 0.5rem; color: #666; }
        .modal .btn-group { margin-top: 1.5rem; display: flex; gap: 1rem; justify-content: center; }
        .loading { text-align: center; padding: 2rem; color: #666; }
        .loading .spinner { width: 40px; height: 40px; border: 4px solid #e5e7eb; border-top-color: #1a1a2e; border-radius: 50%; animation: spin 0.8s linear infinite; margin: 0 auto 1rem; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-top: 2rem; }
        .menu-card { background: white; border-radius: 12px; padding: 2rem; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08); text-decoration: none; color: inherit; transition: transform 0.2s, box-shadow 0.2s; }
        .menu-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
        .menu-card .icon { font-size: 3rem; margin-bottom: 1rem; }
        .menu-card h3 { margin-bottom: 0.5rem; color: #1a1a2e; }
        .menu-card p { color: #666; font-size: 0.9rem; }
        .face-status { text-align: center; padding: 1rem; margin-top: 1rem; border-radius: 8px; font-weight: 500; }
        .face-status.loading { background: #fef3c7; color: #92400e; }
        .face-status.success { background: #d1fae5; color: #065f46; }
        .face-status.error { background: #fee2e2; color: #991b1b; }
        .burger { display: none; flex-direction: column; cursor: pointer; background: none; border: none; padding: 4px; }
        .burger span { width: 24px; height: 3px; background: white; margin: 3px 0; border-radius: 2px; transition: 0.3s; }
        .nav-links { display: flex; align-items: center; }
        .nav-links.open { display: flex; flex-direction: column; position: absolute; top: 100%; left: 0; right: 0; background: #1a1a2e; padding: 1rem 2rem; gap: 0.5rem; z-index: 999; }
        .nav-links.open a { margin-left: 0; }
        @media (max-width: 600px) {
            .burger { display: flex; }
            .nav-links { display: none; }
            .navbar { position: sticky; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo"><a href="{{ route('home') }}">ABSEN CUY</a></div>
        <button class="burger" id="burgerBtn" aria-label="Toggle navigation">
            <span></span><span></span><span></span>
        </button>
        <div class="nav-links" id="navLinks">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('absensi.index') }}">Absensi</a>
            <a href="{{ route('izin.index') }}">Izin/Sakit</a>
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error') || $errors->any())
            <div class="alert alert-error">{{ session('error') ?? $errors->first() }}</div>
        @endif
        @yield('content')
    </div>

    @stack('styles')
    @stack('scripts')
    <script>
        document.getElementById('burgerBtn').addEventListener('click', function() {
            document.getElementById('navLinks').classList.toggle('open');
        });
    </script>
    @laravelPwa
    @pwaUpdateNotifier
    @pwaInstallButton
</body>
</html>
