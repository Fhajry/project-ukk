<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PerpusKita - Sistem Manajemen Perpustakaan</title>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
            padding-top: 76px;
        }

        .navbar {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.95) !important;
        }

        /* --- Styling untuk Menu Aktif --- */
        .navbar-nav .nav-link {
            color: #6c757d;
            font-weight: 500;
            position: relative;
            padding-bottom: 5px;
            /* Memberi ruang untuk garis bawah */
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: #0d6efd;
            /* Warna primary Bootstrap */
        }

        .navbar-nav .nav-link.active {
            color: #0d6efd !important;
            font-weight: 600;
        }

        /* Animasi garis bawah untuk layar besar (Desktop) */
        @media (min-width: 992px) {
            .navbar-nav .nav-link::after {
                content: '';
                position: absolute;
                width: 0;
                height: 2px;
                bottom: 0;
                left: 50%;
                background-color: #0d6efd;
                transition: all 0.3s ease-in-out;
                transform: translateX(-50%);
                border-radius: 2px;
            }

            .navbar-nav .nav-link.active::after {
                width: 80%;
                /* Garis penuh saat aktif */
            }

            .navbar-nav .nav-link:hover::after {
                width: 80%;
                /* Muncul garis saat di-hover */
            }
        }

        /* style card buku */
        .book-cover {
            width: 100%;
            height: 260px;
            /* tinggi semua buku sama */
            background: #f3f4f6;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* gambar cover */
        .book-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* kunci utama */
            transition: 0.3s ease;
        }

        /* efek hover */
        .book-cover:hover img {
            transform: scale(1.05);
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg fixed-top shadow-sm border-bottom py-3">
        <div class="container">
            {{-- Logo Brand --}}
            <a class="navbar-brand d-flex align-items-center gap-3" href="/">

                <img src="{{ asset('assets/logo.jpeg') }}" alt="Logo Perpustakaan"
                    style="width:50px; height:50px; object-fit:cover;" class="rounded">

                <div class="d-flex flex-column lh-sm">
                    <span class="fw-bold text-primary fs-5">Perpustakaan</span>
                    <small class="text-muted" style="font-size: 12px;">
                        SMKN 2 Padang Panjang
                    </small>
                </div>

            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                {{-- Menu Kiri --}}
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 mx-lg-4 gap-lg-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') || request()->routeIs('home.dashboard') ? 'active' : '' }}"
                            href="{{ route('home.dashboard') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('katalog*') || request()->routeIs('home.buku*') ? 'active' : '' }}"
                            href="{{ route('home.buku') }}">Katalog Buku</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('riwayat*') || request()->routeIs('home.riwayat*') ? 'active' : '' }}"
                            href="{{ route('home.riwayat') }}">Riwayat Peminjaman</a>
                    </li>
                </ul>

                {{-- Menu Kanan (Auth & Profil) --}}
                <ul class="navbar-nav align-items-lg-center gap-2">
                    @guest
                    <li class="nav-item">
                        <a class="btn btn-primary px-4 rounded-pill fw-medium shadow-sm"
                            href="{{ route('login') }}">Masuk</a>
                    </li>

                    @else
                    {{-- Lonceng Notifikasi --}}
                    <li class="nav-item me-3 d-flex align-items-center">
                        <a href="{{ route('home.notifikasi') }}" class="nav-link position-relative text-dark">
                            <i class="bi bi-bell fs-5"></i>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                {{ Auth::user()->unreadNotifications->count() }}
                                <span class="visually-hidden">unread messages</span>
                            </span>
                            @endif
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="userDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 35px; height: 35px;">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <span class="fw-semibold text-dark">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-3 rounded-3"
                            aria-labelledby="userDropdown">
                            {{-- Info User Ringkas --}}
                            <li class="px-4 py-3 border-bottom mb-2 text-center bg-light rounded-top-3">
                                <strong class="d-block text-dark">{{ Auth::user()->name }}</strong>
                                <small class="text-muted">{{ Auth::user()->email }}</small>
                            </li>

                            {{-- Menu Berdasarkan Role --}}
                            @if(Auth::user()->role === 'admin')
                            <li>
                                <a class="dropdown-item py-2 fw-medium" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2 text-primary"></i>Panel Admin
                                </a>
                            </li>
                            @endif

                            {{-- Menu Profil & Logout --}}

                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger fw-bold">
                                        <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endguest
                </ul>

            </div>
        </div>
    </nav>

    <main style="min-height: 80vh;">
        @yield('content')
    </main>

    <footer class="bg-white border-top py-4 mt-5 text-center text-muted small">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} <strong>PerpusKita</strong>. Sistem Manajemen Perpustakaan Modern.
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
