<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Tent;
use App\Models\PemesananMaster;
use App\Models\DetailPemesanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingUserController extends Controller
{
    // STEP 1 — Pilih tanggal
    public function datePage()
    {
        return view('booking.date');
    }

    public function storeDate(Request $request)
    {
        $request->validate([
            'checkin' => 'required|date',
            'checkout' => 'required|date|after:checkin'
        ]);

        session([
            'checkin' => $request->checkin,
            'checkout' => $request->checkout
        ]);

        return redirect()->route('booking.paket');
    }

    // STEP 2 — Lihat paket
    public function paketPage()
    {
        if (!session()->has('checkin')) {
            return redirect()->route('booking.date');
        }

        $paketList = Paket::all();
        return view('booking.paket', compact('paketList'));
    }

    // STEP 3 — Pilih tenda
    public function tentPage(Request $request)
{
    if (!session()->has('checkin')) {
        return redirect()->route('booking.date');
    }

    $checkin = session('checkin');
    $checkout = session('checkout');

    $paketList = Paket::all();
    $tendaList = Tent::all();

    // Tandai tenda tersedia / booked
    foreach ($tendaList as $tenda) {
        $isBooked = DetailPemesanan::where('tent_id', $tenda->tent_id)
            ->whereHas('pemesanan', function ($query) use ($checkin, $checkout) {
                $query->where('tanggal_checkin', '<=', $checkout)
                      ->where('tanggal_checkout', '>=', $checkin)
                      ->where('status_pemesanan', '!=', 'dibatalkan');
            })->exists();

        $tenda->available = !$isBooked;
    }

    // Paket default (paket pertama)
    $selectedPaketId = $request->query('paket_id') ?? $paketList->first()->paket_id;

    // Ambil tenda yang sudah dipilih dari session
    $selectedTents = session('tent_id', []);

    // Tandai tenda yang sudah dipilih
    foreach ($tendaList as $tenda) {
        $tenda->selected = in_array($tenda->tent_id, $selectedTents);
    }

    return view('booking.tent', compact('paketList', 'tendaList', 'selectedPaketId', 'selectedTents'));
}


    public function selectTent(Request $request)
    {
        $request->validate([
            'tent_id' => 'required'
        ]);

        $tentIds = explode(',', $request->tent_id); // bisa multi-select
        session(['tent_id' => $tentIds]);

        return redirect()->route('booking.summary');
    }

    // STEP 4 — Ringkasan booking
    public function summaryPage()
    {
        $tentIds = session('tent_id', []);
        if (empty($tentIds)) {
            return redirect()->route('booking.tent');
        }

        $tendaList = Tent::whereIn('tent_id', $tentIds)->get();
        $paketIds = $tendaList->pluck('paket_id')->unique();
        $paketList = Paket::whereIn('paket_id', $paketIds)->get()->keyBy('paket_id');

        $checkin = session('checkin');
        $checkout = session('checkout');
        $selisih = (strtotime($checkout) - strtotime($checkin)) / 86400;

        $total = 0;
        foreach ($tendaList as $tenda) {
            $total += $paketList[$tenda->paket_id]->harga * $selisih;
        }

        return view('booking.summary', compact('tendaList', 'paketList', 'selisih', 'total', 'checkin', 'checkout'));
    }

    // STEP 5 — Simpan booking + upload bukti TF
    public function storeBooking(Request $request)
    {
        $request->validate([
            'bukti_tf' => 'required|image|max:2048'
        ]);

        $tentIds = session('tent_id', []);
        if (empty($tentIds)) return redirect()->route('booking.tent');

        $checkin = session('checkin');
        $checkout = session('checkout');
        $selisih = (strtotime($checkout) - strtotime($checkin)) / 86400;

        // Ambil data tenda dan paket
        $tendaList = Tent::whereIn('tent_id', $tentIds)->get();
        $paketIds = $tendaList->pluck('paket_id')->unique();
        $paketList = Paket::whereIn('paket_id', $paketIds)->get()->keyBy('paket_id');

        // Hitung total
        $total = 0;
        foreach ($tendaList as $tenda) {
            $total += $paketList[$tenda->paket_id]->harga * $selisih;
        }

        // Buat PemesananMaster
        $order = PemesananMaster::create([
            'user_id' => Auth::id(),
            'tanggal_checkin' => $checkin,
            'tanggal_checkout' => $checkout,
            'total_harga' => $total,
            'expired_at' => now()->addDay()
        ]);

        // Update status_pemesanan ke 'menunggu_konfirmasi'
        DB::table('pemesanan_master')
            ->where('pemesanan_id', $order->pemesanan_id)
            ->update(['status_pemesanan' => 'menunggu_konfirmasi']);

        // Buat DetailPemesanan
        foreach ($tendaList as $tenda) {
            DetailPemesanan::create([
                'pemesanan_id' => $order->pemesanan_id,
                'tent_id' => $tenda->tent_id,
                'harga_per_malam' => $paketList[$tenda->paket_id]->harga,
                'subtotal' => $paketList[$tenda->paket_id]->harga * $selisih
            ]);
        }

        // Upload bukti transfer
        $path = $request->file('bukti_tf')->store('bukti_tf', 'public');
        Pembayaran::create([
            'pemesanan_id' => $order->pemesanan_id,
            'total_pembayaran' => $total,
            'bukti_tf' => $path,
            'status_pembayaran' => 'menunggu_verifikasi',
            'tanggal_pembayaran' => now()
        ]);

        // Simpan data untuk complete page
        session([
            'last_booking_id' => $order->pemesanan_id,
            'complete_tendaList' => $tendaList->toArray(),
            'complete_paketList' => $paketList->map(fn($p) => $p->toArray())->toArray(),
            'checkin' => $checkin,
            'checkout' => $checkout
        ]);

        // Hapus session sementara lainnya
        session()->forget(['tent_id','paket_id']);

        // Redirect ke halaman complete
        return redirect()->route('booking.complete', $order->pemesanan_id);
    }

    // STEP 6 — Halaman selesai
public function completePage($id = null)
{
    // WAJIB login untuk akses detail
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    // Jika tidak ada ID dan tidak ada session → balik ke history
    if (!$id && !session()->has('last_booking_id')) {
        return redirect()->route('booking.history');
    }

    // Ambil ID dari session jika perlu
    $id = $id ?? session('last_booking_id');

    // AMBIL DATA LANGSUNG DARI DATABASE
    $order = PemesananMaster::with(['detailPemesanan.tenda', 'pembayaran'])
        ->where('pemesanan_id', $id)
        ->where('user_id', Auth::id()) // FIXED, TIDAK CONDITIONAL
        ->firstOrFail();

    return view('booking.complete', compact('order'));
}


// HISTORY PAGE
public function historyPage()
{
    if (!Auth::check()) {
        return view('booking.history')->with('message', 'Anda belum login');
    }

    $history = PemesananMaster::with(['detailPemesanan.tenda', 'pembayaran'])
        ->where('user_id', Auth::id())
        ->orderBy('pemesanan_id', 'DESC')
        ->get();

    return view('booking.history', compact('history'));
}

}
