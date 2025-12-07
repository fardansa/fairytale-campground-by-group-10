@extends('index')

@section('title', 'History Booking')

@section('custom_css')
<style>
    body { background-color: #f4f8f3; }
    h3 {
        color: #1d4807;
        font-weight: 700;
    }
    .card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(5px);
        border: 2px solid #1d4807;
        border-radius: 1.5rem;
        padding: 1.5rem;
    }
    .table thead th {
        background-color: #1d4807;
        color: #fff !important;
        text-align: left;
    }
    .table tbody td {
        background-color: #eaf3e8 !important;
        color: #1d4807;
    }
    .status-text {
        color: #225909;
        font-weight: bold;
    }
    .btn-detail {
        background-color: #1d4807;
        color: #fff;
        border-radius: 8px;
        padding: 0.5rem 1.2rem;
        font-weight: 600;
        text-decoration: none;
    }
    .btn-detail:hover {
        background-color: #225909;
    }
    .alert-warning {
        background-color: #fff4e5;
        color: #b85c00;
        border-radius: 8px;
    }
    .no-history {
        text-align: center;
        padding: 3rem;
        border: 2px solid #1d4807;
        border-radius: 1.5rem;
        background-color: #ffffff80;
        backdrop-filter: blur(4px);
        font-size: 1.1rem;
        color: #1d4807;
        font-weight: 500;
    }
</style>
@endsection

@section('content')
<div class="container mt-24 mb-5">

    <h3 class="mb-4">History Booking Anda</h3>

    @guest
        <div class="alert alert-warning">
            Anda belum login! Silakan login untuk melihat history booking Anda.
        </div>
        @return
    @endguest

    @if(isset($history) && count($history) > 0)
        @foreach($history as $order)
            <div class="card shadow-lg mb-4">
                <h5 class="mb-3 text-[#1d4807] font-bold">Pemesanan #{{ $order->pemesanan_id }}</h5>

                <p><strong>Check-in:</strong> {{ $order->tanggal_checkin }}</p>
                <p><strong>Check-out:</strong> {{ $order->tanggal_checkout }}</p>
                <p><strong>Total:</strong> Rp{{ number_format($order->total_harga, 0, ',', '.') }}</p>

                <p><strong>Status Pemesanan:</strong> 
                    <span class="status-text">{{ ucfirst($order->status_pemesanan) }}</span>
                </p>

                <h6 class="mt-3 text-[#1d4807] font-semibold">Detail Tenda</h6>

                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Tenda</th>
                            <th>Harga/Malam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->detailPemesanan as $detail)
                            <tr>
                                <td>{{ $detail->tenda->nomor_tent ?? '-' }}</td>
                                <td>Rp{{ number_format($detail->harga_per_malam, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <a href="{{ route('booking.complete', $order->pemesanan_id) }}" class="btn-detail mt-2 inline-block">
                    Lihat Detail
                </a>
            </div>
        @endforeach
    @else
        <div class="no-history shadow-xl">
            Belum ada data history booking.
        </div>
    @endif

</div>
@endsection
