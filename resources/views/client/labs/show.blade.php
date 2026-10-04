@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb Nav -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('labs.index') }}">Katalog Lab</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $lab->lab_name }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                <img src="https://images.unsplash.com/photo-1562774053-701939374585?q=80&w=1000&auto=format&fit=crop" class="img-fluid w-100" style="max-height: 400px; object-fit: cover;" alt="{{ $lab->lab_name }}">
                <div class="card-body p-4">
                    <span class="badge bg-primary mb-2">{{ $lab->category }}</span>
                    <h2 class="fw-bold text-dark mb-1">{{ $lab->lab_name }}</h2>
                    <p class="text-primary fw-semibold mb-3"><i class="fa-solid fa-university me-1"></i> {{ $lab->campus_name }} - {{ $lab->faculty }}</p>

                    <hr>

                    <h5 class="fw-bold mb-3">Deskripsi & Lokasi</h5>
                    <p class="text-secondary mb-4">{{ $lab->address_description ?? 'Laboratorium riset berkualitas tinggi dilengkapi peralatan standar industri dan jaringan riset nasional.' }}</p>

                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-4">
                            <div class="p-3 bg-light rounded-3 text-center">
                                <i class="fa-solid fa-users text-primary fs-4 mb-2"></i>
                                <span class="d-block text-muted small">Kapasitas</span>
                                <strong class="text-dark">{{ $lab->capacity }} Orang</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="p-3 bg-light rounded-3 text-center">
                                <i class="fa-solid fa-award text-success fs-4 mb-2"></i>
                                <span class="d-block text-muted small">Akreditasi</span>
                                <strong class="text-dark">ISO 17025 / KAN</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="p-3 bg-light rounded-3 text-center">
                                <i class="fa-solid fa-clock text-warning fs-4 mb-2"></i>
                                <span class="d-block text-muted small">Sesi Operasional</span>
                                <strong class="text-dark">08:00 - 16:00 WIB</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Peralatan / Lab Equipments -->
                    <h5 class="fw-bold mb-3">Peralatan Tersedia untuk Disewa</h5>
                    <div class="list-group list-group-flush rounded-3 border">
                        @forelse($lab->equipments as $equipment)
                        <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <div>
                                <h6 class="mb-1 fw-bold text-dark">{{ $equipment->equipment_name }}</h6>
                                <span class="text-muted small"><i class="fa-solid fa-tag me-1"></i> Model: {{ $equipment->brand_model ?? 'Standard Model' }}</span>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-success d-block">Rp {{ number_format($equipment->rental_price_per_unit, 0, ',', '.') }} / unit</span>
                                <span class="badge bg-info text-dark">Stok: {{ $equipment->quantity_total }} unit</span>
                            </div>
                        </div>
                        @empty
                        <div class="p-3 text-center text-muted">Belum ada instrumen peralatan tambahan yang didaftarkan pada lab ini.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Sticky Pricing & Action -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 p-4 sticky-top" style="top: 90px;">
                <h5 class="fw-bold mb-3">Ringkasan Reservasi</h5>
                <div class="mb-3">
                    <span class="text-muted small d-block">Biaya Sewa Tempat (Per Sesi)</span>
                    <h3 class="fw-bold text-primary">Rp {{ number_format($lab->base_price_per_session, 0, ',', '.') }}</h3>
                </div>

                <ul class="list-unstyled text-muted small mb-4">
                    <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Termasuk penggunaan fasilitas standar ruangan</li>
                    <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Opsional pendampingan asisten teknisi lab</li>
                    <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Opsi sewa peralatan uji tambahan</li>
                </ul>

                <!-- Tombol Lanjut ke Form Booking Step 1 -->
                <a href="{{ route('bookings.step1', ['lab_id' => $lab->lab_id]) }}" class="btn btn-primary btn-lg w-100 fw-bold rounded-pill">
                    Mulai Reservasi <i class="fa-solid fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
