<style>
    .navbar-wrapper { padding: 20px 0; }
    .navbar {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
        background: #EBF3FF; padding: 8px 16px 8px 24px; border-radius: 9999px;
    }
    .nav-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; }
    .nav-brand img { height: 38px; width: auto; object-fit: contain; border-radius: 8px; }
    .nav-links { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
    .nav-link {
        padding: 8px 16px; border-radius: 12px; text-decoration: none;
        color: #1e293b; font-size: 13px; font-weight: 500; transition: all 0.2s;
    }
    .nav-link:hover, .nav-link.active { background: #334EAC; color: #ffffff; font-weight: 600; }
    .nav-search { position: relative; display: flex; align-items: center; }
    .nav-search input {
        background: #DCE8FB; padding: 8px 36px 8px 16px; border-radius: 20px;
        width: 200px; border: none; outline: none; font-family: inherit; font-size: 12px; color: #334155;
    }
    .nav-search i { position: absolute; right: 14px; color: #64748b; font-size: 12px; }
    .nav-actions { display: flex; align-items: center; gap: 8px; }
    .icon-btn {
        width: 34px; height: 34px; border-radius: 50%; background: #091F5B; color: #ffffff;
        display: flex; align-items: center; justify-content: center; text-decoration: none;
        font-size: 13px; border: none; cursor: pointer; transition: background 0.2s;
    }
    .icon-btn:hover, .icon-btn.active { background: #334EAC; }
</style>

<div class="navbar-wrapper">
    <nav class="navbar">
        <a href="{{ route('home') }}" class="nav-brand">
            <img src="{{ asset('images/logo-palapa.jpg') }}" alt="PALAPA Logo">
        </a>

        <div class="nav-links">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('labs.index') }}" class="nav-link {{ request()->routeIs('labs.*') ? 'active' : '' }}">Katalog Lab</a>
            <a href="{{ route('instruments.index') }}" class="nav-link {{ request()->routeIs('instruments.*') ? 'active' : '' }}">Instrumen Riset</a>
            <a href="{{ route('bantuan') }}" class="nav-link {{ request()->routeIs('bantuan') ? 'active' : '' }}">Pusat Bantuan</a>
            <a href="{{ auth()->check() ? route('riwayat') : route('login') }}" class="nav-link {{ request()->routeIs('riwayat') || request()->routeIs('client.invoice') || request()->routeIs('client.entry-pass') ? 'active' : '' }}">Riwayat Reservasi</a>
        </div>

        <form action="{{ route('labs.index') }}" method="GET" class="nav-search">
            <input type="text" name="search" placeholder="Pencarian">
            <i class="fa-solid fa-magnifying-glass"></i>
        </form>

        <div class="nav-actions">
            <a href="{{ auth()->check() ? route('riwayat') : route('login') }}" class="icon-btn {{ request()->routeIs('riwayat') ? 'active' : '' }}" title="Riwayat Reservasi">
                <i class="fa-solid fa-cart-shopping"></i>
            </a>
            <a href="{{ auth()->check() ? route('client.profile') : route('login') }}" class="icon-btn {{ request()->routeIs('client.profile') ? 'active' : '' }}" title="Profil & Pengaturan Akun">
                <i class="fa-solid fa-user"></i>
            </a>
            @auth
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="icon-btn" style="background:#DC2626; border:none; cursor:pointer;" title="Keluar Akun">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            @endauth
        </div>
    </nav>
</div>
