<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title')</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/styles.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <style>
    .hero-full {
      position: relative;
      width: 100%;
      height: 100vh;
      background: url('./img/img hero.jpg') center/cover no-repeat;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      color: #fff;
    }

    .hero-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(to bottom, rgba(0,0,0,0.4), rgba(0,0,0,0.7));
    }

    .hero-content {
      position: relative;
      z-index: 2;
      max-width: 700px;
      padding: 0 20px;
    }

    .hero-title {
      font-size: 3.5rem;
      font-weight: 800;
      text-shadow: 0 3px 10px rgba(0,0,0,0.5);
    }

    .hero-subtitle {
      font-size: 1.2rem;
      margin-top: 10px;
      margin-bottom: 28px;
      color: #e5e5e5;
    }

    .btn-hero {
      padding: 14px 32px;
      font-size: 1.1rem;
      font-weight: 600;
      border-radius: 8px;
      background-color: #1d4807;
      border: none;
      color: white;
      transition: 0.25s ease;
    }

    .btn-hero:hover {
      background-color: #2e6b0c;
      transform: translateY(-2px);
    }

    @media (max-width: 576px) {
      .hero-title {
        font-size: 2rem;
      }
      .hero-subtitle {
        font-size: 1rem;
      }
    }
  </style>

  @yield('custom_css')
</head>
  
<body>  
    <header class="navbar-custom">
        <div class="navbar-container">

              <!-- Logo -->
            <a href="/" class="navbar-logo">Fairytale Campground</a>

            <!-- Menu Navigasi -->
            <nav>
               <ul class="navbar-menu">
                  <li><a href="/">Home</a></li>
                  <li><a href="/booking/date">Booking</a></li>
                  <li><a href="/booking/history">My Orders</a></li>
                  <li><a href="/contact_us">Contact Us</a></li>
                </ul>
            </nav>

              <!-- Button Auth -->
            <div class="navbar-end gap-2 navbar-auth">
                <div class="navbar-auth">
                @auth
                    <span class="btn-solid">{{ auth()->user()->nama }}</span>
                    <form method="POST" action="/test-logout" class="inline">
                        @csrf
                        <button type="submit" class="btn-solid">Logout</button>
                    </form>
                @else
                    <a href="/test-login" class="btn-solid">Sign In</a>
                    <a href="{{ route('test-register') }}" class="btn-outline">Sign Up</a>
                @endauth
            </div>

        </div>
    </header>

    @yield('content')

    <script>
        const raw = location.pathname.split("/").pop();
        const currentPath = raw === "" ? "index" : raw;

        document.querySelectorAll(".nav-link").forEach(link => {
            const href = link.getAttribute("href");
            const linkFile = href.split("/").pop().replace(/^\.\/+/, "");

            if (linkFile === currentPath) {
            link.classList.add("active");
            } else {
            link.classList.remove("active");
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <footer class="footer">
    <div class="container py-5">
        <div class="row gy-4">

            <div class="col-md-4">
                <h5 class="footer-title">Fairytale Campground</h5>
                <p class="footer-text">
                    Fairytale Campground adalah destinasi wisata alam yang
                    menyediakan pengalaman camping nyaman, aman, dan berkesan
                    untuk keluarga, pasangan, maupun individu.
                </p>
            </div>

            <div class="col-md-4">
                <h6 class="footer-subtitle">Navigasi</h6>
                <ul class="footer-link list-unstyled">
                    <li><a href="/">Home</a></li>
                    <li><a href="/booking/date">Booking</a></li>
                    <li><a href="/booking/history">My Orders</a></li>
                    <li><a href="/contact_us">Contact Us</a></li>
                </ul>
            </div>

            <div class="col-md-4">
                <h6 class="footer-subtitle">Kontak</h6>
                <p class="footer-text mb-2">
                    <i class="bi bi-geo-alt-fill me-2"></i>
                    Bogor, Jawa Barat
                </p>
                <p class="footer-text mb-3">
                    <i class="bi bi-envelope-fill me-2"></i>
                    info@fairytalecampground.com
                </p>

                <div class="footer-social">
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-twitter-x"></i></a>
                </div>
            </div>

        </div>
    </div>

    <div class="footer-bottom text-center py-3">
        © {{ date('Y') }} Fairytale Campground. All rights reserved.
    </div>
</footer>

</body>