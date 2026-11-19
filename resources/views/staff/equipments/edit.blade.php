@extends('layouts.app')
@section('title', 'Ubah Alat Laboratorium')

@section('content')
<x-card judul="Form Ubah Alat">
    <form method="POST" action="{{ route('staff.equipments.update', $equipment->equipment_id) }}">
        @csrf @method('PUT')
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Laboratorium</label>
                <select name="lab_id" class="form-select @error('lab_id') is-invalid @enderror">
                    @foreach ($laboratories as $lab)
                        <option value="{{ $lab->lab_id }}" {{ old('lab_id', $equipment->lab_id) == $lab->lab_id ? 'selected' : '' }}>{{ $lab->lab_name }}</option>
                    @endforeach
                </select>
                @error('lab_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Alat</label>
                <input type="text" name="equipment_name" value="{{ old('equipment_name', $equipment->equipment_name) }}" class="form-control @error('equipment_name') is-invalid @enderror">
                @error('equipment_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Merek / Model</label>
                <input type="text" name="brand_model" value="{{ old('brand_model', $equipment->brand_model) }}" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Nomor Seri</label>
                <input type="text" name="serial_number" value="{{ old('serial_number', $equipment->serial_number) }}" class="form-control @error('serial_number') is-invalid @enderror">
                @error('serial_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Jumlah Stok</label>
                <input type="number" name="quantity_total" value="{{ old('quantity_total', $equipment->quantity_total) }}" class="form-control @error('quantity_total') is-invalid @enderror">
                @error('quantity_total') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Harga Sewa / Unit (Rp)</label>
                <input type="number" name="rental_price_per_unit" value="{{ old('rental_price_per_unit', $equipment->rental_price_per_unit) }}" class="form-control @error('rental_price_per_unit') is-invalid @enderror">
                @error('rental_price_per_unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="available" {{ old('status', $equipment->status) == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="maintenance" {{ old('status', $equipment->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="unavailable" {{ old('status', $equipment->status) == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('staff.equipments.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</x-card>
@endsection
