@extends('home.layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        {{-- HEADER --}}
        <div class="card-header bg-white py-3 border-bottom-0">
            <div class="d-flex align-items-center">
                <div class="icon-shape bg-primary-subtle text-primary rounded-circle p-2 me-3">
                    <i class="bi bi-clock-history fs-4"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold text-dark">Riwayat Peminjaman</h4>
                    <p class="text-muted small mb-0">Pantau status pengembalian buku Anda di sini.</p>
                </div>
            </div>
        </div>

        <div class="card-body">
            {{-- FLASH MESSAGE --}}
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr class="text-muted small text-uppercase fw-bold border-top" style="background: #fafafa">
                            <th class="ps-4 py-3">Buku yang Dipinjam</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th class="text-center">Status</th>
                            <th>Jatuh Tempo</th>
                            <th class="text-end">Denda</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksis as $item)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $item->buku->judul }}</div>
                                <div class="text-muted small">ID Transaksi: #TRX-{{ $item->id }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar-event me-2 text-primary"></i>
                                    {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}
                                </div>
                            </td>
                            <td>
                                @if($item->tanggal_kembali)
                                <div class="d-flex align-items-center text-success">
                                    <i class="bi bi-calendar-check me-2"></i>
                                    {{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') }}
                                </div>
                                @else
                                <span class="text-muted small italic">Belum Dikembalikan</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($item->status === 'menunggu_konfirmasi')
                                <span class="badge bg-warning text-dark border border-warning-subtle rounded-pill px-3">
                                    <i class="bi bi-hourglass-split me-1"></i> Menunggu
                                </span>
                                @elseif ($item->status === 'siap_diambil')
                                <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3">
                                    <i class="bi bi-box-seam me-1"></i> Siap Diambil
                                </span>
                                @elseif ($item->status === 'dipinjam')
                                <span
                                    class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3">
                                    Sedang Dipinjam
                                </span>
                                @elseif ($item->status === 'batal_otomatis')
                                <span class="badge bg-secondary text-white rounded-pill px-3">
                                    Batal Otomatis
                                </span>
                                @elseif ($item->status === 'dikembalikan')
                                <span
                                    class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">
                                    Selesai
                                </span>
                                @else
                                <span class="badge bg-dark text-white rounded-pill px-3">Lainnya</span>
                                @endif
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->format('d M Y') }}
                            </td>

                            <td class="text-end">
                                @if ($item->denda > 0)
                                <span class="fw-bold text-danger">
                                    Rp {{ number_format($item->denda) }}
                                </span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($item->status === 'menunggu_konfirmasi')
                                <button class="btn btn-sm btn-light disabled text-success border-0">
                                    <i class="bi bi-patch-check-fill me-1"></i> Menunggu Konfirmasi
                                </button>
                                @elseif ($item->status === 'siap_diambil')
                                <button class="btn btn-sm btn-info text-white border-0" onclick="alert('Silakan temui admin di perpustakaan dalam 24 jam untuk mengambil buku.')">
                                    <i class="bi bi-info-circle me-1"></i> Info
                                </button>
                                @elseif ($item->status === 'batal_otomatis')
                                <button class="btn btn-sm btn-light disabled text-danger border-0">
                                    <i class="bi bi-x-circle-fill me-1"></i> Dibatalkan
                                </button>
                                @else
                                <button class="btn btn-sm btn-light disabled text-success border-0">
                                    <i class="bi bi-patch-check-fill me-1"></i> Selesai
                                </button>
                                @endif
                            </td>


                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/gray/not-found.svg" alt="empty"
                                    style="width: 120px;" class="mb-3 opacity-50">
                                <p class="text-muted">Anda belum pernah meminjam buku.</p>
                                <a href="/buku" class="btn btn-primary px-4 mt-2">Mulai Cari Buku</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    .icon-shape {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .table thead th {
        font-size: 0.75rem;
        letter-spacing: 0.05em;
    }

    .btn {
        border-radius: 8px;
    }

    .badge {
        font-weight: 500;
    }
</style>
@endpush
@endsection
