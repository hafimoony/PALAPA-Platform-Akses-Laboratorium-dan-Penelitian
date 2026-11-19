@php
    // Ambil data lab: dipakai dari controller jika ada ($lab), atau dicari sendiri dari parameter rute.
    $lab = $lab ?? \App\Models\Laboratory::with('equipments')->where('lab_id', request()->route('lab_id') ?? request('lab_id'))->first();
    abort_if(!$lab, 404, 'Laboratorium tidak ditemukan');

    $vendors = $vendors ?? \Illuminate\Support\Facades\DB::table('payment_vendors')->where('is_active', 1)->orderBy('vendor_id')->get();

    $included = $lab->equipments
        ->where('status', 'available')
        ->filter(function ($e) { return (float) $e->rental_price_per_unit <= 0; })
        ->pluck('equipment_name')->values()->all();

    $safeRoute = function ($name, $params = []) {
        try {
            return \Illuminate\Support\Facades\Route::has($name) ? route($name, $params) : '';
        } catch (\Throwable $e) {
            return '';
        }
    };

    $payload = [
        'lab' => [
            'id' => $lab->lab_id,
            'name' => $lab->lab_name,
            'campus' => $lab->campus_name,
            'address' => $lab->address_description,
        ],
        'included' => $included,
        'vendors' => $vendors->map(function ($v) {
            return [
                'id' => $v->vendor_id,
                'code' => strtoupper($v->vendor_code),
                'name' => $v->vendor_name,
                'type' => $v->vendor_type,
                'fee' => (float) $v->admin_fee,
            ];
        })->values()->all(),
        'urls' => [
            'step1' => $safeRoute('bookings.step1', ['lab_id' => $lab->lab_id]),
            'step2' => $safeRoute('bookings.step2', ['lab_id' => $lab->lab_id]),
            'store' => $safeRoute('bookings.store') ?: url('/bookings'),
            'riwayat' => url('/riwayat'),
            'home' => url('/'),
            'bookings' => url('/bookings'),
            'help' => url('/pusat-bantuan'),
        ],
        'csrf' => csrf_token(),
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1280">
    <title>Formulir Reservasi (Tahap 3) - {{ $lab->lab_name }} - PALAPA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
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
        .breadcrumb { display: flex; align-items: center; gap: 10px; font-size: 13px; color: #475569; white-space: nowrap; min-width: 0; }
        .breadcrumb .crumb-lab { max-width: 150px; overflow: hidden; text-overflow: ellipsis; }
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
        .steps::before { content: ""; position: absolute; left: 16.66%; right: 16.66%; top: 22px; height: 2px; background: #334EAC; }
        .step { position: relative; z-index: 2; text-align: center; }
        .step-circle { width: 46px; height: 46px; border-radius: 50%; margin: 0 auto 18px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 15px; background: #fff; border: 2px solid #E2E8F0; color: #CBD5E1; }
        .step.done .step-circle { background: #091F5B; border-color: #091F5B; color: #fff; }
        .step.active .step-circle { border-color: #334EAC; color: #334EAC; box-shadow: 0 0 0 4px #DCE8FB; }
        .step-no { font-size: 11.5px; font-weight: 700; letter-spacing: .4px; text-transform: uppercase; color: #94a3b8; margin-bottom: 4px; }
        .step-name { font-size: 13.5px; font-weight: 500; color: #94a3b8; }
        .step.done .step-no, .step.done .step-name { color: #0f172a; }
        .step.done .step-name { font-weight: 500; }
        .step.active .step-no { color: #334EAC; }
        .step.active .step-name { color: #0f172a; font-weight: 700; }

        /* LAYOUT */
        .layout { display: grid; grid-template-columns: minmax(0, 1fr) 400px; gap: 30px; align-items: start; }
        .card { background: #EEF5FF; border: 1px solid #E1ECFB; border-radius: 20px; padding: 30px 32px; margin-bottom: 28px; box-shadow: 0 6px 22px rgba(51,78,172,.08); }
        .card-title { font-size: 24px; font-weight: 800; color: #091F5B; }

        /* RINGKASAN VERIFIKASI */
        .rv-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 26px; }
        .rv-status { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
        .chip-status { background: #0b4a38; color: #fff; font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 999px; }
        .rv-code { font-family: 'JetBrains Mono', monospace; font-size: 12px; color: #94a3b8; }
        .rv-edit { font-size: 13px; font-weight: 600; color: #334EAC; display: flex; align-items: center; gap: 6px; margin-top: 6px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 18px; }
        .info-card { background: #fff; border: 1px solid #D6E4FA; border-radius: 12px; padding: 16px 18px; }
        .info-card small { display: block; font-size: 12.5px; color: #64748b; margin-bottom: 4px; }
        .info-card strong { display: block; font-size: 16px; font-weight: 800; color: #091F5B; margin-bottom: 2px; }
        .info-card strong.blue { color: #334EAC; }
        .info-card span { font-size: 12.5px; color: #475569; }
        .file-bar { display: flex; justify-content: space-between; align-items: center; background: #0b4a38; color: #fff; border-radius: 8px; padding: 12px 16px; margin-bottom: 14px; font-size: 12.5px; }
        .file-bar .nm { display: flex; align-items: center; gap: 10px; font-family: 'JetBrains Mono', monospace; font-weight: 700; font-size: 12px; }
        .file-bar .ok { font-weight: 700; display: flex; align-items: center; gap: 6px; }
        .inc-box { background: #fff; border: 2px solid #A9C0EA; border-radius: 10px; padding: 12px 14px; }
        .inc-box h4 { font-size: 11.5px; font-weight: 800; color: #091F5B; letter-spacing: .3px; text-transform: uppercase; margin-bottom: 8px; }
        .chips { display: flex; flex-wrap: wrap; gap: 8px; }
        .chip { border: 1px solid #CBD5E1; border-radius: 5px; padding: 4px 10px; font-size: 12px; color: #1e293b; background: #fff; }

        /* KANAL PEMBAYARAN */
        .pay-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }
        .pay-tag { background: #DCE8FB; color: #334EAC; font-size: 12px; font-weight: 700; padding: 6px 12px; border-radius: 6px; display: flex; align-items: center; gap: 6px; }
        .pay-sub { font-size: 13.5px; color: #64748b; margin-bottom: 18px; }
        .cat { background: #F4F8FF; border: 1px solid #D6E4FA; border-radius: 12px; margin-bottom: 16px; overflow: hidden; }
        .cat-head { display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; background: #F8FAFF; border-bottom: 1px solid #D6E4FA; font-size: 12.5px; font-weight: 800; color: #091F5B; letter-spacing: .3px; text-transform: uppercase; }
        .cat-head .note { font-size: 12.5px; font-weight: 700; text-transform: none; letter-spacing: 0; color: #334EAC; }
        .cat-head .note.green { color: #0b6b4b; }
        .cat-head .pill { background: #0b4a38; color: #fff; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 5px; text-transform: none; letter-spacing: 0; margin-left: 8px; }
        .cat-body { padding: 12px 12px 2px; }
        .vendor { position: relative; display: flex; justify-content: space-between; align-items: center; background: #fff; border: 1px solid #D6E4FA; border-radius: 10px; padding: 14px 18px 14px 52px; margin-bottom: 10px; cursor: pointer; }
        .vendor.on { border: 2px solid #334EAC; }
        .vendor .rd { position: absolute; left: 18px; top: 50%; margin-top: -9px; width: 18px; height: 18px; border-radius: 50%; border: 2px solid #CBD5E1; background: #fff; }
        .vendor.on .rd { border: 5px solid #334EAC; }
        .vendor-name { display: flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 2px; }
        .vendor:not(.on) .vendor-name { font-weight: 600; }
        .vendor-desc { font-size: 12px; color: #64748b; line-height: 1.5; max-width: 480px; }
        .v-badge { background: #334EAC; color: #fff; font-size: 10.5px; font-weight: 700; padding: 2px 8px; border-radius: 4px; }
        .v-badge.light { background: #EEF2F7; color: #475569; font-weight: 600; }
        .v-code { border: 1px solid #D6E4FA; background: #fff; font-size: 12.5px; font-weight: 800; padding: 6px 14px; border-radius: 6px; color: #334EAC; white-space: nowrap; }
        .v-code.orange { color: #ea580c; }
        .v-code.dark { color: #0f172a; }
        .cat-empty { padding: 16px; font-size: 13px; color: #64748b; text-align: center; }

        /* PERSETUJUAN */
        .agree { background: #fff; border: 2px solid #334EAC; border-radius: 16px; padding: 22px 26px; cursor: pointer; }
        .agree-top { display: flex; gap: 16px; align-items: flex-start; padding-bottom: 14px; border-bottom: 1px solid #E2E8F0; }
        .agree-chk { position: relative; width: 24px; height: 24px; border-radius: 6px; border: 2px solid #CBD5E1; background: #fff; flex-shrink: 0; margin-top: 2px; }
        .agree.on .agree-chk { background: #334EAC; border-color: #334EAC; }
        .agree.on .agree-chk::after { content: ""; position: absolute; left: 7px; top: 2px; width: 5px; height: 11px; border: solid #fff; border-width: 0 2px 2px 0; transform: rotate(45deg); }
        .agree-text { font-size: 14px; color: #1e293b; line-height: 1.6; }
        .agree-text b { color: #091F5B; }
        .agree-note { font-size: 12.5px; color: #94a3b8; line-height: 1.6; padding: 14px 0 0 40px; }
        .agree.err { border-color: #dc2626; }

        /* SIDEBAR */
        .side { position: sticky; top: 20px; }
        .side-card { background: #EEF5FF; border: 1px solid #E1ECFB; border-radius: 20px; padding: 26px 26px 22px; box-shadow: 0 6px 22px rgba(51,78,172,.08); margin-bottom: 18px; }
        .side-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 26px; }
        .side-head > div { min-width: 0; }
        .side-head h3 { white-space: nowrap; font-size: 17px; font-weight: 800; color: #091F5B; margin-bottom: 2px; }
        .side-head p { font-size: 12px; color: #475569; }
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
        .btn-pay { width: 100%; background: #334EAC; color: #fff; border: none; border-radius: 12px; padding: 16px; font-family: inherit; font-size: 15px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 12px; cursor: pointer; margin-bottom: 12px; }
        .btn-pay:hover:not(:disabled) { background: #283f8c; }
        .btn-pay:disabled { opacity: .5; cursor: not-allowed; }
        .btn-prev { width: 100%; background: #fff; border: 1px solid #D6E4FA; color: #334155; border-radius: 12px; padding: 12px; font-size: 13.5px; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 10px; }
        .btn-prev:hover { color: #334EAC; border-color: #334EAC; }
        .pay-error { display: none; background: #FDECEC; border: 1px solid #F5B5B5; color: #b91c1c; border-radius: 10px; padding: 10px 14px; font-size: 12.5px; line-height: 1.5; margin-bottom: 12px; }
        .help-card { display: flex; align-items: center; gap: 14px; background: #EEF5FF; border: 1px solid #E1ECFB; border-radius: 16px; padding: 14px 18px; box-shadow: 0 6px 22px rgba(51,78,172,.08); }
        .help-icon { width: 40px; height: 40px; border-radius: 50%; background: #0b4a38; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; }
        .help-card strong { display: block; font-size: 13px; color: #0f172a; }
        .help-card span { font-size: 12.5px; color: #475569; }
        .help-card span b { color: #334EAC; }

        /* POPUP INSTRUKSI PEMBAYARAN */
        .overlay { display: none; position: fixed; inset: 0; background: rgba(9,31,91,.55); z-index: 100; align-items: center; justify-content: center; padding: 20px; }
        .overlay.show { display: flex; }
        .pm { background: #fff; border-radius: 20px; width: 500px; max-height: 94vh; overflow-y: auto; box-shadow: 0 24px 60px rgba(9,31,91,.35); border: 1px solid #D6DCEE; }
        .pm-head { display: flex; align-items: center; gap: 16px; padding: 20px 22px; background: #F3F5FD; border-bottom: 1px solid #D6DCEE; }
        .pm-back { color: #1d3fae; font-size: 20px; width: 28px; }
        .pm-title { flex: 1; }
        .pm-title h2 { font-size: 21px; font-weight: 800; color: #0f172a; line-height: 1.2; }
        .pm-sub { display: flex; align-items: center; gap: 8px; margin-top: 4px; font-size: 12.5px; }
        .pm-sub .rc { font-family: 'JetBrains Mono', monospace; font-weight: 700; color: #0a2a8a; }
        .pm-sub .iso { color: #0b6b4b; font-weight: 700; }
        .pm-receipt { width: 38px; height: 38px; border: 1px solid #D6DCEE; background: #fff; border-radius: 9px; color: #334155; display: flex; align-items: center; justify-content: center; font-size: 16px; cursor: pointer; }
        .pm-timer { display: flex; justify-content: space-between; align-items: center; padding: 11px 22px; background: #F3F5FD; border-bottom: 1px solid #D6DCEE; font-size: 13px; color: #475569; }
        .pm-timer .dot { display: inline-block; width: 9px; height: 9px; border-radius: 50%; background: #b91c1c; margin-right: 8px; }
        .pm-timer .cd { font-family: 'JetBrains Mono', monospace; font-weight: 700; color: #b91c1c; font-size: 14px; margin-left: 6px; }
        .pm-timer b { color: #0f172a; }
        .pm-body { padding: 20px 22px 16px; }
        .pm-card { background: #F3F5FD; border: 1px solid #D6DCEE; border-radius: 14px; padding: 14px 14px 14px; margin-bottom: 14px; }
        .pm-vhead { display: flex; align-items: center; gap: 10px; padding-bottom: 10px; border-bottom: 1px solid #D6DCEE; margin-bottom: 12px; }
        .pm-vcode { background: #1d3fae; color: #fff; font-size: 13px; font-weight: 800; padding: 5px 12px; border-radius: 4px; }
        .pm-vname { font-size: 14px; font-weight: 800; color: #0f172a; }
        .pm-vname span { font-family: 'JetBrains Mono', monospace; font-weight: 500; font-size: 12px; color: #64748b; margin-left: 6px; }
        .pm-auto { margin-left: auto; background: #86f0c4; color: #064e3b; font-size: 12.5px; font-weight: 800; padding: 4px 12px; border-radius: 6px; }
        .pm-box { background: #fff; border-radius: 10px; padding: 12px 14px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; gap: 12px; }
        .pm-box.dash { border: 1.5px dashed #94a3b8; }
        .pm-box.solid { border: 1px solid #CBD5E1; margin-bottom: 0; }
        .pm-box small { display: block; font-size: 13px; color: #475569; margin-bottom: 8px; }
        .pm-box .big { font-family: 'JetBrains Mono', monospace; font-size: 22px; font-weight: 700; color: #0a2a8a; letter-spacing: .5px; }
        .pm-box .big.dark { color: #0f172a; }
        .pm-box .inc { font-size: 12px; color: #0b6b4b; font-weight: 600; position: absolute; right: 0; top: 0; }
        .pm-copy { background: #F3F5FD; border: 1px solid #94a3b8; border-radius: 7px; color: #0a2a8a; font-family: inherit; font-size: 13.5px; font-weight: 700; padding: 8px 14px; cursor: pointer; display: flex; align-items: center; gap: 8px; white-space: nowrap; }
        .pm-copy.ok { color: #0b6b4b; border-color: #0b6b4b; }
        .pm-total-wrap { position: relative; }
        .pm-qr { display: flex; flex-direction: column; align-items: center; gap: 10px; background: #fff; border: 1.5px dashed #94a3b8; border-radius: 10px; padding: 16px; margin-bottom: 10px; }
        .pm-qr svg { width: 190px; height: 190px; }
        .pm-qr .qrc { font-family: 'JetBrains Mono', monospace; font-size: 12px; color: #475569; }
        .pm-lab { border: 1px solid #CBD5E1; border-radius: 10px; margin-bottom: 14px; background: #F3F5FD; }
        .pm-lab-top { display: flex; justify-content: space-between; align-items: center; padding: 13px 16px; cursor: pointer; }
        .pm-lab-top strong { font-size: 14.5px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px; }
        .pm-lab-top strong i { color: #334155; }
        .pm-lab-top span { color: #1d3fae; font-size: 13.5px; font-weight: 600; }
        .pm-lab-detail { display: none; padding: 0 16px 14px; font-size: 12.5px; color: #475569; line-height: 1.9; }
        .pm-lab.open .pm-lab-detail { display: block; }
        .pm-lab.open .pm-lab-top span i { transform: rotate(180deg); }
        .pm-lab-detail b { color: #0f172a; }
        .pm-how { border: 1px solid #CBD5E1; border-radius: 12px; padding: 16px; margin-bottom: 16px; }
        .pm-how-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
        .pm-how-top h4 { white-space: nowrap; font-size: 14.5px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px; }
        .pm-tabs { display: flex; background: #EEF1F8; border: 1px solid #CBD5E1; border-radius: 8px; padding: 3px; }
        .pm-tab { white-space: nowrap; border: none; background: transparent; font-family: inherit; font-size: 13px; font-weight: 600; color: #64748b; padding: 6px 14px; border-radius: 6px; cursor: pointer; }
        .pm-tab.on { background: #0a2a8a; color: #fff; font-weight: 800; }
        .pm-step { display: flex; gap: 12px; align-items: flex-start; font-size: 13.5px; color: #1e293b; line-height: 1.55; margin-bottom: 11px; }
        .pm-step .n { width: 24px; height: 24px; border-radius: 50%; background: #1d3fae; color: #fff; font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .pm-step code { font-family: 'JetBrains Mono', monospace; font-weight: 700; color: #0a2a8a; }
        .pm-check { width: 100%; background: #0a2a8a; color: #fff; border: none; border-radius: 8px; padding: 15px; font-family: inherit; font-size: 15.5px; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 12px; cursor: pointer; }
        .pm-check:hover:not(:disabled) { background: #081f6b; }
        .pm-check:disabled { opacity: .55; cursor: not-allowed; }
        .pm-msg { display: none; margin-top: 10px; font-size: 12.5px; line-height: 1.5; padding: 9px 12px; border-radius: 8px; }
        .pm-msg.info { display: block; background: #EEF5FF; color: #334EAC; }
        .pm-msg.err { display: block; background: #FDECEC; color: #b91c1c; }
        .pm-msg.sim { display: block; background: #FFF7E0; color: #8a5a00; }
        .pm-foot { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #E2E8F0; margin-top: 14px; padding-top: 12px; font-size: 13px; color: #475569; }
        .pm-foot a { color: #475569; display: flex; align-items: center; gap: 8px; }
        .pm-foot a.wa { color: #0b6b4b; font-weight: 700; }
        .pm-done { text-align: center; padding: 18px 8px 8px; }
        .pm-done .ic { width: 68px; height: 68px; border-radius: 50%; background: #0b4a38; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 16px; }
        .pm-done h3 { font-size: 21px; font-weight: 800; color: #091F5B; margin-bottom: 6px; }
        .pm-done p { font-size: 13.5px; color: #64748b; line-height: 1.6; }
        .pm-done .code { font-family: 'JetBrains Mono', monospace; font-size: 26px; font-weight: 700; color: #334EAC; background: #EEF5FF; border: 2px dashed #A9C0EA; border-radius: 12px; padding: 14px; margin: 18px 0 14px; letter-spacing: 1px; }
        .pm-done .rows { text-align: left; background: #F8FAFF; border: 1px solid #D6E4FA; border-radius: 12px; padding: 12px 16px; margin-bottom: 16px; }
        .pm-done .rows div { display: flex; justify-content: space-between; font-size: 13px; padding: 4px 0; color: #475569; }
        .pm-done .rows b { color: #0f172a; }
        .pm-done .btns { display: flex; gap: 12px; }
        .pm-done .btns a { flex: 1; padding: 13px; border-radius: 10px; font-size: 13.5px; font-weight: 600; }
        .pm-done .btns .p { background: #334EAC; color: #fff; }
        .pm-done .btns .s { background: #fff; color: #334155; border: 1px solid #D6E4FA; }

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
    @include('partials.navbar')

    <!-- BREADCRUMB -->
    <div class="crumb-bar">
        <nav class="breadcrumb">
            <a href="{{ route('home') }}">Beranda</a><span>/</span>
            <a href="{{ route('labs.index') }}">Katalog Lab</a><span>/</span>
            <a href="{{ route('labs.show', $lab->lab_id) }}" class="crumb-lab" title="{{ $lab->lab_name }}">{{ $lab->lab_name }}</a><span>/</span>
            <span class="current">Formulir Reservasi (Tahap 3 - Konfirmasi &amp; Pembayaran)</span>
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
                <div class="step done">
                    <div class="step-circle"><i class="fa-solid fa-check"></i></div>
                    <div class="step-no">Tahap 2</div>
                    <div class="step-name">Alat &amp; Add-on</div>
                </div>
                <div class="step active">
                    <div class="step-circle">3</div>
                    <div class="step-no">Tahap 3</div>
                    <div class="step-name">Konfirmasi &amp; Bayar</div>
                </div>
            </div>
        </div>

        <div class="layout">

            <!-- KOLOM KIRI -->
            <div>
                <!-- RINGKASAN VERIFIKASI -->
                <div class="card">
                    <div class="rv-top">
                        <div>
                            <div class="rv-status"><span class="chip-status">Status: Siap Diajukan</span><span class="rv-code" id="rvCode">#DRAF</span></div>
                            <h1 class="card-title">Ringkasan Verifikasi Reservasi</h1>
                        </div>
                        <a href="#" class="rv-edit" id="lnkEdit"><i class="fa-regular fa-pen-to-square"></i> Ubah Detail</a>
                    </div>

                    <div class="info-grid">
                        <div class="info-card"><small>Instansi Pemesan:</small><strong id="rvInst">-</strong><span id="rvInstSub">-</span></div>
                        <div class="info-card"><small>Fasilitas Laboratorium:</small><strong>{{ $lab->lab_name }}</strong><span>{{ $lab->campus_name }}{{ $lab->address_description ? ' (' . $lab->address_description . ')' : '' }}</span></div>
                        <div class="info-card"><small>Jadwal &amp; Sesi:</small><strong class="blue" id="rvDate">-</strong><span id="rvShift">-</span></div>
                        <div class="info-card"><small>Penanggung Jawab / Guru Pendamping:</small><strong id="rvPic">-</strong><span id="rvPicSub">-</span></div>
                    </div>

                    <div class="file-bar">
                        <span class="nm"><i class="fa-regular fa-file-lines"></i> <span id="rvFile">-</span></span>
                        <span class="ok"><i class="fa-solid fa-circle-check"></i> Dokumen Terunggah</span>
                    </div>

                    <div class="inc-box">
                        <h4>Instrumen &amp; Layanan Termasuk:</h4>
                        <div class="chips" id="rvChips"></div>
                    </div>
                </div>

                <!-- KANAL PEMBAYARAN -->
                <div class="card">
                    <div class="pay-head">
                        <h2 class="card-title" style="font-size:22px;">Pilih Kanal Pembayaran Resmi</h2>
                        <span class="pay-tag"><i class="fa-solid fa-shield-halved"></i> PNBP Simponi Terintegrasi</span>
                    </div>
                    <p class="pay-sub">Semua transaksi dilindungi gateway terintegrasi PNBP Kemendikbudristek RI dan kas negara.</p>
                    <div id="vendorCats"></div>
                </div>

                <!-- PERSETUJUAN -->
                <div class="agree" id="agree">
                    <div class="agree-top">
                        <span class="agree-chk"></span>
                        <div class="agree-text"><b>Saya menyetujui SOP, Syarat Operasional, dan Protokol Keselamatan Laboratorium (K3) PALAPA</b> serta menjamin keabsahan data rombongan praktikan yang didaftarkan sesuai ketentuan keselamatan kerja biologis.</div>
                    </div>
                    <div class="agree-note">Setelah pembayaran diverifikasi, E-Tiket Barcode Masuk Lab dan Berita Acara Pelaksanaan akan diterbitkan otomatis serta dikirimkan ke email resmi instansi pemohon.</div>
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
                        <span class="r" id="depVal">Rp 0</span>
                    </div>

                    <div class="total-row"><span class="l">Total Pembayaran</span><span class="r" id="totalVal">Rp 0</span></div>
                    <div class="total-note">Sudah termasuk PPN 11% &amp; Asuransi Alat</div>

                    <div class="faktur">
                        <i class="fa-regular fa-circle-check"></i>
                        <div>Faktur resmi dan billing <b>Simponi/PNBP Kemendikbudristek</b> diterbitkan otomatis untuk SPJ BOS/BIMA.</div>
                    </div>

                    <div class="pay-error" id="payError"></div>
                    <button type="button" class="btn-pay" id="btnPay" disabled><span id="btnPayText">Bayar dan Terbitkan Kode Reservasi</span> <i class="fa-solid fa-arrow-right"></i></button>
                    <a href="#" class="btn-prev" id="lnkBack"><i class="fa-solid fa-arrow-left"></i> Kembali ke Tahap 2 (Pilih Alat dan Add-on)</a>
                </div>

                <div class="help-card">
                    <div class="help-icon"><i class="fa-solid fa-headset"></i></div>
                    <div>
                        <strong>Punya Pertanyaan Pembayaran?</strong>
                        <span>Layanan Billing &amp; Keuangan {{ $lab->campus_name }}: <b>(031) 599-2785</b></span>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

<!-- POPUP INSTRUKSI PEMBAYARAN -->
<div class="overlay" id="overlay">
    <div class="pm" id="pm">
        <div class="pm-head">
            <a href="#" class="pm-back" id="pmBack" title="Ke riwayat reservasi"><i class="fa-solid fa-arrow-left"></i></a>
            <div class="pm-title">
                <h2 id="pmTitle">Instruksi Pembayaran</h2>
                <div class="pm-sub"><span class="rc" id="pmCode">#-</span><span>&bull;</span><span class="iso"><i class="fa-solid fa-certificate"></i> ISO 17025</span></div>
            </div>
            <div class="pm-receipt" id="pmReceipt" title="Rincian reservasi"><i class="fa-solid fa-receipt"></i></div>
        </div>
        <div class="pm-timer" id="pmTimer">
            <span><i class="dot"></i>Sisa:<span class="cd" id="pmCd">--:--:--</span></span>
            <span><i class="fa-regular fa-calendar"></i> Jatuh Tempo: <b id="pmDue">-</b></span>
        </div>

        <div class="pm-body">
            <!-- TAMPILAN BAYAR -->
            <div id="viewPay">
                <div class="pm-card">
                    <div class="pm-vhead">
                        <span class="pm-vcode" id="pmVcode">-</span>
                        <span class="pm-vname" id="pmVname">-</span>
                        <span class="pm-auto"><i class="fa-solid fa-bolt"></i> Otomatis</span>
                    </div>
                    <div id="pmPayBox"></div>
                    <div class="pm-box solid">
                        <div class="pm-total-wrap" style="flex:1;">
                            <small>Total Tagihan</small>
                            <div class="big dark" id="pmTotal">-</div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:12px;color:#0b6b4b;font-weight:600;margin-bottom:8px;">PPN 11% Inc.</div>
                            <button type="button" class="pm-copy" id="pmCopyTotal"><i class="fa-regular fa-copy"></i> Salin</button>
                        </div>
                    </div>
                </div>

                <div class="pm-lab" id="pmLab">
                    <div class="pm-lab-top" id="pmLabTop">
                        <strong><i class="fa-solid fa-flask"></i> <span id="pmLabName">-</span></strong>
                        <span>Detail <i class="fa-solid fa-chevron-down" style="font-size:11px;"></i></span>
                    </div>
                    <div class="pm-lab-detail" id="pmLabDetail"></div>
                </div>

                <div class="pm-how">
                    <div class="pm-how-top">
                        <h4><i class="fa-solid fa-book-open"></i> Cara Pembayaran Cepat</h4>
                        <div class="pm-tabs" id="pmTabs"></div>
                    </div>
                    <div id="pmSteps"></div>
                </div>

                <button type="button" class="pm-check" id="pmCheck"><i class="fa-solid fa-rotate"></i> <span id="pmCheckText">Cek Status Pembayaran</span></button>
                <div class="pm-msg" id="pmMsg"></div>
            </div>

            <!-- TAMPILAN LUNAS -->
            <div id="viewDone" style="display:none;">
                <div class="pm-done">
                    <div class="ic"><i class="fa-solid fa-check"></i></div>
                    <h3>Pembayaran Berhasil</h3>
                    <p>Reservasi Anda sudah terkonfirmasi. E-Tiket masuk lab dapat dilihat di halaman riwayat reservasi.</p>
                    <div class="code" id="dCode">-</div>
                    <div class="rows">
                        <div><span>No. Invoice</span><b id="dInv">-</b></div>
                        <div><span>Kanal Pembayaran</span><b id="dVendor">-</b></div>
                        <div><span>Total Dibayar</span><b id="dTotal">-</b></div>
                    </div>
                    <div class="btns">
                        <a href="#" class="p" id="dRiwayat">Lihat Riwayat Reservasi</a>
                        <a href="#" class="s" id="dHome">Kembali ke Beranda</a>
                    </div>
                </div>
            </div>

            <div class="pm-foot">
                <a href="tel:+62315992785"><i class="fa-solid fa-phone"></i> (031) 599-2785</a>
                <a href="#" class="wa" id="pmWa"><i class="fa-regular fa-comment-dots"></i> WhatsApp Helpdesk</a>
                <a href="#" id="pmDash"><i class="fa-solid fa-house"></i> Dashboard</a>
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
    var $ = function (id) { return document.getElementById(id); };
    var rupiah = function (n) { return 'Rp ' + Math.round(n).toLocaleString('id-ID'); };
    var esc = function (t) { return String(t).replace(/[&<>"]/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]; }); };

    /* ================= POPUP INSTRUKSI PEMBAYARAN ================= */
    var cdTimer = null;

    function copyText(text, btn) {
        var done = function () {
            var old = btn.innerHTML;
            btn.classList.add('ok'); btn.innerHTML = '<i class="fa-solid fa-check"></i> Tersalin';
            setTimeout(function () { btn.classList.remove('ok'); btn.innerHTML = old; }, 1500);
        };
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(done, done);
        } else {
            var ta = document.createElement('textarea'); ta.value = text; ta.style.position = 'fixed'; ta.style.opacity = '0';
            document.body.appendChild(ta); ta.select();
            try { document.execCommand('copy'); } catch (e) {}
            document.body.removeChild(ta); done();
        }
    }

    function dueLabel(iso) {
        var d = new Date(iso);
        if (isNaN(d.getTime())) return '-';
        var parts = {};
        new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit', hour12: false, timeZone: 'Asia/Jakarta' })
            .formatToParts(d).forEach(function (x) { parts[x.type] = x.value; });
        return parts.day + ' ' + parts.month + ', ' + parts.hour + '.' + parts.minute + ' WIB';
    }

    function howTabs(d, totalText) {
        var kind = d.pay.kind, code = d.vendor.code, va = d.pay.code, merchant = d.vendor.merchant;
        var vaTxt = '<code>' + esc(va.replace(/\s+/g, '')) + '</code>';
        if (kind === 'qris') {
            return [{ name: 'Scan QR', steps: [
                'Buka aplikasi <b>e-wallet</b> atau <b>mobile banking</b> yang mendukung QRIS.',
                'Pilih menu <b>Scan / Bayar</b> lalu arahkan kamera ke kode QR di atas.',
                'Pastikan nominal <b>' + esc(totalText) + '</b> sesuai > Masukkan PIN, selesai.'
            ] }];
        }
        if (kind === 'simponi') {
            return [{ name: 'Transfer', steps: [
                'Buka aplikasi mobile banking atau datang ke teller bank penerima <b>Kas BLU</b>.',
                'Pilih <b>Pembayaran PNBP / Billing</b> > Input kode billing ' + vaTxt + '.',
                'Konfirmasi tagihan <b>' + esc(totalText) + '</b> > Simpan bukti pembayaran untuk SPJ.'
            ] }];
        }
        var names = {
            MANDIRI: { app: "Livin' by Mandiri", tab: "Livin'", atm: 'ATM Mandiri', bank: 'Bank Mandiri' },
            BCA:     { app: 'BCA mobile', tab: 'BCA mobile', atm: 'ATM BCA', bank: 'Bank BCA' },
            BNI:     { app: 'BNI Mobile Banking', tab: 'BNI Mobile', atm: 'ATM BNI', bank: 'Bank BNI' },
            BRIVA:   { app: 'BRImo', tab: 'BRImo', atm: 'ATM BRI', bank: 'Bank BRI' }
        };
        var n = names[code] || { app: 'aplikasi mobile banking', tab: 'Mobile', atm: 'ATM', bank: d.vendor.name };
        var biller = 'PALAPA' + (merchant ? ' (' + esc(merchant) + ')' : '');
        return [
            { name: n.tab, steps: [
                'Buka <b>' + esc(n.app) + '</b> > Pilih menu <b>Bayar</b>.',
                'Cari biller <b>' + biller + '</b> > Input VA ' + vaTxt + '.',
                'Konfirmasi tagihan <b>' + esc(totalText) + '</b> > Masukkan PIN, selesai.'
            ] },
            { name: 'ATM', steps: [
                'Masukkan kartu dan PIN pada <b>' + esc(n.atm) + '</b>.',
                'Pilih menu <b>Bayar / Virtual Account</b>.',
                'Input nomor VA ' + vaTxt + ' > Konfirmasi tagihan <b>' + esc(totalText) + '</b>.'
            ] },
            { name: 'Bank Lain', steps: [
                'Buka aplikasi bank Anda > Pilih <b>Transfer ke Bank Lain</b>.',
                'Pilih bank tujuan <b>' + esc(n.bank) + '</b> > Input nomor VA ' + vaTxt + '.',
                'Pastikan nominal <b>' + esc(totalText) + '</b> sesuai > Selesaikan transaksi.'
            ] }
        ];
    }

    function setMsg(type, text) {
        var m = $('pmMsg');
        m.className = 'pm-msg' + (type ? ' ' + type : '');
        m.textContent = text || '';
    }

    function showPaid(d) {
        $('viewPay').style.display = 'none';
        $('viewDone').style.display = 'block';
        $('pmTimer').style.display = 'none';
        $('pmTitle').textContent = 'Pembayaran Berhasil';
        $('dCode').textContent = d.booking_code;
        $('dInv').textContent = d.invoice_number;
        $('dVendor').textContent = d.vendor.name;
        $('dTotal').textContent = rupiah(d.grand_total);
        if (cdTimer) { clearInterval(cdTimer); cdTimer = null; }
        try { sessionStorage.removeItem('palapa_payment'); } catch (e) {}
    }

    function showExpired() {
        $('pmCd').textContent = '00:00:00';
        $('pmCheck').disabled = true;
        setMsg('err', 'Waktu pembayaran telah habis dan slot dilepas kembali. Silakan buat reservasi baru.');
        if (cdTimer) { clearInterval(cdTimer); cdTimer = null; }
        try { sessionStorage.removeItem('palapa_payment'); } catch (e) {}
    }

    function showPayment(d) {
        var totalText = rupiah(d.grand_total);
        $('overlay').classList.add('show');
        $('pmBack').href = P.urls.riwayat; $('pmDash').href = P.urls.riwayat; $('pmWa').href = P.urls.help;
        $('dRiwayat').href = P.urls.riwayat; $('dHome').href = P.urls.home;

        $('pmCode').textContent = '#' + d.booking_code;
        $('pmVcode').textContent = d.vendor.code.replace('_INSTANT', '');
        var title = d.pay.kind === 'qris' ? 'QRIS PALAPA' : (d.pay.kind === 'simponi' ? 'Billing SIMPONI PALAPA' : 'VA PALAPA');
        $('pmVname').innerHTML = esc(title) + (d.vendor.merchant ? '<span>(' + esc(d.vendor.merchant) + ')</span>' : '');
        $('pmDue').textContent = dueLabel(d.expired_at);
        $('pmTotal').textContent = totalText;
        $('pmCopyTotal').onclick = function () { copyText(String(Math.round(d.grand_total)), this); };

        // Kotak nomor VA / kode billing / QR
        var box = '';
        if (d.pay.kind === 'qris') {
            box = '<div class="pm-qr">' + (d.pay.qr_svg ? d.pay.qr_svg : '<i class="fa-solid fa-qrcode" style="font-size:90px;color:#334155;"></i>') +
                '<div class="qrc">' + esc(d.pay.code) + '</div></div>';
        } else {
            var grouped = d.pay.code.replace(/(\d{4})(?=\d)/g, '$1 ');
            box = '<div class="pm-box dash"><div><small>' + esc(d.pay.label) + '</small><div class="big">' + esc(grouped) + '</div></div>' +
                '<button type="button" class="pm-copy" id="pmCopyCode"><i class="fa-regular fa-copy"></i> Salin</button></div>';
        }
        $('pmPayBox').innerHTML = box;
        if ($('pmCopyCode')) $('pmCopyCode').onclick = function () { copyText(d.pay.code, this); };

        // Detail reservasi
        $('pmLabName').textContent = d.lab_name || P.lab.name;
        $('pmLabDetail').innerHTML =
            '<div><b>Instansi:</b> ' + esc(d.institution || '-') + '</div>' +
            '<div><b>Jadwal:</b> ' + esc(d.date_label || '-') + '</div>' +
            '<div><b>Sesi:</b> ' + esc(d.shift_label || '-') + '</div>' +
            '<div><b>Peserta:</b> ' + esc(d.participants || '-') + '</div>' +
            '<div><b>No. Invoice:</b> ' + esc(d.invoice_number) + '</div>';
        $('pmLabTop').onclick = function () { $('pmLab').classList.toggle('open'); };
        $('pmReceipt').onclick = function () { $('pmLab').classList.toggle('open'); };

        // Cara pembayaran
        var tabs = howTabs(d, totalText);
        function drawTab(i) {
            $('pmTabs').innerHTML = tabs.length > 1 ? tabs.map(function (t, k) {
                return '<button type="button" class="pm-tab' + (k === i ? ' on' : '') + '" data-k="' + k + '">' + esc(t.name) + '</button>';
            }).join('') : '';
            $('pmSteps').innerHTML = tabs[i].steps.map(function (st, k) {
                return '<div class="pm-step"><span class="n">' + (k + 1) + '</span><div>' + st + '</div></div>';
            }).join('');
        }
        $('pmTabs').onclick = function (e) { var b = e.target.closest('.pm-tab'); if (b) drawTab(parseInt(b.getAttribute('data-k'), 10)); };
        drawTab(0);

        setMsg('', '');
        if (d.simulation) setMsg('sim', 'Mode simulasi aktif (APP_DEBUG): menekan tombol Cek Status di atas akan menandai pembayaran sebagai lunas.');

        // Hitung mundur
        var end = new Date(d.expired_at).getTime();
        var tick = function () {
            var left = end - Date.now();
            if (left <= 0) { showExpired(); return; }
            var h = Math.floor(left / 3600000), m = Math.floor(left % 3600000 / 60000), sc = Math.floor(left % 60000 / 1000);
            var z = function (n) { return (n < 10 ? '0' : '') + n; };
            $('pmCd').textContent = z(h) + ':' + z(m) + ':' + z(sc);
        };
        if (cdTimer) clearInterval(cdTimer);
        tick(); cdTimer = setInterval(tick, 1000);

        // Cek status pembayaran
        $('pmCheck').onclick = function () {
            var btn = $('pmCheck');
            btn.disabled = true; $('pmCheckText').textContent = 'Memeriksa...';
            fetch(P.urls.bookings + '/' + encodeURIComponent(d.booking_code) + '/check-payment', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': P.csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            }).then(function (res) {
                return res.json().catch(function () { return {}; }).then(function (j) { return { ok: res.ok, status: res.status, json: j }; });
            }).then(function (r) {
                btn.disabled = false; $('pmCheckText').textContent = 'Cek Status Pembayaran';
                if (r.json && r.json.status === 'paid') { showPaid(d); return; }
                if (r.json && r.json.status === 'expired') { showExpired(); return; }
                if (r.json && r.json.status === 'unpaid') { setMsg('info', 'Pembayaran belum kami terima. Selesaikan pembayaran lalu cek kembali dalam beberapa saat.'); return; }
                var msg = (r.json && r.json.message) || 'Gagal memeriksa status pembayaran.';
                if (r.status === 419) msg = 'Sesi halaman kedaluwarsa. Muat ulang halaman lalu coba lagi.';
                setMsg('err', msg);
            }).catch(function () {
                btn.disabled = false; $('pmCheckText').textContent = 'Cek Status Pembayaran';
                setMsg('err', 'Tidak dapat terhubung ke server. Coba lagi.');
            });
        };
    }

    /* ---------- Data dari Tahap 1 & 2 ---------- */
    var D = null;
    try { D = JSON.parse(sessionStorage.getItem('palapa_booking') || 'null'); } catch (e) {}
    var hasDraft = !!(D && D.lab_id === P.lab.id && D.reservation_date);

    var PAY = null;
    try { PAY = JSON.parse(sessionStorage.getItem('palapa_payment') || 'null'); } catch (e) {}
    if (PAY && PAY.lab_id !== P.lab.id) PAY = null;

    if (hasDraft) {
        // Ada pesanan baru yang sedang dibuat: buang tagihan lama dari penyimpanan browser
        try { sessionStorage.removeItem('palapa_payment'); } catch (e) {}
        PAY = null;
    } else if (PAY) {
        // Halaman dimuat ulang setelah reservasi dibuat: tampilkan kembali instruksi pembayaran
        showPayment(PAY);
        return;
    }

    if (!hasDraft) {
        if (P.urls.step1) window.location.replace(P.urls.step1);
        return;
    }

    if (!D.pricing) {
        if (P.urls.step2) window.location.replace(P.urls.step2);
        return;
    }

    $('lnkEdit').href = P.urls.step1 || '#';
    $('lnkBack').href = P.urls.step2 || '#';

    var SHIFT_INFO = {
        'Pagi':  { title: 'Sesi 1: Pagi',  short: 'Sesi 1', clock: '08.00 - 12.00', hours: 4, rent: 'Sesi Pagi' },
        'Siang': { title: 'Sesi 2: Siang', short: 'Sesi 2', clock: '13.00 - 17.00', hours: 4, rent: 'Sesi Siang' },
        'Penuh': { title: 'Sesi Penuh',    short: 'Sesi Penuh', clock: '08.00 - 17.00', hours: 8, rent: 'Sesi Penuh' }
    };
    var SH = SHIFT_INFO[(D.session_time || '').split(' ')[0]] || SHIFT_INFO.Pagi;
    var MONTHS = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    var DAYS = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    var dp = D.reservation_date.split('-');
    var dt = new Date(parseInt(dp[0], 10), parseInt(dp[1], 10) - 1, parseInt(dp[2], 10));
    var dateLabel = parseInt(dp[2], 10) + ' ' + MONTHS[dt.getMonth()] + ' ' + dp[0];

    var N = parseInt(D.participants, 10) || 1;
    var UNIT = D.institution_type === 'sekolah_sma_smk' ? 'Siswa' : 'Peserta';
    var TABLES = Math.max(1, Math.ceil(N / 2));
    var DISC_NOTE = { sekolah_sma_smk: 'Kemitraan Sekolah SMA/SMK', lembaga_riset: 'Kemitraan Lembaga Riset', perusahaan_swasta: 'Kemitraan Industri', komunitas_publik: 'Kemitraan Komunitas' };
    var PR = D.pricing;

    /* ---------- Ringkasan verifikasi ---------- */
    $('rvCode').textContent = '#DRAF-' + dp.join('');
    $('rvInst').textContent = D.institution_name || '-';
    $('rvInstSub').textContent = N + ' ' + UNIT + ' Praktikan';
    $('rvDate').textContent = DAYS[dt.getDay()] + ', ' + dateLabel;
    $('rvShift').textContent = SH.title + ' (' + SH.clock + ' WIB)';
    $('rvPic').textContent = D.pic_name || '-';
    $('rvPicSub').textContent = (D.whatsapp || '-') + ' \u2022 ' + (D.email || '-');
    $('rvFile').textContent = D.proposal_file_name || 'Belum ada dokumen';

    var chips = [];
    P.included.forEach(function (n) { chips.push(TABLES + ' ' + n + ' (Paket Dasar)'); });
    (D.equipments || []).forEach(function (x) { chips.push(x.quantity + ' Unit ' + x.name); });
    if (D.technician) chips.push('1 Pranata Lab Terlisensi BNSP');
    if (D.reagen) chips.push('Bahan Reagen Dasar & Aquabidest');
    if (D.limbah) chips.push('Kit Limbah B3 Khusus');
    if (!chips.length) chips.push('Fasilitas ruangan standar');
    $('rvChips').innerHTML = chips.map(function (c) { return '<span class="chip">' + esc(c) + '</span>'; }).join('');

    /* ---------- Kanal pembayaran ---------- */
    var vendorId = '';
    var cats = {
        A: { title: 'Kategori A: Virtual Account Bank', list: [] },
        B: { title: 'Kategori B: QRIS & Dompet Digital', list: [] },
        C: { title: 'Kategori C: Penagihan Resmi Instansi / SPJ Hibah', list: [] }
    };
    P.vendors.forEach(function (v) {
        if (v.code.indexOf('SIMPONI') > -1) cats.C.list.push(v);
        else if (v.type === 'qris_instant' || v.type === 'ewallet') cats.B.list.push(v);
        else cats.A.list.push(v);
    });
    if (cats.A.list.length) vendorId = cats.A.list[0].id;
    else if (P.vendors.length) vendorId = P.vendors[0].id;

    function vendorHtml(v, idx, key) {
        var badge = '', desc = '';
        if (key === 'A') {
            badge = idx === 0 ? '<span class="v-badge">Instan</span>' : '';
            desc = idx === 0 ? 'Nomor VA otomatis diterbitkan instan setelah konfirmasi' : 'Verifikasi otomatis 24 jam';
        } else if (key === 'B') {
            badge = '<span class="v-badge light">Scan Instan</span>';
            desc = 'Dukung GoPay, OVO, ShopeePay, DANA, LinkAja, BCA Mobile';
        } else {
            desc = 'Khusus SPJ Dana BOS, Hibah Riset BIMA / LPDP, atau APBD Dinas Pendidikan. Sistem akan menerbitkan Invoice Penagihan Resmi dengan stempel digital untuk pencairan bendahara instansi.';
        }
        var codeCls = v.code === 'BNI' ? 'orange' : (key === 'B' ? 'dark' : '');
        var short = v.code.replace('_INSTANT', '');
        return '<div class="vendor' + (vendorId === v.id ? ' on' : '') + '" data-id="' + esc(v.id) + '"><span class="rd"></span>' +
            '<div><div class="vendor-name">' + esc(v.name) + ' ' + badge + '</div><div class="vendor-desc">' + desc + '</div></div>' +
            '<span class="v-code ' + codeCls + '">' + esc(short) + '</span></div>';
    }
    function renderVendors() {
        var html = '';
        ['A', 'B', 'C'].forEach(function (k) {
            var c = cats[k];
            if (!c.list.length) return;
            var right = '', pill = '';
            if (k === 'A') pill = '<span class="pill">Otomatis &amp; Real-time Verifikasi</span>';
            if (k === 'B') {
                var free = c.list.every(function (v) { return v.fee <= 0; });
                right = '<span class="note green">' + (free ? 'Bebas Biaya (0%)' : 'Biaya layanan berlaku') + '</span>';
            }
            if (k === 'C') right = '<span class="note">Surat Tagihan Resmi</span>';
            html += '<div class="cat"><div class="cat-head"><span>' + esc(c.title) + pill + '</span>' + right + '</div><div class="cat-body">' +
                c.list.map(function (v, i) { return vendorHtml(v, i, k); }).join('') + '</div></div>';
        });
        if (!html) html = '<div class="cat"><div class="cat-empty">Belum ada kanal pembayaran aktif. Hubungi pengelola laboratorium.</div></div>';
        $('vendorCats').innerHTML = html;
    }
    $('vendorCats').addEventListener('click', function (e) {
        var v = e.target.closest('.vendor');
        if (!v) return;
        vendorId = v.getAttribute('data-id');
        renderVendors(); renderSummary(); updateBtn();
    });

    /* ---------- Ringkasan biaya ---------- */
    function selectedVendor() { return P.vendors.filter(function (v) { return v.id === vendorId; })[0] || null; }
    function renderSummary() {
        $('sideWho').textContent = (D.institution_name || '-') + ' (' + N + ' ' + UNIT + ')';
        $('sidePill').textContent = SH.short + ': ' + SH.clock;
        $('discNote').textContent = DISC_NOTE[D.institution_type] || 'Kemitraan Institusi';

        var lines = [];
        var incText = P.included.length ? 'Termasuk ' + TABLES + ' ' + P.included.join(', ') : 'Fasilitas ruangan standar';
        lines.push({ l: 'Sewa Lab ' + SH.rent, s: incText, r: PR.rent });
        (D.equipments || []).forEach(function (x) {
            lines.push({ l: x.name + ' (' + x.quantity + ' Unit)', s: '@ ' + rupiah(x.unit_price) + ' / unit', r: x.subtotal });
        });
        if (D.technician) lines.push({ l: '1 Pranata Lab Terlisensi BNSP', s: 'Pendampingan ' + SH.hours + ' Jam Penuh', r: D.technician_fee });
        if (D.reagen) lines.push({ l: 'Bahan Reagen Dasar & Aquabidest', s: 'Batch Rombongan ' + N + ' ' + UNIT, r: 100000 });
        if (D.limbah) lines.push({ l: 'Kit Limbah B3 Khusus', s: 'Per sesi praktikum', r: 50000 * (SH.hours / 4) });
        var v = selectedVendor();
        var fee = v ? v.fee : 0;
        if (fee > 0) lines.push({ l: 'Biaya Layanan ' + v.name, s: 'Biaya admin kanal pembayaran', r: fee });

        $('sumLines').innerHTML = lines.map(function (x) {
            return '<div class="sum-row"><div class="l">' + esc(x.l) + '<small>' + esc(x.s) + '</small></div><div class="r">' + rupiah(x.r) + '</div></div>';
        }).join('');
        $('discVal').textContent = '-' + rupiah(PR.discount);
        $('depVal').textContent = rupiah(PR.deposit);
        $('totalVal').textContent = rupiah(PR.total + fee);
    }

    /* ---------- Persetujuan & tombol bayar ---------- */
    var agreed = false, submitting = false;
    $('agree').addEventListener('click', function () {
        agreed = !agreed;
        $('agree').classList.toggle('on', agreed);
        $('agree').classList.remove('err');
        updateBtn();
    });
    function updateBtn() { $('btnPay').disabled = !(vendorId && agreed) || submitting; }

    /* ---------- Kirim ke server ---------- */
    function idbGet(cb) {
        try {
            var r = indexedDB.open('palapa', 1);
            r.onupgradeneeded = function () { r.result.createObjectStore('files'); };
            r.onerror = function () { cb(null); };
            r.onsuccess = function () {
                try {
                    var q = r.result.transaction('files', 'readonly').objectStore('files').get('proposal');
                    q.onsuccess = function () { cb(q.result || null); };
                    q.onerror = function () { cb(null); };
                } catch (e) { cb(null); }
            };
        } catch (e) { cb(null); }
    }
    function idbClear() {
        try {
            var r = indexedDB.open('palapa', 1);
            r.onsuccess = function () { try { r.result.transaction('files', 'readwrite').objectStore('files').clear(); } catch (e) {} };
        } catch (e) {}
    }
    function showError(msg) { var b = $('payError'); b.textContent = msg; b.style.display = 'block'; }

    $('btnPay').addEventListener('click', function () {
        if (submitting || !vendorId) return;
        if (!agreed) { $('agree').classList.add('err'); return; }
        submitting = true; updateBtn();
        $('payError').style.display = 'none';
        $('btnPayText').textContent = 'Memproses...';

        idbGet(function (blob) {
            var payload = {
                lab_id: P.lab.id,
                reservation_date: D.reservation_date,
                session_time: D.session_time,
                total_participants: N,
                technician: !!D.technician,
                reagen: !!D.reagen,
                limbah: !!D.limbah,
                equipments: (D.equipments || []).map(function (x) { return { equipment_id: x.equipment_id, quantity: x.quantity }; }),
                vendor_id: vendorId
            };
            var fd = new FormData();
            fd.append('payload', JSON.stringify(payload));
            if (blob) fd.append('proposal', blob, D.proposal_file_name || 'proposal.pdf');

            fetch(P.urls.store, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': P.csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
                body: fd
            }).then(function (res) {
                return res.json().catch(function () { return {}; }).then(function (j) { return { ok: res.ok, status: res.status, json: j }; });
            }).then(function (r) {
                if (!r.ok || !r.json || r.json.status !== 'success') {
                    var msg = (r.json && r.json.message) || ('Gagal mengirim reservasi (kode ' + r.status + ').');
                    if (r.status === 419) msg = 'Sesi halaman sudah kedaluwarsa. Muat ulang halaman lalu coba lagi.';
                    if (r.status === 401) msg = 'Sesi login berakhir. Silakan login kembali.';
                    showError(msg);
                    submitting = false; $('btnPayText').textContent = 'Bayar dan Terbitkan Kode Reservasi'; updateBtn();
                    return;
                }
                var d = r.json.data;
                d.lab_id = P.lab.id;
                d.lab_name = P.lab.name;
                d.institution = D.institution_name;
                d.date_label = DAYS[dt.getDay()] + ', ' + dateLabel;
                d.shift_label = SH.title + ' (' + SH.clock + ' WIB)';
                d.participants = N + ' ' + UNIT;
                try {
                    sessionStorage.setItem('palapa_payment', JSON.stringify(d));
                    sessionStorage.removeItem('palapa_booking');
                } catch (e) {}
                idbClear();
                submitting = false; $('btnPayText').textContent = 'Bayar dan Terbitkan Kode Reservasi';
                showPayment(d);
            }).catch(function () {
                showError('Tidak dapat terhubung ke server. Periksa koneksi Anda lalu coba lagi.');
                submitting = false; $('btnPayText').textContent = 'Bayar dan Terbitkan Kode Reservasi'; updateBtn();
            });
        });
    });

    renderVendors();
    renderSummary();
    updateBtn();
})();
</script>

</body>
</html>
