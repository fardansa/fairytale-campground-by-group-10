@extends('layouts.admin')
@section('title','Dashboard')

@section('content')

{{-- Summary Cards --}}
<div class="row g-4">
    <div class="col-md-3">
        <div class="card p-3 shadow-sm">
            <h6 class="text-muted mb-1">Total Users</h6>
            <h2 class="fw-bold">{{ $totalUsers }}</h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3 shadow-sm">
            <h6 class="text-muted mb-1">Total Bookings</h6>
            <h2 class="fw-bold">{{ $totalBookings }}</h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3 shadow-sm">
            <h6 class="text-muted mb-1">Total Paket</h6>
            <h2 class="fw-bold">{{ $totalPaket }}</h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3 shadow-sm">
            <h6 class="text-muted mb-1">Total Tents</h6>
            <h2 class="fw-bold">{{ $totalTent }}</h2>
        </div>
    </div>
</div>

{{-- Bookings Status Cards --}}
<div class="row g-4 mt-3">
    <div class="col-md-4">
        <div class="card p-3 shadow-sm">
            <h6 class="text-muted mb-1">Menunggu Konfirmasi</h6>
            <h2 class="fw-bold text-warning">{{ $pendingBookings }}</h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3 shadow-sm">
            <h6 class="text-muted mb-1">Telah Dibayar</h6>
            <h2 class="fw-bold text-success">{{ $approvedBookings }}</h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3 shadow-sm">
            <h6 class="text-muted mb-1">Total Pendapatan</h6>
            <h3 class="fw-bold text-success">Rp {{ number_format($totalRevenue,0,',','.') }}</h3>
        </div>
    </div>
</div>

{{-- Chart Bookings --}}
<div class="card shadow-sm mt-4">
    <div class="card-header bg-white">
        <strong>Bookings Per Bulan</strong>
    </div>
    <div class="card-body">
        <canvas id="bookingsChart" height="100"></canvas>
    </div>
</div>

{{-- Chart Revenue --}}
<div class="card shadow-sm mt-4">
    <div class="card-header bg-white">
        <strong>Pendapatan Per Bulan</strong>
    </div>
    <div class="card-body">
        <canvas id="revenueChart" height="100"></canvas>
    </div>
</div>

{{-- Recent Bookings Table --}}
<div class="card shadow-sm mt-4">
    <div class="card-header bg-white">
        <strong>Recent Bookings</strong>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Total</th>
                    <th>Status Pemesanan</th>
                    <th>Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentBookings as $b)
                    <tr>
                        <td>{{ $b->user->nama ?? '-' }}</td>
                        <td>{{ $b->tanggal_checkin }}</td>
                        <td>{{ $b->tanggal_checkout }}</td>
                        <td>Rp {{ number_format($b->total_harga,0,',','.') }}</td>
                        <td>{{ $b->status_pemesanan }}</td>
                        <td>{{ $b->pembayaran->status_pembayaran ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.bookings.show',$b->pemesanan_id) }}" class="btn btn-sm btn-outline-primary">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // ---------------- Bookings Chart --------------
    new Chart(document.getElementById('bookingsChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($bookingsPerMonth->toArray())) !!}.map(m => 'Bulan ' + m),
            datasets: [{
                label: 'Total Booking',
                data: {!! json_encode(array_values($bookingsPerMonth->toArray())) !!},
                borderWidth: 2
            }]
        }
    });

    // ---------------- Revenue Chart --------------
    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($revenuePerMonth->toArray())) !!}.map(m => 'Bulan ' + m),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode(array_values($revenuePerMonth->toArray())) !!},
                borderWidth: 2
            }]
        }
    });
</script>
@endpush
