<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Bantuan & Knowledge Base - PALAPA</title>
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

        /* --- TOMBOL KEMBALI (BACK) --- */
        .btn-back-wrapper {
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #334EAC;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            padding: 8px 16px;
            background: #EBF3FF;
            border-radius: 10px;
            transition: all 0.2s;
        }
        .btn-back:hover {
            background: #334EAC;
            color: #ffffff;
        }

        /* --- HERO HELPDESK --- */
        .help-hero {
            background: linear-gradient(135deg, #091F5B 0%, #334EAC 100%);
            border-radius: 20px;
            padding: 40px;
            color: #ffffff;
            text-align: center;
            margin-bottom: 40px;
        }
        .help-hero-subtitle {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            color: #93c5fd;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .help-hero-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 12px;
        }
        .help-hero-desc {
            font-size: 14px;
            color: #cbd5e1;
            max-width: 600px;
            margin: 0 auto 24px auto;
            line-height: 1.6;
        }
        .help-search-box {
            max-width: 500px;
            margin: 0 auto;
            position: relative;
        }
        .help-search-box input {
            width: 100%;
            padding: 14px 20px 14px 48px;
            border-radius: 12px;
            border: none;
            outline: none;
            font-family: inherit;
            font-size: 14px;
        }
        .help-search-box i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 16px;
        }

        /* --- KONTAK BANTUAN (WA & LIVE CHAT) --- */
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }
        .contact-card {
            border-radius: 16px;
            padding: 24px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .contact-card.wa {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
        }
        .contact-card.livechat {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
        }
        .contact-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .contact-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #ffffff;
        }
        .contact-icon.wa { background: #10b981; }
        .contact-icon.livechat { background: #3B82F6; }
        
        .contact-title { font-size: 16px; font-weight: 700; margin-bottom: 2px;}
        .contact-title.wa { color: #065f46; }
        .contact-title.livechat { color: #1e3a8a; }
        
        .contact-desc { font-size: 12px; }
        .contact-desc.wa { color: #047857; }
        .contact-desc.livechat { color: #1d4ed8; }
        
        .btn-action {
            color: #ffffff;
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
            white-space: nowrap;
        }
        .btn-action.wa { background: #10b981; }
        .btn-action.wa:hover { background: #059669; }
        .btn-action.livechat { background: #3B82F6; }
        .btn-action.livechat:hover { background: #2563eb; }

        /* FLOATING CHAT BUTTON */
        .floating-chat {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #091F5B;
            color: #ffffff;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 20px rgba(9, 31, 91, 0.3);
            cursor: pointer;
            z-index: 1000;
            text-decoration: none;
            transition: transform 0.2s;
        }
        .floating-chat:hover {
            transform: scale(1.1);
        }

        /* --- FAQ SECTION --- */
        .faq-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 50px;
        }
        .faq-item {
            background: #ffffff;
            border: 1px solid #E2E8F0;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        }
        .faq-question {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .faq-question i {
            color: #334EAC;
        }
        .faq-answer {
            font-size: 13px;
            color: #475569;
            line-height: 1.6;
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
    </style>
</head>
<body>

    <div class="container">

        <!-- NAVBAR -->
        <div class="navbar-wrapper">
            <nav class="navbar">
                <a href="{{ url('/') }}" class="nav-brand">
                    <img src="{{ asset('images/logo-palapa.jpg') }}" alt="PALAPA Logo">
                </a>

                <div class="nav-links">
                    <a href="{{ url('/') }}" class="nav-link">Beranda</a>
                    <a href="{{ route('labs.index') }}" class="nav-link">Katalog Lab</a>
                    <a href="{{ route('labs.index') }}" class="nav-link">Instrumen Riset</a>
                    <a href="{{ url('/pusat-bantuan') }}" class="nav-link active">Pusat Bantuan</a>
                </div>

                <div class="nav-search">
                    <input type="text" placeholder="Pencarian">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>

                <div class="nav-actions">
                    <a href="{{ url('/riwayat') }}" class="icon-btn" title="Riwayat"><i class="fa-solid fa-cart-shopping"></i></a>
                    <a href="{{ url('/login') }}" class="icon-btn" title="Masuk Akun"><i class="fa-solid fa-user"></i></a>
                </div>
            </nav>
        </div>

        <!-- TOMBOL KEMBALI (BACK TO HOME) -->
        <div class="btn-back-wrapper">
            <a href="{{ url('/') }}" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>

        <!-- HERO PUSAT BANTUAN -->
        <section class="help-hero">
            <div class="help-hero-subtitle">PUSAT BANTUAN & KNOWLEDGE BASE</div>
            <h1 class="help-hero-title">Ada yang Bisa Kami Bantu?</h1>
            <p class="help-hero-desc">Cari panduan prosedur pengujian, cara reservasi instrumen, pembayaran SPK hibah, atau konsultasikan kendala riset Anda.</p>
            <div class="help-search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Cari topik bantuan (misal: cara sewa GC-MS, SPK, pembayaran)...">
            </div>
        </section>

        <!-- KONTAK BANTUAN (WA & LIVE CHAT) -->
        <div class="contact-grid">
            <!-- Kartu WA Hotline -->
            <div class="contact-card wa">
                <div class="contact-info">
                    <div class="contact-icon wa">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                    <div>
                        <div class="contact-title wa">WhatsApp Hotline</div>
                        <div class="contact-desc wa">Kendala jadwal & pembayaran.</div>
                    </div>
                </div>
                <a href="https://wa.me/+6285737657304" target="_blank" class="btn-action wa">
                    <i class="fa-brands fa-whatsapp"></i> Chat WA
                </a>
            </div>

            <!-- Kartu Live Chat Operator -->
            <div class="contact-card livechat">
                <div class="contact-info">
                    <div class="contact-icon livechat">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <div>
                        <div class="contact-title livechat">Live Chat Operator</div>
                        <div class="contact-desc livechat">Konsultasi teknis & instrumen lab.</div>
                    </div>
                </div>
                <a href="#" class="btn-action livechat">
                    <i class="fa-regular fa-message"></i> Mulai Chat
                </a>
            </div>
        </div>

        <!-- DOKUMENTASI FAQ -->
        <section>
            <div style="margin-bottom: 24px;">
                <h2 style="font-size: 20px; font-weight: 700; color: #0f172a;">Pertanyaan Sering Diajukan (FAQ)</h2>
                <p style="font-size: 13px; color: #64748b;">Panduan cepat penggunaan platform PALAPA untuk peneliti dan institusi.</p>
            </div>

            <div class="faq-grid">
                <div class="faq-item">
                    <div class="faq-question"><i class="fa-solid fa-circle-question"></i> Bagaimana cara mengajukan reservasi laboratorium?</div>
                    <div class="faq-answer">Pilih instrumen pada Katalog Lab, tentukan tanggal serta sesi pengujian, unggah protokol sampel Anda, lalu lakukan konfirmasi pembayaran.</div>
                </div>

                <div class="faq-item">
                    <div class="faq-question"><i class="fa-solid fa-file-invoice-dollar"></i> Apakah PALAPA mendukung pembayaran via Dana Hibah?</div>
                    <div class="faq-answer">Ya, PALAPA menyediakan penerbitan SPK otomatis, invoice resmi, dan dokumen pertanggungjawaban hibah (BIMA, LPDP, dll).</div>
                </div>

                <div class="faq-item">
                    <div class="faq-question"><i class="fa-solid fa-qrcode"></i> Apa fungsi Entry Pass QR Code?</div>
                    <div class="faq-answer">QR Code ini berfungsi sebagai tiket masuk digital yang di-scan di gerbang/pintu masuk laboratorium saat hari pengujian berlangsung.</div>
                </div>

                <div class="faq-item">
                    <div class="faq-question"><i class="fa-solid fa-user-gear"></i> Apakah pengujian didampingi oleh teknisi ahli?</div>
                    <div class="faq-answer">Seluruh fasilitas yang terdaftar dilengkapi opsi pendampingan laboran/teknisi tersertifikasi untuk menjamin akurasi data.</div>
                </div>
            </div>
        </section>

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

    <!-- FLOATING LIVE CHAT BUTTON (Tetap di pojok kanan bawah) -->
    <a href="#" class="floating-chat" title="Mulai Live Chat">
        <i class="fa-regular fa-comment-dots"></i>
    </a>

</body>
</html>