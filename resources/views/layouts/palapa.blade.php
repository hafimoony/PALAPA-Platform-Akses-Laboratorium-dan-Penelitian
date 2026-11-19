<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PALAPA Platform')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background-color: #f8fafc; font-family: 'Poppins', sans-serif; color: #0f172a; overflow-x: hidden; }
        .container { max-width: 1240px; margin: 0 auto; padding: 0 20px; }

        /* --- NAVBAR CAPSULE --- */
        .navbar-wrapper { padding: 20px 0; }
        .navbar {
            display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
            background: #EBF3FF; padding: 8px 16px 8px 24px; border-radius: 9999px;
        }
        .nav-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .nav-brand img { height: 38px; width: auto; object-fit: contain; border-radius: 8px; }
        .nav-links { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
        .nav-link {
            padding: 8px 16px; border-radius: 12px; text-decoration: none;
            color: #1e293b; font-size: 13px; font-weight: 500; transition: all 0.2s;
        }
        .nav-link:hover, .nav-link.active { background: #334EAC; color: #ffffff; font-weight: 600; }
        .nav-search { position: relative; display: flex; align-items: center; }
        .nav-search input {
            background: #DCE8FB; padding: 8px 36px 8px 16px; border-radius: 20px;
            width: 200px; border: none; outline: none; font-family: inherit; font-size: 12px; color: #334155;
        }
        .nav-search i { position: absolute; right: 14px; color: #64748b; font-size: 12px; }
        .nav-actions { display: flex; align-items: center; gap: 8px; }
        .icon-btn {
            width: 34px; height: 34px; border-radius: 50%; background: #091F5B; color: #ffffff;
            display: flex; align-items: center; justify-content: center; text-decoration: none;
            font-size: 13px; border: none; cursor: pointer; transition: background 0.2s;
        }
        .icon-btn:hover, .icon-btn.active { background: #334EAC; }

        /* --- HERO BANNER NAVY --- */
        .page-hero {
            background: linear-gradient(135deg, #091F5B 0%, #1E3A8A 60%, #334EAC 100%);
            border-radius: 20px; padding: 30px 36px; color: #ffffff;
            display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;
            margin-bottom: 24px; box-shadow: 0 10px 25px rgba(9, 31, 91, 0.12);
        }
        .page-hero-badge {
            display: inline-block; background: rgba(255,255,255,0.14); color: #93C5FD;
            font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px;
            padding: 4px 10px; border-radius: 6px; margin-bottom: 8px;
        }
        .page-hero-title { font-size: 24px; font-weight: 800; letter-spacing: -0.3px; }
        .page-hero-sub { font-size: 13px; color: #CBD5E1; margin-top: 4px; }

        /* --- BUTTONS & ALERTS --- */
        .btn {
            padding: 9px 18px; border-radius: 10px; font-size: 12px; font-weight: 700;
            text-decoration: none; border: none; cursor: pointer; font-family: inherit;
            display: inline-flex; align-items: center; gap: 7px; transition: all 0.2s;
        }
        .btn-primary { background: #334EAC; color: #ffffff; }
        .btn-primary:hover { background: #091F5B; }
        .btn-light { background: #ffffff; color: #091F5B; }
        .btn-light:hover { background: #EBF3FF; }
        .btn-outline { background: #ffffff; color: #334EAC; border: 1px solid #334EAC; }
        .btn-outline:hover { background: #EBF3FF; }
        .btn-success { background: #059669; color: #ffffff; }
        .btn-danger { background: #DC2626; color: #ffffff; }
        .alert { padding: 12px 18px; border-radius: 12px; font-size: 13px; margin-bottom: 20px; font-weight: 500; }
        .alert-success { background: #DCFCE7; border: 1px solid #86EFAC; color: #166534; }
        .alert-error { background: #FEE2E2; border: 1px solid #FCA5A5; color: #991B1B; }

        /* --- FOOTER --- */
        .footer-wrapper { margin-top: 48px; }
        .partners-row {
            display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;
            padding-bottom: 24px; border-bottom: 1px solid #E2E8F0; margin-bottom: 30px;
        }
        .partners-label { font-size: 11px; font-weight: 700; color: #1e293b; text-transform: uppercase; letter-spacing: 0.5px; }
        .partners-list { display: flex; gap: 24px; flex-wrap: wrap; color: #94a3b8; font-size: 12px; font-weight: 700; }
        .footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; margin-bottom: 30px; }
        .footer-col-title { font-size: 11px; font-weight: 700; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; }
        .footer-links { list-style: none; }
        .footer-links li { margin-bottom: 8px; }
        .footer-links a { color: #475569; font-size: 12px; text-decoration: none; }
        .footer-links a:hover { color: #334EAC; }
        .footer-bottom {
            padding: 20px 0 30px 0; border-top: 1px solid #E2E8F0;
            display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px; font-size: 11px; color: #94a3b8;
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="container">
    <!-- NAVBAR CAPSULE (5 MENU LENGKAP) -->
    <div class="navbar-wrapper">
        <nav class="navbar">
            <a href="{{ route('home') }}" class="nav-brand">
                <img src="{{ asset('images/logo-palapa.jpg') }}" alt="PALAPA Logo" onerror="this.src='https://placehold.co/42x42/091F5B/FFFFFF?text=P'">
            </a>

            <div class="nav-links">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('labs.index') }}" class="nav-link {{ request()->routeIs('labs.*') ? 'active' : '' }}">Katalog Lab</a>
                <a href="{{ route('instruments.index') }}" class="nav-link {{ request()->routeIs('instruments.*') ? 'active' : '' }}">Instrumen Riset</a>
                <a href="{{ route('bantuan') }}" class="nav-link {{ request()->routeIs('bantuan') ? 'active' : '' }}">Pusat Bantuan</a>
                <a href="{{ auth()->check() ? route('riwayat') : route('login') }}" class="nav-link {{ request()->routeIs('riwayat') || request()->routeIs('client.invoice') || request()->routeIs('client.entry-pass') ? 'active' : '' }}">Riwayat Reservasi</a>
            </div>

            <form action="{{ route('labs.index') }}" method="GET" class="nav-search">
                <input type="text" name="search" placeholder="Cari laboratorium...">
                <i class="fa-solid fa-magnifying-glass"></i>
            </form>

            <div class="nav-actions">
                <a href="{{ auth()->check() ? route('riwayat') : route('login') }}" class="icon-btn {{ request()->routeIs('riwayat') ? 'active' : '' }}" title="Riwayat & Tagihan">
                    <i class="fa-solid fa-cart-shopping"></i>
                </a>
                <a href="{{ auth()->check() ? route('client.profile') : route('login') }}" class="icon-btn {{ request()->routeIs('client.profile') ? 'active' : '' }}" title="Profil & Pengaturan Akun">
                    <i class="fa-solid fa-user"></i>
                </a>
                @auth
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="icon-btn" style="background:#DC2626;" title="Keluar Akun">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                @endauth
            </div>
        </nav>
    </div>

    @if (session('success'))
        <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}</div>
    @endif

    @yield('content')

    <!-- FOOTER & MITRA -->
    <div class="footer-wrapper">
        <div class="partners-row">
            <span class="partners-label">MITRA & TERAKREDITASI RESMI:</span>
            <div class="partners-list">
                <span>KAN ISO 17025</span>
                <span>BRIN Partner</span>
                <span>Science Hunter ID</span>
                <span>Universitas Indonesia</span>
                <span>ITB Labs</span>
            </div>
        </div>

        <div class="footer-grid">
            <div>
                <div class="footer-col-title">Marketplace Sains</div>
                <ul class="footer-links">
                    <li><a href="{{ route('labs.index') }}">Layanan Uji Laboratorium</a></li>
                    <li><a href="{{ route('instruments.index') }}">Sewa Instrumen Canggih</a></li>
                    <li><a href="{{ route('labs.index') }}">Asistensi Pranata Lab</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">Dukungan Riset</div>
                <ul class="footer-links">
                    <li><a href="{{ route('bantuan') }}">Bantuan Peneliti</a></li>
                    <li><a href="{{ route('bantuan') }}">Pedoman Keselamatan Lab</a></li>
                    <li><a href="{{ auth()->check() ? route('riwayat') : route('login') }}">Integrasi Billing SIMPONI</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">Akun Penyewa</div>
                <ul class="footer-links">
                    <li><a href="{{ auth()->check() ? route('riwayat') : route('login') }}">Riwayat Reservasi</a></li>
                    <li><a href="{{ auth()->check() ? route('client.profile') : route('login') }}">Pengaturan Profil & Sandi</a></li>
                    <li><a href="{{ route('labs.index') }}">Ajukan Reservasi Baru</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">Legal & Kontak</div>
                <ul class="footer-links">
                    <li><a href="#">Kebijakan Privasi ISO 27001</a></li>
                    <li><a href="#">Syarat & Ketentuan</a></li>
                    <li><a href="#">Helpdesk PALAPA Indonesia</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; 2026 PALAPA Hak Cipta Dilindungi. Platform Kolaborasi & Sewa Lab Riset Nasional.</span>
            <span>Mendukung Kemandirian Riset & Inovasi Indonesia</span>
        </div>
    </div>
</div>
</body>
</html>
