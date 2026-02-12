@extends('layouts.app')

@section('content')


{{-- Header Halaman --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 text-primary fw-bold">
        <i class="bi bi-plus-circle-dotted me-2"></i>Tambah Kategorix
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
                <form action="{{ route('kategori.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-bold">Nama Kategori <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-tag"></i></span>
                            <input type="text" name="nama_kategori" class="form-control form-control-lg"
                                placeholder="Contoh: Sains, Sejarah, Fiksi..." required>
                        </div>
                        <div class="form-text text-muted">
                            Pastikan nama kategori belum pernah terdaftar sebelumnya.
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success fw-bold py-2 rounded-pill">
                            <i class="bi bi-save me-1"></i> Simpan Kategori Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
