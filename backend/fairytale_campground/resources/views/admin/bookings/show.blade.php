@extends('layouts.admin')

@section('content')
<h3 class="mb-4">Detail Booking</h3>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-success text-white fw-bold">Info Pemesan</div>
    <div class="card-body">
        <p><strong>Nama:</strong> {{ $booking->user?->nama ?? 'User Tidak Ditemukan' }}</p>
        <p><strong>Email:</strong> {{ $booking->user?->email ?? '-' }}</p>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-success text-white fw-bold">Detail Booking</div>
    <div class="card-body">
        <p><strong>Tanggal Check-in:</strong> {{ $booking->tanggal_checkin }}</p>
        <p><strong>Tanggal Check-out:</strong> {{ $booking->tanggal_checkout }}</p>
        <p><strong>Status Booking:</strong>
            <span class="badge 
                @if($booking->status_pemesanan=='telah_dibayar') bg-success
                @elseif($booking->status_pemesanan=='menunggu_konfirmasi') bg-warning text-dark
                @elseif($booking->status_pemesanan=='dibatalkan') bg-danger
                @else bg-secondary @endif">
            {{ $booking->status_pemesanan }}</span>
        </p>
        <p><strong>Total Harga:</strong> Rp {{ number_format($booking->total_harga,0,',','.') }}</p>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-success text-white fw-bold">Detail Tenda</div>
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-success">
                <tr>
                    <th>Paket</th>
                    <th>No. Tenda</th>
                    <th>Harga / Malam</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($booking->detailPemesanan as $item)
                <tr>
                    <td>{{ $item->tenda->paket->nama_paket ?? '-' }}</td>
                    <td>{{ $item->tenda->nomor_tent ?? '-' }}</td>
                    <td>Rp {{ number_format($item->harga_per_malam,0,',','.') }}</td>
                    <td>Rp {{ number_format($item->subtotal,0,',','.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">Tidak ada detail tenda.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-success text-white fw-bold">Bukti Pembayaran</div>
    <div class="card-body">
        @if($booking->pembayaran)
            <p><strong>Metode:</strong> {{ $booking->pembayaran->metode_pembayaran }}</p>
            <p><strong>Status:</strong>
                <span class="badge 
                    @if($booking->pembayaran->status_pembayaran=='diterima') bg-success
                    @elseif($booking->pembayaran->status_pembayaran=='ditolak') bg-danger
                    @else bg-warning text-dark @endif">
                {{ $booking->pembayaran->status_pembayaran }}</span>
            </p>
            @if($booking->pembayaran->bukti_transfer)
                <img src="{{ asset('storage/' . $booking->pembayaran->bukti_transfer) }}" class="img-fluid rounded" style="max-width: 320px;">
            @else
                <p class="text-danger">Bukti Transfer Belum Diupload</p>
            @endif
        @else
            <p class="text-danger">Belum Ada Data Pembayaran</p>
        @endif
    </div>
</div>

@if($booking->status_pemesanan == 'menunggu_konfirmasi')
<div class="d-flex gap-2">
    <form action="{{ route('admin.bookings.verify', $booking->pemesanan_id) }}" method="POST">
        @csrf
        <button class="btn btn-success">Verifikasi Pembayaran</button>
    </form>

    <form action="{{ route('admin.bookings.reject', $booking->pemesanan_id) }}" method="POST">
        @csrf
        <button class="btn btn-danger" onclick="return confirm('Yakin ingin menolak pembayaran?')">Tolak Booking</button>
    </form>
</div>
@endif

<a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary mt-3">Kembali</a>
@endsection
