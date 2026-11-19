@extends('layouts.palapa')

@section('title', 'Checkout & Pembayaran PNBP - PALAPA Platform')

@push('styles')
<style>
    .checkout-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 22px; }
    @media (max-width: 860px) { .checkout-grid { grid-template-columns: 1fr; } }
    .box-card {
        background: #ffffff; border: 1px solid #E2E8F0; border-radius: 18px;
        padding: 24px 28px; box-shadow: 0 6px 18px rgba(15, 23, 42, 0.04);
    }
    .box-title { font-size: 16px; font-weight: 800; color: #091F5B; margin-bottom: 14px; display: flex; align-items: center; gap: 8px; }
    .line-item {
        display: flex; justify-content: space-between; padding: 10px 0;
        border-bottom: 1px solid #F1F5F9; font-size: 13px;
    }
    .line-total {
        display: flex; justify-content: space-between; padding: 14px 16px; margin-top: 12px;
        background: #EBF3FF; border-radius: 12px; font-size: 16px; font-weight: 800; color: #091F5B;
    }
    .vendor-box {
        border: 1.5px solid #CBD5E1; border-radius: 12px; padding: 14px 16px;
        margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;
        background: #F8FAFC;
    }
    .vendor-box.active { border-color: #334EAC; background: #EEF5FF; }
</style>
@endpush

@section('content')
    <div class="page-hero">
        <div>
            <span class="page-hero-badge">SIMPONI PNBP • Gateway Resmi</span>
            <h1 class="page-hero-title">Checkout & Penyelesaian Tagihan</h1>
            <p class="page-hero-sub">Periksa rincian biaya sewa fasilitas, deposit alat, serta konfirmasi pembayaran reservasi Anda.</p>
        </div>
        <a href="{{ route('riwayat') }}" class="btn btn-light">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat
        </a>
    </div>

    @if ($booking)
        <div class="checkout-grid">
            <!-- RINGKASAN RINCIAN RESERVASI -->
            <div class="box-card">
                <div class="box-title"><i class="fa-solid fa-receipt"></i> Rincian Tagihan {{ $booking->booking_code }}</div>

                <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:12px; padding:14px 16px; margin-bottom:16px; font-size:13px;">
                    <div style="font-weight:800; color:#091F5B;">{{ $booking->laboratory->lab_name ?? '-' }}</div>
                    <div style="color:#64748b; font-size:12px; margin-top:2px;">
                        {{ $booking->laboratory->campus->campus_name ?? '' }} • {{ \Carbon\Carbon::parse($booking->reservation_date)->format('d M Y') }} ({{ $booking->session_time }})
                    </div>
                </div>

                <div class="line-item">
                    <span>Sewa Ruang Laboratorium (1 Sesi)</span>
                    <strong>Rp {{ number_format($booking->invoice->lab_rental_fee ?? 0, 0, ',', '.') }}</strong>
                </div>

                @foreach ($booking->equipmentItems as $item)
                    <div class="line-item">
                        <span>Instrumen: {{ $item->equipment->equipment_name ?? $item->equipment_id }} ({{ $item->quantity }}x)</span>
                        <strong>Rp {{ number_format($item->subtotal_price ?? 0, 0, ',', '.') }}</strong>
                    </div>
                @endforeach

                <div class="line-item">
                    <span>Biaya Asistensi Pranata Lab</span>
                    <strong>Rp {{ number_format($booking->invoice->technician_fee ?? 0, 0, ',', '.') }}</strong>
                </div>

                <div class="line-item">
                    <span>Deposit Jaminan Keamanan Alat (Refundable)</span>
                    <strong>Rp {{ number_format($booking->invoice->security_deposit_fee ?? 0, 0, ',', '.') }}</strong>
                </div>

                @if (($booking->invoice->discount_amount ?? 0) > 0)
                    <div class="line-item" style="color:#059669;">
                        <span>Diskon Sivitas Akademika</span>
                        <strong>- Rp {{ number_format($booking->invoice->discount_amount, 0, ',', '.') }}</strong>
                    </div>
                @endif

                <div class="line-total">
                    <span>Total Pembayaran</span>
                    <span>Rp {{ number_format($booking->invoice->grand_total ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- KANAL PEMBAYARAN & KONFIRMASI -->
            <div class="box-card">
                <div class="box-title"><i class="fa-solid fa-building-columns"></i> Kanal Virtual Account</div>

                @foreach ($vendors as $v)
                    <div class="vendor-box active">
                        <div>
                            <div style="font-weight:800; font-size:13px; color:#091F5B;">{{ $v->vendor_name }}</div>
                            <div style="font-size:11px; color:#64748b;">Kode Kanal: {{ $v->vendor_code }}</div>
                        </div>
                        <span style="background:#091F5B; color:#fff; font-size:10px; font-weight:700; padding:4px 10px; border-radius:6px;">
                            INSTAN
                        </span>
                    </div>
                @endforeach

                @php $isPaid = ($booking->invoice->payment_status ?? '') === 'paid_successful'; @endphp

                @if ($isPaid)
                    <div class="alert alert-success" style="margin-top:16px; margin-bottom:16px;">
                        <i class="fa-solid fa-circle-check"></i> Tagihan untuk reservasi ini sudah berstatus <strong>LUNAS (PAID)</strong>.
                    </div>
                    <a href="{{ route('client.invoice', $booking->booking_code) }}" class="btn btn-primary" style="width:100%; justify-content:center;">
                        <i class="fa-solid fa-file-invoice-dollar"></i> Lihat Bukti Invoice Lunas
                    </a>
                @else
                    <p style="font-size:12px; color:#64748b; margin:14px 0;">
                        Klik tombol konfirmasi di bawah untuk memverifikasi pembayaran tagihan SIMPONI secara otomatis.
                    </p>
                    <form action="{{ route('bookings.check-payment', $booking->booking_code) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success" style="width:100%; justify-content:center; padding:12px;">
                            <i class="fa-solid fa-check-circle"></i> Konfirmasi Pembayaran Sekarang
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @else
        <div class="box-card" style="text-align:center; padding:48px 24px;">
            <p style="color:#64748b; margin-bottom:16px;">Belum ada tagihan reservasi yang perlu di-checkout.</p>
            <a href="{{ route('labs.index') }}" class="btn btn-primary">Pilih Laboratorium Terlebih Dahulu</a>
        </div>
    @endif
@endsection
