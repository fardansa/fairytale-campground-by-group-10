@extends('layouts.admin')

@section('content')
<h3 class="mb-3">Manajemen Booking</h3>

<div class="card shadow-sm">
    <div class="card-body">

        <div class="mb-3">
            <form method="GET" class="row g-2">
                <div class="col-auto">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="menunggu_pembayaran" {{ $status == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                        <option value="menunggu_konfirmasi" {{ $status == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                        <option value="telah_dibayar" {{ $status == 'telah_dibayar' ? 'selected' : '' }}>Telah Dibayar</option>
                        <option value="dibatalkan" {{ $status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-success">Filter</button>
                </div>
            </form>
        </div>

        <table class="table table-hover align-middle">
            <thead class="table-success">
                <tr>
                    <th>Nama User</th>
                    <th>Tgl Check-in</th>
                    <th>Tgl Checkout</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th width="50">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($bookings as $b)
                <tr>
                    <td>{{ $b->user?->nama ?? 'Guest' }}</td>
                    <td>{{ $b->tanggal_checkin }}</td>
                    <td>{{ $b->tanggal_checkout }}</td>
                    <td>Rp {{ number_format($b->total_harga,0,',','.') }}</td>
                    <td>
                        <span class="badge 
                                @if($b->status_pemesanan=='telah_dibayar') bg-success
                                @elseif($b->status_pemesanan=='menunggu_konfirmasi') bg-warning text-dark
                                @elseif($b->status_pemesanan=='dibatalkan') bg-danger
                                @else bg-secondary @endif">
                            {{ $b->status_pemesanan }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.bookings.show', $b->pemesanan_id) }}" 
                           class="btn btn-sm btn-primary">
                           <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>

        <div class="d-flex justify-content-end">
            {{ $bookings->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>
@endsection
