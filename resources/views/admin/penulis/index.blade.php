@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Data Penulis</h4>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah
            Penulis</button>
    </div>

    @if(session('sukses'))
    <div class="alert alert-success">{{ session('sukses') }}</div>
    @endif

    <table class="table table-bordered bg-white shadow-sm">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Penulis</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penulis as $p)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $p->nama_penulis }}</td>
                <td>
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                        data-bs-target="#modalEdit{{ $p->id }}">Edit</button>

                    <form action="{{ route('penulis.destroy', $p->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">Hapus</button>
                    </form>
                </td>
            </tr>

            <div class="modal fade" id="modalEdit{{ $p->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('penulis.update', $p->id) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Penulis</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <label>Nama Penulis</label>
                                <input type="text" name="nama_penulis" class="form-control"
                                    value="{{ $p->nama_penulis }}" required>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-5 mb-4">
        {{ $penulis->links() }}
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('penulis.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Penulis Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>Nama Penulis</label>
                    <input type="text" name="nama_penulis" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection