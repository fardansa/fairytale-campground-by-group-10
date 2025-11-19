<?php

namespace App\Http\Controllers;

use App\Models\PemesananMaster;
use App\Models\DetailPemesanan;
use App\Models\Camp;
use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PemesananController extends Controller
{
    // create booking (master + multiple detail) using Format C
    public function store(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'tanggal_checkin' => 'required|date',
            'tanggal_checkout' => 'required|date|after:tanggal_checkin',
            'items' => 'required|array|min:1',
            'items.*.paket_id' => 'required|exists:paket,paket_id',
            'items.*.camp_list' => 'required|array|min:1',
            'items.*.camp_list.*' => 'required|integer|exists:camp,camp_id'
        ]);

        $checkin = Carbon::parse($data['tanggal_checkin'])->toDateString();
        $checkout = Carbon::parse($data['tanggal_checkout'])->toDateString();
        $nights = Carbon::parse($data['tanggal_checkin'])->diffInDays(Carbon::parse($data['tanggal_checkout']));

        if ($nights <= 0) {
            return response()->json(['message' => 'Tanggal checkout harus setelah checkin'], 422);
        }

        // Flatten camp list and keep paket info for price lookup
        $flatten = [];
        foreach ($data['items'] as $group) {
            $paketId = $group['paket_id'];
            foreach ($group['camp_list'] as $campId) {
                $flatten[] = [
                    'paket_id' => $paketId,
                    'camp_id' => $campId
                ];
            }
        }

        // Check availability for each camp in the flatten array
        $conflict = [];
        foreach ($flatten as $row) {
            $campId = $row['camp_id'];
            $overlap = DetailPemesanan::where('camp_id', $campId)
                ->whereHas('pemesanan', function($q) use ($checkin, $checkout) {
                    $q->where('tanggal_checkin', '<', $checkout)
                      ->where('tanggal_checkout', '>', $checkin)
                      ->whereNotIn('status_pemesanan', ['expired', 'dibatalkan']);
                })->exists();

            if ($overlap) {
                $conflict[] = $campId;
            }
        }

        if (!empty($conflict)) {
            return response()->json([
                'message' => 'Beberapa camp tidak tersedia pada rentang tanggal tersebut',
                'conflict' => array_values($conflict)
            ], 409);
        }

        // compute prices and create records in transaction
        DB::beginTransaction();
        try {
            $master = PemesananMaster::create([
                'user_id' => $user->user_id,
                'tanggal_checkin' => $checkin,
                'tanggal_checkout' => $checkout,
                'total_harga' => 0, // update later
                'status_pemesanan' => 'menunggu_pembayaran',
                'expired_at' => now()->addHours(2)
            ]);

            $total = 0;
            // loop through items grouped by paket for nicer response (but create detail per camp)
            foreach ($data['items'] as $group) {
                $paket = Paket::find($group['paket_id']);
                $paketPrice = $paket->harga;

                foreach ($group['camp_list'] as $campId) {
                    // check camp exists
                    $camp = Camp::find($campId);
                    if (!$camp) {
                        DB::rollBack();
                        return response()->json(['message' => "Camp id {$campId} tidak ditemukan"], 404);
                    }

                    $harga_per_malam = $paketPrice;
                    $subtotal = $harga_per_malam * $nights;

                    DetailPemesanan::create([
                        'pemesanan_id' => $master->pemesanan_id,
                        'camp_id' => $campId,
                        'harga_per_malam' => $harga_per_malam,
                        'subtotal' => $subtotal
                    ]);

                    $total += $subtotal;
                }
            }

            $master->total_harga = $total;
            $master->save();

            DB::commit();

            // Build response detail grouped like frontend expects
            $detailResponse = [];
            foreach ($data['items'] as $group) {
                $paket = Paket::find($group['paket_id']);
                $items = [];
                foreach ($group['camp_list'] as $campId) {
                    $dp = DetailPemesanan::where('pemesanan_id', $master->pemesanan_id)
                        ->where('camp_id', $campId)->first();
                    $items[] = [
                        'camp_id' => $campId,
                        'harga_per_malam' => $dp->harga_per_malam,
                        'subtotal' => $dp->subtotal
                    ];
                }
                $detailResponse[] = [
                    'paket_id' => $group['paket_id'],
                    'paket_nama' => $paket->nama_paket,
                    'camp_list' => $items
                ];
            }

            return response()->json([
                'message' => 'Pemesanan berhasil dibuat',
                'data' => [
                    'pemesanan_id' => $master->pemesanan_id,
                    'user_id' => $master->user_id,
                    'tanggal_pemesanan' => $master->created_at ?? now()->toDateTimeString(),
                    'tanggal_checkin' => $master->tanggal_checkin,
                    'tanggal_checkout' => $master->tanggal_checkout,
                    'durasi_menginap' => $nights,
                    'total_harga' => $master->total_harga,
                    'detail' => $detailResponse,
                    'expired_at' => $master->expired_at
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal membuat pemesanan', 'error' => $e->getMessage()], 500);
        }
    }

    // list bookings for authenticated user
    public function index(Request $request)
    {
        $user = $request->user();
        $bookings = PemesananMaster::with('detail.camp.paket')->where('user_id', $user->user_id)->orderBy('created_at', 'desc')->get();
        return response()->json($bookings);
    }

    // show booking detail
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $booking = PemesananMaster::with('detail.camp.paket')->where('pemesanan_id', $id)->first();
        if (!$booking) return response()->json(['message' => 'Pemesanan tidak ditemukan'], 404);
        if ($booking->user_id !== $user->user_id && $request->user()->role !== 'admin') {
            return response()->json(['message' => 'Akses dilarang'], 403);
        }
        return response()->json($booking);
    }

    // Admin: confirm booking (optional) - we keep payment flow in PembayaranController
    public function konfirmasi(Request $request, $id)
    {
        // optional endpoint if admin wants to manually mark booking as paid
        $this->authorize('admin-action');

        $booking = PemesananMaster::find($id);
        if (!$booking) return response()->json(['message' => 'Pemesanan tidak ditemukan'], 404);

        $booking->status_pemesanan = 'telah_dibayar';
        $booking->save();

        return response()->json(['message' => 'Pemesanan ditandai sebagai dibayar']);
    }
}
