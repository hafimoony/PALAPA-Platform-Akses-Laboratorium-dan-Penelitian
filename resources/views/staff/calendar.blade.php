@extends('layouts.app')

@section('title', 'Kalender Jadwal Laboratorium - PALAPA')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
        <h1 class="mb-1" style="font-weight:800; color:#091F5B; font-size:22px;">Kalender Okupansi & Jadwal Praktikum</h1>
        <p class="text-muted mb-0" style="font-size:13px;">Pantau jadwal pemakaian ruang laboratorium dan sesi praktikum berdasarkan bulan.</p>
    </div>

    <form action="{{ route('staff.calendar') }}" method="GET" class="d-flex gap-2 align-items-center">
        <input type="month" name="month" value="{{ $month }}" class="form-control" style="width:180px;">
        <button type="submit" class="btn btn-primary px-3 py-2">
            <i class="fa-solid fa-calendar-check me-1"></i> Tampilkan
        </button>
    </form>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fa-solid fa-calendar-days me-1"></i> Agenda Reservasi Bulan {{ $start->translatedFormat('F Y') }}</span>
        <span class="badge bg-info">{{ $bookings->count() }} Jadwal Terdaftar</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Sesi Waktu</th>
                        <th>Kode Booking</th>
                        <th>Laboratorium & Kampus</th>
                        <th>Penyewa / Instansi</th>
                        <th>Peserta</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $b)
                        <tr>
                            <td class="fw-bold" style="color:#091F5B;">
                                {{ \Carbon\Carbon::parse($b->reservation_date)->format('d M Y') }}
                            </td>
                            <td><span class="badge bg-secondary">{{ $b->session_time }}</span></td>
                            <td class="fw-bold">{{ $b->booking_code }}</td>
                            <td>
                                <div class="fw-bold">{{ $b->laboratory->lab_name ?? '-' }}</div>
                                <small class="text-muted">{{ $b->laboratory->campus->campus_name ?? '' }}</small>
                            </td>
                            <td>{{ $b->baseTransaction->user->name ?? '-' }}</td>
                            <td>{{ $b->total_participants }} Orang</td>
                            <td>
                                <span class="badge {{ in_array($b->status, ['approved', 'completed']) ? 'bg-success' : 'bg-info' }}">
                                    {{ strtoupper($b->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                Tidak ada jadwal reservasi laboratorium pada bulan {{ $start->translatedFormat('F Y') }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
