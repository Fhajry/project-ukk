@extends('home.layouts.app')

@section('content')
<div class="container py-4">

    {{-- HERO SECTION --}}
    <div class="row align-items-center bg-white rounded-4 shadow-sm p-4 p-md-5 mb-5 border">
        <div class="col-lg-7 text-center text-lg-start mb-4 mb-lg-0">
            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill mb-3">
                Hai, {{ auth()->user()->name ?? 'Pengunjung' }}! 👋
            </span>
            <h1 class="display-5 fw-bold text-dark mb-3">
                Jelajahi Dunia Pengetahuan Tanpa Batas
            </h1>
            <p class="text-muted fs-5 mb-4">
                Platform pinjam buku digital yang cepat, mudah, dan transparan. Temukan ratusan koleksi buku fiksi
                maupun non-fiksi terbaru di sini.
            </p>
            <div class="d-flex gap-2 justify-content-center justify-content-lg-start">
                <a href="{{ route('home.buku') }}" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm">
                    <i class="bi bi-search me-2"></i>Cari Buku
                </a>
                @if(auth()->check() && auth()->user()->role === 'user')
                <a href="{{ route('home.riwayat') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                    <i class="bi bi-clock-history me-2"></i>Riwayat
                </a>
                @endif
            </div>
        </div>
        <div class="col-lg-5 d-none d-lg-block text-center">
            {{-- Ilustrasi Dummy (Ganti dengan ilustrasi buku Anda sendiri jika ada) --}}
            <img src="https://illustrations.popsy.co/amber/student-going-to-school.svg" alt="Ilustrasi Buku"
                class="img-fluid" style="max-height: 350px;">
        </div>
    </div>

    {{-- STATISTIC SECTION --}}
    @guest
    <div class="text-center mb-4">
        <h4 class="fw-bold text-dark">Informasi Perpustakaan</h4>
        <p class="text-muted small">Ringkasan aktivitas dan koleksi saat ini.</p>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-4 h-100 rounded-4 bg-white">
                <i class="bi bi-book text-primary mb-2" style="font-size: 2.5rem;"></i>
                <h3 class="fw-bold text-dark mb-0">{{ $data['totalBuku'] ?? 0 }}</h3>
                <small class="text-muted">Total Buku</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4 h-100 rounded-4 bg-white">
                <i class="bi bi-journal-bookmark text-primary mb-2" style="font-size: 2.5rem;"></i>
                <h3 class="fw-bold text-dark mb-0">{{ $data['dipinjam'] ?? 0 }}</h3>
                <small class="text-muted">Buku Sedang Dipinjam</small>
            </div>
        </div>

        @else
        @if(!auth()->check() || auth()->user()->role === 'admin')
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-4 h-100 rounded-4 bg-white">
                <i class="bi bi-people text-success mb-2" style="font-size: 2.5rem;"></i>
                <h3 class="fw-bold text-dark mb-0">{{ $data['totalUser'] ?? 0 }}</h3>
                <small class="text-muted">Total Anggota</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-4 h-100 rounded-4 bg-white">
                <i class="bi bi-arrow-repeat text-warning mb-2" style="font-size: 2.5rem;"></i>
                <h3 class="fw-bold text-dark mb-0">{{ $data['transaksiAktif'] ?? 0 }}</h3>
                <small class="text-muted">Buku Dipinjam</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-4 h-100 rounded-4 bg-white">
                <i class="bi bi-cash-coin text-danger mb-2" style="font-size: 2.5rem;"></i>
                <h3 class="fw-bold text-dark mb-0">Rp {{ number_format($data['totalDenda'] ?? 0, 0, ',', '.') }}</h3>
                <small class="text-muted">Total Denda</small>
            </div>
        </div>
        @else

        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4 h-100 rounded-4 bg-white">
                <i class="bi bi-exclamation-triangle text-warning mb-2" style="font-size: 2.5rem;"></i>
                <h3 class="fw-bold text-dark mb-0">{{ $data['telat'] ?? 0 }}</h3>
                <small class="text-muted">Terlambat Dikembalikan</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4 h-100 rounded-4 bg-white">
                <i class="bi bi-cash text-danger mb-2" style="font-size: 2.5rem;"></i>
                <h3 class="fw-bold text-dark mb-0">Rp {{ number_format($data['totalDenda'] ?? 0, 0, ',', '.') }}</h3>
                <small class="text-muted">Tagihan Denda</small>
            </div>
        </div>
        @endif
    </div>
    @endguest
</div>
@endsection
