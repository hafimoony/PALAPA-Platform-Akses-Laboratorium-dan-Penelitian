<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $lab->lab_name }} - PALAPA</title>
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

        /* --- BREADCRUMB --- */
        .breadcrumb {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            font-size: 13px;
            color: #64748b;
            margin: 10px 0 24px 0;
        }
        .breadcrumb a {
            color: #334EAC;
            text-decoration: none;
            font-weight: 500;
        }
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        .breadcrumb .sep {
            font-size: 10px;
            color: #94a3b8;
        }

        /* --- LAYOUT DETAIL --- */
        .detail-layout {
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 28px;
            align-items: start;
            margin-bottom: 60px;
        }
        .detail-card {
            background: #ffffff;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }
        .detail-img {
            width: 100%;
            height: 340px;
            object-fit: cover;
            display: block;
        }
        .detail-body {
            padding: 28px;
        }
        .badge-category {
            display: inline-block;
            background: #EBF3FF;
            color: #334EAC;
            font-size: 12px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 6px;
            margin-bottom: 12px;
        }
        .detail-title {
            font-size: 26px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.3;
            margin-bottom: 8px;
        }
        .detail-campus {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #334EAC;
            margin-bottom: 22px;
        }
        .divider {
            border: none;
            border-top: 1px solid #E2E8F0;
            margin: 22px 0;
        }
        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
        }
        .detail-desc {
            font-size: 14px;
            color: #475569;
            line-height: 1.7;
            margin-bottom: 24px;
        }

        /* --- INFO BOXES --- */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-bottom: 28px;
        }
        .info-box {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 16px 12px;
            text-align: center;
        }
        .info-box i {
            font-size: 22px;
            margin-bottom: 8px;
            display: block;
        }
        .info-box .info-label {
            display: block;
            font-size: 11px;
            color: #94a3b8;
            margin-bottom: 2px;
        }
        .info-box strong {
            font-size: 13px;
            color: #0f172a;
        }

        /* --- DAFTAR ALAT --- */
        .equipment-list {
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            overflow: hidden;
        }
        .equipment-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            padding: 16px 18px;
            border-bottom: 1px solid #E2E8F0;
        }
        .equipment-item:last-child {
            border-bottom: none;
        }
        .equipment-name {
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .equipment-model {
            font-size: 12px;
            color: #64748b;
        }
        .equipment-right {
            text-align: right;
            flex-shrink: 0;
        }
        .equipment-price {
            display: block;
            font-size: 14px;
            font-weight: 700;
            color: #16a34a;
            margin-bottom: 4px;
        }
        .equipment-stock {
            display: inline-block;
            background: #EBF3FF;
            color: #334EAC;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 6px;
        }
        .equipment-empty {
            padding: 24px;
            text-align: center;
            font-size: 13px;
            color: #94a3b8;
        }

        /* --- SIDEBAR RESERVASI --- */
        .sidebar-card {
            background: #ffffff;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            padding: 24px;
            position: sticky;
            top: 20px;
        }
        .sidebar-card h3 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 18px;
        }
        .price-label {
            display: block;
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 4px;
        }
        .price-value {
            font-size: 28px;
            font-weight: 700;
            color: #334EAC;
            margin-bottom: 4px;
        }
        .price-unit {
            font-size: 13px;
            font-weight: 400;
            color: #64748b;
        }
        .benefit-list {
            list-style: none;
            margin: 20px 0 24px 0;
        }
        .benefit-list li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 12px;
            color: #475569;
            line-height: 1.5;
            margin-bottom: 10px;
        }
        .benefit-list i {
            color: #16a34a;
            margin-top: 3px;
            font-size: 12px;
        }
        .btn-reserve {
            width: 100%;
            padding: 14px;
            background: #334EAC;
            color: #ffffff;
            border-radius: 12px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.2s;
        }
        .btn-reserve:hover {
            background: #283f8c;
        }
        .btn-back {
            width: 100%;
            margin-top: 10px;
            padding: 12px;
            background: #EBF3FF;
            color: #334EAC;
            border-radius: 12px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
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

        /* --- RESPONSIVE --- */
        @media (max-width: 992px) {
            .detail-layout {
                grid-template-columns: 1fr;
            }
            .sidebar-card {
                position: static;
            }
            .nav-links,
            .nav-search {
                display: none;
            }
            .info-grid {
                grid-template-columns: 1fr;
            }
            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .partners-row,
            .footer-bottom {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

    @php
        // Tombol reservasi aman: jika rute booking belum dibuat, halaman tidak error.
        $bookingUrl = \Illuminate\Support\Facades\Route::has('bookings.step1')
            ? route('bookings.step1', ['lab_id' => $lab->lab_id])
            : '#';
    @endphp

    <div class="container">

        <!-- NAVBAR -->
        <div class="navbar-wrapper">
            <nav class="navbar">
                <a href="{{ url('/') }}" class="nav-brand">
                    <img src="{{ asset('images/logo-palapa.jpg') }}" alt="PALAPA Logo">
                </a>

                <div class="nav-links">
                    <a href="{{ url('/') }}" class="nav-link">Beranda</a>
                    <a href="{{ route('labs.index') }}" class="nav-link active">Katalog Lab</a>
                    <a href="{{ route('labs.index') }}" class="nav-link">Instrumen Riset</a>
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

        <!-- BREADCRUMB -->
        <nav class="breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <i class="fa-solid fa-chevron-right sep"></i>
            <a href="{{ route('labs.index') }}">Katalog Lab</a>
            <i class="fa-solid fa-chevron-right sep"></i>
            <span>{{ $lab->lab_name }}</span>
        </nav>

        <!-- KONTEN DETAIL -->
        <div class="detail-layout">

            <!-- KOLOM KIRI -->
            <div class="detail-card">
                <img src="https://images.unsplash.com/photo-1532094349884-543bc11b234d?q=80&w=1000&auto=format&fit=crop" class="detail-img" alt="{{ $lab->lab_name }}">

                <div class="detail-body">
                    <span class="badge-category">{{ $lab->category }}</span>
                    <h1 class="detail-title">{{ $lab->lab_name }}</h1>
                    <div class="detail-campus">
                        <i class="fa-solid fa-building-columns"></i>
                        <span>{{ $lab->campus_name }} - {{ $lab->faculty }}</span>
                    </div>

                    <hr class="divider">

                    <h2 class="section-title">Deskripsi & Lokasi</h2>
                    <p class="detail-desc">
                        {{ $lab->address_description ?? 'Laboratorium riset berkualitas tinggi dilengkapi peralatan standar industri dan jaringan riset nasional.' }}
                    </p>

                    <div class="info-grid">
                        <div class="info-box">
                            <i class="fa-solid fa-users" style="color: #334EAC;"></i>
                            <span class="info-label">Kapasitas</span>
                            <strong>{{ $lab->capacity }} Orang</strong>
                        </div>
                        <div class="info-box">
                            <i class="fa-solid fa-award" style="color: #16a34a;"></i>
                            <span class="info-label">Akreditasi</span>
                            <strong>ISO 17025 / KAN</strong>
                        </div>
                        <div class="info-box">
                            <i class="fa-solid fa-clock" style="color: #f59e0b;"></i>
                            <span class="info-label">Sesi Operasional</span>
                            <strong>08:00 - 16:00 WIB</strong>
                        </div>
                    </div>

                    <h2 class="section-title">Peralatan Tersedia untuk Disewa</h2>
                    <div class="equipment-list">
                        @forelse($lab->equipments as $equipment)
                        <div class="equipment-item">
                            <div>
                                <div class="equipment-name">{{ $equipment->equipment_name }}</div>
                                <div class="equipment-model"><i class="fa-solid fa-tag"></i> Model: {{ $equipment->brand_model ?? 'Standard Model' }}</div>
                            </div>
                            <div class="equipment-right">
                                <span class="equipment-price">Rp {{ number_format($equipment->rental_price_per_unit, 0, ',', '.') }} / unit</span>
                                <span class="equipment-stock">Stok: {{ $equipment->quantity_total }} unit</span>
                            </div>
                        </div>
                        @empty
                        <div class="equipment-empty">Belum ada instrumen peralatan tambahan yang didaftarkan pada lab ini.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN (SIDEBAR) -->
            <aside class="sidebar-card">
                <h3>Ringkasan Reservasi</h3>

                <span class="price-label">Biaya Sewa Tempat (Per Sesi)</span>
                <div class="price-value">Rp {{ number_format($lab->base_price_per_session, 0, ',', '.') }} <span class="price-unit">/ sesi</span></div>

                <ul class="benefit-list">
                    <li><i class="fa-solid fa-check"></i> Termasuk penggunaan fasilitas standar ruangan</li>
                    <li><i class="fa-solid fa-check"></i> Opsional pendampingan asisten teknisi lab</li>
                    <li><i class="fa-solid fa-check"></i> Opsi sewa peralatan uji tambahan</li>
                </ul>

                <a href="{{ $bookingUrl }}" class="btn-reserve">
                    Mulai Reservasi <i class="fa-solid fa-arrow-right"></i>
                </a>
                <a href="{{ route('labs.index') }}" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog
                </a>
            </aside>

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
