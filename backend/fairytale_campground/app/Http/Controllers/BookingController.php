<?php
namespace App\Http\Controllers;

use App\Models\PemesananMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    public function uploadForm($id)
    {
        $booking = PemesananMaster::findOrFail($id);

        return view('booking.upload', compact('booking'));
    }

    public function uploadStore(Request $request, $id)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $booking = PemesananMaster::findOrFail($id);

        $file = $request->file('bukti_transfer');
        $filename = $id . '.' . $file->extension();

        $file->storeAs('payments', $filename);

        // Update status menunggu konfirmasi admin
        $booking->status_pemesanan = 'menunggu_konfirmasi';
        $booking->save();

        return redirect()->route('booking.upload', $id)->with('success', 'Bukti transfer berhasil diupload!');
    }
}
