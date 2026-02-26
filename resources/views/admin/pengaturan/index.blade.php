@extends('admin.layouts.app')
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">

        <div class="card border-0">
            {{-- HEADER --}}
            <div class="card-header" style="background:#f4f4f4; border-bottom:1px solid #dedede">
                <h5 class="mb-0 fw-semibold">
                    Pengaturan Denda & Peminjaman
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

                {{-- SUCCESS MESSAGE --}}
                @if (session('success'))
                <div class="alert alert-success small">
                    {{ session('success') }}
                </div>
                @endif

                <form action="{{ route('pengaturan.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-medium">Denda Keterlambatan Harian (Rp)</label>
                        {{-- Ubah menjadi type="text" --}}
                        <input type="text" name="denda_harian" class="form-control"
                            value="{{ old('denda_harian', number_format($pengaturan->denda_harian, 0, '', '.')) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Denda Buku Hilang (Rp)</label>
                        {{-- Ubah menjadi type="text" --}}
                        <input type="text" name="denda_hilang" class="form-control"
                            value="{{ old('denda_hilang', number_format($pengaturan->denda_hilang, 0, '', '.')) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Batas Waktu Peminjaman (Hari)</label>
                        {{-- Tetap type="number" karena format hari tidak butuh titik ribuan --}}
                        <input type="number" name="hari_jatuh_tempo" class="form-control"
                            value="{{ old('hari_jatuh_tempo', $pengaturan->hari_jatuh_tempo) }}">
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between">
                        {{-- Sesuaikan link 'Kembali' ini dengan rute dashboard Anda --}}
                        <a href="/dashboard" class="btn btn-outline-secondary">
                            Kembali
                        </a>

                        <button type="submit" class="btn btn-primary px-4">
                            Simpan Pengaturan
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

@endsection