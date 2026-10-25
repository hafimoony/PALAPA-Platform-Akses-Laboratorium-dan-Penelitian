<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $lab->lab_name }} - PALAPA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body { background: #ffffff; font-family: 'Poppins', sans-serif; color: #0f172a; overflow-x: hidden; }
        .container { max-width: 1240px; margin: 0 auto; padding: 0 20px; }
        a { text-decoration: none; }

        /* NAVBAR */
        .navbar-wrapper { padding: 20px 0; }
        .navbar { display: flex; align-items: center; justify-content: space-between; background: #EBF3FF; padding: 8px 16px 8px 24px; border-radius: 9999px; }
        .nav-brand img { height: 42px; width: auto; object-fit: contain; display: block; }
        .nav-links { display: flex; align-items: center; gap: 8px; }
        .nav-link { padding: 8px 18px; border-radius: 12px; color: #1e293b; font-size: 14px; font-weight: 500; transition: all .2s; }
        .nav-link.active { background: #334EAC; color: #fff; font-weight: 600; }
        .nav-search { position: relative; display: flex; align-items: center; }
        .nav-search input { background: #DCE8FB; padding: 8px 36px 8px 16px; border-radius: 20px; width: 220px; border: none; outline: none; font-family: inherit; font-size: 13px; color: #334155; }
        .nav-search i { position: absolute; right: 14px; color: #64748b; font-size: 13px; }
        .nav-actions { display: flex; align-items: center; gap: 10px; }
        .icon-btn { width: 36px; height: 36px; border-radius: 50%; background: #091F5B; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 14px; }

        /* BREADCRUMB */
        .breadcrumb { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; font-size: 12px; color: #64748b; margin: 6px 0 28px; }
        .breadcrumb a { color: #64748b; }
        .breadcrumb a:hover { color: #334EAC; }
        .breadcrumb .sep { font-size: 8px; color: #94a3b8; }
        .breadcrumb .current { color: #334EAC; font-weight: 600; }

        /* HEADER */
        .lab-header { display: flex; justify-content: space-between; align-items: flex-end; gap: 20px; margin-bottom: 22px; }
        .badge-row { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 10px; }
        .badge { display: inline-flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 600; padding: 5px 12px; border-radius: 999px; }
        .badge-green { background: #0f8a5f; color: #fff; }
        .badge-blue { background: #EBF3FF; color: #334EAC; }
        .lab-title { font-size: 32px; font-weight: 800; color: #091F5B; line-height: 1.25; margin-bottom: 8px; }
        .lab-location { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 500; color: #334EAC; }
        .header-actions { display: flex; gap: 10px; }
        .btn-outline { display: inline-flex; align-items: center; gap: 8px; padding: 9px 16px; border: 1px solid #CBD5E1; background: #fff; border-radius: 8px; font-family: inherit; font-size: 12px; font-weight: 600; color: #1e293b; cursor: pointer; }
        .btn-outline:hover { border-color: #334EAC; color: #334EAC; }
        .btn-outline.saved i { color: #e11d48; }

        /* STATS BAR */
        .stats-bar { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; border: 1px solid #CFE0FA; border-radius: 12px; padding: 14px 20px; margin-bottom: 36px; }
        .stat { display: flex; align-items: center; gap: 12px; }
        .stat-icon { width: 36px; height: 36px; border-radius: 10px; background: #EBF3FF; color: #334EAC; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
        .stat-icon.green { background: #0f8a5f; color: #fff; }
        .stat-icon.yellow { background: #FEF3C7; color: #f59e0b; }
        .stat strong { display: block; font-size: 12px; font-weight: 700; color: #0f172a; }
        .stat span { font-size: 11px; color: #94a3b8; }

        /* GALLERY */
        .gallery { display: grid; grid-template-columns: 2.05fr 1fr; grid-template-rows: 420px; gap: 12px; margin-bottom: 32px; }
        .gallery-side { display: grid; grid-template-rows: repeat(3, minmax(0, 1fr)); gap: 12px; min-height: 0; }
        .g-item { position: relative; border-radius: 12px; overflow: hidden; min-height: 0; min-width: 0; background: linear-gradient(160deg, #DCE8FB, #94a3b8); }
        .g-item img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; display: block; }
        .g-item::after { content: ""; position: absolute; inset: 0; background: linear-gradient(to top, rgba(9,31,91,.65), transparent 55%); }
        .g-caption { position: absolute; left: 14px; bottom: 12px; right: 14px; z-index: 2; color: #fff; font-size: 11px; font-weight: 600; }
        .g-main .g-caption span.tag { display: inline-block; background: #334EAC; padding: 4px 12px; border-radius: 4px; margin-bottom: 6px; font-size: 12px; }
        .g-main .g-caption p { font-weight: 400; font-size: 11px; opacity: .9; }
        .g-all { position: absolute; right: 12px; bottom: 10px; z-index: 3; background: #fff; color: #0f172a; font-size: 11px; font-weight: 700; padding: 6px 12px; border-radius: 6px; }

        /* LAYOUT */
        .detail-layout { display: grid; grid-template-columns: minmax(0, 1fr) 380px; gap: 28px; align-items: start; margin-bottom: 70px; }
        .tabs { display: flex; gap: 6px; border-bottom: 1px solid #E2E8F0; margin-bottom: 28px; position: sticky; top: 0; background: #fff; z-index: 20; }
        .tab { padding: 14px 18px; font-size: 13px; font-weight: 500; color: #475569; border-bottom: 3px solid transparent; margin-bottom: -1px; white-space: nowrap; }
        .tab.active { color: #334EAC; font-weight: 700; border-bottom-color: #334EAC; background: #EEF5FF; border-radius: 8px 8px 0 0; }
        .section { scroll-margin-top: 70px; margin-bottom: 36px; }
        .section-title { font-size: 21px; font-weight: 800; color: #091F5B; margin-bottom: 14px; }
        .section-head { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; }
        .section-head .section-title { margin-bottom: 2px; }
        .section-sub { font-size: 12px; color: #64748b; }
        .pill { background: #EBF3FF; color: #334EAC; font-size: 11px; font-weight: 600; padding: 6px 14px; border-radius: 999px; white-space: nowrap; }
        .about p { font-size: 13.5px; color: #475569; line-height: 1.75; margin-bottom: 14px; }

        /* FEATURE CARDS */
        .feature-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; margin-top: 22px; }
        .feature-card { background: linear-gradient(160deg, #334EAC 0%, #091F5B 100%); color: #fff; border-radius: 12px; padding: 20px; }
        .feature-icon { width: 38px; height: 38px; background: rgba(255,255,255,.18); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 14px; font-size: 15px; }
        .feature-card h4 { font-size: 14px; font-weight: 700; margin-bottom: 6px; }
        .feature-card p { font-size: 12px; line-height: 1.55; color: #fff; opacity: .88; margin-bottom: 0; }

        /* EQUIPMENT */
        .equip-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
        .equip-card { background: #F4F8FF; border: 1px solid #D6E4FA; border-radius: 12px; padding: 16px 18px; display: flex; flex-direction: column; justify-content: space-between; gap: 18px; }
        .equip-top { display: flex; justify-content: space-between; gap: 10px; }
        .equip-name { font-size: 14px; font-weight: 700; color: #091F5B; margin-bottom: 4px; }
        .equip-model { font-size: 11px; color: #64748b; }
        .equip-ready { font-size: 10px; font-weight: 700; color: #0f8a5f; background: #DFF5EC; padding: 3px 8px; border-radius: 6px; height: fit-content; white-space: nowrap; }
        .equip-bottom { display: flex; justify-content: space-between; align-items: center; font-size: 11px; color: #94a3b8; }
        .equip-bottom a { color: #334EAC; font-weight: 700; }
        .empty-box { grid-column: 1 / -1; background: #F4F8FF; border: 1px dashed #BFD2F2; border-radius: 12px; padding: 28px; text-align: center; font-size: 13px; color: #64748b; }

        /* PLP */
        .plp-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
        .plp-card { display: flex; align-items: center; gap: 14px; border: 1px solid #334EAC; border-radius: 12px; padding: 16px; background: #F4F8FF; }
        .avatar { width: 50px; height: 50px; border-radius: 50%; background: #091F5B; color: #fff; font-weight: 700; font-size: 15px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .plp-card strong { display: block; font-size: 14px; color: #0f172a; }
        .plp-card .role { font-size: 12px; color: #334EAC; font-weight: 600; }
        .plp-card .exp { font-size: 10.5px; color: #94a3b8; }

        /* SOP */
        .sop-box { background: #EEF5FF; border: 1px solid #D6E4FA; border-radius: 12px; padding: 22px; }
        .sop-box h3 { font-size: 17px; font-weight: 800; color: #091F5B; display: flex; align-items: center; gap: 10px; margin-bottom: 14px; }
        .sop-box h3 i { color: #0f8a5f; }
        .sop-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
        .sop-item { display: flex; gap: 8px; font-size: 12px; color: #475569; line-height: 1.55; }
        .sop-item i { color: #0f8a5f; margin-top: 3px; font-size: 10px; }

        /* REVIEWS */
        .review-card { background: #F4F8FF; border: 1px solid #D6E4FA; border-radius: 10px; padding: 14px 18px; margin-bottom: 12px; }
        .review-top { display: flex; justify-content: space-between; align-items: flex-start; }
        .review-top strong { font-size: 13px; color: #0f172a; }
        .review-meta { font-size: 10.5px; color: #94a3b8; margin-bottom: 6px; }
        .review-stars { color: #f59e0b; font-size: 11px; letter-spacing: 2px; }
        .review-text { font-size: 12.5px; color: #475569; line-height: 1.6; }
        .rating-top { color: #f59e0b; font-size: 13px; font-weight: 700; }
        .rating-top span { color: #94a3b8; font-weight: 500; }

        /* SIDEBAR */
        .sidebar { position: sticky; top: 20px; background: #F4F8FF; border: 1px solid #D6E4FA; border-radius: 16px; padding: 22px; box-shadow: 0 8px 30px rgba(51,78,172,.08); }
        .side-top { display: flex; justify-content: space-between; align-items: center; }
        .side-label { font-size: 11px; font-weight: 600; color: #64748b; letter-spacing: .4px; text-transform: uppercase; }
        .badge-hibah { background: #334EAC; color: #fff; font-size: 9px; font-weight: 700; padding: 4px 10px; border-radius: 999px; }
        .side-price { font-size: 30px; font-weight: 800; color: #091F5B; margin: 4px 0 16px; }
        .side-price small { font-size: 13px; font-weight: 400; color: #64748b; }
        .side-hr { border: none; border-top: 1px solid #D6E4FA; margin-bottom: 16px; }
        .field-label { display: flex; justify-content: space-between; font-size: 12px; font-weight: 700; color: #0f172a; margin-bottom: 8px; }
        .field-label small { font-weight: 400; color: #94a3b8; font-size: 10.5px; }
        .date-input { width: 100%; padding: 10px 12px; border: 1px solid #CBD5E1; border-radius: 8px; font-family: inherit; font-size: 13px; background: #fff; color: #0f172a; margin-bottom: 16px; }
        .shift-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-bottom: 16px; }
        .shift-btn { padding: 8px 4px; border: 1px solid #CBD5E1; background: #fff; border-radius: 8px; font-family: inherit; text-align: center; cursor: pointer; font-size: 12px; font-weight: 600; color: #475569; }
        .shift-btn small { display: block; font-size: 9.5px; font-weight: 400; color: #94a3b8; margin-top: 2px; }
        .shift-btn.active { border: 2px solid #334EAC; background: #EEF5FF; color: #334EAC; }
        .stepper { display: flex; align-items: center; justify-content: space-between; border: 1px solid #CBD5E1; background: #fff; border-radius: 8px; padding: 5px; margin-bottom: 16px; }
        .stepper button { width: 28px; height: 28px; border: 1px solid #334EAC; background: #fff; color: #334EAC; border-radius: 6px; font-size: 15px; font-weight: 700; cursor: pointer; line-height: 1; }
        .stepper span { font-size: 13px; font-weight: 700; color: #0f172a; }
        .addon { display: flex; align-items: flex-start; gap: 10px; background: #fff; border: 1px solid #CBD5E1; border-radius: 8px; padding: 10px 12px; margin-bottom: 8px; cursor: pointer; }
        .addon input { margin-top: 3px; accent-color: #334EAC; width: 15px; height: 15px; }
        .addon strong { display: block; font-size: 12px; color: #0f172a; }
        .addon small { font-size: 10.5px; color: #334EAC; font-weight: 600; }
        .summary { background: #fff; border: 1px solid #D6E4FA; border-radius: 10px; padding: 14px 16px; margin: 14px 0 16px; }
        .sum-row { display: flex; justify-content: space-between; font-size: 12px; color: #475569; margin-bottom: 6px; }
        .sum-row.discount { color: #0f8a5f; font-weight: 700; }
        .sum-total { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #E2E8F0; margin-top: 10px; padding-top: 12px; font-size: 12px; font-weight: 700; }
        .sum-total span:last-child { font-size: 18px; font-weight: 800; color: #091F5B; }
        .btn-reserve { width: 100%; padding: 14px; background: #334EAC; color: #fff; border: none; border-radius: 10px; font-family: inherit; font-size: 14px; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; }
        .btn-reserve:hover { background: #283f8c; }
        .btn-consult { width: 100%; margin-top: 10px; padding: 11px; background: #fff; border: 1px solid #CBD5E1; color: #334EAC; border-radius: 10px; font-size: 12.5px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .side-notes { margin-top: 14px; font-size: 10.5px; color: #64748b; }
        .side-notes div { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; }
        .side-notes i { color: #334EAC; }

        /* FOOTER */
        .partners-row { display: flex; justify-content: space-between; align-items: center; padding-bottom: 24px; border-bottom: 1px solid #E2E8F0; margin-bottom: 30px; }
        .partners-label { font-size: 11px; font-weight: 700; color: #1e293b; text-transform: uppercase; letter-spacing: .5px; }
        .partners-list { display: flex; gap: 24px; color: #94a3b8; font-size: 12px; font-weight: 700; }
        .footer-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; margin-bottom: 30px; }
        .footer-col-title { font-size: 11px; font-weight: 700; color: #0f172a; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 12px; }
        .footer-links { list-style: none; }
        .footer-links li { margin-bottom: 8px; }
        .footer-links a { color: #475569; font-size: 12px; }
        .footer-links a:hover { color: #334EAC; }
        .footer-bottom { padding: 20px 0 30px; border-top: 1px solid #E2E8F0; display: flex; justify-content: space-between; font-size: 11px; color: #94a3b8; }

        @media (max-width: 992px) {
            .detail-layout { grid-template-columns: minmax(0, 1fr); }
            .sidebar { position: static; }
            .nav-links, .nav-search { display: none; }
            .lab-header { flex-direction: column; align-items: flex-start; }
            .stats-bar { grid-template-columns: repeat(2, 1fr); }
            .gallery { grid-template-columns: 1fr; grid-template-rows: none; }
            .g-main { height: 280px; }
            .gallery-side { grid-template-rows: none; grid-template-columns: repeat(3, 1fr); height: 120px; }
            .gallery-side .g-caption { display: none; }
            .feature-grid, .equip-grid, .plp-grid, .sop-grid { grid-template-columns: 1fr; }
            .tabs { overflow-x: auto; }
            .footer-grid { grid-template-columns: repeat(2, 1fr); }
            .partners-row, .footer-bottom { flex-direction: column; gap: 12px; align-items: flex-start; }
        }
    </style>
</head>
<body>

@php
    $bookingBase = \Illuminate\Support\Facades\Route::has('bookings.step1')
        ? route('bookings.step1', ['lab_id' => $lab->lab_id])
        : '';

    $reviews = [
        ['name' => 'Dr. Nico Rosberg — Fakultas Farmasi UGM', 'meta' => 'Riset Ekstraksi Antioksidan Bioaktif • Selesai April 2025', 'text' => 'Fasilitas BSC dan autoklaf sangat terawat dan steril. Pendampingan dari Pak Ahmad sangat mempermudah isolasi mikroba kami. SPJ sistem PALAPA langsung diakui oleh tim keuangan hibah.'],
        ['name' => 'Gabriel Bortoleto — Sarjana Teknik Kimia UNAIR', 'meta' => 'Tesis Analisis Genetik Bakteri Endofit • Selesai Maret 2023', 'text' => 'Mantap deh pokoknya yah'],
        ['name' => 'Oliver Bearman — Magister Teknik Elektro UI', 'meta' => 'Tesis Perhitungan Hotwheels • Selesai Juni 2025', 'text' => 'Alatnya Mantap Rekomended yah'],
        ['name' => 'Alexander Albon — Magister Kimia Universitas Efwan', 'meta' => 'Tesis Analisis Pertumbuhan Kecambah • Selesai Maret 2026', 'text' => 'GACOR MEN'],
    ];
@endphp

<div class="container">

    <!-- NAVBAR -->
    <div class="navbar-wrapper">
        <nav class="navbar">
            <a href="{{ url('/') }}" class="nav-brand"><img src="{{ asset('images/logo-palapa.jpg') }}" alt="PALAPA Logo"></a>
            <div class="nav-links">
                <a href="{{ url('/') }}" class="nav-link">Beranda</a>
                <a href="{{ route('labs.index') }}" class="nav-link active">Katalog Lab</a>
                <a href="{{ route('labs.index') }}" class="nav-link">Instrumen Riset</a>
                <a href="{{ url('/pusat-bantuan') }}" class="nav-link">Pusat Bantuan</a>
            </div>
            <div class="nav-search">
                <form action="{{ route('labs.index') }}" method="GET" style="margin:0;">
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
        <a href="{{ route('home') }}">Beranda</a><i class="fa-solid fa-chevron-right sep"></i>
        <a href="{{ route('labs.index') }}">Katalog Lab</a><i class="fa-solid fa-chevron-right sep"></i>
        <a href="{{ route('labs.index', ['category' => $lab->category]) }}">{{ $lab->category }}</a><i class="fa-solid fa-chevron-right sep"></i>
        <span class="current">{{ $lab->lab_name }}</span>
    </nav>

    <!-- HEADER -->
    <div class="lab-header">
        <div>
            <div class="badge-row">
                <span class="badge badge-green"><i class="fa-solid fa-circle-check"></i> Terverifikasi ISO/IEC 17025</span>
                <span class="badge badge-blue">Sentra Unggulan {{ $lab->category }}</span>
            </div>
            <h1 class="lab-title">{{ $lab->lab_name }}</h1>
            <div class="lab-location">
                <i class="fa-solid fa-location-dot"></i>
                <span>{{ $lab->campus_name }}{{ $lab->address_description ? ' — ' . $lab->address_description : '' }}</span>
            </div>
        </div>
        <div class="header-actions">
            <button type="button" class="btn-outline" id="btnWishlist"><i class="fa-regular fa-heart"></i> Simpan Wishlist</button>
            <button type="button" class="btn-outline" id="btnShare"><i class="fa-solid fa-share-nodes"></i> Bagikan Info Lab</button>
        </div>
    </div>

    <!-- STATS BAR -->
    <div class="stats-bar">
        <div class="stat">
            <div class="stat-icon yellow"><i class="fa-solid fa-star"></i></div>
            <div><strong>5.0 (28 riset)</strong><span>Reputasi Teruji</span></div>
        </div>
        <div class="stat">
            <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
            <div><strong>Maksimal {{ $lab->capacity }} Peserta</strong><span>Praktikan / Peneliti</span></div>
        </div>
        <div class="stat">
            <div class="stat-icon"><i class="fa-regular fa-clock"></i></div>
            <div><strong>Senin - Sabtu</strong><span>08:00 - 17:00 WIB</span></div>
        </div>
        <div class="stat">
            <div class="stat-icon green"><i class="fa-solid fa-bolt"></i></div>
            <div><strong>Respon Cepat</strong><span>&lt; 1 jam balasan</span></div>
        </div>
    </div>

    <!-- GALLERY -->
    <div class="gallery">
        <div class="g-item g-main">
            <img src="https://images.unsplash.com/photo-1532094349884-543bc11b234d?q=80&w=1400&auto=format&fit=crop" alt="{{ $lab->lab_name }}" onerror="this.style.display='none'">
            <div class="g-caption">
                <span class="tag">Ruang Utama Praktikum Terpadu</span>
                <p>Dilengkapi workstation individu, sistem sirkulasi udara terkontrol, dan area kerja steril.</p>
            </div>
        </div>
        <div class="gallery-side">
            <div class="g-item">
                <img src="https://images.unsplash.com/photo-1579154204601-01588f351e67?q=80&w=800&auto=format&fit=crop" alt="Area Sentrifus" onerror="this.style.display='none'">
                <div class="g-caption">Area Sentrifus &amp; Autoklaf Digital</div>
            </div>
            <div class="g-item">
                <img src="https://images.unsplash.com/photo-1582719471384-894fbb16e074?q=80&w=800&auto=format&fit=crop" alt="Instrumen Spektrometri" onerror="this.style.display='none'">
                <div class="g-caption">Instrumen Spektrometri &amp; Analisis Bahan</div>
            </div>
            <div class="g-item">
                <img src="https://images.unsplash.com/photo-1576086213369-97a306d36557?q=80&w=800&auto=format&fit=crop" alt="Stasiun Mikroskopi" onerror="this.style.display='none'">
                <div class="g-caption">Stasiun Mikroskopi &amp; Kultur Sel</div>
                <span class="g-all"><i class="fa-regular fa-image"></i> Lihat Semua (14)</span>
            </div>
        </div>
    </div>

    <!-- KONTEN + SIDEBAR -->
    <div class="detail-layout">

        <!-- KIRI -->
        <div>
            <div class="tabs" id="tabs">
                <a href="#overview" class="tab active"><i class="fa-regular fa-circle-check"></i> Overview &amp; Deskripsi</a>
                <a href="#alat" class="tab">Daftar Alat &amp; Spesifikasi</a>
                <a href="#sop" class="tab">SOP &amp; Protokol K3</a>
                <a href="#ulasan" class="tab">Ulasan &amp; Rating ({{ count($reviews) * 7 }})</a>
            </div>

            <!-- OVERVIEW -->
            <section class="section about" id="overview">
                <h2 class="section-title">Tentang {{ $lab->lab_name }}</h2>
                <p>{{ $lab->lab_name }} merupakan fasilitas laboratorium riset terpusat di bawah pengelolaan {{ $lab->faculty }}, {{ $lab->campus_name }}. Lab ini dirancang khusus untuk memfasilitasi penelitian, pengujian sampel, dan praktikum terapan dengan dukungan instrumen yang telah terkalibrasi secara berkala.</p>
                <p>Dengan standar mutu laboratorium pengujian ISO/IEC 17025 yang telah terakreditasi KAN, laboratorium ini terbuka bagi peneliti internal, dosen, mahasiswa tingkat sarjana/pascasarjana, serta kolaborator industri, sekolah, dan lembaga riset independen secara legal, akuntabel, dan transparan.</p>

                <div class="feature-grid">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fa-regular fa-building"></i></div>
                        <h4>Kapasitas Luas</h4>
                        <p>Dapat menampung hingga {{ $lab->capacity }} praktikan serentak dengan alokasi bench kerja terpisah.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
                        <h4>Standar Akreditasi Internasional</h4>
                        <p>ISO/IEC 17025:2017 terakreditasi KAN, kalibrasi instrumen berkala dan tersertifikasi.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fa-regular fa-clock"></i></div>
                        <h4>Jam Operasional Fleksibel</h4>
                        <p>Tersedia pembagian shift Sesi Pagi (08:00 - 12:00) dan Sesi Siang (13:00 - 17:00).</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fa-regular fa-file-lines"></i></div>
                        <h4>Dukungan Pendanaan Hibah</h4>
                        <p>Faktur &amp; kuitansi langsung terintegrasi sebagai pelaporan SPJ hibah riset.</p>
                    </div>
                </div>
            </section>

            <!-- ALAT -->
            <section class="section" id="alat">
                <div class="section-head">
                    <div>
                        <h2 class="section-title">Instrumen &amp; Fasilitas Terpasang</h2>
                        <p class="section-sub">Semua instrumen telah lolos kalibrasi kuartal pertama tahun berjalan.</p>
                    </div>
                    <span class="pill">{{ $lab->equipments->count() }} Kategori Siap Pakai</span>
                </div>
                <div class="equip-grid">
                    @forelse($lab->equipments as $equipment)
                    <div class="equip-card">
                        <div class="equip-top">
                            <div>
                                <div class="equip-name">{{ $equipment->equipment_name }}</div>
                                <div class="equip-model">{{ $equipment->brand_model ?? 'Standard Model' }}</div>
                            </div>
                            <span class="equip-ready">{{ $equipment->quantity_total }} Unit Ready</span>
                        </div>
                        <div class="equip-bottom">
                            <span>Rp {{ number_format($equipment->rental_price_per_unit, 0, ',', '.') }} / unit</span>
                            <a href="#alat">Detail Spek <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                    @empty
                    <div class="empty-box">Belum ada instrumen yang didaftarkan pada lab ini.</div>
                    @endforelse
                </div>
            </section>

            <!-- PLP -->
            <section class="section">
                <div class="section-head">
                    <h2 class="section-title">Tim Pranata Laboratorium Pendidikan (PLP)</h2>
                    <span class="pill">Sertifikasi BNSP</span>
                </div>
                <p class="section-sub" style="margin-bottom:14px;">Dapatkan pendampingan langsung dari analis bersertifikat untuk memandu kalibrasi atau preparasi sampel kompleks.</p>
                <div class="plp-grid">
                    <div class="plp-card">
                        <div class="avatar">AF</div>
                        <div>
                            <strong>Ahmad Fauzi, S.Si.</strong>
                            <div class="role">Laboran Ahli Biosains &amp; Mikrobiologi</div>
                            <div class="exp">Pengalaman 8 tahun • Sertifikasi K3 Kimia &amp; Biologi</div>
                        </div>
                    </div>
                    <div class="plp-card">
                        <div class="avatar">SR</div>
                        <div>
                            <strong>Siti Rahmania, M.Biotech</strong>
                            <div class="role">Teknisi Instrumen Kultur Sel</div>
                            <div class="exp">Spesialis Real-Time PCR &amp; Sitogenetika</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SOP -->
            <section class="section" id="sop">
                <div class="sop-box">
                    <h3><i class="fa-solid fa-shield-halved"></i> Prosedur Keselamatan &amp; SOP K3 Laboratorium</h3>
                    <div class="sop-grid">
                        <div class="sop-item"><i class="fa-solid fa-check"></i><span>Wajib mengenakan jas lab lengan panjang, kacamata google, &amp; sarung tangan nitril.</span></div>
                        <div class="sop-item"><i class="fa-solid fa-check"></i><span>Pengolahan limbah B3 biologi &amp; pelarut organik sesuai standar KLHK &amp; autoclave pretreatment.</span></div>
                        <div class="sop-item"><i class="fa-solid fa-check"></i><span>Logbook digital wajib diisi saat check-in dan serah terima instrumen via PALAPA barcode.</span></div>
                    </div>
                </div>
            </section>

            <!-- ULASAN -->
            <section class="section" id="ulasan">
                <div class="section-head">
                    <h2 class="section-title">Ulasan Peneliti &amp; Sivitas Akademika</h2>
                    <div class="rating-top"><i class="fa-solid fa-star"></i> 5.0 <span>/ 5.0 ({{ count($reviews) * 7 }})</span></div>
                </div>
                @foreach($reviews as $review)
                <div class="review-card">
                    <div class="review-top">
                        <strong>{{ $review['name'] }}</strong>
                        <span class="review-stars">★★★★★</span>
                    </div>
                    <div class="review-meta">{{ $review['meta'] }}</div>
                    <p class="review-text">"{{ $review['text'] }}"</p>
                </div>
                @endforeach
            </section>
        </div>

        <!-- SIDEBAR RESERVASI -->
        <aside class="sidebar">
            <div class="side-top">
                <span class="side-label">Tarif Sewa Fasilitas Lab</span>
                <span class="badge-hibah">Subsidi Hibah Nasional</span>
            </div>
            <div class="side-price">Rp {{ number_format($lab->base_price_per_session, 0, ',', '.') }} <small>/ sesi (4 jam)</small></div>
            <hr class="side-hr">

            <div class="field-label">Pilih Tanggal Sesi</div>
            <input type="date" class="date-input" id="inputDate" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">

            <div class="field-label">Pilih Shift / Durasi</div>
            <div class="shift-grid" id="shiftGrid">
                <button type="button" class="shift-btn active" data-shift="Pagi (08:00-12:00)" data-mult="1">Pagi<small>08:00-12:00</small></button>
                <button type="button" class="shift-btn" data-shift="Siang (13:00-17:00)" data-mult="1">Siang<small>13:00-17:00</small></button>
                <button type="button" class="shift-btn" data-shift="Penuh (08:00-17:00)" data-mult="2">Penuh<small>08:00-17:00</small></button>
            </div>

            <div class="field-label">Jumlah Peneliti / Praktikan <small>Maks. {{ $lab->capacity }} orang</small></div>
            <div class="stepper">
                <button type="button" id="btnMinus">−</button>
                <span id="participantLabel">15 Orang</span>
                <button type="button" id="btnPlus">+</button>
            </div>

            <div class="field-label">Layanan Tambahan (Add-on)</div>
            <label class="addon">
                <input type="checkbox" id="addTech" data-price="150000" data-per="session" checked>
                <div><strong>Jasa Laboran Pendamping</strong><small>+Rp 150.000 / sesi</small></div>
            </label>
            <label class="addon">
                <input type="checkbox" id="addReagen" data-price="100000" data-per="batch">
                <div><strong>Bahan Reagen Dasar &amp; Aquabidest</strong><small>+Rp 100.000 / batch</small></div>
            </label>
            <label class="addon">
                <input type="checkbox" id="addLimbah" data-price="50000" data-per="session">
                <div><strong>Preparasi Limbah B3 Tambahan</strong><small>+Rp 50.000 / sesi</small></div>
            </label>

            <div class="summary">
                <div class="sum-row"><span>Sewa Lab <span id="sumShiftName">Sesi Pagi</span></span><span id="sumRent">Rp 0</span></div>
                <div class="sum-row" id="rowTech"><span>Jasa Laboran Pendamping</span><span id="sumTech">Rp 0</span></div>
                <div class="sum-row" id="rowReagen" style="display:none;"><span>Bahan Reagen Dasar</span><span id="sumReagen">Rp 0</span></div>
                <div class="sum-row" id="rowLimbah" style="display:none;"><span>Preparasi Limbah B3</span><span id="sumLimbah">Rp 0</span></div>
                <div class="sum-row discount"><span>Diskon Sivitas Akademika (-5%)</span><span id="sumDisc">-Rp 0</span></div>
                <div class="sum-total"><span>Total Estimasi:</span><span id="sumTotal">Rp 0</span></div>
            </div>

            <button type="button" class="btn-reserve" id="btnReserve">Pesan &amp; Reservasi Sekarang <i class="fa-solid fa-arrow-right"></i></button>
            <a href="{{ url('/pusat-bantuan') }}" class="btn-consult"><i class="fa-regular fa-comment-dots"></i> Konsultasi Teknis dengan Operator</a>

            <div class="side-notes">
                <div><i class="fa-solid fa-lock"></i> Pembayaran Resmi via VA Kampus &amp; SPJ SIMPONI</div>
                <div><i class="fa-solid fa-rotate"></i> Bebas Reschedule hingga H-2 sebelum sesi</div>
            </div>
        </aside>
    </div>
</div>

<!-- FOOTER -->
<div class="container">
    <div class="partners-row">
        <div class="partners-label">MITRA &amp; TERAKREDITASI RESMI:</div>
        <div class="partners-list">
            <span>KAN ISO 17025</span><span>BRIN Partner</span><span>Science Hunter ID</span><span>Universitas Indonesia</span><span>ITB Labs</span>
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
                <li><a href="{{ url('/login') }}">Integrasi Hibah &amp; SPK</a></li>
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
            <div class="footer-col-title">LEGAL &amp; KONTAK</div>
            <ul class="footer-links">
                <li><a href="#">Kebijakan Privasi</a></li>
                <li><a href="#">Syarat &amp; Ketentuan</a></li>
                <li><a href="#">Kontak PALAPA Indonesia</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <div>© 2026 PALAPA Hak Cipta Dilindungi. Platform Kolaborasi &amp; Sewa Lab Riset Nasional.</div>
        <div>Mendukung Kemandirian Riset &amp; Inovasi Indonesia</div>
    </div>
</div>

<script>
    (function () {
        var BASE_PRICE = {{ (float) $lab->base_price_per_session }};
        var MAX_PARTICIPANTS = {{ (int) $lab->capacity }};
        var LAB_ID = @json($lab->lab_id);
        var BOOKING_URL = @json($bookingBase);

        var participants = Math.min(15, MAX_PARTICIPANTS);
        var shiftMult = 1;
        var shiftValue = 'Pagi (08:00-12:00)';
        var shiftName = 'Sesi Pagi';

        var rupiah = function (n) { return 'Rp ' + Math.round(n).toLocaleString('id-ID'); };
        var $ = function (id) { return document.getElementById(id); };

        function recalc() {
            var rent = BASE_PRICE * shiftMult;
            var tech = $('addTech').checked ? 150000 * shiftMult : 0;
            var reagen = $('addReagen').checked ? 100000 : 0;
            var limbah = $('addLimbah').checked ? 50000 * shiftMult : 0;
            var disc = (rent + tech) * 0.05;
            var total = rent + tech + reagen + limbah - disc;

            $('sumShiftName').textContent = shiftName;
            $('sumRent').textContent = rupiah(rent);
            $('sumTech').textContent = rupiah(tech);
            $('sumReagen').textContent = rupiah(reagen);
            $('sumLimbah').textContent = rupiah(limbah);
            $('rowTech').style.display = tech ? 'flex' : 'none';
            $('rowReagen').style.display = reagen ? 'flex' : 'none';
            $('rowLimbah').style.display = limbah ? 'flex' : 'none';
            $('sumDisc').textContent = '-' + rupiah(disc);
            $('sumTotal').textContent = rupiah(total);
            $('participantLabel').textContent = participants + ' Orang';
        }

        // Shift
        document.querySelectorAll('.shift-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.shift-btn').forEach(function (b) { b.classList.remove('active'); });
                btn.classList.add('active');
                shiftMult = parseInt(btn.dataset.mult, 10);
                shiftValue = btn.dataset.shift;
                shiftName = 'Sesi ' + btn.firstChild.textContent.trim();
                recalc();
            });
        });

        // Stepper
        $('btnMinus').addEventListener('click', function () { if (participants > 1) { participants--; recalc(); } });
        $('btnPlus').addEventListener('click', function () { if (participants < MAX_PARTICIPANTS) { participants++; recalc(); } });

        ['addTech', 'addReagen', 'addLimbah'].forEach(function (id) { $(id).addEventListener('change', recalc); });

        // Reservasi
        $('btnReserve').addEventListener('click', function () {
            if (!BOOKING_URL) {
                alert('Halaman reservasi sedang disiapkan. Pilihan Anda akan diteruskan setelah wizard booking tersedia.');
                return;
            }
            var params = new URLSearchParams({
                date: $('inputDate').value,
                session_time: shiftValue,
                participants: participants,
                technician: $('addTech').checked ? 1 : 0,
                reagen: $('addReagen').checked ? 1 : 0,
                limbah: $('addLimbah').checked ? 1 : 0
            });
            window.location.href = BOOKING_URL + '?' + params.toString();
        });

        // Wishlist & share
        $('btnWishlist').addEventListener('click', function () {
            var btn = this, icon = btn.querySelector('i');
            btn.classList.toggle('saved');
            icon.className = btn.classList.contains('saved') ? 'fa-solid fa-heart' : 'fa-regular fa-heart';
        });
        $('btnShare').addEventListener('click', function () {
            var url = window.location.href;
            if (navigator.share) {
                navigator.share({ title: document.title, url: url }).catch(function () {});
            } else if (navigator.clipboard) {
                navigator.clipboard.writeText(url).then(function () { alert('Tautan lab berhasil disalin.'); });
            }
        });

        // Tab aktif mengikuti scroll
        var tabs = document.querySelectorAll('.tab');
        var sections = Array.prototype.map.call(tabs, function (t) { return document.querySelector(t.getAttribute('href')); });
        window.addEventListener('scroll', function () {
            var pos = window.scrollY + 140, current = 0;
            sections.forEach(function (s, i) { if (s && s.offsetTop <= pos) current = i; });
            tabs.forEach(function (t, i) { t.classList.toggle('active', i === current); });
        });

        recalc();
    })();
</script>

</body>
</html>
