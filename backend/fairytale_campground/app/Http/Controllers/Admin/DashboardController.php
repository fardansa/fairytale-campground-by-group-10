<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PemesananMaster;
use App\Models\Pembayaran;
use App\Models\User;
use App\Models\Paket;
use App\Models\Tent;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Summary
        $totalUsers = User::count();
        $totalPaket = Paket::count();
        $totalTent = Tent::count();
        $totalBookings = PemesananMaster::count();

        // Status
        $pendingBookings = PemesananMaster::where('status_pemesanan', 'menunggu_konfirmasi')->count();
        $approvedBookings = PemesananMaster::where('status_pemesanan', 'telah_dibayar')->count();

        // Revenue
        $totalRevenue = PemesananMaster::where('status_pemesanan', 'telah_dibayar')
                            ->sum('total_harga');

        // Chart Bookings per month
        $bookingsPerMonth = PemesananMaster::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        // Chart Revenue per month
        $revenuePerMonth = PemesananMaster::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('SUM(total_harga) as total')
            )
            ->whereYear('created_at', date('Y'))
            ->where('status_pemesanan', 'telah_dibayar')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        // Recent bookings table
        $recentBookings = PemesananMaster::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPaket',
            'totalTent',
            'totalBookings',
            'pendingBookings',
            'approvedBookings',
            'totalRevenue',
            'bookingsPerMonth',
            'revenuePerMonth',
            'recentBookings'
        ));
    }
}
