@extends('index')

@section('title')
    Booking - Fairytale Campground
@endsection

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="row justify-content-center gy-4">

            <div class="col-md-4 d-flex justify-content-center">
                <div class="card" style="width: 18rem;">
                    <img src="/img/Untitled design (24).png" class="card-img-top" style="height: 170px; object-fit:cover;"alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Single Tent</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                            card’s content.</p>
                        
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#myModal1">Book tenda</button>
                        
                    </div>
                </div>
            </div>

            <div class="col-md-4 d-flex justify-content-center">
                <div class="card" style="width: 18rem;">
                    <img src="/img/Untitled design (26).png" class="card-img-top" style="height: 170px; object-fit:cover;" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Double Tent</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                            card’s content.</p>
                            
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#myModal2">Book tenda</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4 d-flex justify-content-center">
                <div class="card" style="width: 18rem;">
                    <img src="/img/Untitled design (25).png" class="card-img-top" style="height: 170px; object-fit:cover;" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Family Tent</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                            card’s content.</p>
                            
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#myModal3">Book tenda</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="myModal1" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Single Tent</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Single
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="/pilihan_tenda" class="btn btn-success">Book</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal2" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Double Tent</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Double
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="/pilihan_tenda" class="btn btn-success">Book</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal3" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Family Tent</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Family
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="/pilihan_tenda" class="btn btn-success">Book</a>
                </div>
            </div>
        </div>
    </div>

@endsection