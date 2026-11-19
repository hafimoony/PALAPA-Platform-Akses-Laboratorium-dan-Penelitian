@extends('layouts.app')
@section('title', 'Check-In Entry Pass (T4)')

@section('content')
<x-card judul="Verifikasi Kedatangan & Entry Pass">
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>ID Pass</th>
                    <th>Kode QR / Pass</th>
                    <th>Kode Booking</th>
                    <th>Laboratorium</th>
                    <th>Status Check-In</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($passes as $p)
                    <tr>
                        <td><code>{{ $p->pass_id }}</code></td>
                        <td><strong>{{ $p->qr_code_data }}</strong></td>
                        <td>{{ $p->booking->booking_code ?? '-' }}</td>
                        <td>{{ $p->booking->laboratory->lab_name ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $p->checkin_status === 'checked_in' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $p->checkin_status }}
                            </span>
                            @if ($p->checked_in_at)
                                <br><small class="text-muted">{{ $p->checked_in_at }}</small>
                            @endif
                        </td>
                        <td>
                            @if ($p->checkin_status !== 'checked_in')
                                <form action="{{ route('staff.checkin.process', $p->pass_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">Konfirmasi Check-In</button>
                                </form>
                            @else
                                <span class="text-success small">Terverifikasi</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Belum ada Entry Pass yang diterbitkan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $passes->links('pagination::bootstrap-5') }}
</x-card>
@endsection
