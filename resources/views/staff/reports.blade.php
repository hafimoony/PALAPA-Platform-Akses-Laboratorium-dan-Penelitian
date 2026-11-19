@extends('layouts.app')
@section('title', 'Laporan Rekapitulasi Reservasi & Pendapatan PNBP')

@section('content')
<x-card judul="Rekap Penggunaan Laboratorium (Query Builder Agregat)">
    <form method="GET" class="row g-2 mb-3">
        <div class="col-auto">
            <select name="campus_id" class="form-select form-select-sm">
                <option value="">-- Semua Kampus --</option>
                @foreach ($campuses as $c)
                    <option value="{{ $c->campus_id }}" {{ request('campus_id') == $c->campus_id ? 'selected' : '' }}>{{ $c->campus_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary btn-sm">Filter Laporan</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Laboratorium</th>
                    <th>Kampus</th>
                    <th>Kategori</th>
                    <th class="text-center">Jumlah Reservasi</th>
                    <th class="text-end">Total Pendapatan Lunas</th>
                </tr>
            </thead>
            <tbody>
                @php $grandReservasi = 0; $grandPendapatan = 0; @endphp
                @forelse ($rekap as $i => $row)
                    @php
                        $grandReservasi += $row->total_reservasi;
                        $grandPendapatan += $row->total_pendapatan;
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><strong>{{ $row->lab_name }}</strong></td>
                        <td>{{ $row->campus_name }}</td>
                        <td>{{ $row->category }}</td>
                        <td class="text-center">{{ $row->total_reservasi }}</td>
                        <td class="text-end">Rp {{ number_format($row->total_pendapatan, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Belum ada data laporan.</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="table-dark fw-bold">
                    <td colspan="4" class="text-end">TOTAL KESELURUHAN</td>
                    <td class="text-center">{{ $grandReservasi }}</td>
                    <td class="text-end">Rp {{ number_format($grandPendapatan, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</x-card>
@endsection
