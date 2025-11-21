@extends('index')

@section('content')
    <div class="d-flex align-items-center justify-content-center" style="min-height: 85vh;">
        <main class="form-signin w-100" style="max-width: 380px;">
            <h1 class="h3 mb-3 fw-normal text-center">Login</h1>
            <form method="post" id="loginForm">
		        <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">

                <div class="form-floating mb-2">
                    <input type="email" class="form-control" id="inputEmail" placeholder="name@example.com" name="email"> <br/>
                    <label for="inputEmail">Email address</label>
                </div>
                    
                <div class="form-floating mb-3">
                    <input type="text" name="password" type="password" class="form-control" id="inputPassword" placeholder="Password"> <br/>
                    <label for="inputPassword">Password</label>
                </div>
		        <input type="submit" value="Login" class="w-100 btn btn-lg btn-success">
	        </form>

        </main>
    </div>

    
    <script>
        document.getElementById("loginForm").addEventListener("submit", async (e) => {
        e.preventDefault();

        const res = await fetch("/api/login", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({
                email: document.querySelector("#inputEmail").value,
                password: document.querySelector("#inputPassword").value
            })
        });

        const data = await res.json();

        if (data.message === "Login berhasil") {
            window.location.href = "/login_success";
        } else {
            alert("Login gagal!");
        }
    });
    </script>
@endsection