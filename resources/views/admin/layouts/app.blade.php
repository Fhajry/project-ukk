<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Digital</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            /* Palette: Fresh & Clean */
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            --bg-body: #f8fafc;
            --sidebar-bg: #ffffff;
            --text-dark: #0f172a;
            --text-light: #64748b;
            --accent: #6366f1;
            --card-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.04);
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-dark);
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
        }

        /* LAYOUT */
        .layout {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main {
            flex: 1;
            display: flex;
        }

        /* NAVBAR */
        .navbar {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(10px);
            /* Efek Kaca */
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem 2rem;
            z-index: 1050;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--text-dark);
            letter-spacing: -0.5px;
        }

        /* SIDEBAR: Putih Bersih */
        #sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            border-right: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            z-index: 1040;
        }

        #sidebar.collapsed {
            margin-left: -280px;
        }

        #sidebar .sidebar-heading {
            padding: 2.5rem 2rem 1rem;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #94a3b8;
        }

        #sidebar .nav-link {
            margin: 0.25rem 1.25rem;
            padding: 0.8rem 1rem;
            color: var(--text-light);
            font-weight: 500;
            border-radius: 12px;
            display: flex;
            align-items: center;
            transition: 0.2s;
        }

        #sidebar .nav-link i {
            font-size: 1.25rem;
            margin-right: 12px;
        }

        #sidebar .nav-link:hover {
            color: var(--accent);
            background: #f1f5f9;
        }

        #sidebar .nav-link.active {
            color: #ffffff;
            background: var(--primary-gradient);
            box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4);
        }

        /* CONTENT */
        #content {
            flex: 1;
            padding: 3rem;
            transition: 0.4s;
        }

        /* CARDS: Mewah & Minimalis */
        .card {
            border: none;
            border-radius: 20px;
            background: #ffffff;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        /* BUTTONS */
        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 14px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.2);
        }

        .btn-primary:hover {
            opacity: 0.9;
            transform: scale(1.02);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.3);
        }

        /* TABLE BUKU */
        <>

        /* Styling Tetap Sama */
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
            transition: all 0.2s ease;
        }

        .card {
            border-radius: 12px;
            overflow: hidden;
        }

        .search-container {
            background: #fcfcfc;
            border: 1px solid #eee;
            border-radius: 12px;
            padding: 20px;
        }

        .img-book-cover {
            width: 50px;
            height: 70px;
            object-fit: cover;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

    /* FOOTER */
    footer { padding: 2rem; color: #94a3b8; font-size: 0.85rem; }

    /* Animasi masuk */
    @keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
    }
    #content { animation: fadeIn 0.5s ease-out; }
    </style>
</head>

<body>

    <div class="layout">
        @include('admin.layouts.partials.navbar')

        <div class="main">
            @auth
            <aside id="sidebar">
                <div class="sidebar-heading">Main Menu</div>
                @include('admin.layouts.partials.sidebar')
            </aside>
            @endauth

            <main id="content">
                @yield('content')
            </main>
        </div>

        <footer class="text-center">
            &copy; {{ date('Y') }} <span style="color:var(--text-dark); font-weight:600;">Perpustakaan</span>. Digital
            Experience.
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('collapsed');
    }
    </script>

</body>

</html>
