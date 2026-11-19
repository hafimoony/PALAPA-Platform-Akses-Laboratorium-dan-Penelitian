@extends('layouts.app')
@section('title', 'Data Master: Kampus')

@section('content')
<x-card judul="Daftar Kampus">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <form method="GET" action="{{ route('staff.campuses.index') }}" class="d-flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari kampus / institusi...">
            <button type="submit" class="btn btn-secondary btn-sm">Cari</button>
        </form>
        <a href="{{ route('staff.campuses.create') }}" class="btn btn-primary btn-sm">+ Tambah Kampus</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Kampus</th>
                    <th>Institusi</th>
                    <th>Alamat</th>
                    <th>Jumlah Lab</th>
                    <th width="140">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($campuses as $c)
                    <tr>
                        <td><code>{{ $c->campus_id }}</code></td>
                        <td>{{ $c->campus_name }}</td>
                        <td>{{ $c->institution_name }}</td>
                        <td>{{ $c->address ?? '-' }}</td>
                        <td>{{ $c->laboratories_count }}</td>
                        <td>
                            <a href="{{ route('staff.campuses.edit', $c->campus_id) }}" class="btn btn-warning btn-sm">Ubah</a>
                            <form action="{{ route('staff.campuses.destroy', $c->campus_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kampus ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Belum ada data kampus.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $campuses->links('pagination::bootstrap-5') }}
</x-card>
@endsection
