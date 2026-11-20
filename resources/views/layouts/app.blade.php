<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Staff Laboratorium - PALAPA')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta2/dist/css/adminlte.min.css">
    <style>
        :root {
            --bs-font-sans-serif: 'Poppins', sans-serif !important;
            --bs-body-font-family: 'Poppins', sans-serif !important;
            --bs-primary: #334EAC !important;
        }
        body, .app-wrapper, h1, h2, h3, h4, h5, h6, p, a, span, div, table, th, td, input, select, textarea, button, label {
            font-family: 'Poppins', sans-serif !important;
        }
        .fa, .fas, .far, .fab, .fa-solid, .fa-regular, .fa-brands {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands" !important;
        }
        body, .app-wrapper {
            background-color: #f8fafc !important;
            color: #0f172a !important;
        }

        /* --- SIDEBAR PALAPA NAVY --- */
        .app-sidebar {
            background: linear-gradient(180deg, #091F5B 0%, #162E7A 100%) !important;
            border-right: none !important;
            box-shadow: 4px 0 20px rgba(9, 31, 91, 0.08);
        }
        .sidebar-brand {
            border-bottom: 1px solid rgba(255, 255, 255, 0.12) !important;
            padding: 14px 18px !important;
        }
        .brand-link {
            display: flex; align-items: center; gap: 10px;
            color: #ffffff !important; font-weight: 800; letter-spacing: 0.5px; text-decoration: none;
        }
        .brand-link img {
            height: 34px; width: 34px; object-fit: cover; border-radius: 8px; background: #fff; padding: 2px;
        }
        .nav-header {
            color: #93C5FD !important; font-size: 10px !important;
            font-weight: 700 !important; letter-spacing: 0.8px; margin-top: 10px;
        }
        .sidebar-menu .nav-link {
            color: #E2E8F0 !important; border-radius: 12px !important;
            margin: 3px 10px !important; padding: 10px 14px !important;
            font-size: 13px !important; font-weight: 500 !important; transition: all 0.2s;
        }
        .sidebar-menu .nav-link:hover {
            background: rgba(255, 255, 255, 0.12) !important; color: #ffffff !important;
        }
        .sidebar-menu .nav-link.active {
            background: #334EAC !important; color: #ffffff !important;
            font-weight: 700 !important; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* --- TOP HEADER --- */
        .app-header {
            background-color: #ffffff !important;
            border-bottom: 1px solid #E2E8F0 !important;
            padding: 8px 16px !important;
        }
        .staff-pill {
            background: #EBF3FF; color: #091F5B; padding: 6px 14px;
            border-radius: 999px; font-size: 12px; font-weight: 700;
            display: inline-flex; align-items: center; gap: 8px;
            text-decoration: none; border: 1px solid #DCE8FB; transition: all 0.2s;
        }
        .staff-pill:hover {
            background: #334EAC; color: #ffffff; border-color: #334EAC;
        }
        .btn-logout-pill {
            background: #FEE2E2; color: #DC2626; border: none; padding: 6px 14px;
            border-radius: 999px; font-size: 12px; font-weight: 700; cursor: pointer;
            transition: all 0.2s;
        }
        .btn-logout-pill:hover { background: #DC2626; color: #ffffff; }

        /* --- MAIN CONTENT & CARDS --- */
        .app-main { background-color: #f8fafc !important; padding-top: 20px; }
        .app-content-header h1, .app-content-header h3, .content-header h1 {
            font-weight: 800 !important; color: #091F5B !important; font-size: 22px !important;
        }
        .card {
            background-color: #ffffff !important;
            border: 1px solid #E2E8F0 !important;
            border-radius: 18px !important;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.04) !important;
            color: #0f172a !important;
            overflow: hidden;
        }
        .card-header {
            background-color: #EBF3FF !important;
            border-bottom: 1px solid #DCE8FB !important;
            color: #091F5B !important;
            font-weight: 700 !important;
            padding: 14px 20px !important;
        }
        .card-title { font-weight: 700 !important; color: #091F5B !important; font-size: 15px !important; }

        /* --- STAT BOXES SERAGAM PALAPA BLUE --- */
        .small-box {
            border-radius: 18px !important; padding: 20px 22px !important;
            box-shadow: 0 8px 20px rgba(9, 31, 91, 0.07) !important; border: none !important;
        }
        .small-box.bg-primary, .small-box.text-bg-primary {
            background: linear-gradient(135deg, #091F5B, #1E3A8A) !important; color: #ffffff !important;
        }
        .small-box.bg-info, .small-box.text-bg-info {
            background: linear-gradient(135deg, #1E3A8A, #334EAC) !important; color: #ffffff !important;
        }
        .small-box.bg-warning, .small-box.text-bg-warning {
            background: linear-gradient(135deg, #334EAC, #4F6FD8) !important; color: #ffffff !important;
        }
        .small-box.bg-success, .small-box.text-bg-success {
            background: #EBF3FF !important; color: #091F5B !important;
            border: 1px solid #DCE8FB !important; box-shadow: none !important;
        }
        .small-box h3 { font-weight: 800 !important; font-size: 26px !important; letter-spacing: -0.5px; }
        .small-box p { font-size: 13px !important; font-weight: 600 !important; opacity: 0.9; margin-bottom: 0 !important; }

        /* --- TABLES & BADGES --- */
        .table { color: #0f172a !important; margin-bottom: 0 !important; }
        .table thead th {
            background-color: #F8FAFC !important; color: #475569 !important;
            font-size: 11px !important; font-weight: 700 !important; text-transform: uppercase;
            border-bottom: 1px solid #E2E8F0 !important; padding: 12px 16px !important;
        }
        .table td {
            border-color: #F1F5F9 !important; padding: 12px 16px !important;
            font-size: 13px !important; vertical-align: middle !important;
        }
        .badge.bg-info, .badge.text-bg-info {
            background-color: #EBF3FF !important; color: #334EAC !important;
            border: 1px solid #DCE8FB; font-weight: 700 !important;
        }
        .badge.bg-secondary, .badge.text-bg-secondary {
            background-color: #F1F5F9 !important; color: #475569 !important;
            border: 1px solid #E2E8F0; font-weight: 700 !important;
        }
        .form-control, .form-select {
            background-color: #EEF5FF !important; border: 1px solid #CBD5E1 !important;
            border-radius: 10px !important; color: #0f172a !important; font-size: 13px !important;
            padding: 9px 14px !important;
        }
        .form-control:focus, .form-select:focus {
            background-color: #ffffff !important; border-color: #334EAC !important;
            box-shadow: 0 0 0 3px rgba(51, 78, 172, 0.15) !important;
        }
        .btn-primary {
            background-color: #334EAC !important; border-color: #334EAC !important;
            border-radius: 10px !important; font-weight: 700 !important; font-size: 12px !important;
        }
        .btn-primary:hover { background-color: #091F5B !important; border-color: #091F5B !important; }
        .btn-success, .btn-warning, .btn-danger, .btn-info, .btn-secondary {
            border-radius: 10px !important; font-weight: 700 !important; font-size: 12px !important;
        }
        .app-footer {
            background-color: #ffffff !important; border-top: 1px solid #E2E8F0 !important;
            color: #64748b !important; font-size: 12px !important;
        }
    </style>
    @stack('styles')
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">
    <!-- TOP HEADER -->
    <nav class="app-header navbar navbar-expand">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" style="color:#091F5B;">
                        <i class="fa-solid fa-bars"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-md-block">
                    <span class="nav-link fw-bold" style="color:#091F5B;">Portal Manajemen Laboratorium & PNBP</span>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <li class="nav-item">
                    <a href="{{ route('staff.profile') }}" class="staff-pill" title="Pengaturan Profil & Kata Sandi">
                        <i class="fa-solid fa-user-shield"></i>
                        {{ auth()->user()->name ?? 'Staff Lab' }}
                    </a>
                </li>
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-logout-pill">
                            <i class="fa-solid fa-right-from-bracket"></i> Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <!-- SIDEBAR -->
    <aside class="app-sidebar">
        <div class="sidebar-brand">
            <a href="{{ route('staff.dashboard') }}" class="brand-link">
                <img src="{{ asset('images/logo-palapa.jpg') }}" alt="PALAPA" onerror="this.src='https://placehold.co/36x36/FFFFFF/091F5B?text=P'">
                <span>PALAPA LAB</span>
            </a>
        </div>

        <div class="sidebar-wrapper">
            <nav class="mt-2">
                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
                    <li class="nav-item">
                        <a href="{{ route('staff.dashboard') }}" class="nav-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fa-solid fa-gauge-high"></i>
                            <p>Dashboard Staff</p>
                        </a>
                    </li>

                    <li class="nav-header">DATA MASTER</li>
                    <li class="nav-item">
                        <a href="{{ route('staff.campuses.index') }}" class="nav-link {{ request()->routeIs('staff.campuses.*') ? 'active' : '' }}">
                            <i class="nav-icon fa-solid fa-building-columns"></i>
                            <p>M1: Kampus</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('staff.laboratories.index') }}" class="nav-link {{ request()->routeIs('staff.laboratories.*') ? 'active' : '' }}">
                            <i class="nav-icon fa-solid fa-flask"></i>
                            <p>M2: Laboratorium</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('staff.equipments.index') }}" class="nav-link {{ request()->routeIs('staff.equipments.*') ? 'active' : '' }}">
                            <i class="nav-icon fa-solid fa-microscope"></i>
                            <p>M3: Alat & Instrumen</p>
                        </a>
                    </li>

                    <li class="nav-header">TRANSAKSI & LAPORAN</li>
                    <li class="nav-item">
                        <a href="{{ route('staff.bookings.index') }}" class="nav-link {{ request()->routeIs('staff.bookings.*') ? 'active' : '' }}">
                            <i class="nav-icon fa-solid fa-clipboard-check"></i>
                            <p>Verifikasi Reservasi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('staff.chat.index') }}" class="nav-link {{ request()->routeIs('staff.chat.*') ? 'active' : '' }}">
                            <i class="nav-icon fa-solid fa-headset"></i>
                            <p>Live Chat Operator</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('staff.checkin.index') }}" class="nav-link {{ request()->routeIs('staff.checkin.*') ? 'active' : '' }}">
                            <i class="nav-icon fa-solid fa-qrcode"></i>
                            <p>Check-In Entry Pass</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('staff.incidents.index') }}" class="nav-link {{ request()->routeIs('staff.incidents.*') ? 'active' : '' }}">
                            <i class="nav-icon fa-solid fa-triangle-exclamation"></i>
                            <p>Insiden & Refund</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('staff.reports.index') }}" class="nav-link {{ request()->routeIs('staff.reports.*') ? 'active' : '' }}">
                            <i class="nav-icon fa-solid fa-chart-column"></i>
                            <p>Laporan PNBP</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('staff.calendar') }}" class="nav-link {{ request()->routeIs('staff.calendar') ? 'active' : '' }}">
                            <i class="nav-icon fa-solid fa-calendar-days"></i>
                            <p>Kalender Jadwal Lab</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('staff.lab-management') }}" class="nav-link {{ request()->routeIs('staff.lab-management') ? 'active' : '' }}">
                            <i class="nav-icon fa-solid fa-sliders"></i>
                            <p>Manajemen Lab</p>
                        </a>
                    </li>

                    <li class="nav-header">AKUN</li>
                    <li class="nav-item">
                        <a href="{{ route('staff.profile') }}" class="nav-link {{ request()->routeIs('staff.profile*') ? 'active' : '' }}">
                            <i class="nav-icon fa-solid fa-user-gear"></i>
                            <p>Profil & Kata Sandi</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="app-main">
        <div class="app-content">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success rounded-3 border-0 shadow-sm">
                        <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger rounded-3 border-0 shadow-sm">
                        <i class="fa-solid fa-circle-exclamation me-1"></i> {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger rounded-3 border-0 shadow-sm">
                        <i class="fa-solid fa-circle-exclamation me-1"></i> {{ $errors->first() }}
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">Sistem Reservasi Laboratorium Nasional</div>
        <strong>&copy; 2026 PALAPA Platform.</strong> Hak Cipta Dilindungi.
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta2/dist/js/adminlte.min.js"></script>
@stack('scripts')
</body>
</html>
