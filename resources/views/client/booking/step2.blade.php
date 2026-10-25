@php
    // Ambil data lab: dipakai dari controller jika ada ($lab), atau dicari sendiri dari parameter rute.
    $lab = $lab ?? \App\Models\Laboratory::with('equipments')->where('lab_id', request()->route('lab_id') ?? request('lab_id'))->first();
    abort_if(!$lab, 404, 'Laboratorium tidak ditemukan');

    $equipments = $lab->equipments
        ->where('status', 'available')
        ->map(function ($e) {
            return [
                'id' => $e->equipment_id,
                'name' => $e->equipment_name,
                'desc' => $e->brand_model ?: 'Instrumen laboratorium standar',
                'price' => (float) $e->rental_price_per_unit,
                'stock' => (int) $e->quantity_total,
            ];
        })->values()->all();

    $step1Url = '';
    try { $step1Url = route('bookings.step1', ['lab_id' => $lab->lab_id]); } catch (\Throwable $e) { $step1Url = ''; }

    $step3Url = '';
    foreach (['bookings.step3', 'bookings.checkout'] as $routeName) {
        try {
            if (\Illuminate\Support\Facades\Route::has($routeName)) {
                $step3Url = route($routeName, ['lab_id' => $lab->lab_id]);
                break;
            }
        } catch (\Throwable $e) { $step3Url = ''; }
    }

    $payload = [
        'lab' => [
            'id' => $lab->lab_id,
            'name' => $lab->lab_name,
            'campus' => $lab->campus_name,
            'capacity' => (int) $lab->capacity,
        ],
        'equipments' => $equipments,
        'step1_url' => $step1Url,
        'step3_url' => $step3Url,
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1280">
    <title>Formulir Reservasi (Tahap 2) - {{ $lab->lab_name }} - PALAPA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #fff; font-family: 'Poppins', sans-serif; color: #0f172a; min-width: 1240px; }
        a { text-decoration: none; }
        .container { max-width: 1240px; margin: 0 auto; padding: 0 20px; }

        /* NAVBAR */
        .navbar-wrapper { padding: 20px 0; }
        .navbar { display: flex; align-items: center; justify-content: space-between; background: #EBF3FF; padding: 8px 16px 8px 24px; border-radius: 9999px; }
        .nav-brand img { height: 42px; width: auto; display: block; }
        .nav-links { display: flex; align-items: center; gap: 8px; }
        .nav-link { white-space: nowrap; padding: 8px 18px; border-radius: 12px; color: #1e293b; font-size: 14px; font-weight: 500; }
        .nav-link.active { background: #334EAC; color: #fff; font-weight: 600; }
        .nav-search { position: relative; display: flex; align-items: center; }
        .nav-search input { background: #DCE8FB; padding: 8px 36px 8px 16px; border-radius: 20px; width: 220px; border: none; outline: none; font-family: inherit; font-size: 13px; color: #334155; }
        .nav-search i { position: absolute; right: 14px; color: #64748b; font-size: 13px; }
        .nav-actions { display: flex; align-items: center; gap: 10px; }
        .icon-btn { width: 36px; height: 36px; border-radius: 50%; background: #091F5B; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 14px; }

        /* BREADCRUMB BAR */
        .crumb-bar { display: flex; justify-content: space-between; align-items: center; padding: 14px 0 18px; border-bottom: 1px solid #E2E8F0; }
        .breadcrumb { display: flex; align-items: center; gap: 10px; font-size: 13px; color: #475569; }
        .breadcrumb a { color: #475569; }
        .breadcrumb a:hover { color: #334EAC; }
        .breadcrumb .current { color: #334EAC; font-weight: 600; }
        .crumb-badges { display: flex; gap: 10px; }
        .cb { white-space: nowrap; display: inline-flex; align-items: center; gap: 7px; font-size: 12px; font-weight: 600; padding: 6px 14px; border-radius: 999px; }
        .cb-green { background: #0b4a38; color: #fff; }
        .cb-green .dot { width: 7px; height: 7px; border-radius: 50%; background: #fff; }
        .cb-blue { background: #EBF3FF; color: #334EAC; }

        /* STEPPER */
        .page { padding: 64px 0 70px; }
        .stepper-card { background: #EEF5FF; border: 1px solid #E1ECFB; border-radius: 18px; padding: 34px 40px 28px; margin-bottom: 34px; box-shadow: 0 8px 28px rgba(51,78,172,.10); }
        .steps { position: relative; display: grid; grid-template-columns: repeat(3, 1fr); width: 77%; margin: 0 auto; }
        .steps::before { content: ""; position: absolute; left: 16.66%; right: 16.66%; top: 22px; height: 2px; background: #D5DEEE; }
        .steps::after { content: ""; position: absolute; left: 16.66%; width: 33.33%; top: 22px; height: 2px; background: #334EAC; }
        .step { position: relative; z-index: 2; text-align: center; }
        .step-circle { width: 46px; height: 46px; border-radius: 50%; margin: 0 auto 18px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 15px; background: #fff; border: 2px solid #E2E8F0; color: #CBD5E1; }
        .step.done .step-circle { background: #091F5B; border-color: #091F5B; color: #fff; }
        .step.active .step-circle { border-color: #334EAC; color: #334EAC; box-shadow: 0 0 0 4px #DCE8FB; }
        .step-no { font-size: 11.5px; font-weight: 700; letter-spacing: .4px; text-transform: uppercase; color: #94a3b8; margin-bottom: 4px; }
        .step-name { font-size: 13.5px; font-weight: 500; color: #94a3b8; }
        .step.done .step-no, .step.done .step-name { color: #0f172a; }
        .step.done .step-name { font-weight: 600; }
        .step.active .step-no { color: #334EAC; }
        .step.active .step-name { color: #0f172a; font-weight: 700; }

        /* LAYOUT */
        .layout { display: grid; grid-template-columns: minmax(0, 1fr) 400px; gap: 30px; align-items: start; }
        .card { background: #EEF5FF; border: 1px solid #E1ECFB; border-radius: 20px; padding: 30px 32px; margin-bottom: 28px; box-shadow: 0 6px 22px rgba(51,78,172,.08); }
        .card-title { font-size: 26px; font-weight: 800; color: #091F5B; margin-bottom: 8px; }
        .card-sub { font-size: 14px; color: #64748b; line-height: 1.6; margin-bottom: 22px; }
        .lab-box { display: flex; align-items: center; gap: 16px; background: #fff; border: 2px solid #A9C0EA; border-radius: 12px; padding: 16px 20px; }
        .lab-box-icon { width: 40px; height: 40px; background: #334EAC; color: #fff; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
        .lab-box strong { display: block; font-size: 15px; font-weight: 700; color: #091F5B; margin-bottom: 3px; }
        .lab-box span { font-size: 13px; color: #475569; }
        .lab-box span b { color: #334EAC; font-weight: 700; }
        .lab-box .sep { color: #cbd5e1; margin: 0 8px; }

        .sec-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 26px; }
        .sec-title { display: flex; align-items: center; gap: 12px; font-size: 21px; font-weight: 800; color: #091F5B; }
        .sec-num { width: 28px; height: 28px; border-radius: 7px; background: #334EAC; color: #fff; font-size: 13px; font-weight: 700; display: flex; align-items: center; justify-content: center; }
        .sec-note { font-size: 13px; color: #64748b; }
        .sec-tag { background: #DCE8FB; color: #334EAC; font-size: 12px; font-weight: 700; padding: 6px 14px; border-radius: 6px; }

        /* ITEM (alat, reagen) */
        .item { display: flex; align-items: center; gap: 16px; background: #fff; border: 1px solid #D6E4FA; border-radius: 12px; padding: 18px 22px; margin-bottom: 14px; }
        .item.on { border: 2px solid #334EAC; }
        .item.off .item-body { opacity: .55; }
        .item-body { flex: 1; min-width: 0; }
        .item-name { font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 3px; }
        .item-desc { font-size: 12.5px; color: #475569; line-height: 1.5; }
        .item-price { font-size: 13px; font-weight: 700; color: #334EAC; margin-top: 6px; }
        .item-price span { font-weight: 400; color: #64748b; }
        .item-pill { border: 1px solid #334EAC; color: #091F5B; background: #fff; font-size: 12.5px; font-weight: 700; padding: 8px 14px; border-radius: 8px; white-space: nowrap; }
        .chk { position: relative; width: 22px; height: 22px; border-radius: 6px; border: 2px solid #CBD5E1; background: #fff; flex-shrink: 0; cursor: pointer; }
        .chk.checked { background: #334EAC; border-color: #334EAC; }
        .chk.checked::after { content: ""; position: absolute; left: 6px; top: 2px; width: 5px; height: 10px; border: solid #fff; border-width: 0 2px 2px 0; transform: rotate(45deg); }
        .chk.locked { opacity: .75; cursor: default; }
        .qty { display: flex; align-items: center; gap: 12px; }
        .qty button { width: 32px; height: 32px; border: 1.5px solid #334EAC; background: #fff; color: #334EAC; border-radius: 8px; font-size: 16px; font-weight: 700; cursor: pointer; line-height: 1; }
        .qty button:disabled { border-color: #E2E8F0; color: #cbd5e1; background: #F8FAFC; cursor: default; }
        .qty .val { min-width: 40px; height: 32px; display: flex; align-items: center; justify-content: center; border: 1px solid #E2E8F0; border-radius: 6px; font-size: 14px; font-weight: 700; color: #0f172a; background: #fff; }
        .empty-box { background: #fff; border: 1px dashed #A9C0EA; border-radius: 12px; padding: 26px; text-align: center; font-size: 13px; color: #64748b; }

        /* PLP RADIO */
        .radio-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .radio-card { position: relative; background: #fff; border: 1px solid #D6E4FA; border-radius: 12px; padding: 20px 18px 20px 50px; cursor: pointer; }
        .radio-card.on { border: 2px solid #334EAC; }
        .radio-card .rd { position: absolute; left: 20px; top: 22px; width: 18px; height: 18px; border-radius: 50%; border: 2px solid #CBD5E1; background: #fff; }
        .radio-card.on .rd { border: 5px solid #334EAC; }
        .radio-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .radio-name { font-size: 14.5px; font-weight: 700; color: #0f172a; }
        .radio-card:not(.on) .radio-name { font-weight: 500; color: #334155; }
        .badge-k3 { white-space: nowrap; background: #334EAC; color: #fff; font-size: 10.5px; font-weight: 700; padding: 4px 10px; border-radius: 5px; }
        .radio-price { font-size: 16px; font-weight: 700; color: #334EAC; margin-bottom: 8px; }
        .radio-price span { font-size: 13px; font-weight: 400; color: #64748b; }
        .radio-card:not(.on) .radio-price { color: #0f172a; }
        .radio-desc { font-size: 12.5px; color: #475569; line-height: 1.6; }

        /* REAGEN */
        .item.slim { align-items: flex-start; padding: 16px 22px; }
        .item.slim .chk { margin-top: 2px; }
        .item.slim .item-side { font-size: 13px; font-weight: 700; color: #334EAC; white-space: nowrap; }
        .item.slim.off .item-side { color: #475569; font-weight: 600; }

        /* CATATAN */
        .note-label { font-size: 13px; color: #475569; margin: -10px 0 14px; }
        .note-area { width: 100%; height: 110px; padding: 16px 18px; border: 2px solid #334EAC; border-radius: 12px; background: #fff; font-family: inherit; font-size: 14px; color: #0f172a; resize: none; outline: none; }
        .note-area:focus { box-shadow: 0 0 0 3px rgba(51,78,172,.12); }

        /* SIDEBAR */
        .side { position: sticky; top: 20px; }
        .side-card { background: #EEF5FF; border: 1px solid #E1ECFB; border-radius: 20px; padding: 26px 26px 22px; box-shadow: 0 6px 22px rgba(51,78,172,.08); margin-bottom: 18px; }
        .side-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 26px; }
        .side-head h3 { white-space: nowrap; font-size: 17px; font-weight: 800; color: #091F5B; margin-bottom: 2px; }
        .side-head p { font-size: 12px; color: #475569; }
        .side-head > div { min-width: 0; }
        .side-pill { background: #DCE8FB; border: 1px solid #A9C0EA; color: #334EAC; font-size: 11px; font-weight: 700; padding: 5px 10px; border-radius: 6px; white-space: nowrap; }
        .sum-row { display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 16px; }
        .sum-row .l { font-size: 13px; font-weight: 600; color: #0f172a; }
        .sum-row .l small { display: block; font-size: 11px; font-weight: 400; color: #94a3b8; margin-top: 1px; }
        .sum-row .r { font-size: 13px; font-weight: 700; color: #0f172a; white-space: nowrap; }
        .disc-box { display: flex; justify-content: space-between; align-items: flex-start; background: #0b4a38; color: #fff; border-radius: 8px; padding: 10px 12px; margin: 6px 0 10px; }
        .disc-box strong { display: block; font-size: 13px; }
        .disc-box small { font-size: 10.5px; opacity: .85; }
        .disc-box .r { font-size: 13px; font-weight: 700; }
        .dep-box { display: flex; justify-content: space-between; align-items: flex-start; background: #fff; border: 1px solid #334EAC; border-radius: 8px; padding: 10px 12px; margin-bottom: 26px; }
        .dep-box strong { display: block; font-size: 13px; color: #091F5B; }
        .dep-box strong i { font-size: 10px; color: #334EAC; margin-left: 4px; }
        .dep-box small { font-size: 10.5px; color: #64748b; }
        .dep-box .r { font-size: 13px; font-weight: 700; color: #091F5B; }
        .total-row { display: flex; justify-content: space-between; align-items: center; }
        .total-row .l { font-size: 14px; font-weight: 700; color: #0f172a; }
        .total-row .r { font-size: 24px; font-weight: 800; color: #091F5B; }
        .total-note { text-align: right; font-size: 11px; color: #94a3b8; margin: 2px 0 18px; }
        .faktur { display: flex; gap: 10px; background: #EEF5FF; border: 2px solid #A9C0EA; border-radius: 12px; padding: 14px 16px; font-size: 11.5px; color: #1e293b; line-height: 1.55; margin-bottom: 22px; }
        .faktur i { color: #334EAC; margin-top: 3px; }
        .btn-next { width: 100%; background: #334EAC; color: #fff; border: none; border-radius: 12px; padding: 16px; font-family: inherit; font-size: 15px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 12px; cursor: pointer; margin-bottom: 12px; }
        .btn-next:hover { background: #283f8c; }
        .btn-prev { width: 100%; background: #fff; border: 1px solid #D6E4FA; color: #334155; border-radius: 12px; padding: 12px; font-size: 13.5px; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 10px; }
        .btn-prev:hover { color: #334EAC; border-color: #334EAC; }
        .help-card { display: flex; align-items: center; gap: 14px; background: #EEF5FF; border: 1px solid #E1ECFB; border-radius: 16px; padding: 14px 18px; box-shadow: 0 6px 22px rgba(51,78,172,.08); }
        .help-icon { width: 40px; height: 40px; border-radius: 50%; background: #0b4a38; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; }
        .help-card strong { display: block; font-size: 13px; color: #0f172a; }
        .help-card span { font-size: 12.5px; color: #475569; }
        .help-card span b { color: #334EAC; }

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
    </style>
</head>
<body>

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
                <a href="{{ url('/login') }}" class="icon-btn" title="Akun"><i class="fa-solid fa-user"></i></a>
            </div>
        </nav>
    </div>

    <!-- BREADCRUMB -->
    <div class="crumb-bar">
        <nav class="breadcrumb">
            <a href="{{ route('home') }}">Beranda</a><span>/</span>
            <a href="{{ route('labs.index') }}">Katalog Lab</a><span>/</span>
            <a href="{{ route('labs.show', $lab->lab_id) }}">{{ $lab->lab_name }}</a><span>/</span>
            <span class="current">Formulir Reservasi (Tahap 2)</span>
        </nav>
        <div class="crumb-badges">
            <span class="cb cb-green"><span class="dot"></span> Terverifikasi ISO/IEC 17025</span>
            <span class="cb cb-blue"><i class="fa-solid fa-circle-check"></i> Integrasi Kemendikbudristek</span>
        </div>
    </div>

    <div class="page">

        <!-- STEPPER -->
        <div class="stepper-card">
            <div class="steps">
                <div class="step done">
                    <div class="step-circle"><i class="fa-solid fa-check"></i></div>
                    <div class="step-no">Tahap 1</div>
                    <div class="step-name">Instansi &amp; Jadwal</div>
                </div>
                <div class="step active">
                    <div class="step-circle">2</div>
                    <div class="step-no">Tahap 2</div>
                    <div class="step-name">Alat &amp; Add-on</div>
                </div>
                <div class="step">
                    <div class="step-circle">3</div>
                    <div class="step-no">Tahap 3</div>
                    <div class="step-name">Konfirmasi &amp; Bayar</div>
                </div>
            </div>
        </div>

        <div class="layout">

            <!-- KOLOM KIRI -->
            <div>
                <div class="card">
                    <h1 class="card-title">Pilih Instrumen &amp; Layanan Tambahan</h1>
                    <p class="card-sub">Sesuaikan jumlah unit alat laboratorium, bahan habis pakai, serta pendampingan pranata laboratorium (PLP) bersertifikat.</p>
                    <div class="lab-box">
                        <div class="lab-box-icon"><i class="fa-regular fa-building"></i></div>
                        <div>
                            <strong>{{ $lab->lab_name }} – {{ $lab->campus_name }}</strong>
                            <span>Jadwal Terjadwal: <b id="hdrDate">-</b><i class="sep">•</i>Shift: <b id="hdrShift">-</b></span>
                        </div>
                    </div>
                </div>

                <!-- 1. INSTRUMEN -->
                <div class="card">
                    <div class="sec-head">
                        <div class="sec-title"><span class="sec-num">1</span> Instrumen &amp; Unit Laboratorium</div>
                        <span class="sec-note" id="capNote">Berdasarkan kapasitas -</span>
                    </div>
                    <div id="equipList"></div>
                </div>

                <!-- 2. PLP -->
                <div class="card">
                    <div class="sec-head">
                        <div class="sec-title"><span class="sec-num">2</span> Jasa Pendampingan Pranata Lab (PLP)</div>
                        <span class="sec-tag">Standar K3 Kemendikbudristek</span>
                    </div>
                    <div class="radio-grid" id="plpGrid"></div>
                </div>

                <!-- 3. REAGEN -->
                <div class="card">
                    <div class="sec-head">
                        <div class="sec-title"><span class="sec-num">3</span> Bahan Reagen &amp; Konsumabel Habis Pakai</div>
                        <span class="sec-note">Opsional</span>
                    </div>
                    <div id="consumList"></div>
                </div>

                <!-- 4. CATATAN -->
                <div class="card" style="margin-bottom:0;">
                    <div class="sec-head">
                        <div class="sec-title"><span class="sec-num">4</span> Catatan Spesifikasi Kebutuhan Riset</div>
                    </div>
                    <p class="note-label">Tuliskan instruksi teknis, suhu awal peralatan, preparasi larutan, atau protokol keamanan khusus:</p>
                    <textarea class="note-area" id="notes" maxlength="500" placeholder="Contoh: Mohon siapkan set immersion oil untuk mikroskop dan pre-heat autoklaf pada suhu 121°C sebelum sesi dimulai."></textarea>
                </div>
            </div>

            <!-- KOLOM KANAN -->
            <aside class="side">
                <div class="side-card">
                    <div class="side-head">
                        <div>
                            <h3>Ringkasan Pemesanan</h3>
                            <p id="sideWho">-</p>
                        </div>
                        <span class="side-pill" id="sidePill">-</span>
                    </div>

                    <div id="sumLines"></div>

                    <div class="disc-box">
                        <div><strong>Diskon Sivitas Akademika (-5%)</strong><small id="discNote">Kemitraan Institusi</small></div>
                        <span class="r" id="discVal">-Rp 0</span>
                    </div>
                    <div class="dep-box">
                        <div><strong>Deposit Jaminan Alat <i class="fa-solid fa-circle-info"></i></strong><small>Refund 100% pasca verifikasi fisik lab</small></div>
                        <span class="r" id="depVal">Rp 200.000</span>
                    </div>

                    <div class="total-row"><span class="l">Total Biaya Estimasi</span><span class="r" id="totalVal">Rp 0</span></div>
                    <div class="total-note">Sudah termasuk PPN 11% &amp; Asuransi Alat</div>

                    <div class="faktur">
                        <i class="fa-regular fa-circle-check"></i>
                        <div>Faktur resmi dan billing <b>Simponi/PNBP Kemendikbudristek</b> diterbitkan otomatis untuk SPJ BOS/BIMA.</div>
                    </div>

                    <button type="button" class="btn-next" id="btnNext">Lanjut ke Konfirmasi &amp; Bayar <i class="fa-solid fa-arrow-right"></i></button>
                    <a href="{{ route('bookings.step1', ['lab_id' => $lab->lab_id]) }}" class="btn-prev" id="btnPrev"><i class="fa-solid fa-arrow-left"></i> Kembali ke Tahap 1 (Instansi &amp; Jadwal)</a>
                </div>

                <div class="help-card">
                    <div class="help-icon"><i class="fa-solid fa-headset"></i></div>
                    <div>
                        <strong>Kebutuhan Alat Non-Katalog?</strong>
                        <span>Hubungi Helpdesk Lab {{ $lab->campus_name }}: <b>(031) 599-2785</b></span>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

<!-- FOOTER -->
<div class="container">
    <div class="partners-row">
        <div class="partners-label">MITRA &amp; TERAKREDITASI RESMI:</div>
        <div class="partners-list"><span>KAN ISO 17025</span><span>BRIN Partner</span><span>Science Hunter ID</span><span>Universitas Indonesia</span><span>ITB Labs</span></div>
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
    window.PALAPA = @json($payload);
</script>
<script>
(function () {
    var P = window.PALAPA;
    var $ = function (id) { return document.getElementById(id); };
    var rupiah = function (n) { return 'Rp ' + Math.round(n).toLocaleString('id-ID'); };
    var esc = function (t) { return String(t).replace(/[&<>"]/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]; }); };

    /* ---------- Data dari Tahap 1 ---------- */
    var D = null;
    try { D = JSON.parse(sessionStorage.getItem('palapa_booking') || 'null'); } catch (e) {}
    if (!D || D.lab_id !== P.lab.id || !D.reservation_date || !D.session_time) {
        if (P.step1_url) { window.location.replace(P.step1_url); }
        return;
    }

    var SHIFT_INFO = {
        'Pagi':  { title: 'Sesi 1: Pagi',  short: 'Sesi 1', clock: '08.00 - 12.00', hours: 4, mult: 1, rent: 'Sesi Pagi' },
        'Siang': { title: 'Sesi 2: Siang', short: 'Sesi 2', clock: '13.00 - 17.00', hours: 4, mult: 1, rent: 'Sesi Siang' },
        'Penuh': { title: 'Sesi Penuh',    short: 'Sesi Penuh', clock: '08.00 - 17.00', hours: 8, mult: 2, rent: 'Sesi Penuh' }
    };
    var shiftKey = D.session_time.split(' ')[0];
    var SH = SHIFT_INFO[shiftKey] || SHIFT_INFO.Pagi;

    var MONTHS = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    var dp = D.reservation_date.split('-');
    var dateLabel = parseInt(dp[2], 10) + ' ' + MONTHS[parseInt(dp[1], 10) - 1] + ' ' + dp[0];

    var PARTICIPANTS = parseInt(D.participants, 10) || 1;
    var UNIT = D.institution_type === 'sekolah_sma_smk' ? 'Siswa' : 'Peserta';
    var TABLES = Math.max(1, Math.ceil(PARTICIPANTS / 2));
    var DISC_NOTE = { sekolah_sma_smk: 'Kemitraan Sekolah SMA/SMK', lembaga_riset: 'Kemitraan Lembaga Riset', perusahaan_swasta: 'Kemitraan Industri', komunitas_publik: 'Kemitraan Komunitas' };

    var TECH_PRICE = 150000 * SH.mult;
    var REAGEN_PRICE = 100000;
    var LIMBAH_PRICE = 50000 * SH.mult;
    var DEPOSIT = 200000;
    var DISC_RATE = 0.05;

    var included = P.equipments.filter(function (e) { return e.price <= 0; });
    var paid = P.equipments.filter(function (e) { return e.price > 0; });

    /* ---------- State (pulihkan jika kembali dari Tahap 3) ---------- */
    var qty = {};
    paid.forEach(function (e) { qty[e.id] = 0; });
    (D.equipments || []).forEach(function (x) { if (qty.hasOwnProperty(x.equipment_id)) qty[x.equipment_id] = x.quantity; });
    var plp = D.technician === false ? false : true;
    var reagen = D.reagen === true;
    var limbah = D.limbah === true;
    $('notes').value = D.notes || '';

    /* ---------- Header ---------- */
    $('hdrDate').textContent = dateLabel;
    $('hdrShift').textContent = SH.title + ' (' + SH.clock + ' WIB)';
    $('capNote').textContent = 'Berdasarkan kapasitas ' + PARTICIPANTS + ' ' + UNIT.toLowerCase();
    $('sideWho').textContent = (D.institution_name || '-') + ' (' + PARTICIPANTS + ' ' + UNIT + ')';
    $('discNote').textContent = DISC_NOTE[D.institution_type] || 'Kemitraan Institusi';

    /* ---------- Render ---------- */
    function renderEquip() {
        var html = '';
        included.forEach(function (e) {
            html += '<div class="item on"><span class="chk checked locked"></span>' +
                '<div class="item-body"><div class="item-name">' + esc(e.name) + '</div><div class="item-desc">' + esc(e.desc) + '</div></div>' +
                '<span class="item-pill">' + TABLES + ' Meja Praktikum (' + PARTICIPANTS + ' ' + UNIT + ')</span></div>';
        });
        paid.forEach(function (e) {
            var q = qty[e.id], on = q > 0;
            html += '<div class="item ' + (on ? 'on' : 'off') + '">' +
                '<span class="chk ' + (on ? 'checked' : '') + '" data-act="toggle" data-id="' + esc(e.id) + '"></span>' +
                '<div class="item-body"><div class="item-name">' + esc(e.name) + '</div><div class="item-desc">' + esc(e.desc) + '</div>' +
                '<div class="item-price">+' + rupiah(e.price) + ' <span>/ unit sesi</span></div></div>' +
                '<div class="qty"><button type="button" data-act="minus" data-id="' + esc(e.id) + '"' + (q <= 0 ? ' disabled' : '') + '>−</button>' +
                '<span class="val">' + q + '</span>' +
                '<button type="button" data-act="plus" data-id="' + esc(e.id) + '"' + (q >= e.stock ? ' disabled' : '') + '>+</button></div></div>';
        });
        if (!included.length && !paid.length) {
            html = '<div class="empty-box">Belum ada instrumen tambahan yang didaftarkan pada lab ini. Fasilitas ruangan standar sudah termasuk dalam tarif sewa.</div>';
        }
        $('equipList').innerHTML = html;
    }
    $('equipList').addEventListener('click', function (e) {
        var t = e.target.closest('[data-act]');
        if (!t || t.disabled) return;
        var id = t.getAttribute('data-id'), act = t.getAttribute('data-act');
        var item = paid.filter(function (x) { return x.id === id; })[0];
        if (!item) return;
        if (act === 'toggle') qty[id] = qty[id] > 0 ? 0 : 1;
        if (act === 'plus' && qty[id] < item.stock) qty[id]++;
        if (act === 'minus' && qty[id] > 0) qty[id]--;
        refresh();
    });

    function renderPlp() {
        $('plpGrid').innerHTML =
            '<div class="radio-card ' + (plp ? 'on' : '') + '" data-plp="1"><span class="rd"></span>' +
                '<div class="radio-top"><span class="radio-name">Butuh 1 Laboran Utama</span><span class="badge-k3">Rekomendasi K3</span></div>' +
                '<div class="radio-price">+' + rupiah(TECH_PRICE) + ' <span>/ sesi praktikum</span></div>' +
                '<div class="radio-desc">Pendampingan teknis langsung oleh Pranata Laboratorium Pendidikan (PLP) tersertifikasi BNSP selama ' + SH.hours + ' jam praktikum.</div></div>' +
            '<div class="radio-card ' + (!plp ? 'on' : '') + '" data-plp="0"><span class="rd"></span>' +
                '<div class="radio-top"><span class="radio-name">Tanpa Laboran Pendamping</span></div>' +
                '<div class="radio-price">Rp 0 <span>(Supervisi Mandiri)</span></div>' +
                '<div class="radio-desc">Hanya diperkenankan jika tim peneliti/guru melampirkan sertifikat kompetensi K3 lab terakreditasi saat check-in.</div></div>';
    }
    $('plpGrid').addEventListener('click', function (e) {
        var c = e.target.closest('[data-plp]');
        if (!c) return;
        plp = c.getAttribute('data-plp') === '1';
        refresh();
    });

    function renderConsum() {
        $('consumList').innerHTML =
            '<div class="item slim ' + (reagen ? 'on' : 'off') + '" data-con="reagen"><span class="chk ' + (reagen ? 'checked' : '') + '"></span>' +
                '<div class="item-body"><div class="item-name">Bahan Reagen Dasar &amp; Aquabidest Steril</div><div class="item-desc">Termasuk Buffer PBS, Alkohol 70%, pewarnaan gram standar &amp; Aquabidest 5 Liter.</div></div>' +
                '<span class="item-side">+' + rupiah(REAGEN_PRICE) + '</span></div>' +
            '<div class="item slim ' + (limbah ? 'on' : 'off') + '" data-con="limbah"><span class="chk ' + (limbah ? 'checked' : '') + '"></span>' +
                '<div class="item-body"><div class="item-name">Kit Preparasi &amp; Pembuangan Limbah B3 Khusus</div><div class="item-desc">Wadah yellow biohazard bag, safe disposal jar jarum suntik, dan netralisator kimia.</div></div>' +
                '<span class="item-side">+' + rupiah(LIMBAH_PRICE) + '</span></div>';
    }
    $('consumList').addEventListener('click', function (e) {
        var c = e.target.closest('[data-con]');
        if (!c) return;
        if (c.getAttribute('data-con') === 'reagen') reagen = !reagen; else limbah = !limbah;
        refresh();
    });

    /* ---------- Hitung ---------- */
    function compute() {
        var lines = [];
        var rent = parseFloat(D.session_price) || 0;
        var incText = included.length
            ? 'Termasuk ' + TABLES + ' ' + included.map(function (e) { return e.name; }).join(', ')
            : 'Fasilitas ruangan standar';
        lines.push({ l: 'Sewa Lab ' + SH.rent, s: incText, r: rent });
        var itemsTotal = 0, chosen = [];
        paid.forEach(function (e) {
            var q = qty[e.id];
            if (q > 0) {
                var sub = e.price * q; itemsTotal += sub;
                chosen.push({ equipment_id: e.id, name: e.name, quantity: q, unit_price: e.price, subtotal: sub });
                lines.push({ l: e.name + ' (' + q + ' Unit)', s: '@ ' + rupiah(e.price) + ' / unit', r: sub });
            }
        });
        if (plp) lines.push({ l: '1 Pranata Lab Terlisensi BNSP', s: 'Pendampingan ' + SH.hours + ' Jam Penuh', r: TECH_PRICE });
        if (reagen) lines.push({ l: 'Bahan Reagen Dasar & Aquabidest', s: 'Batch Rombongan ' + PARTICIPANTS + ' ' + UNIT, r: REAGEN_PRICE });
        if (limbah) lines.push({ l: 'Kit Limbah B3 Khusus', s: 'Per sesi praktikum', r: LIMBAH_PRICE });

        var subtotal = lines.reduce(function (a, x) { return a + x.r; }, 0);
        var disc = subtotal * DISC_RATE;
        var total = subtotal - disc + DEPOSIT;
        return { lines: lines, chosen: chosen, subtotal: subtotal, disc: disc, total: total, rent: rent };
    }

    function refresh() {
        renderEquip(); renderPlp(); renderConsum();
        var c = compute();
        $('sumLines').innerHTML = c.lines.map(function (x) {
            return '<div class="sum-row"><div class="l">' + esc(x.l) + '<small>' + esc(x.s) + '</small></div><div class="r">' + rupiah(x.r) + '</div></div>';
        }).join('');
        $('discVal').textContent = '-' + rupiah(c.disc);
        $('depVal').textContent = rupiah(DEPOSIT);
        $('totalVal').textContent = rupiah(c.total);
        $('sidePill').textContent = SH.short + ': ' + SH.clock;
    }

    /* ---------- Lanjut ---------- */
    $('btnNext').addEventListener('click', function () {
        var c = compute();
        D.equipments = c.chosen;
        D.technician = plp;
        D.technician_fee = plp ? TECH_PRICE : 0;
        D.reagen = reagen;
        D.limbah = limbah;
        D.notes = $('notes').value.trim();
        D.pricing = { rent: c.rent, subtotal: c.subtotal, discount: c.disc, deposit: DEPOSIT, total: c.total };
        try { sessionStorage.setItem('palapa_booking', JSON.stringify(D)); } catch (e) {}

        if (P.step3_url) { window.location.href = P.step3_url; }
        else { alert('Data Tahap 2 tersimpan. Halaman Tahap 3 (Konfirmasi & Bayar) sedang disiapkan.'); }
    });

    refresh();
})();
</script>

</body>
</html>
