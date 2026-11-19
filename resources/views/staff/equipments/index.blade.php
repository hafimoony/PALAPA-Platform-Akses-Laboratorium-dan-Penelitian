@extends('layouts.app')
@section('title', 'Data Master: Alat & Instrumen')

@section('content')
<x-card judul="Daftar Alat & Instrumen Laboratorium">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <form method="GET" action="{{ route('staff.equipments.index') }}" class="d-flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari alat / merek / serial...">
            <button type="submit" class="btn btn-secondary btn-sm">Cari</button>
        </form>
        <a href="{{ route('staff.equipments.create') }}" class="btn btn-primary btn-sm">+ Tambah Alat</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>Nama Alat</th>
                    <th>Laboratorium</th>
                    <th>Merek / Serial</th>
                    <th>Stok</th>
                    <th>Tarif / Unit</th>
                    <th>Status</th>
                    <th width="140">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($equipments as $eq)
                    <tr>
                        <td><strong>{{ $eq->equipment_name }}</strong></td>
                        <td>{{ $eq->laboratory->lab_name ?? '-' }}</td>
                        <td>{{ $eq->brand_model ?? '-' }}<br><small class="text-muted">{{ $eq->serial_number ?? '-' }}</small></td>
                        <td>{{ $eq->quantity_total }}</td>
                        <td>Rp {{ number_format($eq->rental_price_per_unit, 0, ',', '.') }}</td>
                        <td><span class="badge bg-info text-dark">{{ $eq->status }}</span></td>
                        <td>
                            <a href="{{ route('staff.equipments.edit', $eq->equipment_id) }}" class="btn btn-warning btn-sm">Ubah</a>
                            <form action="{{ route('staff.equipments.destroy', $eq->equipment_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus alat ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted">Belum ada data alat laboratorium.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $equipments->links('pagination::bootstrap-5') }}
</x-card>
@endsection
