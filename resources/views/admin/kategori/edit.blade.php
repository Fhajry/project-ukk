@extends('layouts.app')

@section('content')

{{-- Header Halaman --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 text-primary fw-bold">
        <i class="bi bi-pencil-square me-2"></i>Edit Kategori
    </h4>
    <a href="{{ route('kategori.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

{{-- Form Content --}}
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm bg-white">
            <div class="card-body p-4">
                <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label fw-bold">Nama Kategori <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-tag"></i></span>
                            <input type="text" name="nama_kategori" class="form-control form-control-lg"
                                value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
                        </div>
                        {{-- Menampilkan error validasi jika ada --}}
                        @error('nama_kategori')
                        <small class="text-danger mt-1 d-block"><i class="bi bi-exclamation-circle"></i> {{ $message
                            }}</small>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning fw-bold py-2 rounded-pill text-white">
                            <i class="bi bi-arrow-repeat me-1"></i> Update Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
