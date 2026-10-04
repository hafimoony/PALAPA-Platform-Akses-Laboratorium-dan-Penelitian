<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Laboratorium - PALAPA PLATFORM</title>

    <!-- Google Fonts & Font Awesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

        /* --- NAVBAR (Capsule Style Sesuai CSS Kamu) --- */
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

        /* --- FILTER SECTION --- */
        .filter-section {
            background: #ffffff;
            padding: 20px 24px;
            border-radius: 16px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            margin-bottom: 30px;
        }
        .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        .btn-filter-toggle {
            background: #EBF3FF;
            color: #334EAC;
            padding: 8px 16px;
            border-radius: 10px;
            border: none;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: inherit;
        }
        .badge-kan-top {
            background: #059669;
            color: #ffffff;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .filter-form {
            display: flex;
            gap: 16px;
            align-items: flex-end;
            padding-top: 14px;
            border-top: 1px solid #E2E8F0;
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
        select.form-control {
            padding-left: 14px;
            appearance: none;
            cursor: pointer;
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

        /* --- SECTION TITLE --- */
        .catalog-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
        }
        .section-desc {
            font-size: 13px;
            color: #64748b;
            margin-top: 2px;
        }
        .sort-label {
            font-size: 12px;
            color: #64748b;
        }

        /* --- HORIZONTAL LAB CARD LIST --- */
        .lab-list-horizontal {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 40px;
        }
        .lab-card-item {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #E2E8F0;
            overflow: hidden;
            display: flex;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .lab-card-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        }
        .lab-item-img-wrapper {
            position: relative;
            width: 320px;
            min-height: 200px;
            flex-shrink: 0;
        }
        .lab-item-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .badge-kan-card {
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
        .badge-location-card {
            position: absolute;
            bottom: 12px;
            left: 12px;
            background: rgba(15, 23, 42, 0.8);
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
        .lab-item-content {
            padding: 20px 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex: 1;
        }
        .lab-institution-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }
        .lab-institution {
            color: #334EAC;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .lab-rating {
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .lab-rating i { color: #f59e0b; }
        .lab-rating span { color: #94a3b8; font-weight: 400; }
        .lab-name-title {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
            line-height: 1.3;
        }
        .lab-item-desc {
            font-size: 12px;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 14px;
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
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 500;
        }
        .lab-item-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 14px;
            border-top: 1px solid #E2E8F0;
        }
        .lab-price-label {
            font-size: 11px;
            color: #94a3b8;
            display: block;
        }
        .lab-price-val {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
        }
        .lab-price-unit {
            font-size: 12px;
            color: #64748b;
            font-weight: 400;
        }
        .btn-detail {
            padding: 10px 20px;
            background: #334EAC;
            color: #ffffff;
            border-radius: 8px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
        }
        .btn-detail:hover {
            background: #253a8a;
        }

        /* --- FOOTER & PARTNERS (Dari CSS Beranda Kamu) --- */
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
        .custom-pagination {
    display: flex;
    justify-content: center;
    margin: 40px 0;
}
.pagination-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #EBF3FF;
    padding: 6px 12px;
    border-radius: 9999px;
}
.page-btn, .page-num {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    color: #1e293b;
    transition: all 0.2s;
}
.page-num.active {
    background: #334EAC;
    color: #ffffff;
}
.page-num:hover:not(.active), .page-btn:hover:not(.disabled) {
    background: #DCE8FB;
    color: #334EAC;
}
.page-btn.disabled {
    color: #94a3b8;
    cursor: not-allowed;
}
    </style>
</head>
<body>

    <div class="container">

        <!-- NAVBAR CAPSULE -->
        <div class="navbar-wrapper">
            <nav class="navbar">
                <a href="{{ route('home') }}" class="nav-brand">
                    <img src="{{ asset('images/logo-palapa.jpg') }}" alt="PALAPA Logo" onerror="this.src='https://placehold.co/42x42?text=P'">
                </a>

                <div class="nav-links">
                    <a href="{{ route('home') }}" class="nav-link">Beranda</a>
                    <a href="{{ route('labs.index') }}" class="nav-link active">Katalog Lab</a>
                    <a href="#" class="nav-link">Instrumen Riset</a>
                    <a href="#" class="nav-link">Pusat Bantuan</a>
                </div>

                <div class="nav-search">
                    <form action="{{ route('labs.index') }}" method="GET">
                        <input type="text" name="search" placeholder="Pencarian..." value="{{ request('search') }}">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </form>
                </div>

                <div class="nav-actions">
                    <a href="#" class="icon-btn" title="Keranjang"><i class="fa-solid fa-cart-shopping"></i></a>
                    <a href="#" class="icon-btn" title="Profil"><i class="fa-solid fa-user"></i></a>
                </div>
            </nav>
        </div>

        <!-- FILTER CARD -->
        <div class="filter-section">
            <div class="filter-header">
                <button class="btn-filter-toggle">
                    <i class="fa-solid fa-sliders"></i> Filter Pencarian Laboratorium
                </button>
                <div class="badge-kan-top">
                    <i class="fa-solid fa-circle-check"></i> ISO 17025 Accredited Research Labs
                </div>
            </div>

            <form action="{{ route('labs.index') }}" method="GET" class="filter-form">
                <div class="form-group">
                    <label><i class="fa-solid fa-magnifying-glass"></i> Kata Kunci Pencarian</label>
                    <div class="input-box">
                        <i class="fa-solid fa-search"></i>
                        <input type="text" name="search" class="form-control" placeholder="Cari nama lab, universitas, atau fakultas..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fa-solid fa-filter"></i> Kategori Bidang Riset</label>
                    <div class="input-box">
                        <select name="category" class="form-control">
                            <option value="all">Semua Kategori</option>
                            <option value="Material & Kimia" {{ request('category') == 'Material & Kimia' ? 'selected' : '' }}>Material & Kimia</option>
                            <option value="Bioteknologi" {{ request('category') == 'Bioteknologi' ? 'selected' : '' }}>Bioteknologi</option>
                            <option value="Energi Terbarukan" {{ request('category') == 'Energi Terbarukan' ? 'selected' : '' }}>Energi Terbarukan</option>
                            <option value="Farmasi & Medis" {{ request('category') == 'Farmasi & Medis' ? 'selected' : '' }}>Farmasi & Medis</option>
                            <option value="Nanoteknologi" {{ request('category') == 'Nanoteknologi' ? 'selected' : '' }}>Nanoteknologi</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn-search">
                    <i class="fa-solid fa-filter"></i> Terapkan Filter
                </button>
            </form>
        </div>

        <!-- CATALOG HEADER -->
        <div class="catalog-header">
            <div>
                <h2 class="section-title">Menampilkan Laboratorium Tersedia</h2>
                <p class="section-desc">Fasilitas riset berstandar internasional dengan operasional instrumen siap reservasi</p>
            </div>
            <div class="sort-label">
                Urutkan: <strong style="color: #0f172a;">Jenis Rekomendasi (KAN)</strong>
            </div>
        </div>

        <!-- LIST HORIZONTAL LABORATORIUM -->
        <div class="lab-list-horizontal">
            @forelse($labs as $lab)
            <div class="lab-card-item">
                <!-- Image Wrapper -->
                <div class="lab-item-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1562774053-701939374585?q=80&w=600&auto=format&fit=crop" class="lab-item-img" alt="{{ $lab->lab_name }}">
                    <div class="badge-kan-card"><i class="fa-solid fa-circle-check"></i> ISO 17025 / KAN</div>
                    <div class="badge-location-card"><i class="fa-solid fa-location-dot"></i> {{ $lab->campus_name }}</div>
                </div>

                <!-- Info Content -->
                <div class="lab-item-content">
                    <div>
                        <div class="lab-institution-row">
                            <div class="lab-institution"><i class="fa-solid fa-university"></i> {{ $lab->faculty }}</div>
                            <div class="lab-rating"><i class="fa-solid fa-star"></i> 4.9 <span>(128)</span></div>
                        </div>

                        <h3 class="lab-name-title">{{ $lab->lab_name }}</h3>
                        <p class="lab-item-desc">{{ $lab->address_description ?? 'Laboratorium riset berkualitas tinggi dengan dukungan instrumen presisi tinggi untuk kebutuhan akademik & industri.' }}</p>

                        <div class="lab-tags">
                            <span class="lab-tag">{{ $lab->category }}</span>
                            <span class="lab-tag"><i class="fa-solid fa-users me-1"></i> Kapasitas {{ $lab->capacity }} orang</span>
                        </div>
                    </div>

                    <!-- Footer Row -->
                    <div class="lab-item-footer">
                        <div>
                            <span class="lab-price-label">Mulai dari</span>
                            <div class="lab-price-val">Rp {{ number_format($lab->base_price_per_session, 0, ',', '.') }} <span class="lab-price-unit">/ sesi</span></div>
                        </div>
                        <a href="{{ route('labs.show', $lab->lab_id) }}" class="btn-detail">Lihat Detail & Sewa <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 40px; background: #ffffff; border-radius: 16px; border: 1px solid #E2E8F0;">
                <p style="color: #64748b; font-size: 14px; margin-bottom: 12px;">Tidak ada laboratorium yang sesuai dengan kriteria pencarian kamu.</p>
                <a href="{{ route('labs.index') }}" class="btn-detail" style="display: inline-flex;">Reset Filter</a>
            </div>
            @endforelse
        </div>

        <!-- PAGINATION -->
        <div class="custom-pagination">
    @if ($labs->hasPages())
        <div class="pagination-wrapper">
            {{-- Tombol Prev --}}
            @if ($labs->onFirstPage())
                <span class="page-btn disabled"><i class="fa-solid fa-chevron-left"></i></span>
            @else
                <a href="{{ $labs->previousPageUrl() }}" class="page-btn"><i class="fa-solid fa-chevron-left"></i></a>
            @endif

            {{-- Angka Halaman --}}
            @foreach ($labs->getUrlRange(1, $labs->lastPage()) as $page => $url)
                @if ($page == $labs->currentPage())
                    <span class="page-num active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="page-num">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Tombol Next --}}
            @if ($labs->hasMorePages())
                <a href="{{ $labs->nextPageUrl() }}" class="page-btn"><i class="fa-solid fa-chevron-right"></i></a>
            @else
                <span class="page-btn disabled"><i class="fa-solid fa-chevron-right"></i></span>
            @endif
        </div>
    @endif
</div>

        <!-- MITRA & PARTNERS ROW -->
        <div class="partners-row">
            <span class="partners-label">MITRA & TERINTEGRASI BERSAMA</span>
            <div class="partners-list">
                <span>BRIN RI</span>
                <span>ISO 17025</span>
                <span>Kementerian Pendidikan</span>
                <span>ITS Lab</span>
                <span>ITB Lab</span>
            </div>
        </div>

        <!-- FOOTER GRID -->
        <div class="footer-grid">
            <div>
                <div class="footer-col-title">Pusat Akses Lab</div>
                <ul class="footer-links">
                    <li><a href="#">Tentang Kami</a></li>
                    <li><a href="#">Layanan Riset</a></li>
                    <li><a href="#">Mitra Kampus</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">Dukungan Riset</div>
                <ul class="footer-links">
                    <li><a href="#">Panduan Sewa</a></li>
                    <li><a href="#">Sertifikasi Sampel</a></li>
                    <li><a href="#">Asisten Lab</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">Pengajuan Fasilitas</div>
                <ul class="footer-links">
                    <li><a href="#">Daftarkan Lab Anda</a></li>
                    <li><a href="#">Integrasi API</a></li>
                    <li><a href="#">Akses Hibah</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">Legal & Bantuan</div>
                <ul class="footer-links">
                    <li><a href="#">Kebijakan Privasi</a></li>
                    <li><a href="#">Syarat & Ketentuan</a></li>
                    <li><a href="#">Hubungi Customer Service</a></li>
                </ul>
            </div>
        </div>

        <!-- FOOTER BOTTOM -->
        <div class="footer-bottom">
            <span>&copy; 2026 PALAPA Platform. Hak Cipta Dilindungi Undang-Undang.</span>
            <span>Didukung oleh Kementerian Riset dan Pendidikan Tinggi</span>
        </div>

    </div>

</body>
</html>
