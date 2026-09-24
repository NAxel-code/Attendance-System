<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Absensi') — PT Perusahaan</title>
    {{-- Bootstrap 5 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar {
            width: 250px; min-height: 100vh;
            background: linear-gradient(180deg, #1a237e 0%, #283593 100%);
            position: fixed; top: 0; left: 0; z-index: 100;
        }
        .sidebar .nav-link { color: rgba(255,255,255,.75); border-radius: 8px; margin: 2px 8px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,.15); }
        .sidebar .nav-link i { width: 20px; }
        .main-content { margin-left: 250px; padding: 24px; }
        .navbar-top {
            background: #fff; border-bottom: 1px solid #e0e0e0;
            padding: 12px 24px; margin-left: 250px;
            position: sticky; top: 0; z-index: 99;
        }
        .card { border: none; box-shadow: 0 1px 3px rgba(0,0,0,.08); border-radius: 12px; }
        .stat-card { border-left: 4px solid; }
        .badge-present  { background: #e8f5e9; color: #2e7d32; }
        .badge-late     { background: #fff8e1; color: #f57f17; }
        .badge-absent   { background: #ffebee; color: #c62828; }
        .badge-leave    { background: #e3f2fd; color: #1565c0; }
    </style>
    @stack('styles')
</head>
<body>

{{-- Sidebar --}}
<nav class="sidebar d-flex flex-column py-3">
    <div class="text-center mb-4 px-3">
        <i class="bi bi-building text-white" style="font-size:2rem;"></i>
        <div class="text-white fw-bold mt-1" style="font-size:.85rem;">Sistem Absensi</div>
    </div>

    <ul class="nav flex-column gap-1 flex-grow-1">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('attendances.index') }}" class="nav-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check me-2"></i> Absensi
            </a>
        </li>

        @if(auth()->user()->isAdmin())
        <li class="mt-2 px-3" style="font-size:.7rem;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.05em;">Admin</li>
        <li class="nav-item">
            <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i> Pegawai
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('departments.index') }}" class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                <i class="bi bi-diagram-3 me-2"></i> Departemen
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('reports.monthly') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart me-2"></i> Laporan
            </a>
        </li>
        @endif
    </ul>

    <div class="px-3 pb-2">
        <div class="text-white-50" style="font-size:.75rem;">{{ auth()->user()->name }}</div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-sm btn-outline-light mt-1 w-100">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </button>
        </form>
    </div>
</nav>

{{-- Topbar --}}
<div class="navbar-top d-flex align-items-center justify-content-between">
    <h6 class="mb-0 fw-semibold text-dark">@yield('page-title', 'Dashboard')</h6>
    <div class="text-muted" style="font-size:.85rem;">
        <i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('l, d F Y') }}
    </div>
</div>

{{-- Konten Utama --}}
<main class="main-content">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
