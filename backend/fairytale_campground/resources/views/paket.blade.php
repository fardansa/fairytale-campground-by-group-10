@extends('index')

@section('title')
    Package - FairyTale Campground
@endsection

@section('content')
<a href="javascript:history.back()" class="btn-back mt-24 mb-4">← Kembali</a>

<div class="container py-4">
    <div class="row justify-content-center gy-5">

        @foreach($paketList as $paket)
            @php
                if ($paket->nama_paket === 'single') {
                    $images = [
                        asset('img/tenda1.png'),
                        asset('img/s2.jpg'),
                        asset('img/s3.jpg'),
                    ];
                } elseif ($paket->nama_paket === 'double') {
                    $images = [
                        asset('img/tenda2.png'),
                        asset('img/d2.png'),
                        asset('img/d3.jpg'),
                    ];
                } else { // family
                    $images = [
                        asset('img/tenda3.png'),
                        asset('img/f2.png'),
                        asset('img/f3.jpg'),
                    ];
                }
            @endphp


            <div class="col-md-4 d-flex justify-content-center">
                <div class="card shadow-sm border-0 rounded-3 w-100">

                    {{-- Carousel --}}
                    <div id="carousel{{ $paket->paket_id }}" class="carousel slide rounded-top" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($images as $key => $img)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    <img src="{{ $img }}" class="d-block w-100" style="height: 200px; object-fit: cover;" alt="{{ $paket->nama_paket }} photo {{ $key+1 }}">
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carousel{{ $paket->paket_id }}" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon bg-dark rounded-circle p-2"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carousel{{ $paket->paket_id }}" data-bs-slide="next">
                            <span class="carousel-control-next-icon bg-dark rounded-circle p-2"></span>
                        </button>
                    </div>

                    {{-- Card Body --}}
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold mb-3 text-center">{{ $paket->nama_paket }}</h5>
                        <p class="card-text text-muted mb-3" style="white-space: pre-line;">{{ $paket->deskripsi }}</p>
                        <button type="button" class="btn btn-success mt-auto" data-bs-toggle="modal" data-bs-target="#modal{{ $paket->paket_id }}">
                            Lihat Detail
                        </button>
                    </div>
                </div>
            </div>

            {{-- Modal --}}
            <div class="modal fade" id="modal{{ $paket->paket_id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" style="margin-top: 20px; margin-bottom: 20px;">
                    <div class="modal-content rounded-4 shadow" style="max-height: calc(100vh - 40px);">
                        <div class="modal-header border-0">
                            <h5 class="modal-title fw-bold">{{ $paket->nama_paket }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" style="overflow-y: auto; max-height: 70vh;">

                            {{-- Carousel --}}
                            <div id="carouselModal{{ $paket->paket_id }}" class="carousel slide mb-4 rounded" data-bs-ride="carousel">
                                <div class="carousel-inner rounded">
                                    @foreach($images as $key => $img)
                                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                            <img src="{{ $img }}" class="d-block w-100 rounded" style="height: 300px; object-fit: cover;" alt="{{ $paket->nama_paket }} photo {{ $key+1 }}">
                                        </div>
                                    @endforeach
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselModal{{ $paket->paket_id }}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon bg-dark rounded-circle p-2"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselModal{{ $paket->paket_id }}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon bg-dark rounded-circle p-2"></span>
                                </button>
                            </div>

                            {{-- Detail Paket --}}
                            <ul class="list-group list-group-flush mb-3">
                                <li class="list-group-item"><strong>Deskripsi:</strong><br>{{ $paket->deskripsi }}</li>
                                <li class="list-group-item"><strong>Fasilitas:</strong><br>{{ $paket->fasilitas }}</li>
                                <li class="list-group-item"><strong>Kapasitas:</strong> {{ $paket->kapasitas }} orang</li>
                                <li class="list-group-item"><strong>Harga:</strong> Rp {{ number_format($paket->harga,0,',','.') }}</li>
                            </ul>

                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <a href="{{ route('pilih_tenda') }}" class="btn btn-success">pilih tent</a>
                        </div>
                    </div>
                </div>
            </div>

        @endforeach

    </div>
</div>
@endsection
