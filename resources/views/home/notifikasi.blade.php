@extends('home.layouts.app')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-primary-subtle text-primary rounded-circle p-2 me-3">
                            <i class="bi bi-bell-fill fs-4"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold text-dark">Notifikasi</h4>
                            <p class="text-muted small mb-0">Informasi terbaru terkait peminjaman buku Anda</p>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @forelse($notifikasis as $notif)
                    <div
                        class="d-flex align-items-start border-bottom py-3 {{ is_null($notif->read_at) ? 'bg-light rounded px-2' : '' }}">
                        <div class="me-3 mt-1 text-primary">
                            @if($notif->data['type'] === 'siap_diambil')
                            <i
                                class="bi bi-box-seam fs-3 {{ is_null($notif->read_at) ? 'text-info' : 'text-secondary' }}"></i>
                            @elseif($notif->data['type'] === 'dipinjam')
                            <i
                                class="bi bi-book fs-3 {{ is_null($notif->read_at) ? 'text-success' : 'text-secondary' }}"></i>
                            @elseif($notif->data['type'] === 'terlambat')
                            <i
                                class="bi bi-exclamation-triangle-fill fs-3 {{ is_null($notif->read_at) ? 'text-danger' : 'text-secondary' }}"></i>
                            @else
                            <i class="bi bi-info-circle fs-3 text-secondary"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 {{ is_null($notif->read_at) ? 'fw-bold' : '' }}">
                                {{ $notif->data['message'] ?? 'Ada pemberitahuan baru' }}
                            </h6>
                            <small class="text-muted d-block mb-2">
                                {{ $notif->created_at->diffForHumans() }}
                            </small>

                            @if(is_null($notif->read_at))
                            <form action="{{ route('home.notifikasi.baca', $notif->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill py-0 px-3"
                                    style="font-size: 0.75rem;">
                                    Tandai Dibaca
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="bi bi-bell-slash text-muted opacity-50" style="font-size: 4rem;"></i>
                        <p class="text-muted mt-3">Tidak ada notifikasi saat ini.</p>
                    </div>
                    @endforelse

                    <div class="mt-4">
                        {{ $notifikasis->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection