@extends('admin.layouts.app')
@section('content')
<div class="container-fluid py-4">
    {{-- HEADER --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </h3>
            <p class="text-muted mb-0">
                Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong>. Apa yang ingin Anda lakukan hari
                ini?
            </p>
        </div>
        <div>
            {{-- Badge gaya screenshot (Light Blue background, Blue Text) --}}
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill border-0 fs-6 fw-normal">
                <i class="bi bi-phone me-1"></i> {{ ucfirst(auth()->user()->role) }}
            </span>
        </div>
    </div>

    {{-- STATISTIC CARDS --}}
    <div class="row g-4 mb-4 mt-1">

        @if(auth()->user()->role === 'admin')
        {{-- ADMIN: TOTAL BUKU --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center p-4 h-100 rounded-4">
                <div class="mb-2">
                    <i class="bi bi-book text-primary" style="font-size: 2.5rem;"></i>
                </div>
                <small class="text-muted mb-1">Total Buku</small>
                <h3 class="fw-bold text-dark mb-0">{{ $data['totalBuku'] ?? 0 }}</h3>
            </div>
        </div>

        {{-- ADMIN: TOTAL USER --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center p-4 h-100 rounded-4">
                <div class="mb-2">
                    <i class="bi bi-people text-success" style="font-size: 2.5rem;"></i>
                </div>
                <small class="text-muted mb-1">Total User</small>
                <h3 class="fw-bold text-dark mb-0">{{ $data['totalUser'] ?? 0 }}</h3>
            </div>
        </div>

        {{-- ADMIN: DIPINJAM --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center p-4 h-100 rounded-4">
                <div class="mb-2">
                    <i class="bi bi-arrow-repeat text-warning" style="font-size: 2.5rem;"></i>
                </div>
                <small class="text-muted mb-1">Dipinjam</small>
                <h3 class="fw-bold text-dark mb-0">{{ $data['transaksiAktif'] ?? 0 }}</h3>
            </div>
        </div>

        {{-- ADMIN: TOTAL DENDA --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center p-4 h-100 rounded-4">
                <div class="mb-2">
                    <i class="bi bi-cash-stack text-info" style="font-size: 2.5rem;"></i>
                </div>
                <small class="text-muted mb-1">Total Denda</small>
                <h3 class="fw-bold text-dark mb-0">
                    Rp {{ number_format($data['totalDenda'] ?? 0, 0, ',', '.') }}
                </h3>
            </div>
        </div>

        {{-- ADMIN: DENDA BELUM DIBAYAR --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center p-4 h-100 rounded-4">
                <div class="mb-2">
                    <i class="bi bi-exclamation-circle {{ ($data['dendaBelumLunas'] ?? 0) > 0 ? 'text-danger' : 'text-success' }}" style="font-size: 2.5rem;"></i>
                </div>
                <small class="text-muted mb-1">Denda Belum Dibayar</small>
                <h3 class="fw-bold {{ ($data['dendaBelumLunas'] ?? 0) > 0 ? 'text-danger' : 'text-success' }} mb-0">
                    Rp {{ number_format($data['dendaBelumLunas'] ?? 0, 0, ',', '.') }}
                </h3>
            </div>
        </div>

        @else
        {{-- USER: DIPINJAM --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center p-4 h-100 rounded-4">
                <div class="mb-2">
                    <i class="bi bi-journal-bookmark text-primary" style="font-size: 2.5rem;"></i>
                </div>
                <small class="text-muted mb-1">Buku Dipinjam</small>
                <h3 class="fw-bold text-dark mb-0">{{ $data['dipinjam'] ?? 0 }}</h3>
            </div>
        </div>

        {{-- USER: TELAT --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center p-4 h-100 rounded-4">
                <div class="mb-2">
                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2.5rem;"></i>
                </div>
                <small class="text-muted mb-1">Terlambat</small>
                <h3 class="fw-bold text-dark mb-0">{{ $data['telat'] ?? 0 }}</h3>
            </div>
        </div>

        {{-- USER: DENDA --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center p-4 h-100 rounded-4">
                <div class="mb-2">
                    <i class="bi bi-cash text-danger" style="font-size: 2.5rem;"></i>
                </div>
                <small class="text-muted mb-1">Total Denda</small>
                <h3 class="fw-bold text-dark mb-0">
                    Rp {{ number_format($data['totalDenda'] ?? 0, 0, ',', '.') }}
                </h3>
            </div>
        </div>
        @endif

    </div>

    <hr class="my-5 text-muted opacity-25">

    {{-- NAVIGATION MENU (AKSES CEPAT) --}}
    <h5 class="fw-bold mb-4 text-dark">Akses Cepat</h5>

    <div class="row g-4">
        @if (auth()->user()->role === 'admin')

        {{-- AKSES ADMIN 1: DATA BUKU --}}
        <div class="col-md-4">
            <a href="/buku" class="card border-0 shadow-sm text-decoration-none h-100 hover-card rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded-4 p-3 me-3 d-flex align-items-center justify-content-center"
                            style="width: 65px; height: 65px;">
                            <i class="bi bi-database-gear fs-3"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Kelola Data Buku</h6>
                            <p class="text-muted small mb-0">Tambah, edit, atau hapus koleksi buku.</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- AKSES ADMIN 2: KELOLA TRANSAKSI (SESUAI PERMINTAAN) --}}
        <div class="col-md-4">
            <a href="/admin/transaksi" class="card border-0 shadow-sm text-decoration-none h-100 hover-card rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-info text-white rounded-4 p-3 me-3 d-flex align-items-center justify-content-center"
                            style="width: 65px; height: 65px;">
                            <i class="bi bi-arrow-left-right fs-3"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Data Transaksi</h6>
                            <p class="text-muted small mb-0">Pantau peminjaman dan pengembalian.</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- AKSES ADMIN 3: PENGATURAN DENDA (SESUAI PERMINTAAN) --}}
        <div class="col-md-4">
            <a href="/pengaturan" class="card border-0 shadow-sm text-decoration-none h-100 hover-card rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning text-white rounded-4 p-3 me-3 d-flex align-items-center justify-content-center"
                            style="width: 65px; height: 65px;">
                            <i class="bi bi-sliders fs-3"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Pengaturan Sistem</h6>
                            <p class="text-muted small mb-0">Atur denda dan batas waktu peminjaman.</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        @else

        {{-- USER MENU 1: PINJAM BUKU --}}
        <div class="col-md-6">
            <a href="{{ route('home.buku') }}"
                class="card border-0 shadow-sm text-decoration-none h-100 hover-card rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-info text-white rounded-4 p-3 me-3 d-flex align-items-center justify-content-center"
                            style="width: 65px; height: 65px;">
                            <i class="bi bi-search fs-3"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Lihat & Pinjam Buku</h6>
                            <p class="text-muted small mb-0">Cari buku favoritmu dan lakukan peminjaman online.</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- USER MENU 2: RIWAYAT --}}
        <div class="col-md-6">
            <a href="{{ route('home.riwayat') }}"
                class="card border-0 shadow-sm text-decoration-none h-100 hover-card rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning text-white rounded-4 p-3 me-3 d-flex align-items-center justify-content-center"
                            style="width: 65px; height: 65px;">
                            <i class="bi bi-clock-history fs-3"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Riwayat Peminjaman</h6>
                            <p class="text-muted small mb-0">Cek status buku yang sedang dipinjam atau dikembalikan.</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        @endif
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    /* Transisi mulus saat kursor diarahkan ke card Akses Cepat */
    .hover-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
        /* Hapus border-left biru agar lebih bersih seperti screenshot */
    }

    /* Memastikan padding dalam row statis rapi */
    .rounded-4 {
        border-radius: 1rem !important;
    }
</style>
@endpush
@endsection
