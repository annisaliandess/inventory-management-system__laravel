<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Inventori Toko Hijab') - Hijab Inventory</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --bg-body: #fff1f2;
            --card-bg: #ffffff;
            --primary: #be185d;
            --primary-hover: #9d174d;
            --primary-light: #fce7f3;
            --primary-border: #fbcfe8;

            --text-main: #1e1b1d;
            --text-muted: #5e4d51;
            --border-ui: #fbcfe8;
            --border-table: #fce7f3;

            --btn-in: #15803d;
            --btn-out: #b91c1c;
            --btn-edit: #c2410c;
            --btn-del: #9f1239;
        }

        * {
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #fff1f2 0%, #fce7f3 45%, #fbcfe8 100%);
            margin: 0;
            padding: 30px 15px 50px 15px;
            color: var(--text-main);
            min-height: 100vh;
        }

        .main-container {
            max-width: 1240px;
            margin: 0 auto;
            background: var(--card-bg);
            padding: 32px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(190, 24, 93, 0.08), 0 4px 12px rgba(0, 0, 0, 0.03);
            border: 2px solid var(--border-ui);
        }

        /* Navbar Header */
        .app-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            border-bottom: 2px solid var(--border-ui);
            padding-bottom: 20px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .brand-title {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: -0.4px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .brand-title:hover {
            color: var(--primary-hover);
        }

        .brand-subtitle {
            margin: 4px 0 0 0;
            color: var(--text-muted);
            font-size: 13.5px;
            font-weight: 500;
        }

        /* Nav Buttons */
        .nav-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .user-pill {
            background: #fff0f5;
            padding: 7px 14px;
            border-radius: 30px;
            border: 1.5px solid var(--border-ui);
            font-size: 13px;
            color: #37282c;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-custom-primary {
            background-color: var(--primary);
            color: #ffffff;
            font-weight: 700;
            border: none;
            box-shadow: 0 4px 14px rgba(190, 24, 93, 0.25);
            transition: all 0.2s ease-in-out;
            border-radius: 10px;
            padding: 9px 18px;
            font-size: 13.5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-custom-primary:hover {
            background-color: var(--primary-hover);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(190, 24, 93, 0.35);
        }

        .btn-custom-secondary {
            background-color: var(--primary-light);
            color: var(--primary);
            font-weight: 700;
            border: 1.5px solid var(--border-ui);
            border-radius: 10px;
            padding: 9px 16px;
            font-size: 13.5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .btn-custom-secondary:hover {
            background-color: var(--border-ui);
            color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-custom-mutation {
            background-color: #059669;
            color: #ffffff;
            font-weight: 700;
            border: none;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
            border-radius: 10px;
            padding: 9px 16px;
            font-size: 13.5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .btn-custom-mutation:hover {
            background-color: #047857;
            color: #ffffff;
            transform: translateY(-2px);
        }

        .btn-custom-logout {
            background-color: #fee2e2;
            color: #991b1b;
            font-weight: 700;
            border: 1.5px solid #fecaca;
            border-radius: 10px;
            padding: 8px 14px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-custom-logout:hover {
            background-color: #fca5a5;
            color: #7f1d1d;
        }

        /* Alert Styling */
        .alert-custom-success {
            background-color: #f0fdf4;
            color: #166534;
            border: 1.5px solid #86efac;
            border-radius: 12px;
            padding: 13px 18px;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-custom-info {
            background-color: #eff6ff;
            color: #1e40af;
            border: 1.5px solid #bfdbfe;
            border-radius: 12px;
            padding: 13px 18px;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-custom-error {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1.5px solid #fecaca;
            border-radius: 12px;
            padding: 13px 18px;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Stat Cards */
        .stat-card {
            background: #fff8f9;
            border: 1.5px solid var(--border-ui);
            border-radius: 14px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.2s;
        }
        .stat-card:hover {
            background: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(190, 24, 93, 0.08);
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        /* Badges */
        .badge-sku {
            display: inline-block;
            padding: 4px 9px;
            border-radius: 6px;
            font-size: 12px;
            background: #fdf2f8;
            color: #9d174d;
            border: 1.5px solid #fbcfe8;
            font-weight: 800;
            letter-spacing: 0.4px;
        }

        .badge-kategori {
            display: inline-block;
            padding: 4px 11px;
            border-radius: 20px;
            font-size: 12px;
            background: #fce7f3;
            color: #831843;
            border: 1px solid #f472b6;
            font-weight: 700;
        }

        .stok-badge {
            display: inline-block;
            font-weight: 800;
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 13px;
        }
        .stok-aman {
            color: #14532d;
            background: #dcfce7;
            border: 1.5px solid #86efac;
        }
        .stok-menipis {
            color: #7f1d1d;
            background: #fee2e2;
            border: 1.5px solid #fca5a5;
        }

        .harga-text {
            font-weight: 800;
            color: #9d174d;
            font-size: 14.5px;
        }

        /* Action Buttons Grid */
        .action-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
            min-width: 190px;
        }

        .btn-act {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.15s ease-in-out;
            border: 1.5px solid transparent;
            cursor: pointer;
        }

        .btn-act-in {
            background-color: #dcfce7;
            color: var(--btn-in);
            border-color: #86efac;
        }
        .btn-act-in:hover {
            background-color: var(--btn-in);
            color: #ffffff;
        }

        .btn-act-out {
            background-color: #fee2e2;
            color: var(--btn-out);
            border-color: #fca5a5;
        }
        .btn-act-out:hover {
            background-color: var(--btn-out);
            color: #ffffff;
        }

        .btn-act-edit {
            background-color: #ffedd5;
            color: var(--btn-edit);
            border-color: #fdba74;
        }
        .btn-act-edit:hover {
            background-color: var(--btn-edit);
            color: #ffffff;
        }

        .btn-act-del {
            background-color: #ffe4e6;
            color: var(--btn-del);
            border-color: #fda4af;
        }
        .btn-act-del:hover {
            background-color: var(--btn-del);
            color: #ffffff;
        }

        /* Table styling */
        .table-custom-responsive {
            border-radius: 14px;
            border: 2px solid var(--border-ui);
            overflow: hidden;
            background: #ffffff;
        }

        .table-custom-responsive table {
            margin-bottom: 0;
        }

        .table-custom-responsive thead th {
            background-color: #fff1f2;
            color: var(--primary);
            font-weight: 800;
            font-size: 12.5px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 14px 16px;
            border-bottom: 2px solid var(--border-ui);
            vertical-align: middle;
        }

        .table-custom-responsive thead th a {
            text-decoration: none;
            color: inherit;
        }

        .table-custom-responsive tbody td {
            padding: 13px 16px;
            border-bottom: 1px solid var(--border-table);
            font-size: 14px;
            vertical-align: middle;
            color: var(--text-main);
        }

        .table-custom-responsive tbody tr:hover td {
            background-color: #fff8f9;
        }

        /* Form elements */
        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid #fbcfe8;
            padding: 10px 14px;
            font-size: 14px;
            color: var(--text-main);
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(190, 24, 93, 0.15);
        }

        .form-label {
            font-weight: 700;
            color: #4a3b40;
            font-size: 13.5px;
            margin-bottom: 6px;
        }

        /* Footer */
        .app-footer {
            margin-top: 35px;
            text-align: center;
            font-size: 13px;
            color: #9d7b85;
            font-weight: 600;
        }
    </style>
    @yield('styles')
</head>
<body>

    <div class="main-container">
        <!-- Header -->
        <header class="app-header">
            <div>
                <a href="{{ route('products.index') }}" class="brand-title">
                    🌸 Inventori Toko Hijab
                </a>
                <p class="brand-subtitle">Sistem manajemen stok, katalog hijab & mutasi barang masuk/keluar</p>
            </div>
            <div class="nav-actions">
                @auth
                    <div class="user-pill">
                        <i class="bi bi-person-circle text-secondary"></i>
                        <span>{{ Auth::user()->name }}</span>
                    </div>
                @endauth
                <a href="{{ route('products.index') }}" class="btn-custom-secondary">
                    <i class="bi bi-grid"></i> Semua Produk
                </a>
                <a href="{{ route('transactions.create') }}" class="btn-custom-mutation">
                    <i class="bi bi-arrow-left-right"></i> Mutasi Stok
                </a>
                <a href="{{ route('transactions.index') }}" class="btn-custom-secondary">
                    <i class="bi bi-clock-history"></i> Log Mutasi
                </a>
                <a href="{{ route('products.create') }}" class="btn-custom-primary">
                    <i class="bi bi-plus-circle"></i> + Tambah Produk
                </a>
                @auth
                    <form action="{{ route('logout') }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin keluar?')">
                        @csrf
                        <button type="submit" class="btn-custom-logout" title="Keluar">
                            <i class="bi bi-box-arrow-right"></i> Keluar
                        </button>
                    </form>
                @endauth
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert-custom-success">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div class="alert-custom-info">
                <i class="bi bi-info-circle-fill fs-5"></i>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert-custom-error">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <div>
                    <strong>Perhatian:</strong>
                    <ul class="mb-0 ps-3 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

        <footer class="app-footer">
            Sistem Inventori Toko Hijab &copy; {{ date('Y') }}
        </footer>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
