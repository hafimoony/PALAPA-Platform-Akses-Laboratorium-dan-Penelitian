@extends('layouts.app')

@section('title', 'Profil & Pengaturan Akun Staff - PALAPA')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1" style="font-weight:800; color:#091F5B; font-size:22px;">Profil & Pengaturan Akun Staff</h1>
        <p class="text-muted mb-0" style="font-size:13px;">Kelola identitas administrator laboratorium dan perbarui kredensial kata sandi.</p>
    </div>
    <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-primary" style="border-radius:10px; font-weight:700; font-size:12px;">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Dashboard
    </a>
</div>

<!-- KARTU RINGKASAN AKUN -->
<div class="card mb-4">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3 p-4">
        <div class="d-flex align-items-center gap-3">
            <div style="width:60px; height:60px; border-radius:50%; background:linear-gradient(135deg,#091F5B,#334EAC); color:#fff; display:flex; align-items:center; justify-content:center; font-size:22px; font-weight:800;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <h5 class="mb-0" style="font-weight:800; color:#091F5B;">{{ auth()->user()->name }}</h5>
                <div style="font-size:13px; color:#475569;">{{ auth()->user()->email }}</div>
                <span style="font-size:11px; font-weight:700; color:#334EAC;">
                    <i class="fa-solid fa-user-shield"></i> Administrator / Pranata Laboratorium (Role 1)
                </span>
            </div>
        </div>
        <span style="background:#EBF3FF; color:#091F5B; border:1px solid #DCE8FB; padding:8px 14px; border-radius:10px; font-size:12px; font-weight:700;">
            ID Staff: {{ auth()->user()->user_id }}
        </span>
    </div>
</div>

<div class="row g-4">
    <!-- FORM UPDATE DATA PROFIL -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="fa-solid fa-user-pen me-1"></i> Pengaturan Data Profil
            </div>
            <div class="card-body p-4">
                <form action="{{ route('staff.profile.update') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size:12px;">Nama Lengkap & Gelar</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size:12px;">Email Instansi (Tidak dapat diubah)</label>
                        <input type="email" class="form-control" value="{{ auth()->user()->email }}" disabled style="opacity:0.75;">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold" style="font-size:12px;">Nomor Telepon / WhatsApp</label>
                        <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', auth()->user()->phone_number) }}" placeholder="0812xxxxxxxx">
                    </div>
                    <button type="submit" class="btn btn-primary px-4 py-2">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan Profil
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- FORM UBAH PASSWORD -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="fa-solid fa-key me-1"></i> Keamanan & Ubah Kata Sandi
            </div>
            <div class="card-body p-4">
                <form action="{{ route('staff.profile.password') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size:12px;">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password" class="form-control" placeholder="Masukkan kata sandi lama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size:12px;">Kata Sandi Baru (Minimal 8 Karakter)</label>
                        <input type="password" name="new_password" class="form-control" placeholder="Minimal 8 karakter" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold" style="font-size:12px;">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="new_password_confirmation" class="form-control" placeholder="Ulangi kata sandi baru" required>
                    </div>
                    <button type="submit" class="btn btn-primary px-4 py-2" style="background:#091F5B !important; border-color:#091F5B !important;">
                        <i class="fa-solid fa-shield-halved me-1"></i> Perbarui Kata Sandi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
