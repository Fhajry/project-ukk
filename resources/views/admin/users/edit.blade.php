@extends('admin.layouts.app')
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">

        <div class="card border-0">
            {{-- HEADER --}}
            <div class="card-header" style="background:#f4f4f4; border-bottom:1px solid #dedede">
                <h5 class="mb-0 fw-semibold">
                    Edit User
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

                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                            placeholder="Masukkan nama lengkap">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Alamat Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}"
                            placeholder="contoh@email.com">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Password</label>
                            <input type="password" name="password" class="form-control"
                                placeholder="Kosongkan jika tidak ingin mengganti">
                            <small class="text-muted" style="font-size: 0.8rem">
                                *Biarkan kosong agar password tetap sama.
                            </small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Role (Peran)</label>
                            <select name="role" class="form-control">
                                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User
                                </option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin
                                </option>
                            </select>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
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