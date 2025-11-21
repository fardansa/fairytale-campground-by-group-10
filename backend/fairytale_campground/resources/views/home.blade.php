@extends('index')

@section('title')
    Home - Fairytale Campground
@endsection

@section('content')
    <section class="hero-section">
      <h1 class="display-4 fw-bold">Fairytale Campground</h1>
      <p class="fw-normal">Rasakan pengalaman camping yang hangat dan tak terlupakan.</p>
    </section>
  
  
    <section class="image-section">
      <img src="{{ url('/img/Untitled design (30).png') }}" alt="Campground Image">
    </section>
@endsection