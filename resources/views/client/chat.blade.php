<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat Konsultasi Laboratorium - PALAPA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F8FAFC;
            color: #0f172a;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        /* --- NAVBAR MINI PALAPA --- */
        .palapa-top-nav {
            background: #ffffff;
            border-bottom: 1px solid #E2E8F0;
            padding: 10px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
            z-index: 10;
        }
        .nav-brand-group {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .nav-brand-group img {
            height: 34px;
            width: auto;
            border-radius: 6px;
        }
        .nav-menu-links {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .nav-menu-links a {
            text-decoration: none;
            color: #475569;
            font-size: 13px;
            font-weight: 500;
            padding: 6px 14px;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .nav-menu-links a:hover, .nav-menu-links a.active {
            color: #091F5B;
            background: #EBF3FF;
            font-weight: 700;
        }

        /* --- CHAT HEADER (IMAGE 1) --- */
        .chat-header {
            background: #ffffff;
            border-bottom: 1px solid #E2E8F0;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
            z-index: 9;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .btn-back {
            color: #475569;
            font-size: 16px;
            padding: 8px;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-back:hover {
            background: #F1F5F9;
            color: #091F5B;
        }
        .avatar-wrap {
            position: relative;
            width: 44px;
            height: 44px;
        }
        .avatar-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: linear-gradient(135deg, #091F5B, #334EAC);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            box-shadow: 0 4px 10px rgba(9, 31, 91, 0.2);
        }
        .status-dot {
            width: 12px;
            height: 12px;
            background: #10B981;
            border: 2px solid #ffffff;
            border-radius: 50%;
            position: absolute;
            bottom: 0;
            right: 0;
            animation: pulse-dot 2s infinite;
        }
        @keyframes pulse-dot {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
        .operator-meta {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .operator-title-line {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .operator-name {
            font-size: 15px;
            font-weight: 700;
            color: #091F5B;
        }
        .badge-verified {
            color: #2563EB;
            font-size: 14px;
        }
        .pill-category {
            background: #EBF3FF;
            color: #1D4ED8;
            font-size: 11px;
            font-weight: 600;
            padding: 2px 10px;
            border-radius: 999px;
            border: 1px solid #BFDBFE;
        }
        .operator-sub {
            font-size: 12px;
            color: #059669;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .operator-sub span {
            color: #64748B;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .action-icon-btn {
            background: transparent;
            border: none;
            color: #64748B;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .action-icon-btn:hover {
            background: #F1F5F9;
            color: #091F5B;
        }

        /* --- CHAT BODY (CANVAS PERSIS IMAGE 1) --- */
        .chat-body {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            background-color: #F8FAFD;
            background-image: radial-gradient(#CBD5E1 0.8px, transparent 0.8px);
            background-size: 20px 20px;
            scroll-behavior: smooth;
        }

        /* Security encrypted banner */
        .encrypted-banner {
            align-self: center;
            background: #EFF6FF;
            border: 1px solid #BFDBFE;
            color: #1E40AF;
            font-size: 11px;
            padding: 8px 18px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            max-width: 680px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
            margin-bottom: 6px;
        }

        .date-divider {
            align-self: center;
            background: #E2E8F0;
            color: #475569;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 14px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Message Bubbles */
        .msg-row {
            display: flex;
            width: 100%;
        }
        .msg-row.client {
            justify-content: flex-end;
        }
        .msg-row.operator {
            justify-content: flex-start;
        }

        .bubble {
            max-width: 65%;
            padding: 14px 18px;
            border-radius: 16px;
            position: relative;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
            font-size: 13.5px;
            line-height: 1.55;
        }
        .bubble.client {
            background: #0047BA;
            color: #ffffff;
            border-bottom-right-radius: 4px;
        }
        .bubble.operator {
            background: #ffffff;
            color: #1E293B;
            border: 1px solid #E2E8F0;
            border-bottom-left-radius: 4px;
        }

        .bubble-sender {
            font-size: 11px;
            font-weight: 700;
            color: #2563EB;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .bubble-time {
            font-size: 10.5px;
            text-align: right;
            margin-top: 6px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 4px;
        }
        .bubble.client .bubble-time {
            color: #BFDBFE;
        }
        .bubble.operator .bubble-time {
            color: #94A3B8;
        }

        /* REKOMENDASI ALAT CARD PERSIS IMAGE 1 */
        .rekomendasi-card {
            background: #ffffff;
            border: 1px solid #DBEAFE;
            border-radius: 14px;
            padding: 16px;
            margin-top: 8px;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.08);
            max-width: 480px;
        }
        .rekomendasi-badge-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        .tag-rekomendasi {
            color: #1D4ED8;
            font-weight: 700;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .slot-badge {
            background: #EFF6FF;
            color: #2563EB;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 999px;
            border: 1px solid #BFDBFE;
        }
        .alat-flex {
            display: flex;
            gap: 14px;
            align-items: center;
            margin-bottom: 14px;
        }
        .alat-thumb {
            width: 72px;
            height: 72px;
            border-radius: 10px;
            background: #F1F5F9;
            object-fit: cover;
            border: 1px solid #E2E8F0;
        }
        .alat-info-title {
            font-size: 15px;
            font-weight: 800;
            color: #091F5B;
        }
        .alat-info-sub {
            font-size: 12px;
            color: #64748B;
            margin-top: 2px;
        }
        .alat-info-price {
            font-size: 13px;
            font-weight: 700;
            color: #0047BA;
            margin-top: 4px;
        }
        .alat-btn-group {
            display: flex;
            gap: 8px;
        }
        .btn-reservasi-slot {
            flex: 2;
            background: #0047BA;
            color: #ffffff;
            border: none;
            padding: 9px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: background 0.2s;
        }
        .btn-reservasi-slot:hover {
            background: #091F5B;
        }
        .btn-sop {
            flex: 1;
            background: #ffffff;
            color: #334155;
            border: 1px solid #CBD5E1;
            padding: 9px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.2s;
        }
        .btn-sop:hover {
            background: #F8FAFC;
            border-color: #94A3B8;
        }

        /* ATTACHMENT CARD PERSIS IMAGE 1 */
        .attachment-card {
            background: #ffffff;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 12px 14px;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #1E293B;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        }
        .attach-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .attach-icon {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background: #FEE2E2;
            color: #DC2626;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        .attach-name {
            font-size: 13px;
            font-weight: 700;
            color: #0F172A;
        }
        .attach-size {
            font-size: 11px;
            color: #64748B;
            margin-top: 2px;
        }
        .btn-download-attach {
            color: #2563EB;
            font-size: 16px;
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
            transition: background 0.2s;
            text-decoration: none;
        }
        .btn-download-attach:hover {
            background: #EFF6FF;
        }

        /* --- CHAT INPUT BAR PERSIS IMAGE 1 --- */
        .chat-input-bar {
            background: #ffffff;
            border-top: 1px solid #E2E8F0;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.03);
        }
        .input-icon-btn {
            background: transparent;
            border: none;
            color: #64748B;
            font-size: 19px;
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s, background 0.2s;
        }
        .input-icon-btn:hover {
            color: #0047BA;
            background: #F1F5F9;
        }
        .chat-input-field {
            flex: 1;
            background: #F1F5F9;
            border: 1px solid transparent;
            border-radius: 999px;
            padding: 12px 20px;
            font-size: 13.5px;
            font-family: inherit;
            color: #0F172A;
            outline: none;
            transition: all 0.2s;
        }
        .chat-input-field:focus {
            background: #ffffff;
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        .btn-send {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #0047BA;
            color: #ffffff;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            transition: transform 0.15s, background 0.2s;
            box-shadow: 0 4px 12px rgba(0, 71, 186, 0.25);
        }
        .btn-send:hover {
            background: #091F5B;
            transform: scale(1.05);
        }
        .btn-send:active {
            transform: scale(0.95);
        }

        /* MODAL SOP */
        .sop-modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 100;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .sop-modal {
            background: #ffffff;
            border-radius: 18px;
            max-width: 520px;
            width: 100%;
            padding: 24px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        }
        .sop-modal h3 {
            font-size: 16px;
            color: #091F5B;
            font-weight: 700;
            margin-bottom: 12px;
        }
        .sop-modal ul {
            padding-left: 20px;
            font-size: 13px;
            color: #334155;
            line-height: 1.7;
            margin-bottom: 18px;
        }
        .sop-modal button {
            background: #091F5B;
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            float: right;
        }
    </style>
</head>
<body>

    <!-- NAVBAR MINI PALAPA (Sesuai tema aplikasi) -->
    <header class="palapa-top-nav">
        <a href="{{ route('home') }}" class="nav-brand-group">
            <img src="{{ asset('images/logo-palapa.jpg') }}" alt="PALAPA" onerror="this.src='https://placehold.co/36x36/091F5B/FFFFFF?text=P'">
            <span style="font-weight:800; color:#091F5B; font-size:16px;">PALAPA PLATFORM</span>
        </a>

        <nav class="nav-menu-links">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('labs.index') }}">Katalog Lab</a>
            <a href="{{ route('instruments.index') }}">Instrumen Riset</a>
            <a href="{{ route('bantuan') }}" class="active">Pusat Bantuan</a>
            @auth
                <a href="{{ route('riwayat') }}">Riwayat Reservasi</a>
            @endauth
        </nav>
    </header>

    <!-- CHAT HEADER PERSIS SEPERTI GAMBAR 1 -->
    <div class="chat-header">
        <div class="header-left">
            <a href="{{ route('bantuan') }}" class="btn-back" title="Kembali ke Pusat Bantuan">
                <i class="fa-solid fa-arrow-left"></i>
            </a>

            <div class="avatar-wrap">
                <div class="avatar-img">
                    <i class="fa-solid fa-flask-vial"></i>
                </div>
                <div class="status-dot"></div>
            </div>

            <div class="operator-meta">
                <div class="operator-title-line">
                    <span class="operator-name">Operator Laboratorium Palapa</span>
                    <i class="fa-solid fa-circle-check badge-verified" title="Terverifikasi Resmi"></i>
                    <span class="pill-category">Cleanroom & Konsultasi Riset</span>
                </div>
                <div class="operator-sub">
                    ● Online <span>• Enkripsi Sesi Riset Terpadu</span>
                </div>
            </div>
        </div>

        <div class="header-actions">
            <button class="action-icon-btn" onclick="openSopModal()" title="Lihat Panduan & SOP"><i class="fa-regular fa-file-lines"></i></button>
            <button class="action-icon-btn" onclick="alert('Saluran Panggilan Konsultasi Teknis: +62 857-3765-7304')" title="Panggilan Suara"><i class="fa-solid fa-phone"></i></button>
            <button class="action-icon-btn" title="Cari Percakapan"><i class="fa-solid fa-magnifying-glass"></i></button>
            <button class="action-icon-btn" title="Opsi Sesi"><i class="fa-solid fa-ellipsis-vertical"></i></button>
        </div>
    </div>

    <!-- CHAT BODY BERSIH TANPA CHAT DAHULU -->
    <div class="chat-body" id="chatContainer">
        <!-- Banner Enkripsi -->
        <div class="encrypted-banner">
            <i class="fa-solid fa-lock"></i>
            <span>Pesan dan panggilan terenkripsi end-to-end. Tidak seorang pun di luar chat ini yang dapat membaca atau mendengarkannya.</span>
        </div>

        <!-- Pemisah Tanggal -->
        <div class="date-divider">HARI INI</div>

        <!-- Empty State Notice (Hilang otomatis saat ada chat baru) -->
        <div id="emptyChatNotice" class="text-center my-auto py-5" style="color: #64748B;">
            <div style="width: 58px; height: 58px; background: #EBF3FF; border-radius: 50%; color: #091F5B; display: inline-flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 12px; box-shadow: 0 4px 12px rgba(9, 31, 91, 0.08);">
                <i class="fa-solid fa-comments"></i>
            </div>
            <div style="font-weight: 700; color: #091F5B; font-size: 16px;">Ruang Konsultasi Laboratorium</div>
            <div style="font-size: 13px; color: #64748B; max-width: 460px; margin: 6px auto 0 auto; line-height: 1.5;">
                Belum ada percakapan. Silakan ketik pesan di kolom bawah untuk memulai konsultasi teknis pengujian sampel dan ketersediaan instrumen dengan Operator Lab Palapa.
            </div>
        </div>
    </div>

    <!-- CHAT INPUT BAR PERSIS GAMBAR 1 -->
    <footer class="chat-input-bar">
        <button class="input-icon-btn" onclick="insertEmoji('😊')" title="Emoji"><i class="fa-regular fa-face-smile"></i></button>
        <button class="input-icon-btn" onclick="attachSampleFile()" title="Lampirkan Dokumen Sampel / PDF"><i class="fa-solid fa-paperclip"></i></button>
        <input type="text" id="chatInputText" class="chat-input-field" placeholder="Ketik pesan konsultasi laboratorium..." onkeydown="handleEnter(event)">
        <button class="input-icon-btn" onclick="alert('Fitur rekam catatan suara laboratorium aktif.')" title="Pesan Suara"><i class="fa-solid fa-microphone"></i></button>
        <button class="btn-send" onclick="sendClientMessage()" title="Kirim Pesan">
            <i class="fa-solid fa-paper-plane"></i>
        </button>
    </footer>

    <!-- POPUP SOP KESELAMATAN & PREPARASI ALAT -->
    <div class="sop-modal-backdrop" id="sopModal" onclick="closeSopModal(event)">
        <div class="sop-modal" onclick="event.stopPropagation()">
            <h3><i class="fa-solid fa-microscope text-primary me-2"></i> SOP Pengujian GC-MS QP2020 NX</h3>
            <ul>
                <li>Sampel harus bebas partikulat dan disaring menggunakan membrane 0.22 &mu;m (PTFE/Nylon).</li>
                <li>Pelarut ekstraksi wajib grade HPLC / GC Spectroscopic Purity (bebas kandungan air / moisture &lt; 0.05%).</li>
                <li>Gunakan vial autosampler 2 mL dengan screw cap septum PTFE/Silicone.</li>
                <li>Wajib membawa form deklarasi bahaya bahan kimia (MSDS/Lembar K3) saat registrasi meja lab.</li>
                <li>Hasil kromatogram & spektra massal dikirim otomatis via portal PALAPA setelah running selesai.</li>
            </ul>
            <button type="button" onclick="closeSopModal()">Tutup SOP</button>
        </div>
    </div>

    <script>
        const chatContainer = document.getElementById('chatContainer');
        const chatInput = document.getElementById('chatInputText');

        // Scroll to bottom on load
        window.addEventListener('load', () => {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        });

        function handleEnter(e) {
            if (e.key === 'Enter') {
                sendClientMessage();
            }
        }

        function insertEmoji(emoji) {
            chatInput.value += emoji;
            chatInput.focus();
        }

        function attachSampleFile() {
            const fileName = prompt("Masukkan nama dokumen lampiran sampel yang ingin diunggah:", "Hasil_Uji_Awal_Kelor.pdf");
            if (fileName) {
                appendClientAttachment(fileName);
            }
        }

        function sendClientMessage() {
            const text = chatInput.value.trim();
            if (!text) return;

            const emptyNotice = document.getElementById('emptyChatNotice');
            if (emptyNotice) emptyNotice.remove();

            const timeNow = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace('.', ':');

            const msgRow = document.createElement('div');
            msgRow.className = 'msg-row client';
            msgRow.innerHTML = `
                <div class="bubble client">
                    <div>${escapeHtml(text)}</div>
                    <div class="bubble-time">
                        ${timeNow} <i class="fa-solid fa-check" style="color:#93C5FD;"></i>
                    </div>
                </div>
            `;
            chatContainer.appendChild(msgRow);
            chatInput.value = '';
            chatContainer.scrollTop = chatContainer.scrollHeight;

            // Simulasi respon otomatis dari staf laboran setelah 1 detik
            setTimeout(() => {
                simulateOperatorReply(text);
            }, 1200);
        }

        function appendClientAttachment(fileName) {
            const timeNow = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace('.', ':');
            const msgRow = document.createElement('div');
            msgRow.className = 'msg-row client';
            msgRow.innerHTML = `
                <div class="bubble client">
                    <div>Berikut berkas spesifikasi sampel tambahan:</div>
                    <div class="attachment-card">
                        <div class="attach-left">
                            <div class="attach-icon">
                                <i class="fa-solid fa-file-pdf"></i>
                            </div>
                            <div>
                                <div class="attach-name">${escapeHtml(fileName)}</div>
                                <div class="attach-size">850 KB • Dokumen Verifikasi PDF</div>
                            </div>
                        </div>
                        <a href="javascript:void(0)" class="btn-download-attach" title="Unduh Berkas"><i class="fa-solid fa-download"></i></a>
                    </div>
                    <div class="bubble-time">
                        ${timeNow} <i class="fa-solid fa-check" style="color:#93C5FD;"></i>
                    </div>
                </div>
            `;
            chatContainer.appendChild(msgRow);
            chatContainer.scrollTop = chatContainer.scrollHeight;

            setTimeout(() => {
                simulateOperatorReply("berkas lampiran " + fileName);
            }, 1200);
        }

        function simulateOperatorReply(clientQuery) {
            const timeNow = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace('.', ':');
            let replyText = "Baik Rekan Peneliti, pesan Anda sudah diterima oleh operator cleanroom kami. Kami sedang menyiapkan instrumen sesuai jadwal praktikum yang diajukan.";

            const q = clientQuery.toLowerCase();
            if (q.includes('jadwal') || q.includes('kapan') || q.includes('waktu')) {
                replyText = "Slot running instrumen tersedia setiap hari kerja pukul 08:30 - 16:30 WIB. Silakan ajukan melalui formulir reservasi pada katalog lab untuk memastikan kuota antrean.";
            } else if (q.includes('biaya') || q.includes('tarif') || q.includes('harga')) {
                replyText = "Tarif pengujian sesuai PNBP resmi SIMPONI: Pengujian GC-MS Rp 350.000/sampel (sudah termasuk gas helium ultrapure dan kolom kapiler standar).";
            } else if (q.includes('terima kasih') || q.includes('makasih')) {
                replyText = "Sama-sama Rekan Peneliti! Jika ada kendala teknis lainnya, jangan ragu untuk menghubungi kami kembali di sini. Selamat melanjutkan riset!";
            }

            const opRow = document.createElement('div');
            opRow.className = 'msg-row operator';
            opRow.innerHTML = `
                <div class="bubble operator">
                    <div class="bubble-sender">
                        <i class="fa-solid fa-user-gear"></i> Operator Lab • Cleanroom B-204
                    </div>
                    <div>${replyText}</div>
                    <div class="bubble-time">${timeNow}</div>
                </div>
            `;
            chatContainer.appendChild(opRow);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        function openSopModal() {
            document.getElementById('sopModal').style.display = 'flex';
        }
        function closeSopModal() {
            document.getElementById('sopModal').style.display = 'none';
        }

        function escapeHtml(text) {
            const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
            return text.replace(/[&<>"']/g, m => map[m]);
        }
    </script>
</body>
</html>
