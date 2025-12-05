@extends('index')

@section('title')
    Booking Complete - FairyTale Campground
@endsection

@section('custom_css')
<style>
    body { background-color: #f4f8f3; }
    .card { 
        background: rgba(255, 255, 255, 0.05); 
        backdrop-filter: blur(5px); 
        border: 2px solid #1d4807; 
        padding: 2rem; 
        text-align: center; 
        border-radius: 2rem; 
        max-width: 800px; 
        margin: 3rem auto;
    }
    .summary-table th, .summary-table td { padding: 0.75rem 1rem; text-align: left; }
    .summary-table th { background-color: #1d4807; color: #fff; }
    .summary-table td { background-color: #eaf3e8; }
    .btn-action { 
        background-color: #1d4807; 
        color: #fff; 
        padding: 0.75rem 1.5rem; 
        border-radius: 8px; 
        font-weight: 600; 
        text-decoration: none; 
        display: inline-block; 
        min-width: 180px;
    }
    .btn-action:hover { background-color: #225909; }
    .status { font-weight: bold; margin-top: 1rem; font-size: 1.1rem; color: #225909; }
    p { margin-bottom: 0.5rem; color: #1d4807; }
</style>
@endsection

@section('content')
<div class="card shadow-xl mt-24">
    <h1 class="text-3xl font-bold text-[#1d4807] mb-4">Booking Berhasil!</h1>
    <p class="mb-2">Nomor Pemesanan: <strong>{{ session('last_booking_id') ?? '-' }}</strong></p>
    <p class="mb-2">Status Pemesanan: <span class="status">Menunggu Konfirmasi</span></p>
    <p class="mb-4">Tanggal Check-In: {{ session('checkin') }} | Check-Out: {{ session('checkout') }}</p>

    <table class="summary-table w-full mb-6 border-collapse border border-gray-300">
        <thead>
            <tr>
                <th>Paket</th>
                <th>Tenda</th>
                <th>Harga / Malam</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $tendaList = session('complete_tendaList', []);
                $paketList = session('complete_paketList', []);
                $checkin = session('checkin');
                $checkout = session('checkout');
                $selisih = (strtotime($checkout) - strtotime($checkin)) / 86400;
                $grandTotal = 0;
            @endphp

            @foreach($tendaList as $tenda)
                @php
                    $paket = $paketList[$tenda['paket_id']];
                    $subtotal = $paket['harga'] * $selisih;
                    $grandTotal += $subtotal;
                @endphp
                <tr>
                    <td>{{ ucfirst($paket['nama_paket']) }}</td>
                    <td>{{ $tenda['nomor_tent'] }}</td>
                    <td>Rp {{ number_format($paket['harga'],0,',','.') }}</td>
                    <td>Rp {{ number_format($subtotal,0,',','.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">Total</th>
                <th>Rp {{ number_format($grandTotal,0,',','.') }}</th>
            </tr>
        </tfoot>
    </table>

    <p class="mb-2">Silakan tunggu konfirmasi dari admin. Setelah dikonfirmasi, Anda akan mendapatkan email atau notifikasi status pembayaran.</p>

    <a href="{{ route('home') }}" class="btn-action mt-4">← Kembali ke Beranda</a>
</div>
@endsection
