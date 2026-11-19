@extends('layouts.app')
@section('title', 'Verifikasi Pengajuan Reservasi (T2)')

@section('content')
<x-card judul="Daftar Pengajuan Reservasi Client">
    <form method="GET" class="row g-2 mb-3">
        <div class="col-auto">
            <select name="status" class="form-select form-select-sm">
                <option value="">-- Semua Status --</option>
                <option value="pending_review" {{ request('status') == 'pending_review' ? 'selected' : '' }}>Pending Review</option>
                <option value="locked_unpaid" {{ request('status') == 'locked_unpaid' ? 'selected' : '' }}>Locked Unpaid</option>
                <option value="paid_confirmed" {{ request('status') == 'paid_confirmed' ? 'selected' : '' }}>Paid Confirmed</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Laboratorium</th>
                    <th>Jadwal & Peserta</th>
                    <th>Total Tagihan</th>
                    <th>Status</th>
                    <th width="260">Aksi Verifikasi Staff</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookings as $b)
                    <tr>
                        <td><strong>{{ $b->booking_code }}</strong></td>
                        <td>{{ $b->laboratory->lab_name ?? '-' }}</td>
                        <td>{{ $b->reservation_date }}<br><small>{{ $b->session_time }} ({{ $b->total_participants }} org)</small></td>
                        <td>Rp {{ number_format($b->invoice->grand_total ?? 0, 0, ',', '.') }}<br><small class="text-muted">{{ $b->invoice->payment_status ?? '-' }}</small></td>
                        <td>
                            <span class="badge {{ $b->status === 'approved' ? 'bg-success' : ($b->status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                {{ $b->status }}
                            </span>
                            @if ($b->entryPass)
                                <br><small class="text-success">Pass: {{ $b->entryPass->qr_code_data }}</small>
                            @endif
                        </td>
                        <td>
                            @if (!in_array($b->status, ['approved', 'completed']))
                                <form action="{{ route('staff.bookings.approve', $b->booking_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                                </form>
                                <form action="{{ route('staff.bookings.reject', $b->booking_id) }}" method="POST" class="d-inline mt-1">
                                    @csrf
                                    <input type="text" name="rejection_reason" placeholder="Alasan tolak..." required class="form-control form-control-sm d-inline-block w-auto">
                                    <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                </form>
                            @else
                                <span class="text-muted small">Sudah diverifikasi</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Belum ada data reservasi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $bookings->links('pagination::bootstrap-5') }}
</x-card>
@endsection
