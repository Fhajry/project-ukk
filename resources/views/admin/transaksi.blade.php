@extends('admin.layouts.app')
@section('content')
<div class="container-fluid py-4">

    <div class="card border-0 shadow-sm">
        {{-- HEADER --}}
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="icon-shape bg-primary-subtle text-primary rounded-circle me-3">
                        <i class="bi bi-journal-text fs-4"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold">Data Transaksi</h4>
                        <p class="mb-0 text-muted small">Kelola peminjaman dan pengembalian buku.</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.transaksi.export', request()->query()) }}"
                        class="btn btn-success d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-excel"></i> Export Excel
                    </a>
                    <span class="badge bg-primary d-flex align-items-center px-3 rounded-pill">
                        {{ $transaksis->count() }} Total
                    </span>
                </div>
            </div>
        </div>

        {{-- BODY --}}
        <div class="card-body">

            <div class="card-body">

                {{-- Flash Message --}}
                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                {{-- ================= FORM FILTER PENCARIAN ================= --}}
                <div class="bg-light p-3 rounded mb-4">
                    <form action="{{ url()->current() }}" method="GET">
                        <div class="row g-2 align-items-end">

                            <div class="col-md-4">
                                <label class="small fw-bold text-muted mb-1">Cari Peminjam / Buku</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Ketik nama atau judul..." value="{{ request('search') }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="small fw-bold text-muted mb-1">Tgl Pinjam</label>
                                <input type="date" name="tgl_pinjam" class="form-control"
                                    value="{{ request('tgl_pinjam') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="small fw-bold text-muted mb-1">Tgl Kembali</label>
                                <input type="date" name="tgl_kembali" class="form-control"
                                    value="{{ request('tgl_kembali') }}">
                            </div>

                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-dark w-100"><i class="bi bi-funnel me-1"></i>
                                    Filter</button>

                                {{-- Tombol Reset akan muncul jika ada filter yang aktif --}}
                                @if(request()->anyFilled(['search', 'tgl_pinjam', 'tgl_kembali']))
                                <a href="{{ url()->current() }}" class="btn btn-outline-danger" title="Reset Filter">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light text-uppercase small">
                            <tr>
                                <th class="ps-3">Peminjam & Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Jatuh Tempo</th>
                                <th>Tgl Kembali</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Denda</th>
                                <th class="text-center" style="width: 220px;">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($transaksis as $item)
                            <tr>
                                {{-- 1. User & Buku --}}
                                <td class="ps-3">
                                    <div class="fw-bold text-dark">{{ $item->user->name }}</div>
                                    <div class="text-muted small">
                                        <i class="bi bi-book me-1"></i> {{ $item->buku->judul }}
                                    </div>
                                </td>

                                {{-- 2. Tgl Pinjam --}}
                                <td>
                                    @if($item->tanggal_pinjam)
                                    {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}
                                    @else
                                    <span class="text-muted small fst-italic">-</span>
                                    @endif
                                </td>

                                {{-- 3. Jatuh Tempo --}}
                                <td>
                                    @if($item->tanggal_jatuh_tempo)
                                    {{ \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->format('d M Y') }}
                                    @else
                                    <span class="text-muted small fst-italic">-</span>
                                    @endif
                                </td>

                                {{-- 4. Tgl Kembali (TAMBAHAN BARU) --}}
                                <td>
                                    @if($item->tanggal_kembali)
                                    {{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') }}
                                    @else
                                    <span class="text-muted small fst-italic">-</span>
                                    @endif
                                </td>

                                {{-- 5. Status Badge --}}
                                <td class="text-center">
                                    @if ($item->status === 'menunggu_konfirmasi')
                                    <span
                                        class="badge bg-warning text-dark border border-warning-subtle rounded-pill px-3">
                                        <i class="bi bi-hourglass-split me-1"></i> Menunggu
                                    </span>
                                    @elseif ($item->status === 'dipinjam')
                                    <span
                                        class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3">
                                        Dipinjam
                                    </span>
                                    @elseif ($item->status === 'dikembalikan')
                                    <span
                                        class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">
                                        Dikembalikan
                                    </span>
                                    @elseif ($item->status === 'ditolak')
                                    <span
                                        class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">
                                        Ditolak
                                    </span>
                                    @else
                                    <span class="badge bg-dark text-white rounded-pill px-3">Hilang</span>
                                    @endif
                                </td>

                                {{-- 6. Denda --}}
                                <td class="text-end">
                                    @if ($item->denda > 0)
                                    <span class="fw-bold text-danger">Rp {{ number_format($item->denda, 0, ',', '.')
                                        }}</span>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- 7. Tombol Aksi --}}
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">

                                        {{-- KONDISI 1: Jika Status MENUNGGU --}}
                                        @if ($item->status === 'menunggu_konfirmasi')
                                        <form action="/admin/transaksi/{{ $item->id }}/setujui" method="POST">
                                            @csrf
                                            <button class="btn btn-sm btn-success px-3" title="Setujui">
                                                <i class="bi bi-check-lg"></i> Terima
                                            </button>
                                        </form>

                                        <form action="/admin/transaksi/{{ $item->id }}/tolak" method="POST"
                                            onsubmit="return confirm('Tolak permintaan peminjaman ini?')">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-danger" title="Tolak">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>

                                        {{-- KONDISI 2: Jika Status DIPINJAM --}}
                                        @elseif ($item->status === 'dipinjam')
                                        <form action="/admin/transaksi/{{ $item->id }}/kembali" method="POST"
                                            onsubmit="return confirm('Yakin buku sudah dikembalikan?')">
                                            @csrf
                                            <button class="btn btn-sm btn-primary px-3" title="Proses Pengembalian">
                                                <i class="bi bi-arrow-return-left me-1"></i> Dikembalikan
                                            </button>
                                        </form>

                                        <form action="/admin/transaksi/{{ $item->id }}/hilang" method="POST"
                                            onsubmit="return confirm('Tandai buku sebagai hilang? Denda akan diterapkan.')">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-danger" title="Laporkan Hilang">
                                                <i class="bi bi-exclamation-circle"></i>
                                            </button>
                                        </form>

                                        {{-- KONDISI 3: Status Lainnya (Arsip) --}}
                                        @else
                                        <span class="text-muted small">
                                            <i class="bi bi-archive me-1"></i> Arsip
                                        </span>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                {{-- ⬅️ UBAH COLSPAN MENJADI 7 KARENA ADA PENAMBAHAN KOLOM --}}
                                <td colspan="7" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <i class="bi bi-inbox fs-1 text-muted opacity-50"></i>
                                        <p class="text-muted mt-2">Belum ada data transaksi.</p>
                                    </div>
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
            font-size: .75rem;
            letter-spacing: .05em;
            font-weight: 600;
            color: #6c757d;
        }

        /* Mencegah tombol turun ke bawah (wrap) */
        td div.d-flex {
            white-space: nowrap;
        }

        .btn {
            border-radius: 6px;
        }
    </style>
    @endpush
    @endsection