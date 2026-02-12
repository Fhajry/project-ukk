@extends('layouts.app')

@section('content')
{{-- Load SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Gradient Placeholder untuk Detail (Ukuran Lebih Besar) */
    .detail-cover-placeholder {
        width: 100%;
        height: 400px;
        /* Tinggi fix agar proporsional */
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.8);
        border-radius: 15px;
        font-size: 5rem;
    }

    .detail-cover-img {
        width: 100%;
        height: auto;
        max-height: 500px;
        object-fit: contain;
        /* Agar gambar utuh tidak terpotong */
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .meta-table td {
        padding-bottom: 0.8rem;
        vertical-align: top;
    }

    .meta-label {
        width: 130px;
        font-weight: 600;
        color: #6c757d;
    }
</style>

<div class="container py-5">

    {{-- Breadcrumb / Tombol Kembali --}}
    <div class="mb-4">
        <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-body p-0">
            <div class="row g-0">

                {{-- KOLOM KIRI: GAMBAR --}}
                <div class="col-lg-4 p-4 bg-light d-flex align-items-center justify-content-center">
                    @if($buku->gambar)
                    <img src="{{ asset('storage/' . $buku->gambar) }}" class="detail-cover-img"
                        alt="{{ $buku->judul }}">
                    @else
                    <div class="detail-cover-placeholder shadow">
                        <i class="bi bi-journal-text"></i>
                    </div>
                    @endif
                </div>

                {{-- KOLOM KANAN: DETAIL --}}
                <div class="col-lg-8 p-5">

                    {{-- Badge Kategori & Stok --}}
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                            {{ $buku->kategori->nama_kategori ?? 'Umum' }}
                        </span>

                        @if($buku->stok > 0)
                        <span class="badge bg-success px-3 py-2 rounded-pill">
                            Stok: {{ $buku->stok }}
                        </span>
                        @else
                        <span class="badge bg-danger px-3 py-2 rounded-pill">
                            Stok Habis
                        </span>
                        @endif
                    </div>

                    {{-- Judul Buku --}}
                    <h1 class="fw-bold text-dark mb-4">{{ $buku->judul }}</h1>

                    {{-- Tabel Informasi --}}
                    <table class="table table-borderless meta-table mb-4">
                        <tr>
                            <td class="meta-label">Penulis</td>
                            <td class="fw-medium">: {{ $buku->penulis }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Penerbit</td>
                            <td>: {{ $buku->penerbit }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Tahun Terbit</td>
                            <td>: {{ $buku->tahun }}</td>
                        </tr>
                    </table>

                    {{-- Sinopsis --}}
                    <div class="mb-5">
                        <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">Sinopsis</h5>
                        <p class="text-muted" style="line-height: 1.8; text-align: justify;">
                            {{ $buku->sinopsis ?? 'Belum ada sinopsis untuk buku ini.' }}
                        </p>
                    </div>

                    {{-- TOMBOL AKSI (Berdasarkan Role) --}}
                    <div class="d-flex gap-2">
                        @guest
                        <a href="{{ route('login') }}" class="btn btn-primary w-100 rounded-pill py-2">
                            Login untuk Meminjam
                        </a>
                        @endguest

                        @auth
                        @if(auth()->user()->role == 'user')
                        {{-- Tombol User: Pinjam --}}
                        @if($buku->stok > 0)
                        <button onclick="konfirmasiPinjam('{{ $buku->id }}', '{{ $buku->judul }}')"
                            class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">
                            Pinjam Buku Ini
                        </button>
                        @else
                        <button class="btn btn-secondary btn-lg rounded-pill px-5" disabled>
                            Stok Habis
                        </button>
                        @endif

                        @elseif(auth()->user()->role == 'admin')
                        {{-- Tombol Admin: Edit & Hapus --}}
                        <a href="{{ route('buku.edit', $buku->id) }}"
                            class="btn btn-warning btn-lg rounded-pill px-4 text-white">
                            <i class="bi bi-pencil-square me-2"></i> Edit
                        </a>

                        <form action="{{ route('buku.destroy', $buku->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-lg rounded-pill px-4"
                                onclick="konfirmasiHapus(this)">
                                <i class="bi bi-trash me-2"></i> Hapus
                            </button>
                        </form>
                        @endif
                        @endauth
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT ALERT (Pinjam & Hapus) --}}
<script>
    // Konfirmasi Pinjam (Sama seperti di index)
    function konfirmasiPinjam(id, judul) {
        Swal.fire({
            title: 'Konfirmasi Peminjaman',
            text: "Apakah Anda yakin ingin meminjam buku \"" + judul + "\"?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Pinjam!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/pinjam/" + id;
            }
        });
    }

    // Konfirmasi Hapus (Khusus Admin)
    function konfirmasiHapus(button) {
        Swal.fire({
            title: 'Hapus Buku?',
            text: "Data buku ini tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }
</script>

@endsection