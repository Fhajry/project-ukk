@extends('admin.layouts.app')
@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 75vh;">
    <div class="card border-0 shadow-sm p-4" style="width: 100%; max-width: 400px; border-radius: 16px;">

        <div class="text-center mb-4">
            {{-- Icon simpel untuk mengganti warna-warni --}}
            <div class="mx-auto mb-3 d-flex align-items-center justify-content-center bg-light rounded-circle"
                style="width: 60px; height: 60px;">
                <i class="bi bi-shield-lock text-dark fs-3"></i>
            </div>
            <h4 class="fw-bold text-dark mb-1">Selamat Datang</h4>
            <p class="text-muted small">Silakan masuk ke akun perpustakaan Anda</p>
        </div>

        @if (session('error'))
        <div class="alert alert-danger border-0 small py-2">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        </div>
        @endif

        <form method="POST" action="/login">
            @csrf

            <div class="mb-3">
                <label class="form-label small fw-semibold text-muted">Alamat Email</label>
                <input type="email" name="email" class="form-control py-2" placeholder="nama@email.com" required>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <label class="form-label small fw-semibold text-muted">Password</label>
                </div>
                <input type="password" name="password" class="form-control py-2" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mb-3">
                Masuk Sekarang
            </button>
        </form>

        <div class="text-center mt-3">
            <p class="small text-muted mb-0">
                Belum punya akun?
                <a href="/register" class="text-dark fw-bold text-decoration-none border-bottom border-dark border-2">
                    Daftar di sini
                </a>
            </p>
        </div>

    </div>
</div>

<style>
    /* Menyesuaikan dengan gaya minimalis layout baru */
    .form-control {
        border-color: #eee;
        background-color: #fcfcfc;
    }

    .form-control:focus {
        background-color: #fff;
    }

    .btn-primary {
        letter-spacing: 0.02em;
    }
</style>
@endsection