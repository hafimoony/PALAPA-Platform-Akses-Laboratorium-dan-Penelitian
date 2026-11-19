@extends('layouts.app')
@section('title', 'Tambah Laboratorium')

@section('content')
<x-card judul="Form Tambah Laboratorium">
    <form method="POST" action="{{ route('staff.laboratories.store') }}">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Kampus</label>
                <select name="campus_id" class="form-select @error('campus_id') is-invalid @enderror">
                    <option value="">-- Pilih Kampus --</option>
                    @foreach ($campuses as $c)
                        <option value="{{ $c->campus_id }}" {{ old('campus_id') == $c->campus_id ? 'selected' : '' }}>{{ $c->campus_name }}</option>
                    @endforeach
                </select>
                @error('campus_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Fakultas</label>
                <input type="text" name="faculty" value="{{ old('faculty') }}" class="form-control @error('faculty') is-invalid @enderror">
                @error('faculty') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Laboratorium</label>
                <input type="text" name="lab_name" value="{{ old('lab_name') }}" class="form-control @error('lab_name') is-invalid @enderror">
                @error('lab_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Kategori</label>
                <input type="text" name="category" value="{{ old('category') }}" placeholder="Kimia / Komputer / Biologi" class="form-control @error('category') is-invalid @enderror">
                @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Kapasitas (Orang)</label>
                <input type="number" name="capacity" value="{{ old('capacity') }}" class="form-control @error('capacity') is-invalid @enderror">
                @error('capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Tarif Sewa / Sesi (Rp)</label>
                <input type="number" name="base_price_per_session" value="{{ old('base_price_per_session') }}" class="form-control @error('base_price_per_session') is-invalid @enderror">
                @error('base_price_per_session') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12 mb-3">
                <label class="form-label">Deskripsi Lokasi</label>
                <textarea name="address_description" rows="2" class="form-control">{{ old('address_description') }}</textarea>
            </div>
            <div class="col-12 mb-3 form-check ms-2">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" checked>
                <label class="form-check-label" for="is_active">Aktif untuk Reservasi</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('staff.laboratories.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</x-card>
@endsection
