@extends('layouts.app')

@section('content')

{{-- Header Halaman --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 text-primary fw-bold">
        <i class="bi bi-tags-fill me-2"></i>Kategori Buku
    </h4>
    <a href="{{ route('kategori.create') }}" class="btn btn-primary rounded-pill px-4">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
    </a>
</div>

{{-- Alert Sukses --}}
@if(session('sukses'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i> {{ session('sukses') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- Tabel Data --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="10%" class="text-center">No</th>
                        <th width="60%">Nama Kategori</th>
                        <th width="30%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategori as $index => $k)
                    <tr>
                        <td class="text-center text-muted">{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded p-2 me-3">
                                    <i class="bi bi-bookmark-fill"></i>
                                </div>
                                <span class="fw-medium">{{ $k->nama_kategori }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('kategori.edit', $k->id) }}" class="btn btn-sm btn-outline-warning"
                                    title="Edit">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form action="{{ route('kategori.destroy', $k->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus kategori ini? Semua buku terkait mungkin terdampak.')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger border-start-0 rounded-end"
                                        title="Hapus">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Belum ada data kategori ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection