@extends('admin.layouts.app')
@section('content')

<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
            <div>
                <h4 class="mb-0 fw-bold text-dark">Katalog Perpustakaan</h4>
                <p class="text-muted small mb-0">Kelola dan telusuri koleksi buku Anda.</p>
            </div>
            @if (auth()->user()->role === 'admin')
            <a href="{{ route('bukus.create') }}" class="btn btn-primary px-4 shadow-sm">
                <i class="bi bi-plus-lg me-2"></i>Tambah Buku
            </a>
            @endif
        </div>

        <div class="card-body">
            {{-- FILTER & SEARCH BAR --}}
            <div class="search-container mb-4">
                <form action="{{ route('bukus.index') }}" method="GET">
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="small fw-bold text-muted mb-1">Cari Judul Buku</label>
                            <div class="input-group input-group-lg shadow-sm">
                                <input type="text" name="search" class="form-control" placeholder="Ketik judul buku..."
                                    value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-search me-1"></i>
                                    Cari</button>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="small fw-bold text-muted mb-1">Filter Kategori</label>
                            <select name="kategori_id" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoris as $k)
                                <option value="{{ $k->id }}" {{ request('kategori_id')==$k->id ? 'selected' : '' }}>{{
                                    $k->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-md-3">
                            <label class="small fw-bold text-muted mb-1">Filter Penulis</label>
                            <select name="penulis_id" class="form-select">
                                <option value=""> Semua Penulis</option>
                                @foreach($daftar_penulis as $p)
                                <option value="{{ $p->id }}" {{ request('penulis_id')==$p->id ? 'selected' : '' }}>{{
                                    $p->nama_penulis }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="small fw-bold text-muted mb-1">Filter Penerbit</label>
                            <select name="penerbit_id" class="form-select">
                                <option value="">Semua Penerbit</option>
                                @foreach($daftar_penerbit as $p)
                                <option value="{{ $p->id }}" {{ request('penerbit_id')==$p->id ? 'selected' : '' }}>{{
                                    $p->nama_penerbit }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-dark flex-grow-"><i class="bi bi-funnel me-1"></i>
                                Terapkan</button>
                            @if(request()->anyFilled(['search', 'kategori_id', 'penulis_id', 'penerbit_id']))
                            <a href="{{ route('bukus.index') }}" class="btn btn-outline-danger"><i
                                    class="bi bi-arrow-counterclockwise"></i></a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>


            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr class="text-muted small text-uppercase fw-bold border-top bg-light">
                            <th class="ps-4 py-3">Buku & Penulis</th>
                            <th>Kategori</th>
                            <th>Penerbit & Tahun</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center" style="width: 200px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($buku as $item)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if($item->gambar)
                                        <img src="{{ asset('storage/' . $item->gambar) }}" class="img-book-cover">
                                        @else
                                        <div class="d-flex align-items-center justify-content-center bg-secondary-subtle rounded text-secondary"
                                            style="width: 50px; height: 70px;"><i class="bi bi-book fs-4"></i></div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $item->judul }}</div>
                                        <div class="text-muted small">
                                            {{-- PERBAIKAN: Memanggil properti dari relasi --}}
                                            <i class="bi bi-person me-1"></i>{{ $item->penulis->nama_penulis ?? 'Tanpa
                                            Penulis' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span
                                    class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill">
                                    {{ $item->kategori->nama_kategori ?? '-' }}
                                </span>
                            </td>
                            <td>
                                {{-- PERBAIKAN: Memanggil properti dari relasi --}}
                                <div class="fw-semibold text-dark">{{ $item->penerbit->nama_penerbit ?? 'Tanpa Penerbit'
                                    }}</div>
                                <div class="text-muted small">{{ $item->tahun }}</div>
                            </td>
                            <td class="text-center">
                                @if($item->stok > 5)
                                <span
                                    class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">{{
                                    $item->stok }} Tersedia</span>
                                @elseif($item->stok > 0)
                                <span
                                    class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill">{{
                                    $item->stok }} Menipis</span>
                                @else
                                <span
                                    class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">Habis</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    @if (auth()->user()->role === 'admin')
                                    <a href="{{ route('bukus.edit', $item->id) }}"
                                        class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil-square"></i></a>
                                    <form action="{{ route('bukus.destroy', $item->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Hapus buku ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i
                                                class="bi bi-trash"></i></button>
                                    </form>
                                    @else
                                    <button class="btn btn-sm btn-primary">Pinjam</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Tidak ada buku ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
                <div class="d-flex justify-content-center mt-5 mb-4">
                    {{ $buku->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection