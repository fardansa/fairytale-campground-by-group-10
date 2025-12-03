<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PemesananMaster;
use Illuminate\Support\Facades\Storage;

class BookingAdminController extends Controller
{
    // Tampilkan semua booking
    public function index()
    {
        $bookings = PemesananMaster::orderBy('created_at', 'desc')->paginate(10);

        $status = [
            'pending' => 'Pending',
            'telah_dibayar' => 'Telah Dibayar',
            'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
            'dibatalkan' => 'Dibatalkan',
        ];

        return view('admin.bookings.index', compact('bookings', 'status'));
    }

    // Tampilkan detail booking
    public function show($id)
    {
        $booking = PemesananMaster::with(['details.tent', 'user', 'pembayaran'])
                    ->findOrFail($id);

        $buktiExists = Storage::exists("payments/$id.jpg") ||
                       Storage::exists("payments/$id.jpeg") ||
                       Storage::exists("payments/$id.png");

        return view('admin.bookings.show', compact('booking', 'buktiExists'));
    }

    // Verifikasi pembayaran
    public function verify($id)
    {
        $booking = PemesananMaster::findOrFail($id);
        $booking->status_pemesanan = 'telah_dibayar';
        $booking->save();

        return back()->with('success', 'Pembayaran berhasil diverifikasi!');
    }

    // Tolak pembayaran
    public function reject($id)
    {
        $booking = PemesananMaster::findOrFail($id);

        foreach(['jpg','jpeg','png'] as $ext){
            $file = "payments/$id.$ext";
            if(Storage::exists($file)){
                Storage::delete($file);
            }
        }

        $booking->status_pemesanan = 'dibatalkan';
        $booking->save();

        return back()->with('error', 'Pembayaran ditolak!');
    }
}
