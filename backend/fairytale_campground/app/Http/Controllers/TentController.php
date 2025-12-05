<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tent;
use App\Models\Paket;
use App\Models\DetailPemesanan;

class TentController extends Controller
{
    // Halaman list tenda berdasarkan paket
    public function index(Request $request)
    {
        $paketList = Paket::all();

        $selectedPaketId = $request->query('paket_id') ?? $paketList->first()->paket_id;

        // Ambil semua tenda sesuai paket
        $tendaList = Tent::where('paket_id', $selectedPaketId)->get();

        // Jika user mengirim tanggal checkin/checkout, cek ketersediaan
        $checkin = $request->query('checkin');
        $checkout = $request->query('checkout');

        if ($checkin && $checkout) {
            foreach ($tendaList as $tenda) {
                $tenda->available = $tenda->isAvailable($checkin, $checkout);
            }
        } else {
            foreach ($tendaList as $tenda) {
                $tenda->available = ($tenda->status === 'tersedia');
            }
        }

        return view('tent.index', compact('paketList', 'tendaList', 'selectedPaketId', 'checkin', 'checkout'));
    }

    // Detail tenda
    public function show($id)
    {
        $tenda = Tent::with('paket')->findOrFail($id);
        return view('tent.show', compact('tenda'));
    }

    // Update status tenda (misal admin menonaktifkan tenda)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:tersedia,tidak tersedia'
        ]);

        $tenda = Tent::findOrFail($id);
        $tenda->status = $request->status;
        $tenda->save();

        return redirect()->back()->with('success', 'Status tenda berhasil diperbarui');
    }
}
