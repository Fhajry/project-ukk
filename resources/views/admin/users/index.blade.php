@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <div class="card border-0 shadow-sm">
        {{-- HEADER --}}
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                {{-- Judul & Icon --}}
                <div class="d-flex align-items-center">
                    <div class="icon-shape bg-primary-subtle text-primary rounded-circle me-3">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold">Daftar User</h4>
                        <small class="text-muted">
                            Total user: {{ $users->total() }}
                        </small>
                    </div>
                </div>

                {{-- Tombol Tambah --}}
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Tambah User
                </a>
            </div>
        </div>

        {{-- BODY --}}
        <div class="card-body">

            {{-- ALERT SUCCESS --}}
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light text-uppercase small">
                        <tr>
                            <th style="width: 60px" class="text-center">#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th class="text-center">Role</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($users as $key => $user)
                        <tr>
                            {{-- Penomoran halaman (agar nomor berlanjut di page 2, dst) --}}
                            <td class="text-center text-muted">
                                {{ $users->firstItem() + $key }}
                            </td>

                            <td class="fw-semibold">
                                {{ $user->name }}
                            </td>

                            <td class="text-muted">
                                {{ $user->email }}
                            </td>

                            <td class="text-center">
                                @if ($user->role === 'admin')
                                <span
                                    class="badge bg-danger-subtle text-danger px-3 rounded-pill border border-danger-subtle">
                                    Admin
                                </span>
                                @else
                                <span
                                    class="badge bg-success-subtle text-success px-3 rounded-pill border border-success-subtle">
                                    User
                                </span>
                                @endif
                            </td>

                            <td class="text-end">
                                <a href="{{ route('users.edit', $user->id) }}"
                                    class="btn btn-sm btn-outline-warning me-1">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>

                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus user {{ $user->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada data user.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="mt-4 d-flex justify-content-end">
                {{ $users->links() }}
            </div>

        </div>
    </div>

</div>

@push('styles')
{{-- Bootstrap Icons CDN --}}
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
        letter-spacing: .05em;
        font-size: .75rem;
        font-weight: 600;
        color: #6c757d;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endpush
@endsection
