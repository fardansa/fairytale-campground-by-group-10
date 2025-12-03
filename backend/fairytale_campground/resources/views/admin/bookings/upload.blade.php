@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h3>Upload Bukti Pembayaran</h3>
    <p>Booking ID: {{ $booking->pemesanan_id }}</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('booking.upload.store', $booking->pemesanan_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label class="form-label">Pilih Bukti Transfer</label>
        <input type="file" name="bukti_transfer" class="form-control" required>
        <button class="btn btn-primary mt-3" type="submit">Upload</button>
    </form>
</div>
@endsection
