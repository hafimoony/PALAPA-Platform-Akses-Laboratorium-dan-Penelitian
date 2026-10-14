<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Penyewa Baru - PALAPA Platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background-color: #f8fafc; font-family: 'Poppins', sans-serif; color: #0f172a; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px; }
        .register-card { background: #ffffff; border-radius: 20px; border: 1px solid #E2E8F0; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04); width: 100%; max-width: 560px; padding: 40px; position: relative; z-index: 1; }
        .header-section { text-align: center; margin-bottom: 28px; }
        .brand-logo { height: 46px; width: auto; margin-bottom: 16px; object-fit: contain; }
        .title { font-size: 22px; font-weight: 800; color: #0f172a; margin-bottom: 6px; }
        .subtitle { font-size: 12px; color: #64748b; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-group { margin-bottom: 18px; text-align: left; position: relative; }
        .form-label { font-size: 11px; font-weight: 700; color: #1e293b; display: block; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
        .input-wrapper { position: relative; display: flex; align-items: center; }
        .input-wrapper i.left-icon { position: absolute; left: 14px; color: #94a3b8; font-size: 13px; pointer-events: none; }
        .input-wrapper i.right-icon { position: absolute; right: 14px; color: #94a3b8; font-size: 13px; cursor: pointer; z-index: 10; padding: 4px; }
        .form-control { width: 100%; padding: 10px 14px 10px 38px; background: #EEF5FF; border: 1px solid #CBD5E1; border-radius: 8px; font-family: inherit; font-size: 12px; color: #1e293b; outline: none; position: relative; z-index: 2; }
        .form-control:focus { border-color: #334EAC; background: #ffffff; }
        select.form-control { appearance: none; -webkit-appearance: none; cursor: pointer; }
        .section-divider { font-size: 11px; font-weight: 700; color: #334EAC; margin: 20px 0 14px 0; padding-bottom: 6px; border-bottom: 1px solid #E2E8F0; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px; }
        .btn-submit { width: 100%; padding: 12px; background: #334EAC; color: #ffffff; border: none; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer; font-family: inherit; display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 10px; position: relative; z-index: 10; pointer-events: auto !important; }
        .btn-submit:hover { background: #172A68; }
        .footer-text { text-align: center; margin-top: 20px; font-size: 12px; color: #64748b; position: relative; z-index: 10; }
        .footer-text a { color: #334EAC; font-weight: 700; text-decoration: none; pointer-events: auto !important; cursor: pointer !important; }
        .alert-danger { background: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 8px; font-size: 11px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="header-section">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/logo-palapa.jpg') }}" class="brand-logo" alt="PALAPA Logo">
            </a>
            <h1 class="title">Buat Akun Penyewa Baru</h1>
            <p class="subtitle">Daftarkan instansi atau diri Anda untuk dapat mereservasi fasilitas laboratorium riset nasional.</p>
        </div>

        @if ($errors->any())
            <div class="alert-danger">
                <strong><i class="fa-solid fa-triangle-exclamation"></i> Terdapat kesalahan input:</strong>
                <ul style="margin-top: 4px; padding-left: 16px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('/register') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Nama Lengkap Peneliti / Perwakilan</label>
                <div class="input-wrapper">
                    <i class="fa-regular fa-user left-icon"></i>
                    <input type="text" name="name" class="form-control" placeholder="contoh: Dr. Ahmad Subagyo" value="{{ old('name') }}" required autofocus>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Email Instansi</label>
                    <div class="input-wrapper">
                        <i class="fa-regular fa-envelope left-icon"></i>
                        <input type="email" name="email" class="form-control" placeholder="nama@instansi.ac.id" value="{{ old('email') }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor Telepon / WA</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-phone left-icon"></i>
                        <input type="text" name="phone_number" class="form-control" placeholder="08123456789" value="{{ old('phone_number') }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Kata Sandi Akun</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-lock left-icon"></i>
                    <input type="password" name="password" id="passInput" class="form-control" placeholder="Minimal 8 karakter" required>
                    <i class="fa-regular fa-eye right-icon" id="togglePasswordIcon"></i>
                </div>
            </div>

            <div class="section-divider">
                <i class="fa-solid fa-building-columns"></i> Profil Instansi Penyewa
            </div>

            <div class="form-group">
                <label class="form-label">Nama Instansi / Sekolah / Perusahaan</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-building left-icon"></i>
                    <input type="text" name="institution_name" class="form-control" placeholder="contoh: SMA Negeri 1 Pasuruan / PT Herbal Riset" value="{{ old('institution_name') }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kategori Instansi</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-layer-group left-icon"></i>
                        <select name="institution_type" class="form-control">
                            <option value="sekolah_sma_smk" {{ old('institution_type') == 'sekolah_sma_smk' ? 'selected' : '' }}>Sekolah (SMA/SMK)</option>
                            <option value="lembaga_riset" {{ old('institution_type') == 'lembaga_riset' ? 'selected' : '' }}>Lembaga Riset / Universitas</option>
                            <option value="perusahaan_swasta" {{ old('institution_type') == 'perusahaan_swasta' ? 'selected' : '' }}>Perusahaan Swasta / Industri</option>
                            <option value="komunitas_publik" {{ old('institution_type') == 'komunitas_publik' ? 'selected' : '' }}>Komunitas / Independen</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">NPWP Instansi (Opsional)</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-id-card left-icon"></i>
                        <input type="text" name="tax_number_npwp" class="form-control" placeholder="Nomor NPWP" value="{{ old('tax_number_npwp') }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Alamat Lengkap Instansi</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-location-dot left-icon"></i>
                    <input type="text" name="address" class="form-control" placeholder="Jalan, Kota, Provinsi" value="{{ old('address') }}">
                </div>
            </div>

            <button type="submit" class="btn-submit">
                Daftar Sebagai Penyewa <i class="fa-solid fa-user-plus"></i>
            </button>
        </form>

        <div class="footer-text">
            Sudah memiliki akun penyewa? <a href="{{ url('/login') }}">Masuk di Sini</a>
        </div>
    </div>

    <script>
        document.getElementById('togglePasswordIcon').addEventListener('click', function () {
            var input = document.getElementById("passInput");
            if (input.type === "password") {
                input.type = "text";
                this.classList.remove("fa-eye");
                this.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                this.classList.remove("fa-eye-slash");
                this.classList.add("fa-eye");
            }
        });
    </script>
</body>
</html>
