@extends('layouts.app')

@section('content')
{{-- Load SweetAlert2 & Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Styling Kartu Buku */
    .book-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 15px;
        overflow: hidden;
        background: #fff;
    }

    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    /* Placeholder Cover jika tidak ada gambar */
    .book-cover-placeholder {
        height: 160px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.8);
        font-size: 4rem;
    }

    .book-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 0.75rem;
        backdrop-filter: blur(5px);
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: 700;
        line-height: 1.4;
        height: 3rem;
        /* Membatasi tinggi judul 2 baris */
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .meta-text {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .search-container {
        background: #fff;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
    }
</style>

<div class="container py-5">

    {{-- HEADER & SEARCH --}}
    <div class="row mb-5">
        <div class="col-12 text-center mb-4">
            <h2 class="fw-bold text-dark mb-2">Jelajahi Koleksi Perpustakaan</h2>
            <p class="text-muted">Temukan buku favoritmu dan pinjam sekarang juga.</p>
        </div>

        <div class="col-lg-10 mx-auto">
            <div class="search-container">
                <form action="/buku" method="GET" class="row g-3">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted ps-3"><i
                                    class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 py-2"
                                placeholder="Cari judul buku..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="penulis" class="form-select py-2">
                            <option value="">Semua Penulis</option>
                            @foreach($daftar_penulis as $p)
                            <option value="{{ $p }}" {{ request('penulis')==$p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="penerbit" class="form-select py-2">
                            <option value="">Semua Penerbit</option>
                            @foreach($daftar_penerbit as $p)
                            <option value="{{ $p }}" {{ request('penerbit')==$p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100 py-2"><i class="bi bi-filter"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- FLASH MESSAGE (Standard Laravel) --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- GRID VIEW BUKU --}}
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
        @forelse ($buku as $item)
        <div class="col">
            <div class="card h-100 book-card shadow-sm position-relative">

                {{-- Status Badge (Stok) --}}
                @if($item->stok > 0)
                <span class="badge bg-success bg-opacity-75 book-badge rounded-pill shadow-sm">
                    Tersedia: {{ $item->stok }}
                </span>
                @else
                <span class="badge bg-danger bg-opacity-75 book-badge rounded-pill shadow-sm">
                    Stok Habis
                </span>
                @endif

                {{-- Cover Placeholder (Karena di DB tidak ada kolom gambar, pakai icon) --}}
                <div class="book-cover-placeholder">
                    <i class="bi bi-journal-text"></i>
                </div>

                <div class="card-body d-flex flex-column">
                    <div class="mb-2">
                        <small class="text-primary fw-bold text-uppercase" style="font-size: 0.7rem;">{{ $item->penerbit
                            }}</small>
                    </div>
                    <h5 class="card-title text-dark mb-1" title="{{ $item->judul }}">{{ $item->judul }}</h5>
                    <p class="meta-text mb-3"><i class="bi bi-pen me-1"></i> {{ $item->penulis }}</p>

                    <div class="mt-auto pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">{{ $item->tahun }}</small>

                            @if (auth()->user()->role === 'user')
                            @if ($item->stok > 0)
                            {{-- Tombol Pinjam dengan SweetAlert --}}
                            <button onclick="konfirmasiPinjam('{{ $item->id }}', '{{ $item->judul }}')"
                                class="btn btn-sm btn-primary px-3 rounded-pill fw-bold">
                                Pinjam
                            </button>
                            @else
                            <button class="btn btn-sm btn-light text-muted border px-3 rounded-pill"
                                disabled>Habis</button>
                            @endif
                            @else
                            {{-- Tombol Admin (Edit) --}}
                            <a href="/buku/{{ $item->id }}/edit"
                                class="btn btn-sm btn-outline-dark rounded-pill">Kelola</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="mb-3">
                <i class="bi bi-search fs-1 text-muted opacity-50"></i>
            </div>
            <h5 class="text-muted">Buku tidak ditemukan</h5>
            <p class="small text-muted mb-3">Coba kata kunci lain atau reset filter.</p>
            <a href="/buku" class="btn btn-outline-primary rounded-pill px-4">Reset Filter</a>
        </div>
        @endforelse
    </div>

    {{-- Pagination (Jika pakai paginate) --}}
    <div class="d-flex justify-content-center mt-5">
        {{-- {{ $buku->links() }} --}}
    </div>
</div>

{{-- SCRIPT ALERT KONFIRMASI --}}
<script>
    function konfirmasiPinjam(id, judul) {
        Swal.fire({
            title: 'Konfirmasi Peminjaman',
            text: "Apakah Anda yakin ingin meminjam buku \"" + judul + "\"?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd', // Warna Bootstrap Primary
            cancelButtonColor: '#6c757d', // Warna Bootstrap Secondary
            confirmButtonText: 'Ya, Pinjam!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Arahkan ke route peminjaman jika user klik Ya
                window.location.href = "/pinjam/" + id;
            }
        });
    }
</script>

@endsection