@extends('index')

@section('title')
    Order Summary - FairyTale Campground
@endsection

@section('custom_css')
<style>
    body { background-color: #f4f8f3; }
    .card { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(5px); border: 2px solid #1d4807; }
    .summary-table th, .summary-table td { padding: 0.75rem 1rem; text-align: left; }
    .summary-table th { background-color: #1d4807; color: #fff; }
    .summary-table td { background-color: #eaf3e8; }
    .btn-back, .btn-action { padding: 0.75rem 1.5rem; border-radius: 8px; text-align: center; min-width: 180px; font-weight: 600; }
    .btn-back { background-color: #ddd; text-decoration: none; color: #000; }
    .btn-action { background-color: #1d4807; color: #fff; width: 2px}
    .btn-action:hover { background-color: #225909; }
    .payment-upload { margin-top: 1rem; display: flex; flex-direction: column; gap: 0.5rem; max-width: 300px; }
    .flex-buttons { display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem; }
</style>
@endsection

@section('content')
<div class="container mx-auto py-8 px-4 mt-14">
    <div class="card p-6 rounded-2xl shadow-xl">
        <h1 class="text-3xl font-bold text-center text-[#1d4807] mb-6">Order Summary</h1>

        <p class="mb-4 text-[#1d4807] font-semibold">Tanggal Check-In: {{ $checkin }} | Check-Out: {{ $checkout }}</p>

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
                @php $grandTotal = 0; @endphp
                @foreach($tendaList as $tenda)
                    @php
                        $paket = $paketList[$tenda->paket_id];
                        $subtotal = $paket->harga * $selisih;
                        $grandTotal += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ ucfirst($paket->nama_paket) }}</td>
                        <td>{{ $tenda->nomor_tent }}</td>
                        <td>Rp {{ number_format($paket->harga,0,',','.') }}</td>
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

        <div class="mb-4">
            <h2 class="font-semibold text-[#1d4807] mb-2">Pembayaran</h2>
            <p>Transfer ke Bank BCA: <strong>123-456-7890 (a/n FairyTale Campground)</strong></p>
            <p>Atau scan QRIS di bawah:</p>
            <img src="/img/qris.jpg" alt="QRIS" class="mt-4 w-60 h-60">
        </div>

        <form method="POST" action="{{ route('booking.summary.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="payment-upload">
                <label class="font-semibold">Upload Bukti Transfer:</label>
                <input type="file" name="bukti_tf" required>
            </div>

            <div class="flex-buttons">
                <a href="{{ route('booking.tent') }}" class="btn-back">← Kembali ke Pilih Tenda</a>
                <button type="submit" class="btn-action mt-28">Konfirmasi Booking</button>
            </div>
        </form>
    </div>
</div>
@endsection
