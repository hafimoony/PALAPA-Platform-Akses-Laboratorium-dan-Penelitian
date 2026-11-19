@php $role = session('user_role'); @endphp
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('home') }}" class="brand-link">
            <span class="brand-text fw-bold">PALAPA LAB</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
                @if ($role == 1)
                    <li class="nav-item">
                        <a href="{{ route('staff.dashboard') }}" class="nav-link {{ request()->is('staff/dashboard') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-speedometer"></i><p>Dashboard Staff</p>
                        </a>
                    </li>
                    <li class="nav-header">DATA MASTER</li>
                    <li class="nav-item">
                        <a href="{{ route('staff.campuses.index') }}" class="nav-link {{ request()->is('staff/campuses*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-building"></i><p>M1: Kampus</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('staff.laboratories.index') }}" class="nav-link {{ request()->is('staff/laboratories*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-hospital"></i><p>M2: Laboratorium</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('staff.equipments.index') }}" class="nav-link {{ request()->is('staff/equipments*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-tools"></i><p>M3: Alat & Instrumen</p>
                        </a>
                    </li>
                    <li class="nav-header">TRANSAKSI & LAPORAN</li>
                    <li class="nav-item">
                        <a href="{{ route('staff.bookings.index') }}" class="nav-link {{ request()->is('staff/bookings*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-check2-square"></i><p>Verifikasi Reservasi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('staff.checkin.index') }}" class="nav-link {{ request()->is('staff/checkin*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-qr-code-scan"></i><p>Check-In Entry Pass</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('staff.incidents.index') }}" class="nav-link {{ request()->is('staff/incidents*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-exclamation-triangle"></i><p>Insiden & Refund</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('staff.reports.index') }}" class="nav-link {{ request()->is('staff/reports*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-bar-chart-line"></i><p>Laporan PNBP</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
