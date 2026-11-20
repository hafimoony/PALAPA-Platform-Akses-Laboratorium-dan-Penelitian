@extends('layouts.app')
@section('title', 'Live Chat Operator Laboratorium - PALAPA')

@push('styles')
<style>
    /* Full height workspace layout */
    .app-main {
        padding-top: 0 !important;
    }
    .chat-operator-layout {
        display: flex;
        height: calc(100vh - 70px);
        margin: -20px -15px -20px -15px;
        background: #ffffff;
        overflow: hidden;
    }

    /* --- KOLOM 1: INBOX / DAFTAR CHAT PENELITI --- */
    .chat-list-panel {
        width: 320px;
        min-width: 300px;
        border-right: 1px solid #E2E8F0;
        display: flex;
        flex-direction: column;
        background: #ffffff;
    }
    .panel-search-box {
        padding: 16px 16px 12px 16px;
        border-bottom: 1px solid #F1F5F9;
    }
    .search-input-wrap {
        position: relative;
    }
    .search-input-wrap input {
        width: 100%;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        padding: 8px 14px 8px 36px;
        font-size: 12.5px;
        outline: none;
        transition: all 0.2s;
    }
    .search-input-wrap input:focus {
        border-color: #334EAC;
        background: #ffffff;
    }
    .search-input-wrap i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94A3B8;
        font-size: 13px;
    }
    .filter-tabs {
        display: flex;
        gap: 6px;
        margin-top: 10px;
    }
    .filter-tab-pill {
        border: none;
        background: #F1F5F9;
        color: #475569;
        font-size: 11px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 999px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .filter-tab-pill.active, .filter-tab-pill:hover {
        background: #091F5B;
        color: #ffffff;
    }

    .chat-scroll-list {
        flex: 1;
        overflow-y: auto;
    }
    .chat-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        border-bottom: 1px solid #F8FAFC;
        cursor: pointer;
        transition: background 0.15s;
    }
    .chat-item:hover {
        background: #F8FAFC;
    }
    .chat-item.active {
        background: #EFF6FF;
        border-left: 4px solid #091F5B;
    }
    .user-avatar-badge {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #EBF3FF;
        color: #091F5B;
        font-weight: 700;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        position: relative;
    }
    .user-avatar-badge.online::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 11px;
        height: 11px;
        background: #10B981;
        border: 2px solid #ffffff;
        border-radius: 50%;
    }
    .chat-item-body {
        flex: 1;
        min-width: 0;
    }
    .chat-item-head {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-bottom: 3px;
    }
    .chat-name {
        font-size: 13.5px;
        font-weight: 700;
        color: #0F172A;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .chat-time {
        font-size: 11px;
        color: #94A3B8;
        font-weight: 500;
    }
    .chat-meta-inst {
        font-size: 11px;
        color: #3B82F6;
        font-weight: 600;
        margin-bottom: 2px;
    }
    .chat-preview {
        font-size: 12px;
        color: #64748B;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* --- KOLOM 2: RUANG PERCAKAPAN AKTIF (TENGAH) --- */
    .chat-convo-panel {
        flex: 1;
        display: flex;
        flex-direction: column;
        border-right: 1px solid #E2E8F0;
        background: #FAFCFE;
        min-width: 0;
    }
    .convo-header {
        padding: 12px 20px;
        background: #ffffff;
        border-bottom: 1px solid #E2E8F0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
    }
    .convo-user-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .convo-name {
        font-size: 15px;
        font-weight: 800;
        color: #091F5B;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .convo-role-badge {
        background: #EFF6FF;
        color: #1D4ED8;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 999px;
        border: 1px solid #DBEAFE;
    }
    .convo-sub {
        font-size: 11.5px;
        color: #10B981;
        font-weight: 600;
    }
    .convo-sub span {
        color: #64748B;
        font-weight: 400;
    }
    .convo-actions {
        display: flex;
        gap: 8px;
    }
    .convo-action-btn {
        background: transparent;
        border: none;
        color: #64748B;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .convo-action-btn:hover {
        background: #F1F5F9;
        color: #091F5B;
    }

    .convo-messages-area {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 14px;
        background-color: #F8FAFD;
        background-image: radial-gradient(#CBD5E1 0.7px, transparent 0.7px);
        background-size: 18px 18px;
    }

    /* Message styling persis Image 2 */
    .c-msg-row {
        display: flex;
        width: 100%;
    }
    .c-msg-row.client {
        justify-content: flex-start;
    }
    .c-msg-row.staff {
        justify-content: flex-end;
    }

    .c-bubble {
        max-width: 72%;
        padding: 12px 16px;
        border-radius: 14px;
        font-size: 13px;
        line-height: 1.5;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        position: relative;
    }
    .c-bubble.client {
        background: #ffffff;
        color: #1E293B;
        border: 1px solid #E2E8F0;
        border-bottom-left-radius: 3px;
    }
    .c-bubble.staff {
        background: #0047BA;
        color: #ffffff;
        border-bottom-right-radius: 3px;
    }
    .c-bubble-title {
        font-size: 11px;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .c-bubble.client .c-bubble-title { color: #1D4ED8; }
    .c-bubble.staff .c-bubble-title { color: #93C5FD; }

    .c-bubble-time {
        font-size: 10px;
        text-align: right;
        margin-top: 6px;
    }
    .c-bubble.client .c-bubble-time { color: #94A3B8; }
    .c-bubble.staff .c-bubble-time { color: #BFDBFE; }

    /* Rekomendasi Alat Box in Staff Convo */
    .staff-rekom-box {
        background: #ffffff;
        border: 1px solid #DBEAFE;
        border-radius: 12px;
        padding: 14px;
        margin-top: 10px;
        color: #1E293B;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.08);
    }
    .staff-rekom-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    .staff-rekom-header strong {
        color: #1D4ED8;
        font-size: 11px;
        letter-spacing: 0.5px;
    }
    .staff-rekom-title {
        font-size: 14px;
        font-weight: 800;
        color: #091F5B;
    }
    .staff-rekom-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        background: #F8FAFC;
        padding: 8px 12px;
        border-radius: 8px;
        margin: 8px 0 12px 0;
        font-size: 11px;
    }
    .staff-rekom-grid span { color: #64748B; }
    .staff-rekom-grid strong { color: #091F5B; }
    .staff-rekom-btns {
        display: flex;
        gap: 8px;
    }
    .staff-rekom-btns button {
        flex: 1;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-rekom-detail {
        background: #ffffff;
        border: 1px solid #CBD5E1;
        color: #334155;
    }
    .btn-rekom-jadwal {
        background: #0047BA;
        border: 1px solid #0047BA;
        color: #ffffff;
    }

    /* Quick Reply Bar (Persis Image 2) */
    .quick-replies-bar {
        background: #ffffff;
        border-top: 1px solid #F1F5F9;
        padding: 8px 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        overflow-x: auto;
    }
    .quick-label {
        font-size: 10px;
        font-weight: 800;
        color: #3B82F6;
        text-transform: uppercase;
        flex-shrink: 0;
    }
    .quick-pill {
        background: #F1F5F9;
        border: 1px solid #E2E8F0;
        color: #334155;
        font-size: 11.5px;
        font-weight: 500;
        padding: 4px 12px;
        border-radius: 999px;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s;
    }
    .quick-pill:hover {
        background: #091F5B;
        color: #ffffff;
        border-color: #091F5B;
    }

    /* Convo Input */
    .convo-input-bar {
        background: #ffffff;
        border-top: 1px solid #E2E8F0;
        padding: 12px 18px;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }
    .convo-input-field {
        flex: 1;
        background: #F8FAFC;
        border: 1px solid #CBD5E1;
        border-radius: 10px;
        padding: 10px 16px;
        font-size: 13px;
        font-family: inherit;
        outline: none;
    }
    .convo-input-field:focus {
        border-color: #0047BA;
        background: #ffffff;
    }
    .btn-staff-send {
        background: #0047BA;
        color: #ffffff;
        border: none;
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 12.5px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: background 0.2s;
    }
    .btn-staff-send:hover {
        background: #091F5B;
    }

    /* --- KOLOM 3: DETAIL PROFIL & STATUS SAMPEL (KANAN) --- */
    .client-detail-panel {
        width: 320px;
        min-width: 290px;
        background: #ffffff;
        overflow-y: auto;
        padding: 20px 18px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .profile-card-top {
        text-align: center;
        border-bottom: 1px solid #F1F5F9;
        padding-bottom: 16px;
    }
    .large-user-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #091F5B, #334EAC);
        color: #ffffff;
        font-size: 20px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px auto;
        box-shadow: 0 4px 12px rgba(9, 31, 91, 0.15);
    }
    .p-user-name {
        font-size: 16px;
        font-weight: 800;
        color: #091F5B;
    }
    .p-user-inst {
        font-size: 12px;
        color: #475569;
        margin-top: 2px;
    }
    .p-user-nip {
        font-size: 11px;
        color: #94A3B8;
        margin-top: 2px;
    }
    .p-badges-row {
        display: flex;
        justify-content: center;
        gap: 6px;
        margin-top: 10px;
    }
    .p-pill {
        font-size: 10px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 999px;
    }
    .p-pill-blue { background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; }
    .p-pill-green { background: #ECFDF5; color: #047857; border: 1px solid #A7F3D0; }

    /* Box Info Registrasi */
    .reg-info-box {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 14px;
    }
    .reg-item {
        margin-bottom: 10px;
    }
    .reg-item:last-child { margin-bottom: 0; }
    .reg-label {
        font-size: 10px;
        font-weight: 700;
        color: #64748B;
        text-transform: uppercase;
    }
    .reg-val {
        font-size: 12.5px;
        font-weight: 700;
        color: #0F172A;
        margin-top: 2px;
    }

    /* Catatan Internal Staf */
    .notes-box {
        background: #FFFBEB;
        border: 1px solid #FDE68A;
        border-radius: 12px;
        padding: 14px;
        font-size: 12px;
    }
    .notes-title {
        font-weight: 700;
        color: #92400E;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 6px;
    }
    .notes-body {
        color: #78350F;
        font-style: italic;
        line-height: 1.45;
    }
    .notes-author {
        font-size: 10.5px;
        color: #B45309;
        margin-top: 6px;
        display: flex;
        justify-content: space-between;
    }

    /* Kondisi Cleanroom & Sensor */
    .sensor-box {
        background: #F0FDF4;
        border: 1px solid #BBF7D0;
        border-radius: 12px;
        padding: 14px;
    }
    .sensor-title {
        font-size: 11px;
        font-weight: 700;
        color: #166534;
        text-transform: uppercase;
        margin-bottom: 8px;
    }
    .sensor-row {
        display: flex;
        justify-content: space-between;
        font-size: 11.5px;
        margin-bottom: 4px;
    }
    .sensor-row span { color: #374151; }
    .sensor-row strong { color: #166534; }

    /* Action buttons in right panel */
    .btn-side-action {
        width: 100%;
        background: #ffffff;
        border: 1px solid #CBD5E1;
        border-radius: 10px;
        padding: 9px 12px;
        font-size: 12px;
        font-weight: 600;
        color: #1E293B;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-side-action:hover {
        background: #F8FAFC;
        border-color: #94A3B8;
        color: #091F5B;
    }
</style>
@endpush

@section('content')
<div class="chat-operator-layout">

    <!-- 1. KOLOM KIRI: INBOX CHAT PENELITI -->
    <div class="chat-list-panel">
        <div class="panel-search-box">
            <div class="search-input-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Filter nama / instansi..." id="filterUserChat">
            </div>
            <div class="filter-tabs">
                <button class="filter-tab-pill active">Semua (4)</button>
                <button class="filter-tab-pill">Respon (1)</button>
                <button class="filter-tab-pill">Aktif (2)</button>
            </div>
        </div>

        <div class="chat-scroll-list">
            <!-- Item 1: Dr. Hendra (Active) -->
            <div class="chat-item active" onclick="selectChat('hendra')">
                <div class="user-avatar-badge online">DH</div>
                <div class="chat-item-body">
                    <div class="chat-item-head">
                        <div class="chat-name">Dr. Hendra S.</div>
                        <div class="chat-time">Baru</div>
                    </div>
                    <div class="chat-meta-inst">BRIN • GC-MS</div>
                    <div class="chat-preview">Belum ada percakapan baru</div>
                </div>
            </div>

            <!-- Item 2: Prof. Ratna Dewi -->
            <div class="chat-item" onclick="selectChat('ratna')">
                <div class="user-avatar-badge online">RD</div>
                <div class="chat-item-body">
                    <div class="chat-item-head">
                        <div class="chat-name">Prof. Ratna Dewi</div>
                        <div class="chat-time">Baru</div>
                    </div>
                    <div class="chat-meta-inst">ITB • Difraksi Sinar-X</div>
                    <div class="chat-preview">Menunggu respon staf...</div>
                </div>
            </div>

            <!-- Item 3: Ir. Bambang -->
            <div class="chat-item" onclick="selectChat('bambang')">
                <div class="user-avatar-badge">BP</div>
                <div class="chat-item-body">
                    <div class="chat-item-head">
                        <div class="chat-name">Ir. Bambang P.</div>
                        <div class="chat-time">Kemarin</div>
                    </div>
                    <div class="chat-meta-inst">UGM • SEM-EDX</div>
                    <div class="chat-preview">Sesi konsultasi lab...</div>
                </div>
            </div>

            <!-- Item 4: Dr. Ahmad Fauzi -->
            <div class="chat-item" onclick="selectChat('ahmad')">
                <div class="user-avatar-badge">AF</div>
                <div class="chat-item-body">
                    <div class="chat-item-head">
                        <div class="chat-name">Dr. Ahmad Fauzi</div>
                        <div class="chat-time">Kemarin</div>
                    </div>
                    <div class="chat-meta-inst">UNAIR • Spektroskopi</div>
                    <div class="chat-preview">Konsultasi instrumen...</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. KOLOM TENGAH: RUANG PERCAKAPAN AKTIF (BERSIH) -->
    <div class="chat-convo-panel">
        <div class="convo-header">
            <div class="convo-user-info">
                <div class="user-avatar-badge online">DH</div>
                <div>
                    <div class="convo-name">
                        <span id="headerUserName">Dr. Hendra S.</span>
                        <span class="convo-role-badge">PENELITI</span>
                    </div>
                    <div class="convo-sub">
                        ● Online <span>• BRIN (Riset Hayati)</span>
                    </div>
                </div>
            </div>

            <div class="convo-actions">
                <button class="convo-action-btn" title="Cari Pesan"><i class="fa-solid fa-magnifying-glass"></i></button>
                <button class="convo-action-btn" onclick="alert('Menghubungi nomor telepon resmi peneliti: 0812-9876-5432')" title="Telepon Peneliti"><i class="fa-solid fa-phone"></i></button>
                <button class="convo-action-btn" title="Opsi"><i class="fa-solid fa-ellipsis-vertical"></i></button>
            </div>
        </div>

        <div class="convo-messages-area" id="staffConvoArea">
            <div class="text-center my-1">
                <span class="badge bg-light text-muted border" style="font-size:10px;">Hari ini • Enkripsi End-to-End Lab</span>
            </div>

            <!-- Empty State Notice Staff -->
            <div id="staffEmptyNotice" class="text-center my-auto py-5" style="color: #64748B;">
                <div style="width: 54px; height: 54px; background: #EFF6FF; border-radius: 50%; color: #0047BA; display: inline-flex; align-items: center; justify-content: center; font-size: 22px; margin-bottom: 12px; box-shadow: 0 4px 12px rgba(0, 71, 186, 0.08);">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div style="font-weight: 700; color: #091F5B; font-size: 15px;">Ruang Konsultasi Peneliti Siap Digunakan</div>
                <div style="font-size: 12.5px; color: #64748B; max-width: 440px; margin: 6px auto 0 auto; line-height: 1.5;">
                    Belum ada obrolan dalam sesi ini. Anda dapat mengetik pesan balasan atau memilih opsi <strong>Balas Cepat</strong> di bawah untuk menyapa peneliti.
                </div>
            </div>
        </div>

        <!-- QUICK REPLY CHIPS (PERSIS GAMBAR 2) -->
        <div class="quick-replies-bar">
            <span class="quick-label"><i class="fa-solid fa-bolt me-1"></i> Balas Cepat:</span>
            <button type="button" class="quick-pill" onclick="insertQuickReply('Sampel fisik telah kami terima di loket registrasi dan sedang dalam tahap antrean sonikasi.')">Konfirmasi Terima Sampel</button>
            <button type="button" class="quick-pill" onclick="insertQuickReply('Mohon patuhi standar keselamatan cleanroom: wajib jas lab katun 100%, masker partikulat, dan sarung tangan nitril.')">Kirim Prosedur Keselamatan Lab</button>
            <button type="button" class="quick-pill" onclick="insertQuickReply('Mohon kirimkan lembar MSDS pelarut yang digunakan untuk melengkapi izin registrasi K3 laboratorium.')">Minta Data Tambahan</button>
        </div>

        <!-- CONVO INPUT -->
        <div class="convo-input-bar">
            <button type="button" class="convo-action-btn" onclick="alert('Lampirkan template SOP atau form verifikasi.')" title="Kirim Berkas SOP"><i class="fa-solid fa-paperclip"></i></button>
            <input type="text" id="staffReplyInput" class="convo-input-field" placeholder="Ketik pesan balasan untuk Dr. Hendra S...." onkeydown="if(event.key==='Enter') sendStaffReply()">
            <button type="button" class="btn-staff-send" onclick="sendStaffReply()">
                <i class="fa-solid fa-paper-plane"></i> Kirim
            </button>
        </div>
    </div>

    <!-- 3. KOLOM KANAN: DETAIL PROFIL & STATUS SAMPEL PENELITI -->
    <div class="client-detail-panel">
        <div class="profile-card-top">
            <div class="large-user-avatar" id="sideAvatar">DH</div>
            <div class="p-user-name" id="sideName">Dr. Hendra S.</div>
            <div class="p-user-inst" id="sideInst">Badan Riset & Inovasi Nasional (BRIN)</div>
            <div class="p-user-nip">NIP/ID Riset: BRIN-2024-9912</div>
            <div class="p-badges-row">
                <span class="p-pill p-pill-blue"><i class="fa-solid fa-check-circle me-1"></i> Akun Terverifikasi</span>
                <span class="p-pill p-pill-green"><i class="fa-solid fa-crown me-1"></i> Tier VIP Riset</span>
            </div>
        </div>

        <!-- Data Registrasi Sampel -->
        <div class="reg-info-box">
            <div class="reg-item">
                <div class="reg-label">Nomor Registrasi Uji</div>
                <div class="reg-val" style="color:#091F5B;">PLP-IN98264</div>
            </div>
            <div class="reg-item">
                <div class="reg-label">Instrumen Diminta</div>
                <div class="reg-val">GC-MS QP2020 NX</div>
            </div>
            <div class="reg-item">
                <div class="reg-label">Jumlah Sampel</div>
                <div class="reg-val">4 Sampel Daun Kelor</div>
            </div>
            <div class="reg-item">
                <div class="reg-label">Status Sampel Fisik</div>
                <div class="reg-val"><span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Menunggu Penyerahan</span></div>
            </div>
        </div>

        <!-- Catatan Internal Staf -->
        <div class="notes-box">
            <div class="notes-title"><i class="fa-solid fa-lock"></i> Catatan Internal Staf Lab (Privat)</div>
            <div class="notes-body">
                "Peneliti memerlukan fast-track turnaround 24 jam untuk laporan hibah BRIN. Siapkan kolom DB-5MS pada instrumen GC-MS unit #2 besok pagi."
            </div>
            <div class="notes-author">
                <span>Diperbarui oleh: Budi S.</span>
                <a href="javascript:void(0)" onclick="alert('Fitur edit catatan staf.')" style="color:#B45309; font-weight:700;">Edit Catatan</a>
            </div>
        </div>

        <!-- Kondisi Cleanroom & Gas Alat -->
        <div class="sensor-box">
            <div class="sensor-title"><i class="fa-solid fa-temperature-half me-1"></i> Kondisi Cleanroom & Gas Alat</div>
            <div class="sensor-row">
                <span>Suhu Ruang GC-MS:</span>
                <strong>21.4 &deg;C</strong>
            </div>
            <div class="sensor-row">
                <span>Kelembapan (RH):</span>
                <strong>48% (Optimal)</strong>
            </div>
            <div class="sensor-row">
                <span>Gas Pembawa:</span>
                <strong>99.999% Ultra Pure He</strong>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex flex-column gap-2 mt-auto">
            <button type="button" class="btn-side-action" onclick="alert('Mencetak Lembar Serah Terima Sampel Resmi...')">
                <i class="fa-solid fa-print text-primary"></i> Cetak Lembar Serah Terima
            </button>
            <button type="button" class="btn-side-action" onclick="alert('Barcode sampel berhasil di-generate: BARCODE-PLP-IN98264')">
                <i class="fa-solid fa-barcode text-success"></i> Generate Barcode Sampel
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const convoArea = document.getElementById('staffConvoArea');
    const replyInput = document.getElementById('staffReplyInput');

    window.addEventListener('load', () => {
        convoArea.scrollTop = convoArea.scrollHeight;
    });

    function insertQuickReply(text) {
        replyInput.value = text;
        replyInput.focus();
    }

    function sendStaffReply() {
        const text = replyInput.value.trim();
        if (!text) return;

        const emptyNotice = document.getElementById('staffEmptyNotice');
        if (emptyNotice) emptyNotice.remove();

        const timeNow = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace('.', ':');

        const row = document.createElement('div');
        row.className = 'c-msg-row staff';
        row.innerHTML = `
            <div class="c-bubble staff">
                <div class="c-bubble-title">Anda (Operator Palapa Lab)</div>
                <div>${escapeHtml(text)}</div>
                <div class="c-bubble-time">${timeNow} • Terkirim <i class="fa-solid fa-check text-info"></i></div>
            </div>
        `;
        convoArea.appendChild(row);
        replyInput.value = '';
        convoArea.scrollTop = convoArea.scrollHeight;
    }

    function selectChat(userId) {
        document.querySelectorAll('.chat-item').forEach(el => el.classList.remove('active'));
        event.currentTarget.classList.add('active');

        if (userId === 'hendra') {
            document.getElementById('headerUserName').textContent = 'Dr. Hendra S.';
            document.getElementById('sideName').textContent = 'Dr. Hendra S.';
            document.getElementById('sideInst').textContent = 'Badan Riset & Inovasi Nasional (BRIN)';
            document.getElementById('sideAvatar').textContent = 'DH';
            replyInput.placeholder = 'Ketik pesan balasan untuk Dr. Hendra S....';
        } else if (userId === 'ratna') {
            document.getElementById('headerUserName').textContent = 'Prof. Ratna Dewi';
            document.getElementById('sideName').textContent = 'Prof. Ratna Dewi';
            document.getElementById('sideInst').textContent = 'Institut Teknologi Bandung (ITB)';
            document.getElementById('sideAvatar').textContent = 'RD';
            replyInput.placeholder = 'Ketik pesan balasan untuk Prof. Ratna Dewi...';
        } else if (userId === 'bambang') {
            document.getElementById('headerUserName').textContent = 'Ir. Bambang P.';
            document.getElementById('sideName').textContent = 'Ir. Bambang P.';
            document.getElementById('sideInst').textContent = 'Universitas Gadjah Mada (UGM)';
            document.getElementById('sideAvatar').textContent = 'BP';
            replyInput.placeholder = 'Ketik pesan balasan untuk Ir. Bambang P....';
        } else if (userId === 'ahmad') {
            document.getElementById('headerUserName').textContent = 'Dr. Ahmad Fauzi';
            document.getElementById('sideName').textContent = 'Dr. Ahmad Fauzi';
            document.getElementById('sideInst').textContent = 'Universitas Airlangga (UNAIR)';
            document.getElementById('sideAvatar').textContent = 'AF';
            replyInput.placeholder = 'Ketik pesan balasan untuk Dr. Ahmad Fauzi...';
        }
    }

    function escapeHtml(text) {
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, m => map[m]);
    }
</script>
@endpush
@endsection
