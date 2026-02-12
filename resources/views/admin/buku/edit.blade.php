@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">

        <div class="card border-0">
            {{-- HEADER --}}
            <div class="card-header" style="background:#f4f4f4; border-bottom:1px solid #dedede">
                <h5 class="mb-0 fw-semibold">
                    Edit Buku
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

                {{-- PENTING: enctype wajib ada untuk upload file --}}
                <form action="{{ route('buku.update', $buku->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-medium">Judul Buku</label>
                        <input type="text" name="judul" class="form-control" value="{{ old('judul', $buku->judul) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Penulis</label>
                        <input type="text" name="penulis" class="form-control"
                            value="{{ old('penulis', $buku->penulis) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Penerbit</label>
                        <input type="text" name="penerbit" class="form-control"
                            value="{{ old('penerbit', $buku->penerbit) }}">
                    </div>

                    {{-- KATEGORI (Dengan logika 'selected') --}}
                    <div class="mb-3">
                        <label class="form-label fw-medium">Kategori</label>
                        <select name="kategori_id" class="form-select">
                            <option value="" disabled>Pilih Kategori</option>
                            @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ old('kategori_id', $buku->kategori_id) ==
                                $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- INPUT GAMBAR (Dengan Preview) --}}
                    <div class="mb-3">
                        <label class="form-label fw-medium">Cover Buku</label>

                        {{-- Tampilkan gambar lama jika ada --}}
                        @if($buku->gambar)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $buku->gambar) }}" alt="Cover Lama" class="img-thumbnail"
                                style="height: 80px;">
                            <small class="text-muted d-block fst-italic">Cover saat ini</small>
                        </div>
                        @endif

                        <input type="file" name="gambar" class="form-control">
                        <small class="text-muted" style="font-size: 0.85rem">
                            *Biarkan kosong jika tidak ingin mengganti cover.
                        </small>
                    </div>

                    {{-- SINOPSIS (Isi value di antara tag textarea) --}}
                    <div class="mb-3">
                        <label class="form-label fw-medium">Sinopsis</label>
                        <textarea name="sinopsis" rows="4" class="form-control"
                            placeholder="Ringkasan cerita...">{{ old('sinopsis', $buku->sinopsis) }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Tahun Terbit</label>
                            <input type="number" name="tahun" class="form-control"
                                value="{{ old('tahun', $buku->tahun) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Stok</label>
                            <input type="number" name="stok" class="form-control"
                                value="{{ old('stok', $buku->stok) }}">
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">
                            Kembali
                        </a>

                        <button type="submit" class="btn btn-primary px-4">
                            Update
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

@endsection