@extends('layouts.palapa')

@section('title', 'Katalog Instrumen Riset - PALAPA Platform')

@push('styles')
<style>
    .filter-card {
        background: #ffffff; border: 1px solid #E2E8F0; border-radius: 16px;
        padding: 18px 22px; margin-bottom: 24px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
    }
    .filter-form { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; }
    .filter-input {
        flex: 1; min-width: 240px; padding: 10px 16px; border: 1px solid #CBD5E1;
        border-radius: 10px; font-family: inherit; font-size: 13px; background: #F8FAFC;
    }
    .filter-select {
        padding: 10px 16px; border: 1px solid #CBD5E1; border-radius: 10px;
        font-family: inherit; font-size: 13px; background: #F8FAFC;
    }
    .inst-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 20px; }
    .inst-card {
        background: #ffffff; border: 1px solid #E2E8F0; border-radius: 18px;
        padding: 22px; display: flex; flex-direction: column; justify-content: space-between;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.04); transition: transform 0.15s, box-shadow 0.15s;
    }
    .inst-card:hover { transform: translateY(-3px); box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08); }
    .inst-badge {
        display: inline-block; padding: 4px 10px; border-radius: 999px;
        font-size: 10px; font-weight: 700; text-transform: uppercase;
    }
    .status-available { background: #DCFCE7; color: #166534; }
    .status-maintenance { background: #FEF3C7; color: #92400E; }
    .inst-name { font-size: 16px; font-weight: 800; color: #091F5B; margin: 10px 0 4px 0; }
    .inst-brand { font-size: 12px; color: #64748b; font-weight: 600; margin-bottom: 12px; }
    .inst-meta {
        background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px;
        padding: 10px 12px; font-size: 12px; margin-bottom: 16px;
    }
    .inst-footer {
        display: flex; justify-content: space-between; align-items: center;
        border-top: 1px solid #F1F5F9; padding-top: 14px;
    }
    .inst-price { font-size: 15px; font-weight: 800; color: #334EAC; }
</style>
@endpush

@section('content')
    <div class="page-hero">
        <div>
            <span class="page-hero-badge">Terkalibrasi ISO/IEC 17025 • Siap Pakai</span>
            <h1 class="page-hero-title">Katalog Instrumen & Alat Riset</h1>
            <p class="page-hero-sub">Sewa instrumen analisis presisi tinggi beserta fasilitas laboratorium pendukung di berbagai kampus mitra.</p>
        </div>
        <a href="{{ route('labs.index') }}" class="btn btn-light">
            <i class="fa-solid fa-flask"></i> Lihat Semua Laboratorium
        </a>
    </div>

    <div class="filter-card">
        <form action="{{ route('instruments.index') }}" method="GET" class="filter-form">
            <input type="text" name="search" value="{{ request('search') }}" class="filter-input" placeholder="Cari nama instrumen atau merek (misal: Spektrofotometer, Shimadzu, Mikroskop)...">
            <select name="status" class="filter-select">
                <option value="">Semua Status Alat</option>
                <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Tersedia (Available)</option>
                <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Perawatan (Maintenance)</option>
            </select>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter"></i> Filter Instrumen</button>
            @if (request()->hasAny(['search', 'status']))
                <a href="{{ route('instruments.index') }}" class="btn btn-outline">Reset</a>
            @endif
        </form>
    </div>

    <div class="inst-grid">
        @forelse ($equipments as $eq)
            <div class="inst-card">
                <div>
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span class="inst-badge {{ $eq->status === 'available' ? 'status-available' : 'status-maintenance' }}">
                            {{ $eq->status === 'available' ? 'Tersedia (' . $eq->quantity_total . ' Unit)' : 'Maintenance' }}
                        </span>
                        <span style="font-size:11px; color:#94a3b8; font-weight:600;">SN: {{ $eq->serial_number ?? '-' }}</span>
                    </div>

                    <h3 class="inst-name">{{ $eq->equipment_name }}</h3>
                    <div class="inst-brand"><i class="fa-solid fa-microchip"></i> {{ $eq->brand_model ?? 'Standar Laboratorium' }}</div>

                    <div class="inst-meta">
                        <div style="font-weight:700; color:#1e293b;">
                            <i class="fa-solid fa-building-columns" style="color:#334EAC;"></i>
                            {{ $eq->laboratory->lab_name ?? 'Laboratorium Terpadu' }}
                        </div>
                        <div style="color:#64748b; margin-top:3px;">
                            {{ $eq->laboratory->campus->campus_name ?? '' }}
                        </div>
                    </div>
                </div>

                <div class="inst-footer">
                    <div>
                        <div style="font-size:10px; color:#64748b; font-weight:600;">Tarif Sewa / Sesi</div>
                        <div class="inst-price">Rp {{ number_format($eq->rental_price_per_unit, 0, ',', '.') }}</div>
                    </div>
                    @if ($eq->laboratory)
                        <a href="{{ route('labs.show', $eq->lab_id) }}" class="btn btn-primary">
                            Reservasi Lab <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="inst-card" style="grid-column: 1 / -1; text-align:center; padding:48px;">
                <p style="color:#64748b;">Tidak ada instrumen riset yang sesuai dengan pencarian Anda.</p>
            </div>
        @endforelse
    </div>
@endsection
