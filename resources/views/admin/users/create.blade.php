@extends('admin.layouts.app')
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">

        <div class="card border-0">
            {{-- HEADER --}}
            <div class="card-header" style="background:#f4f4f4; border-bottom:1px solid #dedede">
                <h5 class="mb-0 fw-semibold">
                    Tambah User
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

                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                            placeholder="Nama lengkap user">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Alamat Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                            placeholder="contoh@email.com">
                    </div>

                    {{-- SAYA MEMBUAT PASSWORD DAN ROLE SEJAJAR (ROW) AGAR MIRIP BAGIAN TAHUN & STOK --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Password</label>
                            <input type="password" name="password" class="form-control"
                                placeholder="Minimal 8 karakter">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Role</label>
                            <select name="role" class="form-select">
                                <option value="user" {{ old('role')=='user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ old('role')=='admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                            Kembali
                        </a>

                        <button type="submit" class="btn btn-primary px-4">
                            Simpan
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

@endsection