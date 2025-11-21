<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link rel="stylesheet" href="/styles.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&display=swap" rel="stylesheet">

    <style>
    html, body {
      height: 100%;
      overflow: hidden;
    }

    header {
      z-index: 10;
    }

    .hero-section {
      height: 50vh; 
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      background-color: #f8f9fa;
      text-align: center;
    }

    .hero-section h1 {
      margin-bottom: 0.5rem;
    }

    .hero-section p {
      margin: 0;
      color: #6c757d;
    }

    .image-section {
      height: 100vh; 
      overflow: hidden;
    }

    .image-section img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

  </style>
</head>
  
<body>  
    <header class="warna-navbar">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
            <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
            <use xlink:href="#bootstrap"></use>
            </svg>
        </a>
        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
            <li>
            <a class="nav-link px-2" href="./index/home">Home</a>
            </li>
            <li>
            <a class="nav-link px-2" href="./index/booking">Booking</a>
            </li>
            <li>
            <a class="nav-link px-2" href="./index/contact_us">Contact Us</a>
            </li>
        </ul>

        <div class="text-end">
            <a href="./register" class="btn btn-outline-light me-2">Registrasi</a>
            <a href="./login" class="btn btn-light me-2">Login</a>
        </div>
        </div>
    </div>
    </header>

    @yield('content')

    <script>
        const raw = location.pathname.split("/").pop();
        const currentPath = raw === "" ? "index.html" : raw;

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

    <footer>
        <p class="mt-5 mb-3 text-body-secondary text-center">© Fairytale Campground Gacorrr</p>
    </footer>
</body>