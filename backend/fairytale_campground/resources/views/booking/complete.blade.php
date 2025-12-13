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
    .summary-table th, .summary-table td {
        padding: 0.75rem 1rem;
        text-align: left;
    }
    .summary-table th {
        background-color: #1d4807;
        color: #fff;
    }
    .summary-table td {
        background-color: #eaf3e8;
    }
    .btn-action {
        background-color: #1d4807;
        color: #fff;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        min-width: 180px;
        margin: 0.3rem 0;
    }
    .btn-action:hover { background-color: #225909; }
    .status {
        font-weight: bold;
        margin-top: 1rem;
        font-size: 1.1rem;
        color: #225909;
    }
    p { margin-bottom: 0.5rem; color: #1d4807; }
</style>
@endsection

@section('content')

@if (!isset($order))
<div class="card shadow-xl mt-24">
    <h2 class="text-xl font-semibold text-red-600">
        Tidak ada data booking untuk ditampilkan.
    </h2>
    <p class="mt-2 text-[#1d4807]">
        Silakan lakukan pemesanan terlebih dahulu.
    </p>
    <a href="{{ route('booking.date') }}" class="btn-action mt-4">
        Mulai Booking
    </a>
</div>
@else

<div class="card shadow-xl mt-24">
    <h1 class="text-3xl font-bold text-[#1d4807] mb-4">
        Booking Berhasil!
    </h1>

    <p class="mb-2">
        Nomor Pemesanan:
        <strong>{{ $order->pemesanan_id }}</strong>
    </p>

    <p class="mb-2">
        Status Pemesanan:
        <span class="status">
            {{ ucfirst(str_replace('_',' ', $order->status_pemesanan)) }}
        </span>
    </p>

    <p class="mb-4">
        Tanggal Check-In:
        {{ $order->tanggal_checkin }}
        |
        Check-Out:
        {{ $order->tanggal_checkout }}
    </p>

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
            @foreach ($order->detailPemesanan as $detail)
                <tr>
                    <td>
                        {{ ucfirst($detail->tenda->paket->nama_paket ?? '-') }}
                    </td>
                    <td>
                        {{ $detail->tenda->nomor_tent }}
                    </td>
                    <td>
                        Rp {{ number_format($detail->harga_per_malam,0,',','.') }}
                    </td>
                    <td>
                        Rp {{ number_format($detail->subtotal,0,',','.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">Total</th>
                <th>
                    Rp {{ number_format($order->total_harga,0,',','.') }}
                </th>
            </tr>
        </tfoot>
    </table>

    <p class="mb-2">
        Silakan tunggu konfirmasi dari admin.
    </p>

    <a href="{{ route('home') }}" class="btn-action">
        ← Kembali ke Beranda
    </a>

    <a href="{{ route('booking.history') }}" class="btn-action">
        Lihat Semua Pesanan Saya
    </a>
</div>

@endif

@endsection
