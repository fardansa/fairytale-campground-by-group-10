<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title','Admin') - Fairytale Campground</title>

  @yield('custom_css')

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    /* Navbar */
    .navbar-custom {
        width: 100%;
        background-color: #1d4807;
        color: white;
        position: fixed;
        top: 0;
        left: 0;
        padding: 14px 0;
        z-index: 999;
        box-shadow: 0px 2px 8px rgba(0,0,0,0.2);
    }
    .navbar-container {
        margin:auto;
        padding:0 24px;
        display:flex;
        justify-content:space-between;
        align-items:center;
    }
    .navbar-logo {
        font-size:26px;
        font-weight:700;
        color:white;
        text-decoration:none;
    }
    .navbar-menu {
        list-style:none;
        display:flex;
        gap:32px;
        margin:0;
        padding:0;
    }
    .navbar-menu a {
        color:white;
        text-decoration:none;
        font-size:18px;
        font-weight:500;
        transition:0.2s;
    }
    .navbar-menu a:hover { color:#bbf7d0; }

    /* Navbar Auth Buttons */
    .navbar-auth {
        display:flex;
        gap:12px;
        align-items:center;
    }
    .navbar-auth .btn-outline,
    .navbar-auth .btn-solid {
        padding: 8px 16px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 10px;
        text-decoration: none;
        transition: 0.2s;
    }
    .navbar-auth .btn-outline {
        border: 2px solid white;
        background: transparent;
        color: white;
    }
    .navbar-auth .btn-outline:hover {
        background-color: white;
        color: #1d4807;
    }
    .navbar-auth .btn-solid {
        border: none;
        background-color: white;
        color: #1d4807;
    }
    .navbar-auth .btn-solid:hover {
        background-color: #bbf7d0;
        color: #1d4807;
    }

    /* Layout */
    body { padding-top:68px; background:#f7faf0; }
    .sidebar {
        background:#163e05;
        color:white;
        min-height:calc(100vh - 68px);
        position:fixed;
        top:68px;
        left:0;
        width:220px;
        padding:18px 12px;
    }
    .sidebar .nav-link { color: rgba(255,255,255,0.9); }
    .sidebar .nav-link.active {
        background:#bbf7d0;
        color:#1d4807 !important;
        border-radius:8px;
        font-weight:600;
    }
    .main { margin-left:240px; padding:24px; }
    .card { border-radius:10px; }
    .table thead { background:#e6f2df; }
    form.m-0 { margin:0; }
  </style>
  @stack('styles')
</head>
<body>
  {{-- Navbar --}}
  <header class="navbar-custom">
    <div class="navbar-container">
      <a class="navbar-logo" href="{{ route('admin.dashboard') }}">Fairytale Campground</a>

      <nav>
        <ul class="navbar-menu d-none d-md-flex">
          <li><a href="{{ url('/') }}" target="_blank">Public Site</a></li>
          <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
        </ul>
      </nav>

      {{-- Navbar Auth --}}
      <div class="navbar-auth">
        @auth
            <span class="text-white d-none d-md-inline">Hi, {{ auth()->user()->nama ?? auth()->user()->name }}</span>

            <form method="POST" action="{{ route('test-logout') }}" class="m-0">
                @csrf
                <button type="submit" class="btn-solid">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn-solid">Sign In</a>
            <a href="{{ route('register') }}" class="btn-outline">Sign Up</a>
        @endauth
      </div>
    </div>
  </header>

  {{-- Sidebar --}}
  <aside class="sidebar">
    <ul class="nav flex-column">
      <li class="nav-item mb-2">
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>
      </li>
      <li class="nav-item mb-2">
        <a href="{{ route('admin.paket.index') }}" class="nav-link {{ request()->routeIs('admin.paket.*') ? 'active' : '' }}">
          <i class="bi bi-box-seam me-2"></i> Paket
        </a>
      </li>
      <li class="nav-item mb-2">
        <a href="{{ route('admin.tent.index') }}" class="nav-link {{ request()->routeIs('admin.tent.*') ? 'active' : '' }}">
          <i class="bi bi-archive me-2"></i> Tents
        </a>
      </li>
      <li class="nav-item mb-2">
        <a href="{{ route('admin.bookings.index') }}" class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
          <i class="bi bi-journal-check me-2"></i> Bookings
        </a>
      </li>
    </ul>
  </aside>

  {{-- Main Content --}}
  <main class="main">
    <div class="container-fluid">
      @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      @yield('content')
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
