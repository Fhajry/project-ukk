@extends('home.layouts.app')
@section('content')
<div class="container py-5">

    {{-- HEADER SAMBUTAN --}}
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="fw-bold text-dark mb-1">Selamat Datang, {{ auth()->user()->name }}! 👋</h3>
            <p class="text-muted">Berikut adalah ringkasan aktivitas perpustakaanmu saat ini.</p>
        </div>
    </div>

    {{-- ALERT DENDA BELUM LUNAS --}}
    @php
        // Ambil transaksi user yang sedang login dengan status denda belum lunas
        $totalHutang = \App\Models\Transaksi::where('user_id', auth()->id())
            ->where('status_denda', 'belum_lunas')
            ->sum('denda');
    @endphp
    
    @if ($totalHutang > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-danger d-flex align-items-center border-0 shadow-sm m-0" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                <div>
                    <strong>Perhatian!</strong> Anda memiliki tagihan denda keterlambatan buku yang belum dilunasi sebesar 
                    <strong class="fs-5">Rp {{ number_format($totalHutang, 0, ',', '.') }}</strong>. 
                    <br>Mohon segera lunasi di meja administrasi perpustakaan.
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- WIDGET STATISTIK --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm bg-primary text-white h-100 rounded-4">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 60px; height: 60px;">
                        <i class="bi bi-book fs-3"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 fw-bold">{{ $sedangDipinjam }}</h3>
                        <span class="small opacity-75">Buku Dipinjam</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm bg-warning text-dark h-100 rounded-4">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="bg-dark bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 60px; height: 60px;">
                        <i class="bi bi-box-seam fs-3"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 fw-bold">{{ $siapDiambil }}</h3>
                        <span class="small opacity-75 fw-medium">Buku Siap Diambil</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm bg-info text-white h-100 rounded-4">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 60px; height: 60px;">
                        <i class="bi bi-cash-stack fs-3"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 fw-bold">Rp {{ number_format($totalDenda, 0, ',', '.') }}</h3>
                        <span class="small opacity-75">Total Denda</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div
                class="card border-0 shadow-sm {{ $dendaBelumLunas > 0 ? 'bg-danger' : 'bg-success' }} text-white h-100 rounded-4">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 60px; height: 60px;">
                        <i class="bi bi-exclamation-circle fs-3"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 fw-bold">Rp {{ number_format($dendaBelumLunas, 0, ',', '.') }}</h3>
                        <span class="small opacity-75">Denda Belum Dibayar</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- PANEL PEMINJAMAN AKTIF --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
                    <i class="bi bi-activity text-primary fs-4 me-2"></i>
                    <h5 class="mb-0 fw-bold">Peminjaman Aktif</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Judul Buku</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Batas Kembali</th>
                                    <th>Status Saat Ini</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksiAktif as $trx)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            @if($trx->buku->gambar)
                                            <img src="{{ asset('storage/'.$trx->buku->gambar) }}" alt="Cover"
                                                class="rounded shadow-sm me-3"
                                                style="width: 45px; height: 60px; object-fit: cover;">
                                            @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3"
                                                style="width: 45px; height: 60px;">
                                                <i class="bi bi-book text-muted"></i>
                                            </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $trx->buku->judul }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $trx->tanggal_pinjam ? \Carbon\Carbon::parse($trx->tanggal_pinjam)->format('d
                                        M Y') : '-' }}</td>
                                    <td>
                                        @if($trx->tanggal_jatuh_tempo)
                                        <span class="fw-medium text-danger">{{
                                            \Carbon\Carbon::parse($trx->tanggal_jatuh_tempo)->format('d M Y') }}</span>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td>
                                        @if($trx->status === 'menunggu_konfirmasi')
                                        <span class="badge bg-secondary rounded-pill px-3 py-2"><i
                                                class="bi bi-hourglass me-1"></i> Menunggu Konfirmasi</span>
                                        @elseif($trx->status === 'siap_diambil')
                                        {{-- Ini yang nanti akan kita beri animasi/timer hitung mundur --}}
                                        <span
                                            class="badge bg-warning text-dark rounded-pill px-3 py-2 border border-warning"><i
                                                class="bi bi-bell-fill me-1"></i> Segera Ambil!</span>
                                        @elseif($trx->status === 'dipinjam')
                                        <span class="badge bg-primary rounded-pill px-3 py-2"><i
                                                class="bi bi-book-half me-1"></i> Sedang Dipinjam</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            Kamu tidak memiliki peminjaman buku yang aktif saat ini.
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="text-end mt-3">
                <a href="{{ route('home.riwayat') }}" class="text-decoration-none fw-medium">Lihat Semua Riwayat <i
                        class="bi bi-arrow-right"></i></a>
            </div>

        </div>
    </div>

</div>
@endsection