<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    @vite('resources/css/app.css')
    <style>
        body { background: #f3f4f6; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; font-family: system-ui, sans-serif; }
        .card { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 420px; }
        .card h1 { text-align: center; color: #374151; margin-bottom: 8px; font-size: 24px; }
        .card p { text-align: center; color: #6b7280; margin-bottom: 24px; font-size: 14px; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; margin-bottom: 6px; font-size: 14px; font-weight: 600; color: #374151; }
        .form-group input { width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; box-sizing: border-box; }
        .form-group input:focus { outline: none; border-color: #00c357; box-shadow: 0 0 0 3px rgba(0,195,87,0.15); }
        .btn { width: 100%; padding: 12px; background: #00c357; color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; }
        .btn:hover { background: #00b855; }
        .alert { padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; }
        .alert-error { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .back-link { display: block; text-align: center; margin-top: 16px; font-size: 14px; }
        .back-link a { color: #00c357; text-decoration: none; }
        .back-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Reset Password</h1>
        <p>Buat password baru untuk akun Anda</p>

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('auth.reset-password') }}">
            @csrf
            <div class="form-group">
                <label for="password">Password Baru</label>
                <input type="password" name="password" id="password" required minlength="8">
                @error('password')
                    <div style="color: #b91c1c; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8">
            </div>
            <button type="submit" class="btn">Reset Password</button>
        </form>
    </div>
</body>
</html>
