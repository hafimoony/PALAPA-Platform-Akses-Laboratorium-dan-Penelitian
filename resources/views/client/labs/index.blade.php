<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Laboratorium & Instrumen - PALAPA</title>
    <!-- Google Fonts & FontAwesome Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            background-color: #ffffff;
            font-family: 'Poppins', sans-serif;
            color: #0f172a;
            overflow-x: hidden;
        }
        .container {
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* --- NAVBAR --- */
        .navbar-wrapper {
            padding: 20px 0;
        }
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #EBF3FF;
            padding: 8px 16px 8px 24px;
            border-radius: 9999px;
        }
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .nav-brand img {
            height: 42px;
            width: auto;
            object-fit: contain;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .nav-link {
            padding: 8px 18px;
            border-radius: 12px;
            text-decoration: none;
            color: #1e293b;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .nav-link.active {
            background: #334EAC;
            color: #ffffff;
            font-weight: 600;
        }
        .nav-search {
            position: relative;
            display: flex;
            align-items: center;
        }
        .nav-search input {
            background: #DCE8FB;
            padding: 8px 36px 8px 16px;
            border-radius: 20px;
            width: 220px;
            border: none;
            outline: none;
            font-family: inherit;
            font-size: 13px;
            color: #334155;
        }
        .nav-search i {
            position: absolute;
            right: 14px;
            color: #64748b;
            font-size: 13px;
        }
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .icon-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #091F5B;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 14px;
        }

        /* --- SEARCH CARD --- */
        .search-card {
            background: #ffffff;
            padding: 24px 28px;
            border-radius: 16px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            margin-bottom: 40px;
            margin-top: 20px;
        }
        .search-form {
            display: flex;
            gap: 16px;
            align-items: flex-end;
            margin-bottom: 18px;
        }
        .form-group {
            flex: 1;
        }
        .form-group label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 700;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        .input-box {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-box i {
            position: absolute;
            left: 14px;
            color: #64748b;
            font-size: 13px;
        }
        .form-control {
            width: 100%;
            padding: 10px 14px 10px 38px;
            background: #EEF5FF;
            border: none;
            border-radius: 8px;
            font-family: inherit;
            font-size: 13px;
            color: #1e293b;
            outline: none;
        }
        .btn-search {
            padding: 11px 28px;
            background: #334EAC;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: inherit;
            transition: background 0.2s;
        }
        .btn-search:hover {
            background: #253a8a;
        }
        .filter-tags {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }
        .tag-title {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            margin-right: 4px;
        }
        .tag {
            padding: 5px 14px;
            background: #E8F1FF;
            color: #334155;
            border-radius: 20px;
            text-decoration: none;
            font-size: 11px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .tag.active {
            background: #334EAC;
            color: #ffffff;
        }

        /* --- CATALOG GRID & CARDS --- */
        .catalog-header {
            background: linear-gradient(135deg, #091F5B 0%, #334EAC 100%);
            border-radius: 20px;
            padding: 40px;
            color: #ffffff;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .catalog-header-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .catalog-header-desc {
            font-size: 14px;
            color: #cbd5e1;
        }
        .catalog-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 50px;
        }
        .lab-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #E2E8F0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .lab-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        }
        .lab-card-img-wrapper {
            position: relative;
            height: 180px;
        }
        .lab-card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .badge-kan {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(255, 255, 255, 0.92);
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
            color: #059669;
            display: flex;
            align-items: center;
            gap: 5px;
            backdrop-filter: blur(4px);
        }
        .badge-location {
            position: absolute;
            bottom: 12px;
            right: 12px;
            background: rgba(15, 23, 42, 0.75);
            color: #ffffff;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
            backdrop-filter: blur(4px);
        }
        .lab-card-body {
            padding: 18px;
            display: flex;
            flex-direction: column;
            flex: 1;
            justify-content: space-between;
        }
        .lab-institution-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }
        .lab-institution {
            color: #334EAC;
            font-size: 11px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .lab-rating {
            font-size: 11px;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 3px;
        }
        .lab-rating i {
            color: #f59e0b;
        }
        .lab-rating span {
            color: #94a3b8;
        }
        .lab-name {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.3;
            margin-bottom: 12px;
        }
        .lab-tags {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }
        .lab-tag {
            background: #EBF3FF;
            color: #334155;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 500;
        }
        .lab-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 14px;
            border-top: 1px solid #E2E8F0;
        }
        .lab-price-label {
            font-size: 10px;
            color: #94a3b8;
            display: block;
        }
        .lab-price-val {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
        }
        .lab-price-unit {
            font-size: 11px;
            color: #64748b;
            font-weight: 400;
        }
        .btn-detail {
            padding: 8px 14px;
            background: #334EAC;
            color: #ffffff;
            border-radius: 8px;
            text-decoration: none;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* --- FOOTER --- */
        .partners-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 24px;
            border-bottom: 1px solid #E2E8F0;
            margin-bottom: 30px;
        }
        .partners-label {
            font-size: 11px;
            font-weight: 700;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .partners-list {
            display: flex;
            gap: 24px;
            color: #94a3b8;
            font-size: 12px;
            font-weight: 700;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            margin-bottom: 30px;
        }
        .footer-col-title {
            font-size: 11px;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }
        .footer-links {
            list-style: none;
        }
        .footer-links li {
            margin-bottom: 8px;
        }
        .footer-links a {
            color: #475569;
            font-size: 12px;
            text-decoration: none;
        }
        .footer-links a:hover {
            color: #334EAC;
        }

        .footer-bottom {
            padding: 20px 0 30px 0;
            border-top: 1px solid #E2E8F0;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #94a3b8;
        }
    </style>
</head>
<body>

    <div class="container">

        <!-- NAVBAR -->
        <div class="navbar-wrapper">
            <nav class="navbar">
                <a href="{{ url('/') }}" class="nav-brand">
                    <img src="{{ asset('images/logo-palapa.jpg') }}" alt="PALAPA Logo">
                </a>

                <div class="nav-links">
                    <a href="{{ url('/') }}" class="nav-link">Beranda</a>
                    <!-- Katalog Lab sekarang menjadi class "active" (ditandai dengan warna biru) -->
                    <a href="{{ route('labs.index') }}" class="nav-link active">Katalog Lab</a>
                    <a href="{{ route('labs.index') }}" class="nav-link">Instrumen Riset</a>
                    <!-- Tautan ke Pusat Bantuan -->
                    <a href="{{ url('/pusat-bantuan') }}" class="nav-link">Pusat Bantuan</a>
                </div>

                <div class="nav-search">
                    <form action="{{ route('labs.index') }}" method="GET" style="margin: 0;">
                        <input type="text" name="search" placeholder="Pencarian" autocomplete="off">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </form>
                </div>

                <div class="nav-actions">
                    <a href="{{ url('/riwayat') }}" class="icon-btn" title="Riwayat"><i class="fa-solid fa-cart-shopping"></i></a>
                    <a href="{{ url('/login') }}" class="icon-btn" title="Masuk Akun"><i class="fa-solid fa-user"></i></a>
                </div>
            </nav>
        </div>

        <!-- HEADER KATALOG -->
        <div class="catalog-header">
            <div>
                <h1 class="catalog-header-title">Katalog Laboratorium & Instrumen</h1>
                <p class="catalog-header-desc">Eksplorasi fasilitas riset terstandarisasi yang siap digunakan untuk menunjang penelitian Anda.</p>
            </div>
            <!-- Tombol jalan pintas menuju pusat bantuan -->
            <div>
                <a href="{{ url('/pusat-bantuan') }}" style="background: rgba(255,255,255,0.2); padding: 10px 20px; border-radius: 10px; color: #fff; text-decoration: none; font-size: 13px; font-weight: 600; backdrop-filter: blur(5px);">
                    <i class="fa-solid fa-circle-question"></i> Panduan Reservasi
                </a>
            </div>
        </div>

        <!-- SEARCH FORM CARD -->
        <section class="search-card">
            <form action="{{ route('labs.index') }}" method="GET" class="search-form">
                <div class="form-group">
                    <label><i class="fa-solid fa-location-dot"></i> LOKASI / KAMPUS</label>
                    <div class="input-box">
                        <i class="fa-solid fa-building-columns"></i>
                        <input type="text" name="location" class="form-control" placeholder="Pilih Kota / Univ (misal: ITB, UI, UGM)..." value="{{ request('location') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label><i class="fa-solid fa-microscope"></i> INSTRUMEN / BIDANG</label>
                    <div class="input-box">
                        <i class="fa-solid fa-vial"></i>
                        <input type="text" name="equipment" class="form-control" placeholder="Nama alat (FE-SEM, XRD, NMR, GC-MS)..." value="{{ request('equipment') }}">
                    </div>
                </div>
                <div class="form-group" style="flex: 0.5;">
                    <label><i class="fa-solid fa-tag"></i> MAKS. TARIF</label>
                    <div class="input-box">
                        <input type="number" name="max_price" class="form-control" style="padding-left: 14px;" placeholder="IDR" value="{{ request('max_price') }}">
                    </div>
                </div>
                <button type="submit" class="btn-search">Filter Lab <i class="fa-solid fa-filter"></i></button>
            </form>

            <div class="filter-tags">
                <span class="tag-title">KATEGORI:</span>
                <a href="{{ route('labs.index', ['filter' => 'Mikroskopi Elektron']) }}" class="tag">Mikroskopi Elektron</a>
                <a href="{{ route('labs.index', ['filter' => 'Spektrometri Massa']) }}" class="tag">Spektrometri Massa</a>
                <a href="{{ route('labs.index', ['filter' => 'Tersedia Hari Ini']) }}" class="tag">Tersedia Hari Ini</a>
                <a href="{{ route('labs.index', ['filter' => 'Didampingi Teknisi']) }}" class="tag">Didampingi Teknisi</a>
                <a href="{{ route('labs.index', ['filter' => 'Akreditasi KAN']) }}" class="tag">Akreditasi KAN</a>
            </div>
        </section>

        <!-- KATALOG GRID (DAFTAR LAB) -->
        <section>
            <div class="catalog-grid">
                @forelse($labs ?? [] as $lab)
                <div class="lab-card">
                    <div class="lab-card-img-wrapper">
                        <!-- Menggunakan foto default/placeholder jika belum ada foto -->
                        <img src="https://images.unsplash.com/photo-1532094349884-543bc11b234d?q=80&w=600&auto=format&fit=crop" class="lab-card-img" alt="{{ $lab->lab_name }}">
                        <div class="badge-kan"><i class="fa-solid fa-circle-check"></i> KAN Accredited</div>
                        <div class="badge-location"><i class="fa-solid fa-location-dot"></i> {{ $lab->campus_name ?? 'Fasilitas Terpadu' }}</div>
                    </div>
                    <div class="lab-card-body">
                        <div>
                            <div class="lab-institution-row">
                                <div class="lab-institution"><i class="fa-solid fa-circle"></i> {{ $lab->faculty ?? 'Pusat Studi' }}</div>
                                <div class="lab-rating"><i class="fa-solid fa-star"></i> 5.0 <span>(76)</span></div>
                            </div>
                            <h3 class="lab-name">{{ $lab->lab_name }}</h3>
                            <div class="lab-tags">
                                <span class="lab-tag">{{ $lab->category ?? 'Layanan Pengujian' }}</span>
                                <span class="lab-tag">Kapasitas {{ $lab->capacity ?? '1' }} Sampel</span>
                            </div>
                        </div>
                        <div class="lab-footer">
                            <div>
                                <span class="lab-price-label">Mulai dari</span>
                                <div class="lab-price-val">Rp {{ number_format($lab->base_price_per_session ?? 0, 0, ',', '.') }} <span class="lab-price-unit">/ sesi</span></div>
                            </div>
                            <a href="{{ route('labs.show', $lab->lab_id ?? 1) }}" class="btn-detail">Lihat Detail <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px 0;">
                    <i class="fa-solid fa-microscope" style="font-size: 40px; color: #cbd5e1; margin-bottom: 16px;"></i>
                    <h3 style="font-size: 18px; color: #0f172a; margin-bottom: 8px;">Laboratorium Tidak Ditemukan</h3>
                    <p style="color: #64748b; font-size: 14px;">Belum ada data laboratorium atau instrumen yang sesuai dengan pencarian Anda.</p>
                </div>
                @endforelse
            </div>
        </section>

    </div>

    <!-- FOOTER -->
    <div class="container">
        <div class="partners-row">
            <div class="partners-label">MITRA & TERAKREDITASI RESMI:</div>
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
                <div class="footer-col-title">MARKETPLACE SAINS</div>
                <ul class="footer-links">
                    <li><a href="{{ route('labs.index') }}">Layanan Uji</a></li>
                    <li><a href="{{ route('labs.index') }}">Sewa Instrumen Canggih</a></li>
                    <li><a href="{{ route('labs.index') }}">Jasa Laboran</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">DUKUNGAN RISET</div>
                <ul class="footer-links">
                    <li><a href="{{ url('/pusat-bantuan') }}">Bantuan Peneliti</a></li>
                    <li><a href="{{ url('/pusat-bantuan') }}">Pedoman Keselamatan Lab</a></li>
                    <li><a href="{{ url('/login') }}">Integrasi Hibah & SPK</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">PENGELOLA FASILITAS</div>
                <ul class="footer-links">
                    <li><a href="{{ url('/login') }}">Daftarkan Lab Anda</a></li>
                    <li><a href="{{ route('labs.index') }}">Standar Kalibrasi</a></li>
                    <li><a href="{{ url('/pusat-bantuan') }}">Asuransi Alat</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">LEGAL & KONTAK</div>
                <ul class="footer-links">
                    <!-- Link legal dibiarkan kosong sesuai kesepakatan awal -->
                    <li><a href="#">Kebijakan Privasi</a></li>
                    <li><a href="#">Syarat & Ketentuan</a></li>
                    <li><a href="#">Kontak PALAPA Indonesia</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div>© 2026 PALAPA Hak Cipta Dilindungi. Platform Kolaborasi & Sewa Lab Riset Nasional.</div>
            <div>Mendukung Kemandirian Riset & Inovasi Indonesia</div>
        </div>
    </div>

</body>
</html>