<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PemesananMaster;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = PemesananMaster::with('user','pembayaran')->orderBy('created_at','desc')->paginate(15);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = PemesananMaster::with('user','pembayaran')->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    // Verify payment (admin checks bukti transfer and approves)
    public function verifyPayment(Request $request, $id)
    {
        $booking = PemesananMaster::findOrFail($id);
        $pembayaran = Pembayaran::where('pemesanan_id',$booking->pemesanan_id)->first();

        if ($pembayaran) {
            $pembayaran->status_pembayaran = 'diterima';
            $pembayaran->save();
        }

        // set booking status to 'telah_dibayar' or 'menunggu_konfirmasi' — here: 'menunggu_konfirmasi'
        $booking->status_pemesanan = 'menunggu_konfirmasi';
        $booking->save();

        return redirect()->route('admin.bookings.show', $id)->with('success','Pembayaran diverifikasi.');
    }

    // Reject payment
    public function rejectPayment(Request $request, $id)
    {
        $booking = PemesananMaster::findOrFail($id);
        $pembayaran = Pembayaran::where('pemesanan_id',$booking->pemesanan_id)->first();

        if ($pembayaran) {
            $pembayaran->status_pembayaran = 'ditolak';
            $pembayaran->save();
        }

        $booking->status_pemesanan = 'dibatalkan';
        $booking->save();

        return redirect()->route('admin.bookings.show', $id)->with('error','Pembayaran ditolak, booking dibatalkan.');
    }
}
