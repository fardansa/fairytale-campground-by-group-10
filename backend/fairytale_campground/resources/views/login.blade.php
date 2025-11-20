<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fairytale Campground</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link rel="stylesheet" href="./styles.css">
</head>

<body>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fairytale Campground</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link rel="stylesheet" href="./styles.css">
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
                        <a class="nav-link px-2" href="./index.html">Home</a>
                    </li>
                    <li>
                        <a class="nav-link px-2" href="./booking.html">Booking</a>
                    </li>
                    <li>
                        <a class="nav-link px-2" href="./contact_us.html">Contact Us</a>
                    </li>
                </ul>

                <div class="text-end">
                    <a href="./registrasi.html" class="btn btn-outline-light me-2">Registrasi</a>
                    <a href="./login.html" class="btn btn-light me-2">Login</a>
                </div>
            </div>
        </div>
    </header>

    <div class="d-flex align-items-center justify-content-center" style="min-height: 85vh;">
        <main class="form-signin w-100" style="max-width: 380px;">
            <form>

                <h1 class="h3 mb-3 fw-normal text-center">Login</h1>

                <div class="form-floating mb-2">
                    <input type="text" class="form-control" id="inputName" placeholder="Nama Anda">
                    <label for="inputName">Nama Anda</label>
                </div>

                <div class="form-floating mb-2">
                    <input type="email" class="form-control" id="inputEmail" placeholder="name@example.com">
                    <label for="inputEmail">Email address</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="inputPassword" placeholder="Password">
                    <label for="inputPassword">Password</label>
                </div>

                <div class="form-check text-start mb-3">
                    <input class="form-check-input" type="checkbox" value="remember-me" id="checkDefault">
                    <label class="form-check-label" for="checkDefault">Remember me</label>
                </div>

                <a href="./berhasil_login.html" class="w-100 btn btn-lg btn-success">
                    Login
                </a>


                <p class="mt-5 mb-3 text-body-secondary text-center">© Fairytale Campground Gacorrr</p>
            </form>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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

</body>

</html>