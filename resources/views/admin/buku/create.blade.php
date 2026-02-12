@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">

        <div class="card border-0">
            {{-- HEADER --}}
            <div class="card-header" style="background:#f4f4f4; border-bottom:1px solid #dedede">
                <h5 class="mb-0 fw-semibold">
                    Tambah Buku
                </h5>
            </div>

            <div class="card-body p-4">

                {{-- ERROR VALIDASI --}}
                @if ($errors->any())
                <div class="alert alert-danger small">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-medium">Judul Buku</label>
                        <input type="text" name="judul" class="form-control" value="{{ old('judul') }}"
                            placeholder="Judul buku">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Penulis</label>
                        <input type="text" name="penulis" class="form-control" value="{{ old('penulis') }}"
                            placeholder="Nama penulis">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Penerbit</label>
                        <input type="text" name="penerbit" class="form-control" value="{{ old('penerbit') }}"
                            placeholder="Nama penerbit">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Kategori</label>
                        <select name="kategori_id" class="form-select">
                            <option value="" disabled selected>Pilih Kategori</option>
                            @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Cover Buku</label>
                        <input type="file" name="gambar" class="form-control" >
                        <small class="text-muted">Format: JPG, JPEG, PNG. Maks: 2MB</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Sinopsis</label>
                        <textarea name="sinopsis" rows="4" class="form-control"
                            placeholder="Ringkasan cerita..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Tahun Terbit</label>
                            <input type="number" name="tahun" class="form-control" value="{{ old('tahun') }}"
                                placeholder="Contoh: 2024">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Stok</label>
                            <input type="number" name="stok" class="form-control" value="{{ old('stok') }}"
                                placeholder="Jumlah stok">
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between">
                        <a href="/buku" class="btn btn-outline-secondary">
                            Kembali
                        </a>

                        <button type="submit" class="btn btn-primary px-4">
                            Simpan
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

@endsection
