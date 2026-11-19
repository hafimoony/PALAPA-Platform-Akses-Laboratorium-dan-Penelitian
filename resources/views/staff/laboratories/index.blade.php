@extends('layouts.app')
@section('title', 'Data Master: Laboratorium')

@section('content')
<x-card judul="Daftar Laboratorium">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <form method="GET" action="{{ route('staff.laboratories.index') }}" class="d-flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari lab / fakultas / kampus...">
            <button type="submit" class="btn btn-secondary btn-sm">Cari</button>
        </form>
        <a href="{{ route('staff.laboratories.create') }}" class="btn btn-primary btn-sm">+ Tambah Laboratorium</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>Nama Lab</th>
                    <th>Kampus & Fakultas</th>
                    <th>Kategori</th>
                    <th>Kapasitas</th>
                    <th>Tarif / Sesi</th>
                    <th>Status</th>
                    <th width="140">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laboratories as $lab)
                    <tr>
                        <td><strong>{{ $lab->lab_name }}</strong><br><small class="text-muted">{{ $lab->lab_id }}</small></td>
                        <td>{{ $lab->campus->campus_name ?? '-' }}<br><small>{{ $lab->faculty }}</small></td>
                        <td>{{ $lab->category }}</td>
                        <td>{{ $lab->capacity }} orang</td>
                        <td>Rp {{ number_format($lab->base_price_per_session, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $lab->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $lab->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('staff.laboratories.edit', $lab->lab_id) }}" class="btn btn-warning btn-sm">Ubah</a>
                            <form action="{{ route('staff.laboratories.destroy', $lab->lab_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus laboratorium ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted">Belum ada data laboratorium.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $laboratories->links('pagination::bootstrap-5') }}
</x-card>
@endsection
