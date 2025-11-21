@extends('index')

@section('content')
      <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <form class="row g-3 shadow p-4 rounded bg-white" style="width: 600px;" id="registerForm">
    
            <div class="col-md-6">
                <label for="inputNama" class="form-label">Nama</label>
                <input type="text" class="form-control" id="inputNama" placeholder="Masukkan nama lengkap" name="nama">
            </div>

            <div class="col-md-6">
                <label for="inputEmail4" class="form-label">Email</label>
                <input type="email" class="form-control" id="inputEmail4" placeholder="email@gmail.com" name="email">
            </div>

            <div class="col-12">
                <label for="inputPassword4" class="form-label">Password</label>
                <input type="password" class="form-control" id="inputPassword4" placeholder="Buat password" name="password">
            </div>

            <div class="col-12">
                <label for="inputConfirmPassword" class="form-label">Konfirmasi Password</label>
                <input type="password" class="form-control" id="inputConfirmPassword" placeholder="Ulangi password" name="password_confirmation">
            </div>
    
            <div class="col-12 text-center">
                 <input type="submit" value="Daftarkan Saya" class="w-100 btn btn-lg btn-success">
            </div>
        </form>
    </div>

    <script>
        document.getElementById("registerForm").addEventListener("submit", async (e) => {
        e.preventDefault();

        const res = await fetch("/api/register", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({
                nama: document.querySelector("#inputNama").value,
                email: document.querySelector("#inputEmail4").value,
                password: document.querySelector("#inputPassword4").value,
                password_confirmation: document.querySelector("#inputConfirmPassword").value
            })
        });

        const data = await res.json();

        if (data.message === "Registrasi berhasil") {
            window.location.href = "/register_success";
        } else {
            alert(data.message, "Registrasi gagal!");
        }
    });
    </script>
@endsection