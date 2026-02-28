@extends('home.layouts.app')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home.buku') }}">Katalog</a></li>
            <li class="breadcrumb-item active">{{ $buku->judul }}</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-lg p-3" style="border-radius: 20px;">
        <div class="row g-0">
            <div class="col-md-4 text-center">
                @if($buku->gambar)
                <img src="{{ asset('storage/' . $buku->gambar) }}" class="img-fluid rounded shadow-sm detail-cover-img"
                    alt="Cover">
                @else
                <div class="detail-cover-placeholder">
                    <i class="bi bi-book"></i>
                </div>
                @endif
            </div>

            <div class="col-md-8">
                <div class="card-body px-md-4 py-4 py-md-0">
                    <h1 class="fw-bold text-dark mb-2">{{ $buku->judul }}</h1>
                    <h5 class="text-primary mb-4">{{ $buku->penulis->nama_penulis ?? 'Penulis Tidak Diketahui' }}</h5>

                    <hr class="opacity-10 mb-4">

                    <table class="table table-borderless meta-table small">
                        <tr>
                            <td class="text-muted" width="150">Kategori</td>
                            <td class="fw-semibold">: {{ $buku->kategori->nama_kategori ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Penerbit</td>
                            <td class="fw-semibold">: {{ $buku->penerbit->nama_penerbit ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tahun Terbit</td>
                            <td class="fw-semibold">: {{ $buku->tahun }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Stok Tersedia</td>
                            <td>:
                                @if($buku->stok > 0)
                                <span class="badge bg-success-subtle text-success">{{ $buku->stok }} Buku</span>
                                @else
                                <span class="badge bg-danger-subtle text-danger">Stok Habis</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    <div class="mt-4">
                        <h6 class="fw-bold mb-2 text-dark">Sinopsis :</h6>
                        <p class="text-muted leading-relaxed" style="text-align: justify">
                            {{ $buku->sinopsis ?? 'Tidak ada sinopsis untuk buku ini.' }}
                        </p>
                    </div>

                    <div class="mt-5 d-flex gap-2">
                        @auth
                        @if(auth()->user()->role ==='user')

                        @if($buku->stok > 0)
                        <button
                            onclick="konfirmasiPinjam('{{ route('transaksi.pinjam', $buku->id) }}', '{{ addslashes($buku->judul) }}')"
                            class="btn btn-primary px-5 py-2 rounded-pill shadow">
                            <i class="bi bi-bookmark-plus me-2"></i>Pinjam Buku
                        </button>
                        @endif
                        @endif
                        @else
                        <a href="/login" class="btn btn-outline-primary px-5 py-2 rounded-pill">Login untuk Meminjam</a>
                        @endauth
                        <a href="{{ route('home.buku') }}"
                            class="btn btn-light px-4 py-2 rounded-pill border">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
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