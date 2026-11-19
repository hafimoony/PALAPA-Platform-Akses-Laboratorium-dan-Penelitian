@extends('layouts.palapa')

@section('title', 'Profil & Pengaturan Akun - PALAPA Platform')

@push('styles')
<style>
    .profile-summary-card {
        background: #ffffff; border: 1px solid #E2E8F0; border-radius: 18px;
        padding: 24px 28px; margin-bottom: 22px; box-shadow: 0 6px 18px rgba(15,23,42,0.04);
        display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;
    }
    .avatar-circle {
        width: 64px; height: 64px; border-radius: 50%; background: linear-gradient(135deg, #091F5B, #334EAC);
        color: #ffffff; display: flex; align-items: center; justify-content: center;
        font-size: 24px; font-weight: 800; flex-shrink: 0;
    }
    .settings-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(380px, 1fr)); gap: 22px; }
    .setting-card {
        background: #ffffff; border: 1px solid #E2E8F0; border-radius: 18px;
        padding: 26px 28px; box-shadow: 0 6px 18px rgba(15,23,42,0.04);
    }
    .setting-title {
        font-size: 16px; font-weight: 800; color: #091F5B; margin-bottom: 6px;
        display: flex; align-items: center; gap: 8px;
    }
    .setting-sub { font-size: 12px; color: #64748b; margin-bottom: 20px; }
    .form-group { margin-bottom: 16px; }
    .form-label { display: block; font-size: 12px; font-weight: 700; color: #1e293b; margin-bottom: 6px; }
    .form-input {
        width: 100%; padding: 11px 14px; background: #EEF5FF; border: 1px solid #CBD5E1;
        border-radius: 10px; font-family: inherit; font-size: 13px; color: #0f172a; transition: border 0.2s;
    }
    .form-input:focus { outline: none; border-color: #334EAC; background: #ffffff; }
    .badge-id {
        background: #EBF3FF; color: #334EAC; padding: 6px 12px; border-radius: 8px;
        font-size: 12px; font-weight: 700; border: 1px solid #DCE8FB;
    }
</style>
@endpush

@section('content')
    <div class="page-hero">
        <div>
            <span class="page-hero-badge">Keamanan Akun ISO 27001 • SSO PALAPA</span>
            <h1 class="page-hero-title">Profil & Pengaturan Akun</h1>
            <p class="page-hero-sub">Kelola informasi identitas peneliti/instansi serta perbarui kredensial kata sandi Anda.</p>
        </div>
        <a href="{{ route('riwayat') }}" class="btn btn-light">
            <i class="fa-solid fa-clock-rotate-left"></i> Lihat Riwayat Reservasi
        </a>
    </div>

    <div class="profile-summary-card">
        <div style="display:flex; align-items:center; gap:16px;">
            <div class="avatar-circle">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <h2 style="font-size:18px; font-weight:800; color:#091F5B;">{{ auth()->user()->name }}</h2>
                <div style="font-size:13px; color:#475569;">{{ auth()->user()->email }}</div>
                <span style="display:inline-block; margin-top:4px; font-size:11px; font-weight:700; color:#059669;">
                    <i class="fa-solid fa-circle-check"></i> Akun Penyewa Terverifikasi
                </span>
            </div>
        </div>
        <div class="badge-id">
            ID Peneliti: {{ auth()->user()->user_id }}
        </div>
    </div>

    <div class="settings-grid">
        <div class="setting-card">
            <div class="setting-title"><i class="fa-solid fa-user-pen"></i> Informasi Identitas Akun</div>
            <p class="setting-sub">Data ini akan tercantum pada dokumen Invoice resmi dan Lab Entry Pass.</p>
            <form action="{{ route('client.profile.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Lengkap / Penanggung Jawab</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', auth()->user()->name) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Instansi Terdaftar</label>
                    <input type="email" class="form-input" value="{{ auth()->user()->email }}" disabled style="opacity:0.75; cursor:not-allowed;">
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor Telepon / WhatsApp Aktif</label>
                    <input type="text" name="phone_number" class="form-input" value="{{ old('phone_number', auth()->user()->phone_number) }}" placeholder="0812xxxxxxxx">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Profil
                </button>
            </form>
        </div>

        <div class="setting-card">
            <div class="setting-title"><i class="fa-solid fa-shield-halved"></i> Keamanan & Ubah Kata Sandi</div>
            <p class="setting-sub">Gunakan minimal 8 karakter kombinasi huruf dan angka untuk menjaga keamanan akun.</p>
            <form action="{{ route('client.profile.password') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Kata Sandi Saat Ini</label>
                    <input type="password" name="current_password" class="form-input" placeholder="••••••••••••" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kata Sandi Baru</label>
                    <input type="password" name="new_password" class="form-input" placeholder="Minimal 8 karakter" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" name="new_password_confirmation" class="form-input" placeholder="Ulangi kata sandi baru" required>
                </div>
                <button type="submit" class="btn btn-primary" style="background:#091F5B;">
                    <i class="fa-solid fa-key"></i> Perbarui Kata Sandi
                </button>
            </form>
        </div>
    </div>
@endsection
