<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Jadwal Jimpitan RT') - RT 35 Digital</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- PWA Settings -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1e3a8a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="/uploads/icon-192.png">

    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        .navbar-brand {
            letter-spacing: 0.5px;
        }
        .transition-all {
            transition: all 0.25s ease;
        }
    </style>
    @yield('styles')
</head>
<body class="bg-light">

    @php
        $currentRoute = Route::currentRouteName();
        $isAdmin = session('superuser') === true;
    @endphp

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-uppercase d-flex align-items-center" href="{{ route('home') }}">
                <span class="bg-warning text-dark px-2 py-1 rounded me-2 fs-6">RT 35</span>
                <span>Jadwal <span class="text-warning">Digital</span></span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-2 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded {{ $currentRoute == 'home' ? 'active bg-warning text-dark fw-bold' : 'text-white-50' }}" href="{{ route('home') }}">
                            <i class="bi bi-calendar-event me-1"></i> Jadwal Malam Ini
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded {{ $currentRoute == 'jadwal' ? 'active bg-warning text-dark fw-bold' : 'text-white-50' }}" href="{{ route('jadwal') }}">
                            <i class="bi bi-grid-3x3-gap me-1"></i> Jadwal Lengkap
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded {{ $currentRoute == 'laporan.harian' ? 'active bg-warning text-dark fw-bold' : 'text-white-50' }}" href="{{ route('laporan.harian') }}">
                            <i class="bi bi-clipboard-data-fill me-1"></i> Laporan Harian
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded {{ $currentRoute == 'laporan.keuangan' ? 'active bg-warning text-dark fw-bold' : 'text-white-50' }}" href="{{ route('laporan.keuangan') }}">
                            <i class="bi bi-cash-coin me-1"></i> Laporan Keuangan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded {{ $currentRoute == 'admin.warga' ? 'active bg-warning text-dark fw-bold' : 'text-white-50' }}" href="{{ route('admin.warga') }}">
                            <i class="bi bi-people me-1"></i> Data Warga {!! !$isAdmin ? '<i class="bi bi-lock-fill text-warning ms-1" style="font-size: 0.8rem;"></i>' : '' !!}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded {{ $currentRoute == 'admin.jadwal' ? 'active bg-warning text-dark fw-bold' : 'text-white-50' }}" href="{{ route('admin.jadwal') }}">
                            <i class="bi bi-calendar3 me-1"></i> Atur Jadwal {!! !$isAdmin ? '<i class="bi bi-lock-fill text-warning ms-1" style="font-size: 0.8rem;"></i>' : '' !!}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded {{ $currentRoute == 'admin.pengingat' ? 'active bg-warning text-dark fw-bold' : 'text-white-50' }}" href="{{ route('admin.pengingat') }}">
                            <i class="bi bi-bell-fill me-1"></i> Pengingat WA {!! !$isAdmin ? '<i class="bi bi-lock-fill text-warning ms-1" style="font-size: 0.8rem;"></i>' : '' !!}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded {{ $currentRoute == 'admin.pengeluaran' ? 'active bg-warning text-dark fw-bold' : 'text-white-50' }}" href="{{ route('admin.pengeluaran') }}">
                            <i class="bi bi-wallet2 me-1"></i> Kelola Kas {!! !$isAdmin ? '<i class="bi bi-lock-fill text-warning ms-1" style="font-size: 0.8rem;"></i>' : '' !!}
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        @if ($isAdmin)
                            <a class="btn btn-outline-danger btn-sm px-3 rounded-pill fw-bold" href="{{ route('logout') }}">
                                <i class="bi bi-box-arrow-right me-1"></i> Keluar
                            </a>
                        @else
                            <a class="btn btn-outline-warning btn-sm px-3 rounded-pill fw-bold" href="{{ route('login') }}">
                                <i class="bi bi-person-fill-lock me-1"></i> Masuk Admin
                            </a>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- PWA Service Worker Registration -->
    <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then(reg => console.log('PWA Service Worker registered successfully.', reg.scope))
                .catch(err => console.log('PWA Service Worker registration failed.', err));
        });
    }
    </script>
    @yield('scripts')
</body>
</html>
