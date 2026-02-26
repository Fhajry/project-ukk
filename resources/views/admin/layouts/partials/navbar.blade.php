<nav class="navbar shadow-sm" style="background:#e9e9e9; border-bottom:1px solid #dcdcdc">
    <div class="container-fluid">

        <div class="d-flex align-items-center gap-3">

            {{-- HAMBURGER --}}
            @auth
            <button class="btn btn-sm btn-outline-secondary" onclick="toggleSidebar()">
                ☰
            </button>
            @endauth

            <span class="navbar-brand fw-semibold mb-0 text-dark">
                Perpustakaan
            </span>
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