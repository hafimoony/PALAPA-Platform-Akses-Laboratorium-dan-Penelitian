<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - PALAPA Platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background-color: #f8fafc; font-family: 'Poppins', sans-serif; color: #0f172a; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .reset-card { background: #ffffff; border-radius: 20px; border: 1px solid #E2E8F0; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04); width: 100%; max-width: 460px; padding: 40px; text-align: center; }
        .brand-logo { height: 48px; width: auto; margin-bottom: 24px; object-fit: contain; }
        .icon-circle { width: 60px; height: 60px; background: #EBF3FF; color: #334EAC; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 16px; }
        .title { font-size: 22px; font-weight: 800; color: #0f172a; margin-bottom: 8px; }
        .subtitle { font-size: 12px; color: #64748b; line-height: 1.5; margin-bottom: 24px; }
        .form-group { text-align: left; margin-bottom: 20px; }
        .form-label { font-size: 11px; font-weight: 700; color: #1e293b; display: block; margin-bottom: 6px; }
        .input-wrapper { position: relative; display: flex; align-items: center; }
        .input-wrapper i { position: absolute; left: 14px; color: #94a3b8; font-size: 13px; }
        .form-control { width: 100%; padding: 10px 14px 10px 38px; background: #EEF5FF; border: 1px solid #CBD5E1; border-radius: 8px; font-family: inherit; font-size: 12px; color: #1e293b; outline: none; }
        .btn-submit { width: 100%; padding: 12px; background: #334EAC; color: #ffffff; border: none; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer; font-family: inherit; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .btn-submit:hover { background: #172A68; }
        .back-link { display: inline-flex; align-items: center; gap: 6px; margin-top: 20px; font-size: 12px; font-weight: 700; color: #334EAC; text-decoration: none; }
        .alert-success { background: #d1fae5; color: #065f46; padding: 10px 14px; border-radius: 8px; font-size: 11px; margin-bottom: 16px; text-align: left; }
        .alert-danger { background: #fee2e2; color: #991b1b; padding: 10px 14px; border-radius: 8px; font-size: 11px; margin-bottom: 16px; text-align: left; }
    </style>
</head>
<body>
    <div class="reset-card">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logo-palapa.jpg') }}" class="brand-logo" alt="PALAPA Logo">
        </a>
        <div class="icon-circle"><i class="fa-solid fa-key"></i></div>
        <h1 class="title">Lupa Kata Sandi?</h1>
        <p class="subtitle">Masukkan email instansi Anda yang terdaftar. Kami akan mengirimkan instruksi dan tautan untuk mengatur ulang kata sandi Anda.</p>

        @if (session('status'))
            <div class="alert-success">
                <i class="fa-solid fa-circle-check"></i> {{ session('status') }}
            </div>
        @endif

        @if ($errors->has('email'))
            <div class="alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first('email') }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Email Instansi / ID Peneliti</label>
                <div class="input-wrapper">
                    <i class="fa-regular fa-envelope"></i>
                    <input type="email" name="email" class="form-control" placeholder="contoh: nama@instansi.ac.id" value="{{ old('email') }}" required autofocus>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                Kirim Tautan Reset <i class="fa-solid fa-paper-plane"></i>
            </button>
        </form>

        <a href="{{ route('login') }}" class="back-link">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Halaman Login
        </a>
    </div>
</body>
</html>
