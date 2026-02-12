@extends('layouts.app')

@section('content')
<style>
    /* Styling Custom */
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        transition: all 0.2s ease;
    }

    .card {
        border-radius: 12px;
        overflow: hidden;
    }

    .search-container {
        background: #fcfcfc;
        border: 1px solid #eee;
        border-radius: 12px;
        padding: 20px;
    }

    .img-book-cover {
        width: 50px;
        height: 70px;
        object-fit: cover;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        {{-- HEADER --}}
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
            <div>
                <h4 class="mb-0 fw-bold text-dark">Katalog Perpustakaan</h4>
                <p class="text-muted small mb-0">Kelola dan telusuri koleksi buku Anda.</p>
            </div>

            @if (auth()->user()->role === 'admin')
            <a href="{{ route('buku.create') }}" class="btn btn-primary px-4 shadow-sm">
                <i class="bi bi-plus-lg me-2"></i>Tambah Buku
            </a>
            @endif
        </div>

        <div class="card-body">

            {{-- FILTER & SEARCH BAR --}}
            <div class="search-container mb-4">
                <form action="{{ route('buku.index') }}" method="GET">
                    <div class="row g-3">

                        {{-- 1. Search Judul --}}
                        <div class="col-md-3">
                            <label class="small fw-bold text-muted mb-1">Cari Judul</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" class="form-control border-start-0 ps-0"
                                    placeholder="Judul buku..." value="{{ request('search') }}">
                            </div>
                        </div>

                        {{-- 2. Filter Kategori (BARU) --}}
                        <div class="col-md-3">
                            <label class="small fw-bold text-muted mb-1">Kategori</label>
                            <select name="kategori" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoris as $k)
                                <option value="{{ $k->id }}" {{ request('kategori')==$k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kategori }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 3. Filter Penulis --}}
                        <div class="col-md-2">
                            <label class="small fw-bold text-muted mb-1">Penulis</label>
                            <select name="penulis" class="form-select">
                                <option value="">Semua Penulis</option>
                                @foreach($daftar_penulis as $p)
                                <option value="{{ $p }}" {{ request('penulis')==$p ? 'selected' : '' }}>{{ $p }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 4. Filter Penerbit --}}
                        <div class="col-md-2">
                            <label class="small fw-bold text-muted mb-1">Penerbit</label>
                            <select name="penerbit" class="form-select">
                                <option value="">Semua Penerbit</option>
                                @foreach($daftar_penerbit as $p)
                                <option value="{{ $p }}" {{ request('penerbit')==$p ? 'selected' : '' }}>{{ $p }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 5. Tombol Filter & Reset --}}
                        <div class="col-md-2 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-dark w-100">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                            @if(request()->anyFilled(['search', 'kategori', 'penulis', 'penerbit']))
                            <a href="{{ route('buku.index') }}" class="btn btn-outline-danger" title="Reset Filter">
                                <i class="bi bi-x-lg"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            {{-- FLASH MESSAGE --}}
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 py-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr class="text-muted small text-uppercase fw-bold border-top bg-light">
                            <th class="ps-4 py-3">Buku & Penulis</th>
                            <th>Kategori</th> {{-- Kolom Baru --}}
                            <th>Penerbit & Tahun</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center" style="width: 200px">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="border-top-0">
                        @forelse ($buku as $item)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if($item->gambar)
                                        <img src="{{ asset('storage/' . $item->gambar) }}" class="img-book-cover"
                                            alt="Cover">
                                        @else
                                        <div class="d-flex align-items-center justify-content-center bg-secondary-subtle rounded text-secondary"
                                            style="width: 50px; height: 70px;">
                                            <i class="bi bi-book fs-4"></i>
                                        </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $item->judul }}</div>
                                        <div class="text-muted small">
                                            <i class="bi bi-person me-1"></i>{{ $item->penulis }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- MENAMPILKAN KATEGORI --}}
                            <td>
                                @if($item->kategori)
                                <span
                                    class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill">
                                    {{ $item->kategori->nama_kategori }}
                                </span>
                                @else
                                <span class="text-muted small">-</span>
                                @endif
                            </td>

                            <td>
                                <div class="fw-semibold text-dark">{{ $item->penerbit }}</div>
                                <div class="text-muted small">{{ $item->tahun }}</div>
                            </td>

                            <td class="text-center">
                                @if($item->stok > 5)
                                <span
                                    class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">
                                    {{ $item->stok }} Tersedia
                                </span>
                                @elseif($item->stok > 0)
                                <span
                                    class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill">
                                    {{ $item->stok }} Menipis
                                </span>
                                @else
                                <span
                                    class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">
                                    Habis
                                </span>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    @if (auth()->user()->role === 'admin')
                                    <a href="{{ route('buku.edit', $item->id) }}"
                                        class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('buku.destroy', $item->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Hapus buku ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @else
                                    <button class="btn btn-sm btn-primary">Pinjam</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-search fs-1 d-block mb-2"></i>
                                    Tidak ada buku ditemukan.
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection