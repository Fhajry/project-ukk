@extends('admin.layouts.app')
@section('content')
<div class="d-flex justify-content-center align-items-center"
    style="min-height: 85vh; padding-top: 2rem; padding-bottom: 2rem;">
    <div class="card border-0 shadow-sm p-4" style="width: 100%; max-width: 450px; border-radius: 16px;">

        <div class="text-center mb-4">
            <div class="mx-auto mb-3 d-flex align-items-center justify-content-center bg-light rounded-circle"
                style="width: 60px; height: 60px;">
                <i class="bi bi-person-plus text-dark fs-3"></i>
            </div>
            <h4 class="fw-bold text-dark mb-1">Buat Akun Baru</h4>
            <p class="text-muted small">Lengkapi data di bawah untuk bergabung</p>
        </div>

        {{-- VALIDATION ERRORS --}}
        @if ($errors->any())
        <div class="alert alert-danger border-0 small py-2 mb-4">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="/register" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label small fw-semibold text-muted">Nama Lengkap</label>
                <input type="text" name="name" class="form-control py-2" placeholder="Nama Anda"
                    value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold text-muted">Alamat Email</label>
                <input type="email" name="email" class="form-control py-2" placeholder="nama@email.com"
                    value="{{ old('email') }}" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold text-muted">Password</label>
                    <input type="password" name="password" class="form-control py-2" placeholder="••••••••" required>
                </div>
                <div class="col-md-6 mb-4">
                    <label class="form-label small fw-semibold text-muted">Konfirmasi</label>
                    <input type="password" name="password_confirmation" class="form-control py-2" placeholder="••••••••"
                        required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mb-3">
                Daftar Sekarang
            </button>
        </form>

        <div class="text-center mt-2">
            <p class="small text-muted mb-0">
                Sudah punya akun?
                <a href="/login" class="text-dark fw-bold text-decoration-none border-bottom border-dark border-2">
                    Login di sini
                </a>
            </p>
        </div>

    </div>
</div>

<style>
    /* Konsistensi dengan tema minimalis */
    .form-control {
        border-color: #eee;
        background-color: #fcfcfc;
        border-radius: 8px;
    }

    .form-control:focus {
        background-color: #fff;
        border-color: var(--accent);
        box-shadow: none;
    }

    .alert ul {
        list-style-type: none;
    }
</style>
@endsection