@extends('admin.layouts.app')

@section('content')
<style>
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        transition: all 0.2s ease;
        transform: scale(1.002);
    }

    .card {
        border-radius: 12px;
        overflow: hidden;
    }

    .btn {
        border-radius: 8px;
        font-weight: 500;
    }

    .badge {
        padding: 0.5em 0.8em;
        font-weight: 500;
    }
</style>

<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">

        {{-- HEADER --}}
        <div class="card-header bg-white py-3 border-bottom-0">
            <div>
                <h4 class="mb-0 fw-bold text-dark">Laporan Peminjaman</h4>
                <p class="text-muted small mb-0">
                    Cetak laporan peminjaman buku per user dalam bentuk PDF
                </p>
            </div>
        </div>

        <div class="card-body">

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr class="text-muted small text-uppercase fw-bold border-top" style="background:#fafafa">
                            <th class="ps-4 py-3">User</th>
                            <th>Email</th>
                            <th class="text-center">Role</th>
                            <th class="text-center" style="width:200px">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $user->name }}</div>
                                <div class="text-muted small">ID: {{ $user->id }}</div>
                            </td>

                            <td>{{ $user->email }}</td>

                            <td class="text-center">
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill">
                                    {{ $user->role }}
                                </span>
                            </td>

                            <td class="text-center">
                                <a href="/admin/laporan/user/{{ $user->id }}"
                                    class="btn btn-sm btn-primary px-4 shadow-sm">
                                    <i class="bi bi-file-earmark-pdf me-1"></i>
                                    Cetak PDF
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="bi bi-journal-x fs-1 text-muted"></i>
                                <p class="text-muted mt-3">Tidak ada user untuk laporan</p>
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
@endpush
@endsection
