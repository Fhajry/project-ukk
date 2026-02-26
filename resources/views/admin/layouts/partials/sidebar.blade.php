<div class="p-3">
    <small class="text-muted text-uppercase fw-semibold d-block mb-3">
        Menu
    </small>

    <ul class="nav flex-column gap-1">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link px-3 py-2 rounded
               {{ request()->is('dashboard') ? 'active' : '' }}">
                Dashboard
            </a>
        </li>

        @if (auth()->user()->role === 'admin')
        <hr class="my-3">
        <small class="text-muted text-uppercase fw-semibold mb-2 d-block">Admin</small>

        <li class="nav-item">
            <a class="nav-link px-3 py-2 rounded d-flex justify-content-between align-items-center {{ request()->is('buku*', 'kategori*', 'penulis*', 'penerbit*') ? 'active' : '' }}"
                data-bs-toggle="collapse" href="#menuBuku" role="button"
                aria-expanded="{{ request()->is('buku*', 'kategori*', 'penulis*', 'penerbit*') ? 'true' : 'false' }}">
                <span>Data Buku</span>
                <i class="bi bi-chevron-down small"></i>
            </a>

            <div class="collapse {{ request()->is('buku*', 'kategori*', 'penulis*', 'penerbit*') ? 'show' : '' }} ms-3 mt-1"
                id="menuBuku">
                <ul class="nav flex-column gap-1 border-start ps-2">
                    <li class="nav-item">
                        <a href="{{ route('bukus.index') }}"
                            class="nav-link py-1 {{ request()->is('buku') ? 'fw-bold text-primary' : '' }}">
                            Daftar Buku
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('kategori.index') }}"
                            class="nav-link py-1 {{ request()->is('kategori*') ? 'fw-bold text-primary' : '' }}">
                            Kategori Buku
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('penulis.index') }}"
                            class="nav-link py-1 {{ request()->is('penulis*') ? 'fw-bold text-primary' : '' }}">
                            Penulis
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('penerbit.index') }}"
                            class="nav-link py-1 {{ request()->is('penerbit*') ? 'fw-bold text-primary' : '' }}">
                            Penerbit
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a href="{{ route('users.index') }}"
                class="nav-link px-3 py-2 rounded {{ request()->is('users*') ? 'active' : '' }}">
                User
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('pengaturan.index') }}"
                class="nav-link px-3 py-2 rounded {{ request()->is('pengaturan*') ? 'active' : '' }}">
                Pengaturan Denda
            </a>
        </li>
        <li class="nav-item">
            <a href="/admin/transaksi"
                class="nav-link px-3 py-2 rounded {{ request()->is('admin/transaksi*') ? 'active' : '' }}">
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