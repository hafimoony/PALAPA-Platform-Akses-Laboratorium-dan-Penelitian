@extends('layouts.app')
@section('title', 'Data Master: Alat & Instrumen')

@section('content')
<x-card judul="Daftar Alat & Instrumen Laboratorium">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <form method="GET" action="{{ route('staff.equipments.index') }}" class="d-flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari ID alat / nama / merek / serial...">
            <button type="submit" class="btn btn-secondary btn-sm">Cari</button>
            @if(request('search'))
                <a href="{{ route('staff.equipments.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            @endif
        </form>
        <a href="{{ route('staff.equipments.create') }}" class="btn btn-primary btn-sm">+ Tambah Alat</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th style="width: 130px;">ID Alat</th>
                    <th>Nama Alat</th>
                    <th>Laboratorium</th>
                    <th>Merek / Serial</th>
                    <th style="width: 70px;">Stok</th>
                    <th>Tarif / Unit</th>
                    <th style="width: 110px;">Status</th>
                    <th width="140">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($equipments as $eq)
                    <tr>
                        <td><code class="fw-bold" style="color: #091F5B;">{{ $eq->equipment_id }}</code></td>
                        <td><strong>{{ $eq->equipment_name }}</strong></td>
                        <td>{{ $eq->laboratory->lab_name ?? '-' }}</td>
                        <td>{{ $eq->brand_model ?? '-' }}<br><small class="text-muted">{{ $eq->serial_number ?? '-' }}</small></td>
                        <td>{{ $eq->quantity_total }}</td>
                        <td>Rp {{ number_format($eq->rental_price_per_unit, 0, ',', '.') }}</td>
                        <td>
                            @if ($eq->status === 'available')
                                <span class="badge bg-success">available</span>
                            @elseif ($eq->status === 'maintenance')
                                <span class="badge bg-warning text-dark">maintenance</span>
                            @else
                                <span class="badge bg-secondary">{{ $eq->status }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('staff.equipments.edit', $eq->equipment_id) }}" class="btn btn-warning btn-sm">Ubah</a>
                            <form action="{{ route('staff.equipments.destroy', $eq->equipment_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus alat ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted">Belum ada data alat laboratorium.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $equipments->links('pagination::bootstrap-5') }}
</x-card>
@endsection
