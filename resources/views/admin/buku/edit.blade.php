@extends('admin.layouts.app')
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold text-warning">Edit Data Buku</h5>
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

                <form action="{{ route('bukus.update', $buku->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-medium">Judul Buku</label>
                        <input type="text" name="judul" class="form-control" value="{{ old('judul', $buku->judul) }}">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Kategori</label>
                            <select name="kategori_id" class="form-select">
                                @foreach($kategoris as $k)
                                <option value="{{ $k->id }}" {{ $buku->kategori_id == $k->id ? 'selected' : '' }}>{{
                                    $k->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Tahun Terbit</label>
                            <input type="number" name="tahun" class="form-control"
                                value="{{ old('tahun', $buku->tahun) }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Penulis</label>
                            <select name="penulis_id" class="form-select">
                                @foreach($penulis as $p)
                                <option value="{{ $p->id }}" {{ $buku->penulis_id == $p->id ? 'selected' : '' }}>{{
                                    $p->nama_penulis }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Penerbit</label>
                            <select name="penerbit_id" class="form-select">
                                @foreach($penerbit as $pen)
                                <option value="{{ $pen->id }}" {{ $buku->penerbit_id == $pen->id ? 'selected' : '' }}>{{
                                    $pen->nama_penerbit }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Stok</label>
                        <input type="number" name="stok" class="form-control" value="{{ old('stok', $buku->stok) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Gambar (Kosongkan jika tidak diganti)</label>
                        <input type="file" name="gambar" class="form-control mb-2">
                        @if($buku->gambar)
                        <small class="text-muted">Gambar saat ini: <a href="{{ asset('storage/'.$buku->gambar) }}"
                                target="_blank">Lihat</a></small>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Sinopsis</label>
                        <textarea name="sinopsis" rows="4"
                            class="form-control">{{ old('sinopsis', $buku->sinopsis) }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('bukus.index') }}" class="btn btn-outline-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary px-4">Update Buku</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection