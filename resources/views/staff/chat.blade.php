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
        min-width: 280px;
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
        flex-wrap: wrap;
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
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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
        gap: 10px;
    }
    .convo-user-info {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }
    .convo-name {
        font-size: 15px;
        font-weight: 800;
        color: #091F5B;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
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
        gap: 6px;
        flex-shrink: 0;
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
        max-width: 75%;
        padding: 12px 16px;
        border-radius: 14px;
        font-size: 13px;
        line-height: 1.5;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        position: relative;
        word-wrap: break-word;
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

    /* Quick Reply Bar */
    .quick-replies-bar {
        background: #ffffff;
        border-top: 1px solid #F1F5F9;
        padding: 8px 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        overflow-x: auto;
        flex-shrink: 0;
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
        min-width: 0;
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
        flex-shrink: 0;
        transition: background 0.2s;
    }
    .btn-staff-send:hover {
        background: #091F5B;
    }

    /* --- KOLOM 3: DETAIL PROFIL & STATUS SAMPEL (KANAN) --- */
    .client-detail-panel {
        width: 310px;
        min-width: 280px;
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
        flex-wrap: wrap;
    }
    .p-pill {
        font-size: 10px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 999px;
    }
    .p-pill-blue { background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; }
    .p-pill-green { background: #ECFDF5; color: #047857; border: 1px solid #A7F3D0; }

    .reg-info-box {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 14px;
    }
    .reg-item { margin-bottom: 10px; }
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

    /* --- RESPONSIVE BREAKPOINTS (SPLIT SCREEN & TABLET/MOBILE) --- */
    @media (max-width: 1280px) {
        .client-detail-panel {
            display: none;
        }
        .chat-list-panel {
            width: 250px;
            min-width: 220px;
        }
    }

    @media (max-width: 820px) {
        .chat-operator-layout {
            flex-direction: column;
            height: auto;
            min-height: calc(100vh - 70px);
            overflow-y: auto;
        }
        .chat-list-panel {
            width: 100%;
            min-width: 100%;
            max-height: 210px;
            border-right: none;
            border-bottom: 1px solid #E2E8F0;
        }
        .chat-convo-panel {
            width: 100%;
            min-height: 460px;
            border-right: none;
        }
        .c-bubble {
            max-width: 88%;
        }
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
                <input type="text" placeholder="Filter nama / instansi..." id="filterUserChat" onkeyup="filterThreadList(this.value)">
            </div>
            <div class="filter-tabs">
                <button type="button" class="filter-tab-pill active">Semua ({{ $threads->count() }})</button>
                <button type="button" class="filter-tab-pill">Aktif</button>
            </div>
        </div>

        <div class="chat-scroll-list">
            @forelse ($threads as $t)
                @php
                    $initials = strtoupper(substr($t->name, 0, 2));
                @endphp
                <div class="chat-item" id="thread-{{ $t->user_id }}" data-name="{{ strtolower($t->name . ' ' . $t->email) }}" onclick="selectRealChat('{{ $t->user_id }}', '{{ addslashes($t->name) }}', '{{ addslashes($t->email) }}', '{{ $initials }}', this)">
                    <div class="user-avatar-badge online">{{ $initials }}</div>
                    <div class="chat-item-body">
                        <div class="chat-item-head">
                            <div class="chat-name">{{ $t->name }}</div>
                            <div class="chat-time">{{ \Carbon\Carbon::parse($t->last_time)->format('H:i') }}</div>
                        </div>
                        <div class="chat-meta-inst">{{ $t->email }}</div>
                        <div class="chat-preview">
                            @if ($t->unread_count > 0)
                                <strong style="color:#0047BA;">{{ $t->unread_count }} pesan baru belum dibaca</strong>
                            @else
                                Klik untuk membuka sesi chat...
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-muted" style="font-size:12.5px;">
                    Belum ada pesan masuk dari Client. Kirim pesan dari halaman <code>/live-chat</code> akun Client untuk memulai.
                </div>
            @endforelse
        </div>
    </div>

    <!-- 2. KOLOM TENGAH: RUANG PERCAKAPAN AKTIF -->
    <div class="chat-convo-panel">
        <div class="convo-header">
            <div class="convo-user-info">
                <div class="user-avatar-badge online" id="headerAvatar">PL</div>
                <div>
                    <div class="convo-name">
                        <span id="headerUserName">Pilih Peneliti</span>
                        <span class="convo-role-badge">PENELITI</span>
                    </div>
                    <div class="convo-sub">
                        ● Online <span id="headerSubInst">• Konsultasi Laboratorium</span>
                    </div>
                </div>
            </div>

            <div class="convo-actions">
                <button type="button" class="convo-action-btn" title="Cari Pesan"><i class="fa-solid fa-magnifying-glass"></i></button>
                <button type="button" class="convo-action-btn" onclick="alert('Menghubungi saluran resmi peneliti...')" title="Telepon Peneliti"><i class="fa-solid fa-phone"></i></button>
            </div>
        </div>

        <div class="convo-messages-area" id="staffConvoArea">
            <div class="text-center my-1">
                <span class="badge bg-light text-muted border" style="font-size:10px;">Hari ini • Enkripsi End-to-End Lab</span>
            </div>

            <div id="staffEmptyNotice" class="text-center my-auto py-5" style="color: #64748B;">
                <div style="width: 54px; height: 54px; background: #EFF6FF; border-radius: 50%; color: #0047BA; display: inline-flex; align-items: center; justify-content: center; font-size: 22px; margin-bottom: 12px; box-shadow: 0 4px 12px rgba(0, 71, 186, 0.08);">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div style="font-weight: 700; color: #091F5B; font-size: 15px;">Ruang Konsultasi Peneliti Siap Digunakan</div>
                <div style="font-size: 12.5px; color: #64748B; max-width: 440px; margin: 6px auto 0 auto; line-height: 1.5;">
                    Pilih peneliti di daftar atau ketik pesan balasan di bawah untuk memulai sesi konsultasi.
                </div>
            </div>
        </div>

        <!-- QUICK REPLY CHIPS -->
        <div class="quick-replies-bar">
            <span class="quick-label"><i class="fa-solid fa-bolt me-1"></i> Balas Cepat:</span>
            <button type="button" class="quick-pill" onclick="insertQuickReply('Sampel fisik telah kami terima di loket registrasi dan sedang dalam tahap antrean sonikasi.')">Konfirmasi Terima Sampel</button>
            <button type="button" class="quick-pill" onclick="insertQuickReply('Mohon patuhi standar keselamatan cleanroom: wajib jas lab katun 100%, masker partikulat, dan sarung tangan nitril.')">Prosedur Keselamatan Lab</button>
            <button type="button" class="quick-pill" onclick="insertQuickReply('Mohon kirimkan lembar MSDS pelarut yang digunakan untuk melengkapi izin registrasi K3 laboratorium.')">Minta Data Tambahan</button>
        </div>

        <!-- CONVO INPUT -->
        <div class="convo-input-bar">
            <button type="button" class="convo-action-btn" onclick="alert('Lampirkan template SOP atau form verifikasi.')" title="Kirim Berkas SOP"><i class="fa-solid fa-paperclip"></i></button>
            <input type="text" id="staffReplyInput" class="convo-input-field" placeholder="Ketik pesan balasan untuk peneliti..." onkeydown="if(event.key==='Enter') sendStaffReply()">
            <button type="button" class="btn-staff-send" onclick="sendStaffReply()">
                <i class="fa-solid fa-paper-plane"></i> Kirim
            </button>
        </div>
    </div>

    <!-- 3. KOLOM KANAN: DETAIL PROFIL & STATUS SAMPEL PENELITI -->
    <div class="client-detail-panel">
        <div class="profile-card-top">
            <div class="large-user-avatar" id="sideAvatar">PL</div>
            <div class="p-user-name" id="sideName">Peneliti PALAPA</div>
            <div class="p-user-inst" id="sideInst">Instansi Riset Terdaftar</div>
            <div class="p-user-nip">Status: Terverifikasi SIMPONI</div>
            <div class="p-badges-row">
                <span class="p-pill p-pill-blue"><i class="fa-solid fa-check-circle me-1"></i> Akun Terverifikasi</span>
                <span class="p-pill p-pill-green"><i class="fa-solid fa-crown me-1"></i> Tier VIP Riset</span>
            </div>
        </div>

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
                <div class="reg-label">Status Sampel Fisik</div>
                <div class="reg-val"><span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Menunggu Penyerahan</span></div>
            </div>
        </div>

        <div class="notes-box">
            <div class="notes-title"><i class="fa-solid fa-lock"></i> Catatan Internal Staf Lab (Privat)</div>
            <div class="notes-body">
                "Siapkan kolom standar dan verifikasi formulir K3 peneliti sebelum jadwal sesi dimulai."
            </div>
            <div class="notes-author">
                <span>Operator PALAPA</span>
                <a href="javascript:void(0)" onclick="alert('Fitur edit catatan staf.')" style="color:#B45309; font-weight:700;">Edit</a>
            </div>
        </div>

        <div class="sensor-box">
            <div class="sensor-title"><i class="fa-solid fa-temperature-half me-1"></i> Kondisi Cleanroom & Gas Alat</div>
            <div class="sensor-row">
                <span>Suhu Ruang:</span>
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

        <div class="d-flex flex-column gap-2 mt-auto">
            <button type="button" class="btn-side-action" onclick="alert('Mencetak Lembar Serah Terima Sampel Resmi...')">
                <i class="fa-solid fa-print text-primary"></i> Cetak Lembar Serah Terima
            </button>
            <button type="button" class="btn-side-action" onclick="alert('Barcode sampel berhasil di-generate.')">
                <i class="fa-solid fa-barcode text-success"></i> Generate Barcode Sampel
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const convoArea = document.getElementById('staffConvoArea');
    const replyInput = document.getElementById('staffReplyInput');
    let activeClientId = null;
    let staffChatTimer = null;

    function filterThreadList(keyword) {
        const q = keyword.toLowerCase();
        document.querySelectorAll('.chat-item').forEach(el => {
            const text = el.getAttribute('data-name') || '';
            el.style.display = text.includes(q) ? 'flex' : 'none';
        });
    }

    function insertQuickReply(text) {
        replyInput.value = text;
        replyInput.focus();
    }

    function selectRealChat(clientId, name, email, initials, el) {
        activeClientId = clientId;
        document.querySelectorAll('.chat-item').forEach(item => item.classList.remove('active'));
        if (el) el.classList.add('active');

        document.getElementById('headerUserName').textContent = name;
        document.getElementById('headerAvatar').textContent = initials;
        document.getElementById('headerSubInst').textContent = `• ${email}`;
        document.getElementById('sideName').textContent = name;
        document.getElementById('sideInst').textContent = email;
        document.getElementById('sideAvatar').textContent = initials;
        replyInput.placeholder = `Ketik pesan balasan untuk ${name}...`;

        loadStaffThreadMessages();
        if (staffChatTimer) clearInterval(staffChatTimer);
        staffChatTimer = setInterval(loadStaffThreadMessages, 3000);
    }

    async function loadStaffThreadMessages() {
        if (!activeClientId) return;
        try {
            const res = await fetch(`/staff/live-chat/${activeClientId}/messages`);
            if (!res.ok) return;
            const messages = await res.json();

            const emptyNotice = document.getElementById('staffEmptyNotice');
            if (emptyNotice) emptyNotice.remove();

            convoArea.querySelectorAll('.c-msg-row').forEach(el => el.remove());

            messages.forEach(m => {
                const isStaff = m.sender_role === 'staff';
                const timeStr = m.created_at ? m.created_at.substring(11, 16) : '';
                const row = document.createElement('div');
                row.className = `c-msg-row ${isStaff ? 'staff' : 'client'}`;

                if (isStaff) {
                    row.innerHTML = `
                        <div class="c-bubble staff">
                            <div class="c-bubble-title">Anda (Operator Palapa Lab)</div>
                            <div>${escapeHtml(m.message)}</div>
                            <div class="c-bubble-time">${timeStr} • Terkirim <i class="fa-solid fa-check text-info"></i></div>
                        </div>
                    `;
                } else {
                    row.innerHTML = `
                        <div class="c-bubble client">
                            <div class="c-bubble-title">Peneliti / Client</div>
                            <div>${escapeHtml(m.message)}</div>
                            <div class="c-bubble-time">${timeStr}</div>
                        </div>
                    `;
                }
                convoArea.appendChild(row);
            });

            convoArea.scrollTop = convoArea.scrollHeight;
        } catch (err) {}
    }

    async function sendStaffReply() {
        if (!activeClientId) {
            alert('Pilih nama peneliti di daftar sebelah kiri terlebih dahulu.');
            return;
        }
        const text = replyInput.value.trim();
        if (!text) return;

        replyInput.value = '';

        await fetch(`/staff/live-chat/${activeClientId}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: text })
        });

        loadStaffThreadMessages();
    }

    function escapeHtml(text) {
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }

    window.addEventListener('load', () => {
        const firstThread = document.querySelector('.chat-item');
        if (firstThread) firstThread.click();
    });
</script>
@endpush
@endsection
