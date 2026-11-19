@extends('layouts.app')

@section('title', 'Manajemen Operasional Laboratorium - PALAPA')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
        <h1 class="mb-1" style="font-weight:800; color:#091F5B; font-size:22px;">Manajemen Operasional & Kesiapan Lab</h1>
        <p class="text-muted mb-0" style="font-size:13px;">Kontrol kesiapan ruang laboratorium, kapasitas peserta, dan ketersediaan instrumen terpasang.</p>
    </div>
    <a href="{{ route('staff.laboratories.index') }}" class="btn btn-primary px-3 py-2">
        <i class="fa-solid fa-sliders me-1"></i> Kelola Master Data Lab (M2)
    </a>
</div>

<div class="row g-3">
    @forelse ($laboratories as $lab)
        @php
            $status = $lab->operational_status ?? 'active';
            $isActive = $status === 'active';
        @endphp
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold" style="font-size:12px;">{{ $lab->lab_id }}</span>
                    <span class="badge {{ $isActive ? 'bg-success' : 'bg-warning text-dark' }}">
                        {{ $isActive ? 'OPERASIONAL (AKTIF)' : 'MAINTENANCE' }}
                    </span>
                </div>
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="fw-bold mb-1" style="color:#091F5B; font-size:15px;">{{ $lab->lab_name }}</h5>
                        <p class="text-muted mb-3" style="font-size:12px;">
                            <i class="fa-solid fa-building-columns me-1"></i> {{ $lab->campus->campus_name ?? '-' }}
                        </p>

                        <div class="p-3 mb-3" style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:12px; font-size:12px;">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Kapasitas Ruang:</span>
                                <strong>{{ $lab->capacity }} Orang</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Instrumen Terpasang:</span>
                                <strong>{{ $lab->equipments->count() }} Unit</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Total Reservasi Aktif:</span>
                                <strong>{{ $lab->bookings_count }} Sesi</strong>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('staff.lab-management.toggle', $lab->lab_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn w-100 {{ $isActive ? 'btn-outline-secondary' : 'btn-primary' }}" style="border-radius:10px; font-weight:700; font-size:12px;">
                            <i class="fa-solid fa-power-off me-1"></i>
                            {{ $isActive ? 'Setel Mode Maintenance' : 'Aktifkan Kembali Lab' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card p-5 text-center text-muted">Belum ada data laboratorium terdaftar.</div>
        </div>
    @endforelse
</div>
@endsection
