@extends('layouts.palapa')

@section('title', 'Lab Entry Pass - ' . $booking->booking_code)

@push('styles')
<style>
    .pass-wrapper { max-width: 680px; margin: 0 auto; }
    .pass-ticket {
        background: #ffffff; border: 1px solid #CBD5E1; border-radius: 22px;
        overflow: hidden; box-shadow: 0 14px 32px rgba(9, 31, 91, 0.08);
    }
    .ticket-top {
        background: linear-gradient(135deg, #091F5B 0%, #334EAC 100%);
        color: #ffffff; padding: 26px 32px; display: flex; justify-content: space-between; align-items: center;
    }
    .ticket-body { padding: 28px 32px; }
    .qr-panel {
        background: #EEF5FF; border: 2px dashed #334EAC; border-radius: 16px;
        padding: 24px; text-align: center; margin-bottom: 24px;
    }
    .qr-token {
        font-family: monospace; font-size: 16px; font-weight: 800; color: #091F5B;
        background: #ffffff; display: inline-block; padding: 6px 14px; border-radius: 8px;
        border: 1px solid #CBD5E1; margin-top: 12px;
    }
    .detail-list { border-top: 1px solid #F1F5F9; }
    .detail-item {
        display: flex; justify-content: space-between; padding: 12px 0;
        border-bottom: 1px solid #F1F5F9; font-size: 13px;
    }
    .detail-label { color: #64748b; font-weight: 500; }
    .detail-value { font-weight: 700; color: #0f172a; text-align: right; }
    .status-pill {
        display: inline-block; padding: 5px 12px; border-radius: 999px;
        font-size: 11px; font-weight: 700; background: #DCFCE7; color: #166534; margin-top: 10px;
    }
</style>
@endpush

@section('content')
    <div class="page-hero">
        <div>
            <span class="page-hero-badge">Akses Masuk Fasilitas • Terverifikasi</span>
            <h1 class="page-hero-title">Digital Lab Entry Pass</h1>
            <p class="page-hero-sub">Tunjukkan kode akses ini kepada Laboran saat melakukan check-in di lokasi praktikum.</p>
        </div>
        <div style="display:flex; gap:10px;">
            <a href="{{ route('riwayat') }}" class="btn btn-light"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
            <button onclick="window.print()" class="btn btn-primary"><i class="fa-solid fa-print"></i> Cetak Pass</button>
        </div>
    </div>

    <div class="pass-wrapper">
        <div class="pass-ticket">
            <div class="ticket-top">
                <div>
                    <div style="font-size:11px; text-transform:uppercase; letter-spacing:1px; color:#93C5FD; font-weight:700;">PALAPA Official Access</div>
                    <div style="font-size:20px; font-weight:800; margin-top:2px;">{{ $booking->booking_code }}</div>
                </div>
                <i class="fa-solid fa-shield-check" style="font-size:32px; color:#60A5FA;"></i>
            </div>

            <div class="ticket-body">
                @if ($booking->entryPass)
                    <div class="qr-panel">
                        <i class="fa-solid fa-qrcode" style="font-size: 92px; color: #091F5B;"></i>
                        <br>
                        <div class="qr-token">{{ $booking->entryPass->qr_code_data }}</div>
                        <br>
                        <span class="status-pill">STATUS CHECK-IN: {{ strtoupper($booking->entryPass->checkin_status) }}</span>
                    </div>

                    <div class="detail-list">
                        <div class="detail-item">
                            <span class="detail-label">Penanggung Jawab Rombongan</span>
                            <span class="detail-value">{{ $booking->baseTransaction->user->name ?? auth()->user()->name }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Laboratorium Tujuan</span>
                            <span class="detail-value">{{ $booking->laboratory->lab_name ?? '-' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Lokasi Kampus</span>
                            <span class="detail-value">{{ $booking->laboratory->campus->campus_name ?? '-' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Tanggal & Sesi Kunjungan</span>
                            <span class="detail-value">{{ \Carbon\Carbon::parse($booking->reservation_date)->format('d M Y') }} ({{ $booking->session_time }})</span>
                        </div>
                        <div class="detail-item" style="border-bottom:none;">
                            <span class="detail-label">Jumlah Peserta Terdaftar</span>
                            <span class="detail-value">{{ $booking->total_participants }} Orang</span>
                        </div>
                    </div>
                @else
                    <div style="text-align:center; padding:32px 16px;">
                        <i class="fa-solid fa-hourglass-half" style="font-size:40px; color:#F59E0B; margin-bottom:12px;"></i>
                        <h3 style="font-size:16px; font-weight:800; color:#091F5B; margin-bottom:6px;">Entry Pass Menunggu Persetujuan</h3>
                        <p style="font-size:13px; color:#64748b;">Entry Pass akan diterbitkan secara otomatis segera setelah pengajuan reservasi Anda disetujui oleh Staff Laboratorium.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
