@extends('layouts.palapa')

@section('title', 'Riwayat Reservasi & Pembayaran - PALAPA Platform')

@push('styles')
<style>
    .booking-card {
        background: #ffffff; border: 1px solid #E2E8F0; border-radius: 18px;
        padding: 24px 28px; margin-bottom: 18px; box-shadow: 0 6px 18px rgba(15, 23, 42, 0.04);
        transition: transform 0.15s, box-shadow 0.15s;
    }
    .booking-card:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(15, 23, 42, 0.07); }
    .card-top {
        display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px;
        border-bottom: 1px solid #F1F5F9; padding-bottom: 16px; margin-bottom: 16px;
    }
    .booking-code { font-size: 17px; font-weight: 800; color: #091F5B; }
    .lab-subtitle { font-size: 13px; font-weight: 600; color: #475569; margin-top: 3px; }
    .badge {
        padding: 5px 12px; border-radius: 999px; font-size: 11px; font-weight: 700;
        text-transform: uppercase; display: inline-block;
    }
    .bg-warning { background: #FEF3C7; color: #92400E; }
    .bg-success { background: #DCFCE7; color: #166534; }
    .bg-info { background: #DBEAFE; color: #1E40AF; }
    .bg-danger { background: #FEE2E2; color: #991B1B; }
    .meta-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 14px; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 14px 18px;
    }
    .meta-label { font-size: 11px; color: #64748b; font-weight: 600; }
    .meta-val { font-size: 13px; font-weight: 700; color: #0f172a; margin-top: 3px; }
    .pass-strip {
        margin-top: 14px; background: #ECFDF5; border: 1px dashed #10B981;
        padding: 12px 16px; border-radius: 12px; font-size: 12px; color: #065F46;
        display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;
    }
    .card-footer-actions { margin-top: 16px; display: flex; justify-content: flex-end; gap: 10px; flex-wrap: wrap; }
</style>
@endpush

@section('content')
    <div class="page-hero">
        <div>
            <span class="page-hero-badge">Portal Penyewa Riset • SIMPONI Billing</span>
            <h1 class="page-hero-title">Riwayat Reservasi & Pembayaran</h1>
            <p class="page-hero-sub">Pantau status persetujuan laboran, tagihan PNBP, serta unduh Entry Pass kunjungan Anda.</p>
        </div>
        <a href="{{ route('labs.index') }}" class="btn btn-light">
            <i class="fa-solid fa-plus"></i> Ajukan Reservasi Baru
        </a>
    </div>

    @forelse ($bookings as $b)
        <div class="booking-card">
            <div class="card-top">
                <div>
                    <div class="booking-code">{{ $b->booking_code }}</div>
                    <div class="lab-subtitle">
                        <i class="fa-solid fa-flask" style="color:#334EAC;"></i>
                        {{ $b->laboratory->lab_name ?? '-' }} — {{ $b->laboratory->campus->campus_name ?? '' }}
                    </div>
                </div>
                <div style="text-align: right; display:flex; flex-direction:column; gap:6px; align-items:flex-end;">
                    <span class="badge {{ in_array($b->status, ['approved', 'completed']) ? 'bg-success' : ($b->status === 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                        Booking: {{ $b->status }}
                    </span>
                    <span class="badge {{ ($b->invoice->payment_status ?? '') === 'paid_successful' ? 'bg-success' : 'bg-info' }}">
                        Pembayaran: {{ $b->invoice->payment_status ?? 'unpaid' }}
                    </span>
                </div>
            </div>

            <div class="meta-grid">
                <div>
                    <div class="meta-label">Tanggal & Sesi Praktikum</div>
                    <div class="meta-val">{{ \Carbon\Carbon::parse($b->reservation_date)->format('d M Y') }} ({{ $b->session_time }})</div>
                </div>
                <div>
                    <div class="meta-label">Jumlah Peserta</div>
                    <div class="meta-val">{{ $b->total_participants }} Orang</div>
                </div>
                <div>
                    <div class="meta-label">Total Tagihan (Termasuk Deposit)</div>
                    <div class="meta-val" style="color:#091F5B;">Rp {{ number_format($b->invoice->grand_total ?? 0, 0, ',', '.') }}</div>
                </div>
                <div>
                    <div class="meta-label">Instrumen & Alat Dipinjam</div>
                    <div class="meta-val">
                        @if ($b->equipmentItems->count() > 0)
                            @foreach ($b->equipmentItems as $item)
                                {{ $item->equipment->equipment_name ?? $item->equipment_id }} ({{ $item->quantity }}x){{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        @else
                            Hanya Ruang Laboratorium
                        @endif
                    </div>
                </div>
            </div>

            @if ($b->rejection_reason)
                <div class="alert alert-error" style="margin-top:14px; margin-bottom:0;">
                    <strong>Alasan Penolakan Staff:</strong> {{ $b->rejection_reason }}
                </div>
            @endif

            @if ($b->entryPass)
                <div class="pass-strip">
                    <div>
                        <i class="fa-solid fa-qrcode"></i> <strong>Entry Pass Aktif:</strong>
                        <code>{{ $b->entryPass->qr_code_data }}</code>
                    </div>
                    <span>Status Kehadiran: <strong>{{ strtoupper($b->entryPass->checkin_status) }}</strong></span>
                </div>
            @endif

            <div class="card-footer-actions">
                <a href="{{ route('client.invoice', $b->booking_code) }}" class="btn btn-outline">
                    <i class="fa-solid fa-file-invoice-dollar"></i> Lihat Invoice
                </a>

                @if ($b->entryPass)
                    <a href="{{ route('client.entry-pass', $b->booking_code) }}" class="btn btn-primary">
                        <i class="fa-solid fa-id-card"></i> Buka Entry Pass
                    </a>
                @endif

                @if (($b->invoice->payment_status ?? '') !== 'paid_successful')
                    <form action="{{ route('bookings.check-payment', $b->booking_code) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-check-circle"></i> Konfirmasi Pembayaran (T3)
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="booking-card" style="text-align: center; padding: 48px 24px;">
            <i class="fa-solid fa-flask-vial" style="font-size: 38px; color: #94a3b8; margin-bottom: 12px;"></i>
            <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">Belum Ada Riwayat Reservasi</h3>
            <p style="font-size: 13px; color: #64748b; margin-bottom: 18px;">Mulai jelajahi ratusan laboratorium terakreditasi KAN untuk kegiatan riset Anda.</p>
            <a href="{{ route('labs.index') }}" class="btn btn-primary">Jelajahi Katalog Laboratorium</a>
        </div>
    @endforelse
@endsection
