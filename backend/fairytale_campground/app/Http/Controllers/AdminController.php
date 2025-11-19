<?php

namespace App\Http\Controllers;

use App\Models\PemesananMaster;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // list all bookings (admin)
    public function bookings()
    {
        $this->authorize('admin-action');
        $bookings = PemesananMaster::with('user','detail.camp.paket')->orderBy('created_at','desc')->get();
        return response()->json($bookings);
    }

    // get single booking detail (admin)
    public function bookingDetail($id)
    {
        $this->authorize('admin-action');
        $booking = PemesananMaster::with('user','detail.camp.paket')->find($id);
        if (!$booking) return response()->json(['message' => 'Pemesanan tidak ditemukan'], 404);
        return response()->json($booking);
    }
}
