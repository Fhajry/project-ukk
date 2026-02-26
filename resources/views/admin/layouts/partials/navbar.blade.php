<nav class="navbar shadow-sm" style="background:#e9e9e9; border-bottom:1px solid #dcdcdc">
    <div class="container-fluid">

        <div class="d-flex align-items-center gap-3">

            {{-- HAMBURGER --}}
            @auth
            <button class="btn btn-sm btn-outline-secondary" onclick="toggleSidebar()">
                ☰
            </button>
            @endauth

            <a href="/" class="navbar-brand d-flex align-items-center gap-3 mb-0">

                <img src="{{ asset('assets/logo.jpeg') }}" alt="Logo Perpustakaan"
                    style="width:45px; height:45px; object-fit:cover;" class="rounded">

                <div class="d-flex flex-column lh-sm">
                    <span class="fw-bold text-dark">Perpustakaan</span>
                    <small class="text-muted" style="font-size:12px;">
                        SMKN 2 Padang Panjang
                    </small>
                </div>

            </a>
        </div>

        @auth
        <div class="d-flex align-items-center gap-3">

            <span class="text-dark small">
                {{ auth()->user()->name }}
            </span>

            <span class="badge bg-light text-dark border">
                {{ auth()->user()->role }}
            </span>

            <a href="{{ route('logout') }}" class="btn btn-sm btn-outline-danger">
                Logout
            </a>

        </div>
        @endauth

    </div>
</nav>