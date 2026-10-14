<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PALAPA - Platform Akses Laboratorium & Penelitian</title>
    <!-- Google Fonts & FontAwesome Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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

        /* --- HERO SECTION --- */
        .hero {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
            padding: 40px 0 30px 0;
        }
        .hero-content {
            max-width: 650px;
        }
        .hero-title {
            font-size: 44px;
            font-weight: 700;
            line-height: 1.18;
            letter-spacing: -0.5px;
            margin-bottom: 16px;

            /* Gradasi Warna Biru Gelap ke Biru PALAPA */
            background: linear-gradient(135deg, #182840 0%, #334EAC 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-desc {
            font-size: 15px;
            color: #475569;
            line-height: 1.6;
        }
        .hero-image-wrapper {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
        }
        .hero-image {
            width: 440px;
            height: 270px;
            object-fit: cover;
            display: block;
        }

        /* --- SEARCH CARD --- */
        .search-card {
            background: #ffffff;
            padding: 24px 28px;
            border-radius: 16px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            margin-bottom: 50px;
        }
        .search-card-title {
            color: #334EAC;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 16px;
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

        /* --- SECTION HEADERS --- */
        .section-header {
            margin-bottom: 24px;
        }
        .section-header.center {
            text-align: center;
        }
        .section-subtitle {
            color: #334EAC;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 4px;
        }
        .section-title-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .section-title {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
        }
        .section-desc {
            font-size: 13px;
            color: #64748b;
            margin-top: 2px;
        }
        .see-all-link {
            color: #334EAC;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* --- CATALOG GRID --- */
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
            font-weight: 400;
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

        /* --- MAP SECTION --- */
.map-card {
    background: #ffffff;
    padding: 24px;
    border-radius: 16px;
    border: 1px solid #E2E8F0;
    margin-bottom: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
}
.map-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 16px;
    padding-bottom: 14px;
    border-bottom: 1px solid #E2E8F0;
}
.region-tabs {
    display: flex;
    gap: 6px;
}
.region-btn {
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 500;
    background: #EBF3FF;
    color: #475569;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}
.region-btn.active {
    background: #334EAC;
    color: #ffffff;
    font-weight: 600;
}

/* UBAH BAGIAN INI: Wadah Peta Leaflet */
#map-container {
    width: 100%;
    height: 380px;
    border-radius: 12px;
    position: relative;
    z-index: 1;
}
.map-stat-badge {
    position: absolute;
    right: 20px;
    bottom: 20px;
    background: rgba(51, 78, 172, 0.92);
    color: #ffffff;
    padding: 8px 16px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    backdrop-filter: blur(4px);
    z-index: 1000;
}

/* Custom Popup Leaflet agar serasi dengan UI Palapa */
.leaflet-popup-content-wrapper {
    border-radius: 12px;
    font-family: 'Poppins', sans-serif;
    padding: 2px;
}

        /* --- GRANT BANNER --- */
        .grant-banner {
            background: linear-gradient(135deg, #334EAC 0%, #091F5B 100%);
            border-radius: 16px;
            padding: 28px 32px;
            color: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 50px;
            box-shadow: 0 4px 15px rgba(51, 78, 172, 0.15);
        }
        .grant-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .grant-desc {
            font-size: 12px;
            color: #cbd5e1;
            max-width: 700px;
            line-height: 1.5;
        }
        .btn-grant {
            background: #DCE8FB;
            color: #334EAC;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            font-size: 12px;
            white-space: nowrap;
        }

        /* --- CATEGORIES GRID --- */
        .category-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 14px;
            margin-bottom: 50px;
        }
        .category-card {
            background: linear-gradient(180deg, #334EAC 0%, #091F5B 100%);
            border-radius: 12px;
            padding: 18px 12px;
            text-align: center;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .category-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(9, 31, 91, 0.2);
        }
        .category-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: #DCE8FB;
            color: #334EAC;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .category-name {
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 3px;
            line-height: 1.3;
        }
        .category-price {
            font-size: 10px;
            color: #cbd5e1;
        }

        /* --- STEPS SECTION --- */
        .steps-wrapper {
            background: #F8FAFC;
            padding: 40px 0;
            border-bottom: 1px solid #E2E8F0;
            margin-bottom: 40px;
        }
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }
        .step-card {
            background: #DCE8FB;
            padding: 20px;
            border-radius: 14px;
            border: 1px solid #cbd5e1;
        }
        .step-badge {
            display: inline-block;
            background: #334EAC;
            color: #ffffff;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .step-title {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }
        .step-desc {
            font-size: 11px;
            color: #475569;
            line-height: 1.5;
        }

        /* --- PARTNERS & FOOTER --- */
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
                <!-- LOGO PALAPA (Memanggil dari public/images/logo-palapa.png) -->
                <a href="{{ url('/') }}" class="nav-brand">
                    <img src="{{ asset('images/logo-palapa.jpg') }}" alt="PALAPA Logo">
                </a>

                <div class="nav-links">
                    <a href="{{ url('/') }}" class="nav-link active">Beranda</a>
                    <a href="{{ route('labs.index') }}" class="nav-link">Katalog Lab</a>
                    <a href="#instrumen" class="nav-link">Instrumen Riset</a>
                    <a href="#bantuan" class="nav-link">Pusat Bantuan</a>
                </div>

                <div class="nav-search">
                    <input type="text" placeholder="Pencarian">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>

                <div class="nav-actions">
                    <a href="#" class="icon-btn"><i class="fa-solid fa-cart-shopping"></i></a>
                    <a href="#" class="icon-btn"><i class="fa-solid fa-globe"></i></a>
                </div>
            </nav>
        </div>

        <!-- HERO SECTION -->
        <section class="hero">
            <div class="hero-content">
                <h1 class="hero-title">
                    Pilihan Utama untuk <br>
                    Akselerasi Riset & <br>
                    Pengujian Nasional
                </h1>
                <p class="hero-desc">
                    Temukan fasilitas laboratorium riset, sewa instrumen canggih (GC-MS, SEM, XRD), uji klinis terakreditasi KAN, dan konsultasi laboran di seluruh Indonesia dalam satu ekosistem terpadu.
                </p>
            </div>
            <div class="hero-image-wrapper">
                <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=800&auto=format&fit=crop" class="hero-image" alt="Laboratorium Riset PALAPA">
            </div>
        </section>

        <!-- SEARCH FORM CARD -->
        <section class="search-card">
            <div class="search-card-title">Cari Instrumen Khusus</div>
            <form action="{{ route('labs.index') }}" method="GET" class="search-form">
                <div class="form-group">
                    <label><i class="fa-solid fa-location-dot"></i> LOKASI / KAMPUS</label>
                    <div class="input-box">
                        <i class="fa-solid fa-building-columns"></i>
                        <input type="text" name="location" class="form-control" placeholder="Pilih Kota / Univ (misal: ITB, UI, UGM)...">
                    </div>
                </div>
                <div class="form-group">
                    <label><i class="fa-solid fa-microscope"></i> INSTRUMEN / BIDANG</label>
                    <div class="input-box">
                        <i class="fa-solid fa-vial"></i>
                        <input type="text" name="equipment" class="form-control" placeholder="Nama alat (FE-SEM, XRD, NMR, GC-MS)...">
                    </div>
                </div>
                <div class="form-group" style="flex: 0.5;">
                    <label><i class="fa-solid fa-tag"></i> TARIF</label>
                    <div class="input-box">
                        <input type="number" name="max_price" class="form-control" style="padding-left: 14px;" placeholder="IDR">
                    </div>
                </div>
                <button type="submit" class="btn-search">Cari Lab <i class="fa-solid fa-arrow-right"></i></button>
            </form>

            <div class="filter-tags">
                <span class="tag-title">FILTER POPULER:</span>
                <a href="{{ route('labs.index', ['filter' => 'Mikroskopi Elektron']) }}" class="tag active">Mikroskopi Elektron <i class="fa-solid fa-xmark"></i></a>
                <a href="{{ route('labs.index', ['filter' => 'Spektrometri Massa']) }}" class="tag">Spektrometri Massa</a>
                <a href="{{ route('labs.index', ['filter' => 'Tersedia Hari Ini']) }}" class="tag">Tersedia Hari Ini</a>
                <a href="{{ route('labs.index', ['filter' => 'Didampingi Teknisi']) }}" class="tag">Didampingi Teknisi</a>
                <a href="{{ route('labs.index', ['filter' => 'Akreditasi KAN']) }}" class="tag">Akreditasi KAN</a>
            </div>
        </section>

        <!-- KATALOG UNGGULAN -->
        <section>
            <div class="section-header">
                <div class="section-subtitle">KATALOG UNGGULAN</div>
                <div class="section-title-row">
                    <div>
                        <h2 class="section-title">Laboratorium Rekomendasi Terpopuler</h2>
                        <p class="section-desc">Fasilitas riset berstandar internasional dengan operasional instrumen siap reservasi</p>
                    </div>
                    <a href="{{ route('labs.index') }}" class="see-all-link">Lihat Semua Lab (142 Fasilitas) <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>

            <div class="catalog-grid">
    @forelse($labs as $lab)
    <div class="lab-card">
        <div class="lab-card-img-wrapper">
            <img src="https://images.unsplash.com/photo-1532094349884-543bc11b234d?q=80&w=600&auto=format&fit=crop" class="lab-card-img" alt="{{ $lab->lab_name }}">
            <div class="badge-kan"><i class="fa-solid fa-circle-check"></i> KAN Accredited</div>
            <div class="badge-location"><i class="fa-solid fa-location-dot"></i> {{ $lab->campus_name }}</div>
        </div>
        <div class="lab-card-body">
            <div>
                <div class="lab-institution-row">
                    <div class="lab-institution"><i class="fa-solid fa-circle"></i> {{ $lab->faculty }}</div>
                    <div class="lab-rating"><i class="fa-solid fa-star"></i> 5.0 <span>(76)</span></div>
                </div>
                <h3 class="lab-name">{{ $lab->lab_name }}</h3>
                <div class="lab-tags">
                    <span class="lab-tag">{{ $lab->category }}</span>
                    <span class="lab-tag">Kapasitas {{ $lab->capacity }} Orang</span>
                </div>
            </div>
            <div class="lab-footer">
                <div>
                    <span class="lab-price-label">Mulai dari</span>
                    <div class="lab-price-val">Rp {{ number_format($lab->base_price_per_session, 0, ',', '.') }} <span class="lab-price-unit">/ sesi</span></div>
                </div>
                <a href="{{ route('labs.show', $lab->lab_id) }}" class="btn-detail">Lihat Detail & Sewa <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
    @empty
    <p style="color: #64748b; font-size: 14px; grid-column: 1 / -1; text-align: center;">Belum ada data laboratorium yang tersedia.</p>
    @endforelse
</div>
        </section>

        <!-- PETA SEBARAN NASIONAL -->
<section class="map-card">
    <div class="map-header">
        <div>
            <div class="section-subtitle">PETA SEBARAN NASIONAL</div>
            <h2 class="section-title">Jejaring Pusat Laboratorium Riset Indonesia</h2>
            <p class="section-desc">Tersebar di 5 koridor riset utama dengan lebih dari 2.400 unit instrumen terhubung.</p>
        </div>
        <div class="region-tabs">
    <a href="javascript:void(0)" class="region-btn active" onclick="filterMap('all', this)">Semua</a>
    <a href="javascript:void(0)" class="region-btn" onclick="filterMap('jabodetabek', this)">Jabodetabek</a>
    <a href="javascript:void(0)" class="region-btn" onclick="filterMap('jabar', this)">Bandung & Jabar</a>
    <a href="javascript:void(0)" class="region-btn" onclick="filterMap('yogya', this)">DI Yogyakarta</a>
    <a href="javascript:void(0)" class="region-btn" onclick="filterMap('jatim', this)">Jawa Timur</a>
    <a href="javascript:void(0)" class="region-btn" onclick="filterMap('sulawesi', this)">Sulawesi & Luar Jawa</a>
</div>
    </div>

    <div style="position: relative;">
        <!-- Container Peta Leaflet Interaktif -->
        <div id="map-container"></div>

        <!-- Stat Badge Pojok Kanan Bawah -->
        <div class="map-stat-badge">
            <i class="fa-solid fa-circle-check" style="color: #34d399;"></i> 2.410 Instrumen Aktif Terkoneksi Hari Ini
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Fix Icon Path Leaflet
        delete L.Icon.Default.prototype._getIconUrl;
        L.Icon.Default.mergeOptions({
            iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-2x.png',
            iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
        });

        // 1. Inisialisasi Peta Indonesia
        var map = L.map('map-container').setView([-2.548926, 118.014863], 5);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        var rawLabs = @json($mapLabs ?? []);

        // 2. Database Geocoding Kampus Lengkap Indonesia
        function resolveLocation(lab) {
            // Jika seeder sudah punya lat & lng dari DB
            if (lab.latitude && lab.longitude) {
                return {
                    lat: parseFloat(lab.latitude),
                    lng: parseFloat(lab.longitude),
                    region: getRegionFromCoords(lab.latitude, lab.longitude)
                };
            }

            var text = ((lab.campus_name || '') + ' ' + (lab.lab_name || '') + ' ' + (lab.address_description || '')).toLowerCase();

            // YOGYAKARTA (UII, UGM, UNY, UMY, UIN Suka, UAD, dll)
            if (text.includes('uii') || text.includes('islam indonesia') || text.includes('kaliurang')) {
                return { lat: -7.6878, lng: 110.4144, region: 'yogya' }; // Kampus Terpadu UII Kaliurang
            }
            if (text.includes('ugm') || text.includes('gadjah mada')) {
                return { lat: -7.7713, lng: 110.3775, region: 'yogya' }; // UGM Sleman
            }
            if (text.includes('uny') || text.includes('negeri yogyakarta')) {
                return { lat: -7.7725, lng: 110.3860, region: 'yogya' };
            }
            if (text.includes('umy') || text.includes('muhammadiyah yogyakarta')) {
                return { lat: -7.8105, lng: 110.3200, region: 'yogya' };
            }
            if (text.includes('yogyakarta') || text.includes('yogya') || text.includes('jogja')) {
                return { lat: -7.7800, lng: 110.3700, region: 'yogya' };
            }

            // JABODETABEK & SERPONG (UI, UIN Ciputat, IPB, BRIN, UNJ, Trisakti, Telkom)
            if (text.includes('uin') && (text.includes('syarif') || text.includes('jakarta') || text.includes('ciputat'))) {
                return { lat: -6.3061, lng: 106.7558, region: 'jabodetabek' }; // UIN Syarif Hidayatullah Ciputat
            }
            if (text.includes('ui') || text.includes('universitas indonesia')) {
                return { lat: -6.3650, lng: 106.8291, region: 'jabodetabek' }; // UI Depok
            }
            if (text.includes('brin') || text.includes('serpong') || text.includes('habibie')) {
                return { lat: -6.3553, lng: 106.6710, region: 'jabodetabek' }; // BRIN Serpong
            }
            if (text.includes('ipb') || text.includes('bogor')) {
                return { lat: -6.5593, lng: 106.7262, region: 'jabodetabek' }; // IPB Dramaga
            }
            if (text.includes('jakarta') || text.includes('depok') || text.includes('tangerang') || text.includes('bekasi')) {
                return { lat: -6.2000, lng: 106.8166, region: 'jabodetabek' };
            }

            // BANDUNG & JABAR (ITB, UNPAD, UPI, Telkom)
            if (text.includes('itb') || text.includes('teknologi bandung')) {
                return { lat: -6.8914, lng: 107.6106, region: 'jabar' }; // ITB Ganesha
            }
            if (text.includes('unpad') || text.includes('padjadjaran')) {
                return { lat: -6.9261, lng: 107.7745, region: 'jabar' }; // UNPAD Jatinangor
            }
            if (text.includes('bandung') || text.includes('jabar')) {
                return { lat: -6.9147, lng: 107.6098, region: 'jabar' };
            }

            // JAWA TIMUR (UNAIR, ITS, UB, UM, UNEJ, UNESA, UPN, Pasuruan)
            if (text.includes('unair') || text.includes('airlangga')) {
                return { lat: -7.2756, lng: 112.7836, region: 'jatim' }; // UNAIR Kampus C
            }
            if (text.includes('its') || text.includes('sepuluh nopember')) {
                return { lat: -7.2823, lng: 112.7949, region: 'jatim' }; // ITS Sukolilo
            }
            if (text.includes('ub') || text.includes('brawijaya') || text.includes('malang')) {
                return { lat: -7.9525, lng: 112.6144, region: 'jatim' }; // UB Malang
            }
            if (text.includes('surabaya') || text.includes('pasuruan') || text.includes('jatim') || text.includes('jawa timur')) {
                return { lat: -7.2504, lng: 112.7688, region: 'jatim' };
            }

            // SULAWESI & LUAR JAWA (UNHAS, UNPATTI, UNAND, USU, UNSRAT, UNMUL)
            if (text.includes('pattimura') || text.includes('unpatti') || text.includes('ambon')) {
                return { lat: -3.6552, lng: 128.1906, region: 'sulawesi' }; // Unpatti Ambon
            }
            if (text.includes('unhas') || text.includes('hasanuddin') || text.includes('makassar')) {
                return { lat: -5.1328, lng: 119.4872, region: 'sulawesi' }; // Unhas Makassar
            }
            if (text.includes('andalas') || text.includes('unand') || text.includes('padang')) {
                return { lat: -0.9142, lng: 100.4661, region: 'sulawesi' };
            }

            // Default Fallback: Tersebar di Jawa Timur/Surabaya
            return { lat: -7.2756, lng: 112.7836, region: 'jatim' };
        }

        function getRegionFromCoords(lat, lng) {
            if (lat < -3.0 && lng > 118.0) return 'sulawesi';
            if (lat < -7.0 && lng > 111.0) return 'jatim';
            if (lat < -7.5 && lng > 109.0) return 'yogya';
            if (lat < -6.5 && lng > 107.0) return 'jabar';
            return 'jabodetabek';
        }

        var activeMarkers = [];

        // 3. Render 50 Marker dengan Posisi yang Sangat Presisi
        if (rawLabs.length > 0) {
            rawLabs.forEach(function (lab, index) {
                var loc = resolveLocation(lab);

                // Offset sangat halus (berjarak ~50-100m) agar jika ada beberapa lab di kampus yang sama, pinnya bersanding rapi
                var jitterLat = loc.lat + ((index % 3 - 1) * 0.0008);
                var jitterLng = loc.lng + ((Math.floor(index / 3) % 3 - 1) * 0.0008);

                var marker = L.marker([jitterLat, jitterLng]).addTo(map);

                var popupContent = `
                    <div style="padding: 4px; min-width: 190px;">
                        <span style="font-size: 10px; font-weight: 700; color: #334EAC; text-transform: uppercase;">
                            <i class="fa-solid fa-university"></i> ${lab.campus_name || 'Fasilitas Riset'}
                        </span>
                        <h4 style="font-size: 12px; font-weight: 700; color: #0f172a; margin: 3px 0 6px 0;">${lab.lab_name}</h4>
                        <p style="font-size: 10px; color: #64748b; margin: 0 0 8px 0;"><i class="fa-solid fa-layer-group"></i> ${lab.category || 'Laboratorium'}</p>
                        <a href="/labs/${lab.lab_id}" style="display: inline-block; padding: 5px 12px; background: #334EAC; color: #fff; border-radius: 6px; font-size: 10px; font-weight: 700; text-decoration: none;">
                            Lihat Detail Lab
                        </a>
                    </div>
                `;

                marker.bindPopup(popupContent);
                activeMarkers.push({ region: loc.region, marker: marker, lat: jitterLat, lng: jitterLng });
            });
        }

        // 4. Logika Tombol Tab Filter Wilayah
        window.filterMap = function(region, btnElement) {
            document.querySelectorAll('.region-btn').forEach(btn => btn.classList.remove('active'));
            btnElement.classList.add('active');

            if (region === 'all') {
                map.flyTo([-2.548926, 118.014863], 5);
                return;
            }

            var regionMarkers = activeMarkers.filter(m => m.region === region);
            if (regionMarkers.length > 0) {
                var first = regionMarkers[0];
                var zoomLevel = (region === 'sulawesi') ? 6 : 12;
                map.flyTo([first.lat, first.lng], zoomLevel, { duration: 1.2 });
                first.marker.openPopup();
            }
        };
    });
</script>
        <!-- BANNER DANA HIBAH -->
        <section class="grant-banner">
            <div>
                <h3 class="grant-title"><i class="fa-solid fa-file-invoice-dollar"></i> Mempunyai Dana Hibah Riset (BIMA Kemendiktisaintek, RISPRO LPDP)?</h3>
                <p class="grant-desc">PALAPA menyediakan penerbitan SPK otomatis, faktur pajak resmi, dan integrasi penagihan langsung ke pengelola keuangan universitas.</p>
            </div>
            <a href="#" class="btn-grant">Aktivasi Akun Hibah</a>
        </section>

        <!-- KATALOG KATEGORI CEPAT -->
        <section>
            <div class="section-header center">
                <div class="section-subtitle">KATALOG KATEGORI CEPAT</div>
                <h2 class="section-title">Layanan Pengujian Paling Dicari</h2>
            </div>
            <div class="category-grid">
                <div class="category-card">
                    <div class="category-icon"><i class="fa-solid fa-flask"></i></div>
                    <div class="category-name">GC-MS & HPLC</div>
                    <div class="category-price">Mulai 180rb</div>
                </div>
                <div class="category-card">
                    <div class="category-icon"><i class="fa-solid fa-microscope"></i></div>
                    <div class="category-name">SEM & TEM</div>
                    <div class="category-price">Mulai 250rb</div>
                </div>
                <div class="category-card">
                    <div class="category-icon"><i class="fa-solid fa-cubes"></i></div>
                    <div class="category-name">XRD Kristalinitas</div>
                    <div class="category-price">Mulai 220rb</div>
                </div>
                <div class="category-card">
                    <div class="category-icon"><i class="fa-solid fa-dna"></i></div>
                    <div class="category-name">Sanger & NGS DNA</div>
                    <div class="category-price">Mulai 400rb</div>
                </div>
                <div class="category-card">
                    <div class="category-icon"><i class="fa-solid fa-fire-flame-curved"></i></div>
                    <div class="category-name">TGA & DSC Termal</div>
                    <div class="category-price">Mulai 190rb</div>
                </div>
                <div class="category-card">
                    <div class="category-icon"><i class="fa-solid fa-gauge-high"></i></div>
                    <div class="category-name">Kalibrasi Sensor KAN</div>
                    <div class="category-price">Mulai 310rb</div>
                </div>
            </div>
        </section>

    </div>

    <!-- PROSEDUR PRAKTIS -->
    <div class="steps-wrapper">
        <div class="container">
            <div class="section-header center">
                <div class="section-subtitle">PROSEDUR PRAKTIS</div>
                <h2 class="section-title">Alur Mudah 4 Langkah Menuju Lab Riset</h2>
                <p class="section-desc">Reservasi instrumen canggih semudah memesan akomodasi perjalanan. Bebas birokrasi berbelit.</p>
            </div>
            <div class="steps-grid">
                <div class="step-card">
                    <span class="step-badge">01</span>
                    <h4 class="step-title">Temukan Lab Terdekat</h4>
                    <p class="step-desc">Gunakan filter kota, nama instrumen, atau aktifkan geolokasi radius untuk menemukan instrumen siap pakai terdekat.</p>
                </div>
                <div class="step-card">
                    <span class="step-badge">02</span>
                    <h4 class="step-title">Bandingkan Tarif & Akreditasi</h4>
                    <p class="step-desc">Periksa sertifikasi KAN ISO 17025, ketersediaan operator ahli, transparansi biaya per jam atau per sampel uji.</p>
                </div>
                <div class="step-card">
                    <span class="step-badge">03</span>
                    <h4 class="step-title">Jadwalkan & Pembayaran Fleksibel</h4>
                    <p class="step-desc">Pilih tanggal operasional, unggah protokol sampel, dan bayar lewat virtual account, invoice SPK hibah, atau kartu institusi.</p>
                </div>
                <div class="step-card">
                    <span class="step-badge">04</span>
                    <h4 class="step-title">Terima Entry Pass QR Code</h4>
                    <p class="step-desc">Dapatkan izin akses digital resmi dan kartu instruksi keselamatan lab langsung di smartphone Anda untuk check-in.</p>
                </div>
            </div>
        </div>
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
                    <li><a href="#">Layanan Uji</a></li>
                    <li><a href="#">Sewa Instrumen Canggih</a></li>
                    <li><a href="#">Jasa Laboran</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">DUKUNGAN RISET</div>
                <ul class="footer-links">
                    <li><a href="#">Bantuan Peneliti</a></li>
                    <li><a href="#">Pedoman Keselamatan Lab</a></li>
                    <li><a href="#">Integrasi Hibah & SPK</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">PENGELOLA FASILITAS</div>
                <ul class="footer-links">
                    <li><a href="#">Daftarkan Lab Anda</a></li>
                    <li><a href="#">Standar Kalibrasi</a></li>
                    <li><a href="#">Asuransi Alat</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">LEGAL & KONTAK</div>
                <ul class="footer-links">
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
