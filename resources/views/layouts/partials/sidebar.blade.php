<div class="p-3">

    <small class="text-muted text-uppercase fw-semibold d-block mb-3">
        Menu
    </small>

    <ul class="nav flex-column gap-1">
        <li class="nav-item">
            <a href="/dashboard" class="nav-link px-3 py-2 rounded
               {{ request()->is('dashboard') ? 'active' : '' }}">
                Dashboard
            </a>
        </li>



        @if (auth()->user()->role === 'user')
        <li class="nav-item">
            <a href="/riwayat" class="nav-link px-3 py-2 rounded
            {{ request()->is('riwayat') ? 'active' : '' }}">
                Riwayat
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('user.buku') }}" class="nav-link px-3 py-2 rounded
            {{ request()->is('user/buku') ? 'active' : '' }}">
                Data Buku
            </a>
        </li>
        @endif

        @if (auth()->user()->role === 'admin')
        <hr class="my-3">

        <small class="text-muted text-uppercase fw-semibold mb-2 d-block">
            Admin
        </small>

        <!-- <li class="nav-item">
                <a href="/buku/create"
                   class="nav-link px-3 py-2 rounded text-primary">
                    Tambah Buku
                </a>
            </li> -->
        @endif

        @if (auth()->user()->role === 'admin')
        <li class="nav-item">
            <a href="/buku" class="nav-link px-3 py-2 rounded
               {{ request()->is('buku*') ? 'active' : '' }}">
                Data Buku
            </a>
        </li>

        <li class="nav-item">
            <a href="/admin/users" class="nav-link">
                User
            </a>
        </li>

        <li class="nav-item">
            <a href="/admin/transaksi" class="nav-link">
                Transaksi
            </a>
        </li>
        <li class="nav-item">
            <a href="/admin/laporan"
                class="nav-link px-3 py-2 rounded {{ request()->is('admin/laporan*') ? 'active' : '' }}">
                Laporan
            </a>
        </li>
        @endif


    </ul>
</div>