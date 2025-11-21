@extends('index')

@section('title')
    Success - FairyTale Campground
@endsection

@section('content')
    <section id="titledog" class="gradient-background d-flex" style="height: 100vh;">
        <div class="left-middle-wrapper">
            <h1>
                @yield('heading')
            </h1>

            <a href="/" class="btn btn-light btn-lg big-booking-btn shadow-sm p-3 mb-5">
                @yield('button')    
            </a>
        </div>
    </section>
@endsection