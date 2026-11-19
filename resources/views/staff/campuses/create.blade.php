@extends('layouts.app')
@section('title', 'Tambah Kampus')

@section('content')
<x-card judul="Form Tambah Kampus">
    <form method="POST" action="{{ route('staff.campuses.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nama Kampus</label>
            <input type="text" name="campus_name" value="{{ old('campus_name') }}" class="form-control @error('campus_name') is-invalid @enderror">
            @error('campus_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Institusi</label>
            <input type="text" name="institution_name" value="{{ old('institution_name') }}" class="form-control @error('institution_name') is-invalid @enderror">
            @error('institution_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('staff.campuses.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</x-card>
@endsection
