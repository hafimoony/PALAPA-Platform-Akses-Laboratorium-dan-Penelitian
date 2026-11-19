@extends('layouts.app')
@section('title', 'Dashboard Staff Laboratorium')

@section('content')
<div class="row mb-3">
    <div class="col-md-3">
        <div class="small-box text-bg-primary p-3 rounded">
            <h3>{{ $stats['total_labs'] }}</h3>
            <p class="mb-0">Total Laboratorium</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="small-box text-bg-info p-3 rounded">
            <h3>{{ $stats['total_equipments'] }}</h3>
            <p class="mb-0">Total Alat & Instrumen</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="small-box text-bg-warning p-3 rounded">
            <h3>{{ $stats['pending_bookings'] }}</h3>
            <p class="mb-0">Menunggu Verifikasi</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="small-box text-bg-success p-3 rounded">
            <h3>Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
            <p class="mb-0">Pendapatan Lunas</p>
        </div>
    </div>
</div>

<x-card judul="5 Pengajuan Reservasi Terbaru">
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Kode Booking</th>
                    <th>Laboratorium</th>
                    <th>Tanggal & Sesi</th>
                    <th>Status Booking</th>
                    <th>Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentBookings as $b)
                    <tr>
                        <td><strong>{{ $b->booking_code }}</strong></td>
                        <td>{{ $b->laboratory->lab_name ?? '-' }}</td>
                        <td>{{ $b->reservation_date }} ({{ $b->session_time }})</td>
                        <td><span class="badge bg-secondary">{{ $b->status }}</span></td>
                        <td><span class="badge bg-info text-dark">{{ $b->invoice->payment_status ?? '-' }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Belum ada transaksi reservasi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-card>
@endsection
