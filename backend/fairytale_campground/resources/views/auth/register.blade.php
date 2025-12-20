@extends('index')

@section('title')
    Register - FairyTale Campground
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-center" style="min-height: 85vh;">
    <main class="form-signin w-100" style="max-width: 380px;">
        <div class="card-body">
            <h1 class="text-3xl font-bold text-center mb-6 mt-24">Create Account</h1>

            <form method="POST" action="/test-register">
                @csrf


                <div class="form-floating mb-4">
                    <input type="text"
                        name="nama"
                        id="nama"
                        placeholder="John Doe"
                        value="{{ old('nama') }}"
                        class="form-control @error('nama') is-invalid @enderror"
                        required>
                    <label for="nama">Name</label>
                </div>
                @error('nama')
                    <div class="mb-3">
                        <span class="text-danger small">{{ $message }}</span>
                    </div>
                @enderror


                <div class="form-floating mb-4">
                    <input type="email"
                        name="email"
                        id="email"
                        placeholder="mail@example.com"
                        value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror"
                        required>
                    <label for="email">Email</label>
                </div>
                @error('email')
                    <div class="mb-3">
                        <span class="text-danger small">{{ $message }}</span>
                    </div>
                @enderror

               
                <div class="form-floating mb-4">
                    <input type="password"
                        name="password"
                        id="password"
                        placeholder="********"
                        class="form-control @error('password') is-invalid @enderror"
                        required>
                    <label for="password">Password</label>
                </div>
                @error('password')
                    <div class="mb-3">
                        <span class="text-danger small">{{ $message }}</span>
                    </div>
                @enderror

          
                <div class="form-floating mb-4">
                    <input type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        placeholder="********"
                        class="form-control"
                        required>
                    <label for="password_confirmation">Confirm Password</label>
                </div>

            
                <div class="mt-4">
                    <button type="submit" class="w-100 btn btn-lg btn-success">
                        Register
                    </button>
                </div>
            </form>

            <div class="divider text-center mt-4">OR</div>
            <p class="text-center text-sm mt-10 mb-6">
                Already have an account?
                <a href="/login" class="link link-primary">Sign in</a>
            </p>
        </div>
    </main>
</div>
@endsection
