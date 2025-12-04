@extends('index')

@section('title')
    Sign In
@endsection
    
@section('content')
    <div class="d-flex align-items-center justify-content-center" style="min-height: 85vh;">
        <main class="form-signin w-100" style="max-width: 380px;">            
            <div class="card-body">
                    <h1 class="text-3xl font-bold text-center mb-6">Login</h1>

                    <form method="POST" action="/test-login">
                        @csrf

                        <!-- Email -->
                        <div class="form-floating mb-6">
                            <input type="email"
                            name="email"
                            placeholder="mail@example.com"
                            value="{{ old('email') }}"
                            class="form-control input @error('email') input-error @enderror"
                            required
                            autofocus id="email">
                            <label for="email">Email</label>
                        </div>
                        @error('email')
                            <div class="label -mt-4 mb-2">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </div>
                        @enderror

                        <!-- Password -->
                        <div class="form-floating mb-6">
                            <input type="password"
                            name="password"
                            placeholder="xxxxxxxx"
                            class="form-control input input-bordered @error('password') input-error @enderror"
                            required>
                            <label for="password">Password</label>
                        </label>
                        @error('password')
                            <div class="label -mt-4 mb-2">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </div>
                        @enderror

                        <!-- Remember Me -->
                        <div class="mt-2">
                            <label class="label cursor-pointer justify-start">
                                <input type="checkbox"
                                    name="remember"
                                    class="checkbox">
                                <span class="label-text ml-2">Remember me</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-4">
                            <button type="submit" class="w-100 btn btn-lg btn-success">
                                Sign In
                            </button>
                        </div>
                    </form>

                    <div class="divider text-center mt-4">OR</div>
                    <p class="text-center text-sm mt-10">
                        Don't have an account?
                        <a href="/test-register" class="link link-primary">Register</a>
                    </p>
                </div>

        </main>
    </div>

@endsection
