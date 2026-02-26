@extends('admin.layouts.app')
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold text-primary">Tambah Buku Baru</h5>
            </div>

            <div class="card-body p-4">
                @if ($errors->any())
                <div class="alert alert-danger small">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('bukus.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-medium">Judul Buku</label>
                        <input type="text" name="judul" class="form-control" value="{{ old('judul') }}"
                            placeholder="Masukkan judul buku">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Kategori</label>
                            <select name="kategori_id" class="form-select">
                                <option value="" selected disabled>Pilih Kategori</option>
                                @foreach($kategoris as $k)
                                <option value="{{ $k->id }}" {{ old('kategori_id')==$k->id ? 'selected' : '' }}>{{
                                    $k->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Tahun Terbit</label>
                            <input type="number" name="tahun" class="form-control" value="{{ old('tahun') }}"
                                placeholder="2024">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Penulis</label>
                            <select name="penulis_id" class="form-select">
                                <option value="" selected disabled>Pilih Penulis</option>
                                @foreach($penulis as $p)
                                <option value="{{ $p->id }}" {{ old('penulis_id')==$p->id ? 'selected' : '' }}>{{
                                    $p->nama_penulis }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Penerbit</label>
                            <select name="penerbit_id" class="form-select">
                                <option value="" selected disabled>Pilih Penerbit</option>
                                @foreach($penerbit as $pen)
                                <option value="{{ $pen->id }}" {{ old('penerbit_id')==$pen->id ? 'selected' : '' }}>{{
                                    $pen->nama_penerbit }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Stok Buku</label>
                        <input type="number" name="stok" class="form-control" value="{{ old('stok') }}"
                            placeholder="Jumlah stok">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Gambar Sampul</label>
                        <input type="file" name="gambar" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Sinopsis</label>
                        <textarea name="sinopsis" rows="4" class="form-control"
                            placeholder="Ringkasan cerita...">{{ old('sinopsis') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('bukus.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">Simpan Buku</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection