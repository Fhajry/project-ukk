@extends('layouts.app')

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
            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill border border-primary-subtle">
                <i class="bi bi-person-badge me-1"></i> {{ ucfirst(auth()->user()->role) }}
            </span>
        </div>
    </div>

    <div class="row g-3 mb-4">

        @if(auth()->user()->role === 'admin')
        {{-- TOTAL BUKU --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center p-3">
                <i class="bi bi-book fs-1 text-primary"></i>
                <small class="text-muted">Total Buku</small>
                <h4 class="fw-bold">{{ $data['totalBuku'] }}</h4>
            </div>
        </div>

        {{-- TOTAL USER --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center p-3">
                <i class="bi bi-people fs-1 text-success"></i>
                <small class="text-muted">Total User</small>
                <h4 class="fw-bold">{{ $data['totalUser'] }}</h4>
            </div>
        </div>

        {{-- TRANSAKSI AKTIF --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center p-3">
                <i class="bi bi-arrow-repeat fs-1 text-warning"></i>
                <small class="text-muted">Dipinjam</small>
                <h4 class="fw-bold">{{ $data['transaksiAktif'] }}</h4>
            </div>
        </div>

        {{-- TOTAL DENDA --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center p-3">
                <i class="bi bi-cash-coin fs-1 text-danger"></i>
                <small class="text-muted">Total Denda</small>
                <h4 class="fw-bold">
                    Rp {{ number_format($data['totalDenda']) }}
                </h4>
            </div>
        </div>

        @else
        {{-- USER: DIPINJAM --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center p-3">
                <i class="bi bi-journal-bookmark fs-1 text-primary"></i>
                <small class="text-muted">Buku Dipinjam</small>
                <h4 class="fw-bold">{{ $data['dipinjam'] }}</h4>
            </div>
        </div>

        {{-- USER: TELAT --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center p-3">
                <i class="bi bi-exclamation-triangle fs-1 text-warning"></i>
                <small class="text-muted">Terlambat</small>
                <h4 class="fw-bold">{{ $data['telat'] }}</h4>
            </div>
        </div>

        {{-- USER: DENDA --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center p-3">
                <i class="bi bi-cash fs-1 text-danger"></i>
                <small class="text-muted">Total Denda</small>
                <h4 class="fw-bold">
                    Rp {{ number_format($data['totalDenda']) }}
                </h4>
            </div>
        </div>
        @endif

    </div>


    <hr class="my-4 opacity-25">

    {{-- NAVIGATION MENU --}}
    <h5 class="fw-bold mb-3 text-dark">Akses Cepat</h5>
    <div class="row g-3">
        @if (auth()->user()->role === 'admin')
        {{-- ADMIN MENU --}}
        <div class="col-md-4">
            <a href="/buku" class="card border-0 shadow-sm text-decoration-none h-100 hover-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start">
                        <div class="bg-primary text-white rounded-3 p-3 me-3">
                            <i class="bi bi-database-gear fs-3"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Kelola Data Buku</h6>
                            <p class="text-muted small mb-0">Tambah, edit, atau hapus koleksi buku perpustakaan.</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        {{-- Tambahkan menu admin lain di sini nantinya --}}
        @else
        {{-- USER MENU --}}
        <div class="col-md-6">
            <a href="{{ route('home.buku') }}" class="card border-0 shadow-sm text-decoration-none h-100 hover-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start">
                        <div class="bg-info text-white rounded-3 p-3 me-3">
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
        <div class="col-md-6">
            <a href="{{ route('home.riwayat') }}" class="card border-0 shadow-sm text-decoration-none h-100 hover-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start">
                        <div class="bg-warning text-white rounded-3 p-3 me-3">
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

<style>
    .hover-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        border-left: 4px solid #0d6efd !important;
    }
</style>
@endsection
