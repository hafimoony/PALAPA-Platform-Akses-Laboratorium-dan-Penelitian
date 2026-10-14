<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Akun - PALAPA Platform</title>

    <!-- Google Fonts & Font Awesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            background-color: #f8fafc;
            font-family: 'Poppins', sans-serif;
            color: #0f172a;
            overflow-x: hidden;
        }
        .container {
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* --- NAVBAR CAPSULE --- */
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
            height: 38px;
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
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .nav-link:hover, .nav-link.active {
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
            width: 200px;
            border: none;
            outline: none;
            font-family: inherit;
            font-size: 12px;
            color: #334155;
        }
        .nav-search i {
            position: absolute;
            right: 14px;
            color: #64748b;
            font-size: 12px;
        }
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .icon-btn {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #091F5B;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 13px;
        }

        /* --- LOGIN CARD SPLIT CONTAINER --- */
        .login-card-container {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            display: flex;
            overflow: hidden;
            margin-bottom: 30px;
        }

        /* LEFT BANNER SIDE */
        .login-left-banner {
            width: 48%;
            background: linear-gradient(145deg, #091F5B 0%, #172A68 100%);
            padding: 48px 40px;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .banner-title {
            font-size: 28px;
            font-weight: 800;
            line-height: 1.25;
            margin-bottom: 16px;
            letter-spacing: -0.5px;
        }
        .banner-title span {
            color: #60A5FA;
        }
        .banner-desc {
            font-size: 13px;
            color: #CBD5E1;
            line-height: 1.6;
            margin-bottom: 32px;
        }
        .feature-box {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 14px;
            display: flex;
            gap: 14px;
            align-items: flex-start;
        }
        .feature-icon {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #60A5FA;
            font-size: 15px;
            flex-shrink: 0;
        }
        .feature-title-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
        }
        .feature-title {
            font-size: 13px;
            font-weight: 700;
            color: #ffffff;
        }
        .feature-tag {
            background: rgba(59, 130, 246, 0.3);
            color: #93C5FD;
            font-size: 9px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 4px;
        }
        .feature-desc {
            font-size: 11px;
            color: #94A3B8;
            line-height: 1.4;
        }

        /* RIGHT FORM SIDE */
        .login-right-form {
            width: 52%;
            padding: 40px 48px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* TAB SWITCHER ROLE */
        .role-tab-wrapper {
            background: #E2E8F0;
            padding: 4px;
            border-radius: 10px;
            display: flex;
            gap: 4px;
            margin-bottom: 28px;
        }
        .role-tab-btn {
            flex: 1;
            padding: 10px 12px;
            border-radius: 8px;
            border: none;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.2s;
            color: #64748b;
            background: transparent;
        }
        .role-tab-btn.active {
            background: #334EAC;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(51, 78, 172, 0.2);
        }

        .sso-badge-label {
            color: #334EAC;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 4px;
        }
        .form-welcome-title {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
        }
        .form-welcome-sub {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 24px;
            line-height: 1.5;
        }

        /* FORM INPUTS */
        .form-group-item {
            margin-bottom: 18px;
        }
        .form-label-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }
        .form-label-text {
            font-size: 11px;
            font-weight: 700;
            color: #1e293b;
        }
        .form-label-hint {
            font-size: 10px;
            color: #334EAC;
            font-weight: 600;
        }
        .input-icon-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-icon-wrapper i.left-icon {
            position: absolute;
            left: 14px;
            color: #94a3b8;
            font-size: 13px;
        }
        .input-icon-wrapper i.right-icon {
            position: absolute;
            right: 14px;
            color: #94a3b8;
            font-size: 13px;
            cursor: pointer;
        }
        .form-input-field {
            width: 100%;
            padding: 10px 14px 10px 38px;
            background: #EEF5FF;
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            font-family: inherit;
            font-size: 12px;
            color: #1e293b;
            outline: none;
            transition: border 0.2s;
        }
        .form-input-field:focus {
            border-color: #334EAC;
            background: #ffffff;
        }

        .form-extra-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
            font-size: 11px;
        }
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #475569;
            cursor: pointer;
        }
        .forgot-link {
            color: #334EAC;
            font-weight: 700;
            text-decoration: none;
        }

        .btn-login-submit {
            width: 100%;
            padding: 12px;
            background: #172A68;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.2s;
        }
        .btn-login-submit:hover {
            background: #091F5B;
        }

        .divider-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 22px 0;
        }
        .divider-line {
            flex: 1;
            height: 1px;
            background: #E2E8F0;
        }
        .divider-text {
            font-size: 10px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sso-buttons-row {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }
        .btn-sso-item {
            flex: 1;
            padding: 9px 12px;
            background: #EEF5FF;
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            text-decoration: none;
            color: #1e293b;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.2s;
        }
        .btn-sso-item:hover {
            background: #DCE8FB;
        }

        .register-footer-text {
            text-align: center;
            font-size: 11px;
            color: #64748b;
            margin-bottom: 16px;
        }
        .register-footer-text a {
            color: #334EAC;
            font-weight: 700;
            text-decoration: none;
        }

        .security-badge-footer {
            text-align: center;
            font-size: 10px;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .security-badge-footer i {
            color: #059669;
        }

        /* --- BOTTOM ASSISTANCE BANNER --- */
        .assistance-banner {
            background: #EBF3FF;
            border: 1px solid #DCE8FB;
            border-radius: 16px;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        .assistance-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .assistance-icon {
            width: 44px;
            height: 44px;
            background: #DCE8FB;
            border-radius: 10px;
            color: #334EAC;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        .assistance-sub {
            font-size: 10px;
            font-weight: 700;
            color: #334EAC;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .assistance-title {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 2px;
        }
        .assistance-desc {
            font-size: 11px;
            color: #64748b;
        }
        .assistance-actions {
            display: flex;
            gap: 10px;
        }
        .btn-assistance-outline {
            padding: 9px 16px;
            border: 1px solid #334EAC;
            color: #334EAC;
            border-radius: 8px;
            text-decoration: none;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .btn-assistance-solid {
            padding: 9px 16px;
            background: #334EAC;
            color: #ffffff;
            border-radius: 8px;
            text-decoration: none;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* FOOTER */
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
                    <a href="{{ route('labs.index') }}" class="nav-link">Katalog Lab</a>
                    <a href="#" class="nav-link">Instrumen Riset</a>
                    <a href="#" class="nav-link">Alur Peminjaman</a>
                    <a href="#" class="nav-link">Daftarkan Lab</a>
                </div>

                <div class="nav-search">
                    <input type="text" placeholder="Cari...">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>

                <div class="nav-actions">
                    <a href="#" class="icon-btn" title="Keranjang"><i class="fa-solid fa-cart-shopping"></i></a>
                    <a href="#" class="icon-btn" title="Pengguna"><i class="fa-solid fa-user"></i></a>
                    <a href="#" class="icon-btn" title="Notifikasi"><i class="fa-solid fa-bell"></i></a>
                    <a href="#" class="icon-btn" title="Menu"><i class="fa-solid fa-bars"></i></a>
                </div>
            </nav>
        </div>

        <!-- MAIN LOGIN CARD (SPLIT 2 COLUMNS) -->
        <div class="login-card-container">

            <!-- LEFT BANNER -->
            <div class="login-left-banner">
                <h1 class="banner-title">Mulai Akses Ratusan <span>Laboratorium & Fasilitas Riset</span> Unggulan Kampus.</h1>
                <p class="banner-desc">Platform resmi integrasi peminjaman laboratorium universitas berstandar ISO bagi sekolah, universitas, periset mandiri, & mitra industri di seluruh Indonesia.</p>

                <div class="feature-box">
                    <div class="feature-icon"><i class="fa-solid fa-award"></i></div>
                    <div>
                        <div class="feature-title-row">
                            <span class="feature-title">500+ Laboratorium Terakreditasi KAN</span>
                            <span class="feature-tag">Terverifikasi</span>
                        </div>
                        <p class="feature-desc">Tersebar di 38 Provinsi dengan Kalibrasi Berkala dan standar pengujian bersertifikasi nasional.</p>
                    </div>
                </div>

                <div class="feature-box">
                    <div class="feature-icon"><i class="fa-solid fa-microscope"></i></div>
                    <div>
                        <div class="feature-title-row">
                            <span class="feature-title">Standardisasi Pengujian ISO/IEC 17025</span>
                            <span class="feature-tag">Global Standard</span>
                        </div>
                        <p class="feature-desc">Hasil data mentah (raw data) terjamin valid dan dapat dipertanggungjawabkan untuk publikasi ilmiah internasional.</p>
                    </div>
                </div>

                <div class="feature-box">
                    <div class="feature-icon"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                    <div>
                        <div class="feature-title-row">
                            <span class="feature-title">Kepatuhan Billing SIMPONI & PNBP Resmi</span>
                            <span class="feature-tag">Audit Ready</span>
                        </div>
                        <p class="feature-desc">Invoicing otomatis dengan kode billing resmi negara, akuntabel, dan audit-ready untuk LPDP, BIMA, maupun BPK.</p>
                    </div>
                </div>
            </div>

            <!-- RIGHT FORM -->
            <div class="login-right-form">

                <!-- ROLE TAB SWITCHER -->
                <div class="role-tab-wrapper">
                    <button type="button" class="role-tab-btn active" id="tabPenyewa">
                        <i class="fa-solid fa-graduation-cap"></i> Penyewa / Sekolah / Industri
                    </button>
                    <button type="button" class="role-tab-btn" id="tabPengelola">
                        <i class="fa-solid fa-building-user"></i> Pengelola Lab Kampus
                    </button>
                </div>

                <div class="sso-badge-label">Single Sign-On SSO</div>
                <h2 class="form-welcome-title">Selamat Datang Kembali</h2>
                <p class="form-welcome-sub">Masukkan kredensial akun instansi Anda untuk mengakses reservasi instrumen & status pengujian sampel laboratorium.</p>

                <!-- LOGIN FORM -->
                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="form-group-item">
                        <div class="form-label-row">
                            <span class="form-label-text">Email Instansi / ID Peneliti</span>
                            <span class="form-label-hint">Domain .sch.id / .ac.id / Lembaga</span>
                        </div>
                        <div class="input-icon-wrapper">
                            <i class="fa-regular fa-envelope left-icon"></i>
                            <input type="email" name="email" class="form-input-field" placeholder="contoh: nico.hermansyah@sman1pasuruan.sch.id" required autofocus>
                        </div>
                    </div>

                    <div class="form-group-item">
                        <div class="form-label-row">
                            <span class="form-label-text">Kata Sandi Akun</span>
                            <span class="form-label-hint" style="color: #64748b;">Kerahasiaan data ISO 27001</span>
                        </div>
                        <div class="input-icon-wrapper">
                            <i class="fa-solid fa-lock left-icon"></i>
                            <input type="password" name="password" id="passInput" class="form-input-field" placeholder="••••••••••••" required>
                            <i class="fa-regular fa-eye right-icon" onclick="togglePassword()"></i>
                        </div>
                    </div>

                    <div class="form-extra-options">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember"> Ingat saya di perangkat ini
                        </label>
                        <a href="#" class="forgot-link">Lupa Kata Sandi?</a>
                    </div>

                    <button type="submit" class="btn-login-submit">
                        Masuk ke Akun PALAPA <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>

                <div class="divider-row">
                    <div class="divider-line"></div>
                    <span class="divider-text">Atau Masuk Melalui Saluran Resmi</span>
                    <div class="divider-line"></div>
                </div>

                <div class="sso-buttons-row">
                    <a href="#" class="btn-sso-item">
                        <i class="fa-brands fa-google text-danger"></i> Akun Belajar.id / Google
                    </a>
                    <a href="#" class="btn-sso-item">
                        <i class="fa-solid fa-graduation-cap text-primary"></i> SSO Kedaireka / Dikti
                    </a>
                </div>

                <div class="register-footer-text">
                    Belum memiliki akun instansi atau lembaga riset? <a href="#">Daftar Penyewa Baru Di Sini</a>
                </div>

                <div class="security-badge-footer">
                    <i class="fa-solid fa-lock"></i> Enkripsi SSL 256-bit & Kepatuhan Kerahasian Data Riset Nasional
                </div>
            </div>

        </div>

        <!-- BOTTOM ASSISTANCE BANNER -->
        <div class="assistance-banner">
            <div class="assistance-left">
                <div class="assistance-icon"><i class="fa-solid fa-box-archive"></i></div>
                <div>
                    <div class="assistance-sub">Bimbingan & Fasilitas Khusus</div>
                    <div class="assistance-title">Butuh Dukungan Akreditasi Khusus atau Sewa Kolektif Praktikum Sekolah?</div>
                    <div class="assistance-desc">Tim kurasi instrumen PALAPA siap mendampingi pengajuan verifikasi lab, SPK pengadaan riset, asistensi instruktur bersertifikat, dan integrasi silabus praktikum Kurikulum Merdeka.</div>
                </div>
            </div>
            <div class="assistance-actions">
                <a href="#" class="btn-assistance-outline"><i class="fa-regular fa-file-pdf"></i> Panduan SOP Peminjaman</a>
                <a href="#" class="btn-assistance-solid"><i class="fa-solid fa-headset"></i> Hubungi Helpdesk Riset</a>
            </div>
        </div>

        <!-- FOOTER & MITRA -->
        <div class="partners-row">
            <span class="partners-label">MITRA & TERAKREDITASI RESMI:</span>
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
                <div class="footer-col-title">Marketplace Sains</div>
                <ul class="footer-links">
                    <li><a href="#">Layanan Uji</a></li>
                    <li><a href="#">Sewa Instrumen Canggih</a></li>
                    <li><a href="#">Jasa Laboran</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">Dukungan Riset</div>
                <ul class="footer-links">
                    <li><a href="#">Bantuan Peneliti</a></li>
                    <li><a href="#">Pedoman Keselamatan Lab</a></li>
                    <li><a href="#">Integrasi Hibah & SPK</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">Pengelola Fasilitas</div>
                <ul class="footer-links">
                    <li><a href="#">Daftarkan Lab Anda</a></li>
                    <li><a href="#">Standar Kalibrasi</a></li>
                    <li><a href="#">Asuransi Alat</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">Legal & Kontak</div>
                <ul class="footer-links">
                    <li><a href="#">Kebijakan Privasi</a></li>
                    <li><a href="#">Syarat & Ketentuan</a></li>
                    <li><a href="#">Kontak Labbook Indonesia</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; 2026 PALAPA Hak Cipta Dilindungi. Platform Kolaborasi & Sewa Lab Riset Nasional.</span>
            <span>Mendukung Kemandirian Riset & Inovasi Indonesia</span>
        </div>

    </div>

    <!-- SCRIPT TOGGLE PASSWORD -->
    <script>
        function togglePassword() {
            const passInput = document.getElementById('passInput');
            if (passInput.type === 'password') {
                passInput.type = 'text';
            } else {
                passInput.type = 'password';
            }
        }

        // Toggle Tab Role
        const tabPenyewa = document.getElementById('tabPenyewa');
        const tabPengelola = document.getElementById('tabPengelola');

        tabPenyewa.addEventListener('click', () => {
            tabPenyewa.classList.add('active');
            tabPengelola.classList.remove('active');
        });

        tabPengelola.addEventListener('click', () => {
            tabPengelola.classList.add('active');
            tabPenyewa.classList.remove('active');
        });
    </script>
</body>
</html>
