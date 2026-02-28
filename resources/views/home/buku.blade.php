@extends('home.layouts.app')
@section('content')
{{-- Load SweetAlert2 & Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container py-5">
    <div class="row mb-5 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark mb-1">Katalog Buku</h2>
            <p class="text-muted">Temukan buku favoritmu dan mulai membaca hari ini.</p>
        </div>

        {{-- Filter Berdasarkan Kategori --}}
        <div class="search-container mb-4">
            <form action="{{ route('home.buku') }}" method="GET">
                <div class="row g-2 align-items-end">
                    <!-- Input Pencarian -->
                    <div class="col-md-4">
                        <label class="small fw-bold text-muted mb-1">Cari Judul Buku</label>
                        <div class="input-group input-group-lg shadow-sm">
                            <input type="text" name="search" class="form-control" placeholder="Ketik judul buku..."
                                value="{{ request('search') }}">
                            <!-- Tombol icon minimalis -->
                            <button type="submit" class="btn btn-outline-none border-0 p-0">
                                <i class="bi bi-search text-primary fs-5"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Filter Kategori -->
                    <div class="col-md-2">
                        <label class="small fw-bold text-muted mb-1">Filter Kategori</label>
                        <select name="kategori_id" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $k)
                            <option value="{{ $k->id }}" {{ request('kategori_id')==$k->id ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Penulis -->
                    <div class="col-md-2">
                        <label class="small fw-bold text-muted mb-1">Filter Penulis</label>
                        <select name="penulis_id" class="form-select">
                            <option value="">Semua Penulis</option>
                            @foreach($daftar_penulis as $p)
                            <option value="{{ $p->id }}" {{ request('penulis_id')==$p->id ? 'selected' : '' }}>
                                {{ $p->nama_penulis }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Penerbit -->
                    <div class="col-md-2">
                        <label class="small fw-bold text-muted mb-1">Filter Penerbit</label>
                        <select name="penerbit_id" class="form-select">
                            <option value="">Semua Penerbit</option>
                            @foreach($daftar_penerbit as $p)
                            <option value="{{ $p->id }}" {{ request('penerbit_id')==$p->id ? 'selected' : '' }}>
                                {{ $p->nama_penerbit }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tombol Terapkan & Reset -->
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-dark"><i class="bi bi-funnel me-1"></i> Terapkan</button>
                        @if(request()->anyFilled(['search', 'kategori_id', 'penulis_id', 'penerbit_id']))
                        <a href="{{ route('home.buku') }}" class="btn btn-outline-danger"><i
                                class="bi bi-arrow-counterclockwise"></i></a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        @forelse ($buku as $item)
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card h-100 book-card shadow-sm border-0">
                <a href="{{ route('home.detail', $item->id) }}" class="text-decoration-none">
                    @if($item->gambar)
                    <div class="book-cover">
                        <img src="{{ asset('storage/'.$item->gambar) }}" alt="{{ $item->judul }}">
                    </div>
                    @else
                    <div class="book-cover-placeholder">
                        <i class="bi bi-book fs-1"></i>
                    </div>
                    @endif
                </a>
                <div class="card-body">
                    <span class="badge bg-primary-subtle text-primary border-primary-subtle rounded-pill mb-2 small">
                        {{ $item->kategori->nama_kategori ?? 'Umum' }}
                    </span>
                    <h6 class="card-title text-dark fw-bold text-truncate mb-1">{{ $item->judul }}</h6>
                    <p class="card-text text-muted small mb-3">
                        <i class="bi bi-person me-1"></i> {{ $item->penulis->nama_penulis ?? '-' }}
                    </p>

                    <div class="grid-2 ">
                        @auth
                        @if(auth()->user()->role ==='user')
                        @if($item->stok > 0)

                        <button
                            onclick="konfirmasiPinjam('{{ route('transaksi.pinjam', $item->id) }}', '{{ addslashes($item->judul) }}')"
                            class="btn btn-primary btn-sm rounded-pill shadow-sm w-100">
                            Pinjam Sekarang
                        </button>


                        @else
                        <button class="btn btn-secondary btn-sm rounded-pill disabled">Stok Habis</button>
                        @endif
                        @endif
                        @else
                        <a href="/login" class="btn btn-outline-primary btn-sm rounded-pill">Login untuk Pinjam</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-search fs-1 text-muted opacity-50 d-block mb-3"></i> <button
                onclick="konfirmasiPinjam('{{ route('transaksi.pinjam', $item->id) }}', '{{ addslashes($item->judul) }}')"
                class="btn btn-primary btn-sm rounded-pill shadow-sm w-100">
                Pinjam Sekarang
            </button>
            <h5 class="text-muted">Buku tidak ditemukan</h5>
            <a href="{{ route('home.buku') }}" class="btn btn-primary mt-2">Reset Filter</a>
        </div>
        @endforelse

    </div>
    <div class="d-flex justify-content-center mt-5 mb-4">
        {{ $buku->appends(request()->query())->links() }}
    </div>
</div>

<script>
    function konfirmasiPinjam(actionUrl, judul) {
        Swal.fire({
            title: 'Pinjam Buku?',
            text: "Apakah Anda ingin meminjam '" + judul + "'?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Pinjam!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // 1. Buat form bayangan secara dinamis
                let form = document.createElement('form');
                form.action = actionUrl;
                form.method = 'POST'; // Memaksa metode POST agar cocok dengan web.php

                // 2. Tambahkan token keamanan bawaan Laravel (Wajib untuk POST)
                let csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                // 3. Masukkan form ke dalam halaman dan tekan tombol submit otomatis
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endsection