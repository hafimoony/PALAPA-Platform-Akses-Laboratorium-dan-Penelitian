@php
    // Ambil data lab: dipakai dari controller jika ada ($lab), atau dicari sendiri dari parameter rute.
    $lab = $lab ?? \App\Models\Laboratory::where('lab_id', request()->route('lab_id') ?? request('lab_id'))->first();
    abort_if(!$lab, 404, 'Laboratorium tidak ditemukan');

    $user = auth()->user();
    $profile = null;
    try { $profile = $user ? $user->externalProfile : null; } catch (\Throwable $e) { $profile = null; }

    // Jadwal yang sudah terisi (untuk menandai tanggal / sesi penuh di kalender)
    $bookedMap = [];
    try {
        \App\Models\Booking::where('lab_id', $lab->lab_id)
            ->whereDate('reservation_date', '>=', now()->toDateString())
            ->whereIn('status', ['approved', 'paid_confirmed', 'locked_unpaid'])
            ->get(['reservation_date', 'session_time'])
            ->each(function ($b) use (&$bookedMap) {
                $d = \Carbon\Carbon::parse($b->reservation_date)->toDateString();
                $bookedMap[$d][] = $b->session_time;
            });
    } catch (\Throwable $e) { $bookedMap = []; }

    $step2Url = '';
    try {
        if (\Illuminate\Support\Facades\Route::has('bookings.step2')) {
            $step2Url = route('bookings.step2', ['lab_id' => $lab->lab_id]);
        }
    } catch (\Throwable $e) { $step2Url = ''; }

    $payload = [
        'lab' => [
            'id' => $lab->lab_id,
            'name' => $lab->lab_name,
            'capacity' => (int) $lab->capacity,
            'base_price' => (float) $lab->base_price_per_session,
        ],
        'booked' => (object) $bookedMap,
        'step2_url' => $step2Url,
        'prefill' => [
            'institution_name' => $profile->institution_name ?? '',
            'institution_type' => $profile->institution_type ?? 'sekolah_sma_smk',
            'pic_name' => $user->name ?? '',
            'whatsapp' => $user->phone_number ?? '',
            'email' => $user->email ?? '',
            'participants' => (int) request('participants', 0),
            'date' => request('date', ''),
            'session_time' => request('session_time', ''),
        ],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1280">
    <title>Formulir Reservasi (Tahap 1) - {{ $lab->lab_name }} - PALAPA</title>
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
        .nav-link { white-space: nowrap; padding: 8px 16px; border-radius: 12px; color: #1e293b; font-size: 14px; font-weight: 500; }
        .nav-link.active { background: #334EAC; color: #fff; font-weight: 600; }
        .nav-search { position: relative; display: flex; align-items: center; }
        .nav-search input { background: #DCE8FB; padding: 8px 36px 8px 16px; border-radius: 20px; width: 220px; border: none; outline: none; font-family: inherit; font-size: 13px; color: #334155; }
        .nav-search i { position: absolute; right: 14px; color: #64748b; font-size: 13px; }
        .nav-actions { display: flex; align-items: center; gap: 8px; }
        .icon-btn { width: 34px; height: 34px; border-radius: 50%; background: #091F5B; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 13px; }

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
        .step { position: relative; z-index: 2; text-align: center; }
        .step-circle { width: 46px; height: 46px; border-radius: 50%; margin: 0 auto 18px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 15px; background: #fff; border: 2px solid #E2E8F0; color: #CBD5E1; }
        .step.active .step-circle { border-color: #334EAC; color: #334EAC; box-shadow: 0 0 0 4px #DCE8FB; }
        .step-no { font-size: 11.5px; font-weight: 700; letter-spacing: .4px; text-transform: uppercase; color: #94a3b8; margin-bottom: 4px; }
        .step-name { font-size: 13.5px; font-weight: 500; color: #94a3b8; }
        .step.active .step-no { color: #0f172a; }
        .step.active .step-name { color: #0f172a; font-weight: 600; }

        /* MAIN CARD */
        .main-card { background: #EEF5FF; border: 1px solid #CFE0FA; border-radius: 22px; padding: 40px 46px 40px; }
        .card-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 24px; padding-bottom: 26px; border-bottom: 1px solid #A9C0EA; margin-bottom: 30px; }
        .card-title { font-size: 30px; font-weight: 800; color: #091F5B; line-height: 1.25; margin-bottom: 8px; }
        .card-sub { font-size: 14px; color: #64748b; line-height: 1.55; max-width: 560px; }
        .lab-chip { display: flex; align-items: center; gap: 14px; background: #fff; border: 1px solid #A9C0EA; border-radius: 12px; padding: 14px 20px; flex-shrink: 0; margin-top: 6px; }
        .lab-chip-icon { width: 36px; height: 36px; background: #EBF3FF; color: #334EAC; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 14px; }
        .lab-chip small { display: block; font-size: 10px; font-weight: 700; color: #7c93c9; letter-spacing: .8px; margin-bottom: 2px; }
        .lab-chip strong { font-size: 13.5px; font-weight: 700; color: #091F5B; }

        .sec-title { display: flex; align-items: center; gap: 12px; font-size: 18px; font-weight: 700; color: #091F5B; margin-bottom: 18px; }
        .sec-badge { width: 24px; height: 24px; border-radius: 6px; background: #DCE8FB; color: #334EAC; font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center; }
        .sec-desc { font-size: 13px; color: #64748b; margin: -8px 0 22px 0; }

        /* FORM */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; column-gap: 28px; row-gap: 22px; margin-bottom: 36px; }
        .field label.lbl { display: block; font-size: 12px; font-weight: 700; color: #091F5B; letter-spacing: .5px; text-transform: uppercase; margin-bottom: 8px; }
        .req { color: #dc2626; }
        .input-wrap { position: relative; }
        .input, .select { width: 100%; height: 50px; padding: 0 16px; border: 2px solid #A9C0EA; border-radius: 10px; background: #fff; font-family: inherit; font-size: 15px; color: #0f172a; outline: none; }
        .input:focus, .select:focus { border-color: #334EAC; box-shadow: 0 0 0 3px rgba(51,78,172,.12); }
        .input.filled { border-color: #334EAC; }
        .select { appearance: none; -webkit-appearance: none; cursor: pointer; padding-right: 42px; }
        .select-arrow { position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #334EAC; font-size: 12px; pointer-events: none; }
        .ok-icon { position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #16a34a; font-size: 14px; display: none; }
        .ok-icon.show { display: block; }
        .two-cols { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .suffix { position: absolute; right: 16px; top: 8px; font-size: 12px; color: #94a3b8; }
        .participants-row { display: flex; align-items: center; gap: 14px; }
        .participants-row .input-wrap { width: 230px; }
        .cap-note { background: #F4F8FF; border: 1px solid #D6E4FA; border-radius: 8px; padding: 12px 16px; font-size: 13px; color: #64748b; white-space: nowrap; }
        .cap-note strong { color: #091F5B; }
        .err .input, .err .select, .err .upload-box { border-color: #dc2626 !important; }
        .err-msg { display: none; font-size: 11.5px; color: #dc2626; margin-top: 5px; }
        .err .err-msg { display: block; }

        .upload-box { display: flex; align-items: center; gap: 14px; height: 78px; padding: 0 16px; background: #fff; border: 2px dashed #5b79d4; border-radius: 12px; cursor: pointer; }
        .upload-icon { width: 38px; height: 38px; border-radius: 9px; background: #FDECEC; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; }
        .upload-info { flex: 1; min-width: 0; }
        .upload-info strong { display: block; font-size: 13.5px; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .upload-info span { font-size: 11.5px; color: #94a3b8; }
        .upload-info span em { font-style: normal; color: #0f8a5f; font-weight: 600; }
        .btn-small { padding: 8px 16px; background: #EBF3FF; border: 1px solid #CFE0FA; color: #334EAC; border-radius: 8px; font-family: inherit; font-size: 12.5px; font-weight: 600; cursor: pointer; }

        .section-divider { border: none; border-top: 1px solid #D6E4FA; margin: 0 0 34px; }

        /* JADWAL */
        .schedule { display: grid; grid-template-columns: minmax(0, .68fr) minmax(0, 1fr); gap: 36px; align-items: start; margin-bottom: 30px; }
        .cal { background: #fff; border: 1px solid #D6E4FA; border-radius: 14px; padding: 22px 22px 16px; }
        .cal-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; }
        .cal-head strong { font-size: 16px; font-weight: 700; color: #091F5B; }
        .cal-nav { display: flex; gap: 6px; }
        .cal-nav button { width: 30px; height: 30px; border: none; background: transparent; color: #64748b; font-size: 14px; cursor: pointer; border-radius: 6px; }
        .cal-nav button:hover:not(:disabled) { background: #EBF3FF; color: #334EAC; }
        .cal-nav button:disabled { opacity: .3; cursor: default; }
        .cal-week, .cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); text-align: center; }
        .cal-week span { font-size: 12px; font-weight: 600; color: #94a3b8; padding-bottom: 10px; }
        .cal-cell { height: 46px; display: flex; align-items: center; justify-content: center; }
        .day { position: relative; width: 38px; height: 38px; border: none; background: transparent; border-radius: 10px; font-family: inherit; font-size: 14px; font-weight: 600; color: #0f172a; cursor: pointer; }
        .day.avail::after { content: ""; position: absolute; left: 50%; bottom: 3px; width: 4px; height: 4px; margin-left: -2px; border-radius: 50%; background: #16a34a; }
        .day.avail:hover { background: #EBF3FF; }
        .day.muted { color: #cbd5e1; font-weight: 400; cursor: default; }
        .day.off { color: #b91c1c; cursor: default; }
        .day.sel { background: #334EAC; color: #fff; }
        .day.sel::after { background: #fff; }
        .cal-legend { display: flex; justify-content: space-between; border-top: 1px solid #E2E8F0; margin-top: 8px; padding-top: 14px; font-size: 12px; color: #475569; }
        .cal-legend span { display: flex; align-items: center; gap: 8px; }
        .cal-legend i { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }

        .shift-title { font-size: 12px; font-weight: 700; color: #091F5B; letter-spacing: .5px; text-transform: uppercase; margin-bottom: 12px; }
        .shift-card { position: relative; display: flex; justify-content: space-between; align-items: center; background: #fff; border: 2px solid #A9C0EA; border-radius: 12px; padding: 16px 22px 16px 54px; margin-bottom: 12px; cursor: pointer; }
        .shift-card.selected { border-color: #334EAC; box-shadow: 0 0 0 1px #334EAC inset; }
        .shift-card.disabled { opacity: .5; cursor: not-allowed; background: #F8FAFC; }
        .radio { position: absolute; left: 20px; top: 50%; margin-top: -9px; width: 18px; height: 18px; border-radius: 50%; border: 2px solid #CBD5E1; background: #fff; }
        .shift-card.selected .radio { border: 5px solid #334EAC; }
        .shift-name { display: flex; align-items: center; gap: 10px; font-size: 16px; font-weight: 700; color: #091F5B; margin-bottom: 4px; }
        .shift-time { font-size: 13px; color: #475569; }
        .pill { font-size: 10.5px; font-weight: 600; padding: 3px 10px; border-radius: 999px; }
        .pill-green { background: #0b4a38; color: #fff; }
        .pill-blue { background: #EBF3FF; color: #334EAC; border: 1px solid #CFE0FA; }
        .pill-red { background: #FDECEC; color: #b91c1c; }
        .shift-price { text-align: right; }
        .shift-price strong { display: block; font-size: 16px; font-weight: 700; color: #334EAC; }
        .shift-price small { font-size: 11.5px; color: #94a3b8; }
        .info-box { display: flex; align-items: flex-start; gap: 12px; background: #EBF3FF; border: 1px solid #A9C0EA; border-radius: 12px; padding: 16px 20px; font-size: 13.5px; color: #1e293b; line-height: 1.6; }
        .info-box i { color: #334EAC; margin-top: 4px; }

        .spj-box { display: flex; align-items: center; gap: 16px; background: #F4F8FF; border: 1px solid #D6E4FA; border-radius: 14px; padding: 16px 20px; margin-bottom: 32px; }
        .spj-icon { width: 38px; height: 38px; border-radius: 50%; background: #0b4a38; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
        .spj-text { flex: 1; }
        .spj-text strong { display: block; font-size: 14px; color: #0f172a; }
        .spj-text span { font-size: 13px; color: #64748b; }
        .spj-tag { background: #fff; border: 1px solid #CFE0FA; color: #334EAC; font-size: 12.5px; font-weight: 600; padding: 8px 16px; border-radius: 8px; }

        .actions { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #D6E4FA; padding-top: 28px; }
        .btn-back { color: #64748b; font-size: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; }
        .btn-back:hover { color: #334EAC; }
        .btn-next { background: #334EAC; color: #fff; border: none; border-radius: 12px; padding: 16px 36px; font-family: inherit; font-size: 15px; font-weight: 600; display: flex; align-items: center; gap: 12px; cursor: pointer; }
        .btn-next:hover { background: #283f8c; }

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
            <span class="current">Formulir Reservasi (Tahap 1)</span>
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
                <div class="step active">
                    <div class="step-circle">1</div>
                    <div class="step-no">Tahap 1</div>
                    <div class="step-name">Instansi &amp; Jadwal</div>
                </div>
                <div class="step">
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

        <!-- KARTU FORM -->
        <div class="main-card">
            <div class="card-head">
                <div>
                    <h1 class="card-title">Informasi Penyewa &amp; Jadwal Praktikum</h1>
                    <p class="card-sub">Lengkapi data identitas institusi dan tentukan tanggal serta sesi peminjaman laboratorium.</p>
                </div>
                <div class="lab-chip">
                    <div class="lab-chip-icon"><i class="fa-regular fa-building"></i></div>
                    <div>
                        <small>LAB TERPILIH</small>
                        <strong>{{ $lab->lab_name }} - {{ $lab->campus_name }}</strong>
                    </div>
                </div>
            </div>

            <!-- A. DATA INSTITUSI -->
            <div class="sec-title"><span class="sec-badge">A</span> Data Institusi &amp; Penanggung Jawab</div>
            <div class="form-grid">
                <div class="field" id="f-inst">
                    <label class="lbl" for="inst">Nama Instansi / Sekolah <span class="req">*</span></label>
                    <div class="input-wrap">
                        <input type="text" class="input" id="inst" placeholder="Contoh: SMA Negeri 1 Pasuruan" autocomplete="off">
                        <i class="fa-solid fa-check ok-icon" id="instOk"></i>
                    </div>
                    <div class="err-msg">Nama instansi wajib diisi.</div>
                </div>
                <div class="field" id="f-type">
                    <label class="lbl" for="itype">Tipe Instansi <span class="req">*</span></label>
                    <div class="input-wrap">
                        <select class="select" id="itype">
                            <option value="sekolah_sma_smk">Sekolah Menengah Atas (SMA/SMK)</option>
                            <option value="lembaga_riset">Lembaga Riset</option>
                            <option value="perusahaan_swasta">Perusahaan Swasta</option>
                            <option value="komunitas_publik">Komunitas / Publik</option>
                        </select>
                        <i class="fa-solid fa-chevron-down select-arrow"></i>
                    </div>
                </div>

                <div class="field" id="f-pic">
                    <label class="lbl" for="pic">Nama Penanggung Jawab / Guru Pendamping <span class="req">*</span></label>
                    <input type="text" class="input" id="pic" placeholder="Nama lengkap beserta gelar" autocomplete="off">
                    <div class="err-msg">Nama penanggung jawab wajib diisi.</div>
                </div>
                <div class="two-cols">
                    <div class="field" id="f-wa">
                        <label class="lbl" for="wa">WhatsApp Aktif <span class="req">*</span></label>
                        <input type="text" class="input" id="wa" placeholder="+62 8xx-xxxx-xxxx" autocomplete="off">
                        <div class="err-msg">Nomor WhatsApp tidak valid.</div>
                    </div>
                    <div class="field" id="f-email">
                        <label class="lbl" for="email">Email Instansi <span class="req">*</span></label>
                        <input type="text" class="input" id="email" placeholder="Email resmi instansi" autocomplete="off">
                        <div class="err-msg">Format email tidak valid.</div>
                    </div>
                </div>

                <div class="field" id="f-peserta">
                    <label class="lbl" for="peserta">Jumlah Peserta Praktikan / Siswa <span class="req">*</span></label>
                    <div class="participants-row">
                        <div class="input-wrap">
                            <input type="number" class="input" id="peserta" min="1" max="{{ (int) $lab->capacity }}" placeholder="0">
                            <span class="suffix">Orang</span>
                        </div>
                        <div class="cap-note">Kapasitas lab maks. <strong>{{ (int) $lab->capacity }} orang</strong></div>
                    </div>
                    <div class="err-msg" id="pesertaErr">Jumlah peserta tidak valid.</div>
                </div>
                <div class="field" id="f-file">
                    <label class="lbl">Surat Tugas / Proposal Praktikum <span class="req">*</span></label>
                    <div class="upload-box" id="uploadBox">
                        <div class="upload-icon"><i class="fa-solid fa-file-pdf"></i></div>
                        <div class="upload-info">
                            <strong id="fileName">Klik untuk unggah dokumen</strong>
                            <span id="fileMeta">PDF / DOC, maksimal 5 MB</span>
                        </div>
                        <button type="button" class="btn-small" id="btnFile">Pilih File</button>
                    </div>
                    <input type="file" id="fileInput" accept=".pdf,.doc,.docx" style="display:none;">
                    <div class="err-msg" id="fileErr">Surat tugas / proposal wajib diunggah.</div>
                </div>
            </div>

            <hr class="section-divider">

            <!-- B. JADWAL -->
            <div class="sec-title"><span class="sec-badge">B</span> Pilih Jadwal &amp; Sesi Kunjungan Lab</div>
            <p class="sec-desc">Laboratorium beroperasi Senin s.d. Sabtu. Tanggal dengan titik hijau menunjukkan ketersediaan instrumen penuh.</p>

            <div class="schedule">
                <div>
                    <div class="cal">
                        <div class="cal-head">
                            <strong id="calTitle">-</strong>
                            <div class="cal-nav">
                                <button type="button" id="calPrev"><i class="fa-solid fa-chevron-left"></i></button>
                                <button type="button" id="calNext"><i class="fa-solid fa-chevron-right"></i></button>
                            </div>
                        </div>
                        <div class="cal-week"><span>Min</span><span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span></div>
                        <div class="cal-grid" id="calGrid"></div>
                        <div class="cal-legend">
                            <span><i style="background:#334EAC"></i> Terpilih</span>
                            <span><i style="background:#16a34a"></i> Kuota Tersedia</span>
                            <span><i style="background:#b91c1c"></i> Libur / Penuh</span>
                        </div>
                    </div>
                    <div class="err-msg" id="dateErr" style="margin-top:8px;">Pilih tanggal kunjungan terlebih dahulu.</div>
                </div>

                <div>
                    <div class="shift-title">Pilihan Shift / Durasi Praktikum</div>
                    <div id="shiftList"></div>
                    <div class="info-box">
                        <i class="fa-solid fa-circle-info"></i>
                        <div>Setiap sesi didampingi oleh <strong>1 Pranata Laboratorium Pendidikan (PLP) bersertifikat BNSP untuk pengawasan protokol K3 dan operasional instrumen.</strong></div>
                    </div>
                </div>
            </div>

            <div class="spj-box">
                <div class="spj-icon"><i class="fa-solid fa-check"></i></div>
                <div class="spj-text">
                    <strong>Kepatuhan SPJ &amp; Billing Simponi Siap</strong>
                    <span>Kuitansi dan invoice resmi instansi perguruan tinggi diterbitkan otomatis untuk pelaporan BIMA/BOS.</span>
                </div>
                <span class="spj-tag">PNBP Kemendikbud</span>
            </div>

            <div class="actions">
                <a href="{{ route('labs.show', $lab->lab_id) }}" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Kembali ke Detail Lab</a>
                <button type="button" class="btn-next" id="btnNext">Lanjut ke Tahap 2: Pilih Alat <i class="fa-solid fa-arrow-right"></i></button>
            </div>
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
    var CAP = P.lab.capacity;
    var BASE = P.lab.base_price;
    var BOOKED = P.booked || {};
    var MONTHS = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    var HOLIDAYS = ['01-01', '05-01', '06-01', '08-17', '12-25'];
    var $ = function (id) { return document.getElementById(id); };
    var rupiah = function (n) { return 'Rp ' + Math.round(n).toLocaleString('id-ID'); };
    var pad = function (n) { return (n < 10 ? '0' : '') + n; };
    var fmt = function (y, m, d) { return y + '-' + pad(m + 1) + '-' + pad(d); };

    var SHIFTS = [
        { key: 'Pagi (08:00-12:00)',  title: 'Sesi 1: Pagi',  time: 'Pukul 08.00 - 12.00 WIB (Durasi 4 Jam)', price: BASE, note: 'Tarif Standar Siswa', badge: 'Tersedia ' + CAP + ' Meja', type: 'green' },
        { key: 'Siang (13:00-17:00)', title: 'Sesi 2: Siang', time: 'Pukul 13.00 - 17.00 WIB (Durasi 4 Jam)', price: BASE, note: 'Tarif Standar Siswa', badge: 'Tersedia ' + CAP + ' Meja', type: 'green' },
        { key: 'Penuh (08:00-17:00)', title: 'Sesi Penuh: 1 Hari Kerja', time: 'Pukul 08.00 - 17.00 WIB (Durasi 8 Jam)', price: Math.max(BASE * 2 - 50000, 0), note: 'Hemat Rp 50.000', badge: 'Rekomendasi Riset', type: 'blue' }
    ];

    var now = new Date();
    var todayStr = fmt(now.getFullYear(), now.getMonth(), now.getDate());
    var viewY = now.getFullYear(), viewM = now.getMonth();
    var selDate = '', selShift = '';
    var chosenFile = null;

    /* ---------- Ketersediaan ---------- */
    function shiftFree(ds, key) {
        var b = BOOKED[ds] || [];
        if (b.indexOf('Penuh (08:00-17:00)') > -1) return false;
        if (key === 'Penuh (08:00-17:00)') return b.length === 0;
        return b.indexOf(key) === -1;
    }
    function isFull(ds) { return !shiftFree(ds, SHIFTS[0].key) && !shiftFree(ds, SHIFTS[1].key); }
    function isHoliday(y, m, d) { return HOLIDAYS.indexOf(pad(m + 1) + '-' + pad(d)) > -1; }
    function selectable(y, m, d) {
        var ds = fmt(y, m, d);
        if (ds < todayStr) return false;
        if (new Date(y, m, d).getDay() === 0) return false;
        if (isHoliday(y, m, d)) return false;
        return !isFull(ds);
    }

    /* ---------- Kalender ---------- */
    function renderCal() {
        $('calTitle').textContent = MONTHS[viewM] + ' ' + viewY;
        var first = new Date(viewY, viewM, 1).getDay();
        var dim = new Date(viewY, viewM + 1, 0).getDate();
        var prevDim = new Date(viewY, viewM, 0).getDate();
        var rows = Math.ceil((first + dim) / 7);
        var html = '';
        for (var i = 0; i < rows * 7; i++) {
            var dayNum = i - first + 1, cls = 'day', label = '', attrs = '';
            if (dayNum < 1) { label = prevDim + dayNum; cls += ' muted'; }
            else if (dayNum > dim) { label = dayNum - dim; cls += ' muted'; }
            else {
                label = dayNum;
                var ds = fmt(viewY, viewM, dayNum);
                var dow = new Date(viewY, viewM, dayNum).getDay();
                if (ds < todayStr) { cls += ' muted'; }
                else if (dow === 0 || isHoliday(viewY, viewM, dayNum) || isFull(ds)) { cls += ' off'; }
                else { cls += ' avail'; attrs = ' data-date="' + ds + '"'; }
                if (ds === selDate && selectable(viewY, viewM, dayNum)) cls += ' sel';
            }
            html += '<div class="cal-cell"><button type="button" class="' + cls + '"' + attrs + '>' + label + '</button></div>';
        }
        $('calGrid').innerHTML = html;
        $('calPrev').disabled = (viewY === now.getFullYear() && viewM === now.getMonth());
    }
    $('calGrid').addEventListener('click', function (e) {
        var b = e.target.closest('.day.avail');
        if (!b) return;
        selDate = b.getAttribute('data-date');
        $('dateErr').style.display = 'none';
        if (!selShift || !shiftFree(selDate, selShift)) {
            selShift = '';
            for (var i = 0; i < SHIFTS.length; i++) { if (shiftFree(selDate, SHIFTS[i].key)) { selShift = SHIFTS[i].key; break; } }
        }
        renderCal(); renderShifts();
    });
    $('calPrev').addEventListener('click', function () { viewM--; if (viewM < 0) { viewM = 11; viewY--; } renderCal(); });
    $('calNext').addEventListener('click', function () { viewM++; if (viewM > 11) { viewM = 0; viewY++; } renderCal(); });

    /* ---------- Shift ---------- */
    function renderShifts() {
        var html = '';
        SHIFTS.forEach(function (s) {
            var free = selDate ? shiftFree(selDate, s.key) : true;
            var cls = 'shift-card' + (selShift === s.key ? ' selected' : '') + (free ? '' : ' disabled');
            var badge = free ? '<span class="pill pill-' + s.type + '">' + s.badge + '</span>' : '<span class="pill pill-red">Sudah Terisi</span>';
            html += '<div class="' + cls + '" data-key="' + s.key + '"><span class="radio"></span>' +
                '<div><div class="shift-name">' + s.title + ' ' + badge + '</div><div class="shift-time">' + s.time + '</div></div>' +
                '<div class="shift-price"><strong>' + rupiah(s.price) + '</strong><small>' + s.note + '</small></div></div>';
        });
        $('shiftList').innerHTML = html;
    }
    $('shiftList').addEventListener('click', function (e) {
        var c = e.target.closest('.shift-card');
        if (!c || c.classList.contains('disabled')) return;
        selShift = c.getAttribute('data-key');
        renderShifts();
    });

    /* ---------- Form ---------- */
    function setErr(fieldId, on, msg, msgId) {
        var f = $(fieldId);
        f.classList.toggle('err', !!on);
        if (on && msg && msgId) $(msgId).textContent = msg;
    }
    function refreshInstOk() {
        var v = $('inst').value.trim();
        $('instOk').classList.toggle('show', v.length >= 3);
        $('inst').classList.toggle('filled', v.length >= 3);
    }
    $('inst').addEventListener('input', function () { refreshInstOk(); setErr('f-inst', false); });
    ['pic', 'wa', 'email', 'peserta'].forEach(function (id) {
        $(id).addEventListener('input', function () { setErr('f-' + id, false); });
    });

    function fmtSize(b) { return b >= 1048576 ? (b / 1048576).toFixed(1) + ' MB' : Math.max(1, Math.round(b / 1024)) + ' KB'; }
    function openPicker() { $('fileInput').click(); }
    $('uploadBox').addEventListener('click', function (e) { if (e.target.id !== 'btnFile') openPicker(); });
    $('btnFile').addEventListener('click', openPicker);
    $('fileInput').addEventListener('change', function () {
        var f = this.files[0];
        if (!f) return;
        if (f.size > 5 * 1048576) {
            chosenFile = null;
            setErr('f-file', true, 'Ukuran file maksimal 5 MB.', 'fileErr');
            return;
        }
        chosenFile = f;
        setErr('f-file', false);
        $('fileName').textContent = f.name;
        $('fileMeta').innerHTML = fmtSize(f.size) + ' &bull; <em>Verifikasi Siap</em>';
        $('btnFile').textContent = 'Ganti File';
    });

    $('btnNext').addEventListener('click', function () {
        var bad = [];
        var inst = $('inst').value.trim(), pic = $('pic').value.trim(), wa = $('wa').value.trim(), em = $('email').value.trim();
        var n = parseInt($('peserta').value, 10);

        if (inst.length < 3) { setErr('f-inst', true); bad.push('f-inst'); }
        if (pic.length < 3) { setErr('f-pic', true); bad.push('f-pic'); }
        if (!/^(\+62|62|0)[0-9\s\-]{8,16}$/.test(wa)) { setErr('f-wa', true); bad.push('f-wa'); }
        if (!/^[^\s]+@[^\s]+\.[^\s]+$/.test(em)) { setErr('f-email', true); bad.push('f-email'); }
        if (!n || n < 1) { setErr('f-peserta', true, 'Jumlah peserta wajib diisi.', 'pesertaErr'); bad.push('f-peserta'); }
        else if (n > CAP) { setErr('f-peserta', true, 'Melebihi kapasitas lab (maks. ' + CAP + ' orang).', 'pesertaErr'); bad.push('f-peserta'); }
        if (!chosenFile) { setErr('f-file', true, 'Surat tugas / proposal wajib diunggah.', 'fileErr'); bad.push('f-file'); }
        if (!selDate || !selShift) { $('dateErr').style.display = 'block'; bad.push('calGrid'); }

        if (bad.length) {
            var el = $(bad[0]);
            window.scrollTo({ top: el.getBoundingClientRect().top + window.scrollY - 120, behavior: 'smooth' });
            return;
        }

        var shift = SHIFTS.filter(function (s) { return s.key === selShift; })[0];
        var data = {
            lab_id: P.lab.id,
            lab_name: P.lab.name,
            institution_name: inst,
            institution_type: $('itype').value,
            pic_name: pic,
            whatsapp: wa,
            email: em,
            participants: n,
            proposal_file_name: chosenFile.name,
            proposal_file_size: chosenFile.size,
            reservation_date: selDate,
            session_time: selShift,
            session_price: shift.price
        };
        try { sessionStorage.setItem('palapa_booking', JSON.stringify(data)); } catch (e) {}

        if (P.step2_url) { window.location.href = P.step2_url; }
        else { alert('Data Tahap 1 tersimpan. Halaman Tahap 2 (Pilih Alat) sedang disiapkan.'); }
    });

    /* ---------- Prefill ---------- */
    var pf = P.prefill || {};
    var saved = null;
    try { saved = JSON.parse(sessionStorage.getItem('palapa_booking') || 'null'); } catch (e) {}
    if (saved && saved.lab_id !== P.lab.id) saved = null;

    $('inst').value = (saved && saved.institution_name) || pf.institution_name || '';
    $('itype').value = (saved && saved.institution_type) || pf.institution_type || 'sekolah_sma_smk';
    $('pic').value = (saved && saved.pic_name) || pf.pic_name || '';
    $('wa').value = (saved && saved.whatsapp) || pf.whatsapp || '';
    $('email').value = (saved && saved.email) || pf.email || '';
    var pn = pf.participants || (saved && saved.participants) || '';
    if (pn) $('peserta').value = Math.min(pn, CAP);
    refreshInstOk();

    var wantDate = pf.date || (saved && saved.reservation_date) || '';
    var wantShift = pf.session_time || (saved && saved.session_time) || '';
    if (wantDate && /^\d{4}-\d{2}-\d{2}$/.test(wantDate)) {
        var y = +wantDate.slice(0, 4), m = +wantDate.slice(5, 7) - 1, d = +wantDate.slice(8, 10);
        if (selectable(y, m, d)) { selDate = wantDate; viewY = y; viewM = m; }
    }
    if (selDate) {
        var okShift = SHIFTS.filter(function (s) { return s.key === wantShift && shiftFree(selDate, s.key); })[0];
        if (okShift) selShift = okShift.key;
        else { for (var i = 0; i < SHIFTS.length; i++) { if (shiftFree(selDate, SHIFTS[i].key)) { selShift = SHIFTS[i].key; break; } } }
    }
    $('dateErr').style.display = 'none';
    renderCal();
    renderShifts();
})();
</script>

</body>
</html>
