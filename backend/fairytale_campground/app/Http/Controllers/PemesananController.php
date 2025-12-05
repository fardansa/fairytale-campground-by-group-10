<?php

namespace App\Http\Controllers;

use App\Models\PemesananMaster;
use App\Models\DetailPemesanan;
use App\Models\Tent;
use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PemesananController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'tanggal_checkin' => 'required|date',
            'tanggal_checkout' => 'required|date|after:tanggal_checkin',
            'items' => 'required|array|min:1',
            'items.*.paket_id' => 'required|exists:paket,paket_id',
            'items.*.tent_list' => 'required|array|min:1',
            'items.*.tent_list.*' => 'required|integer|exists:tent,tent_id'
        ]);

        $checkin = Carbon::parse($data['tanggal_checkin'])->toDateString();
        $checkout = Carbon::parse($data['tanggal_checkout'])->toDateString();
        $nights = Carbon::parse($data['tanggal_checkin'])
                        ->diffInDays(Carbon::parse($data['tanggal_checkout']));

        if ($nights <= 0) {
            return response()->json(['message' => 'Tanggal checkout harus setelah checkin'], 422);
        }

        // Flatten tent list
        $flatten = [];
        foreach ($data['items'] as $group) {
            foreach ($group['tent_list'] as $campId) {
                $flatten[] = [
                    'paket_id' => $group['paket_id'],
                    'tent_id' => $campId
                ];
            }
        }

        // Check availability
        $conflict = [];
        foreach ($flatten as $row) {
            $tentId = $row['tent_id'];

            $overlap = DetailPemesanan::where('tent_id', $tentId)
                ->whereHas('pemesanan', function ($q) use ($checkin, $checkout) {
                    $q->where('tanggal_checkin', '<', $checkout)
                      ->where('tanggal_checkout', '>', $checkin)
                      ->whereNotIn('status_pemesanan', ['expired', 'dibatalkan']);
                })
                ->exists();

            if ($overlap) {
                $conflict[] = $tentId;
            }
        }

        if (!empty($conflict)) {
            return response()->json([
                'message' => 'Beberapa tenda tidak tersedia pada tanggal tersebut',
                'conflict' => $conflict
            ], 409);
        }

        // CREATE RECORD
        DB::beginTransaction();
        try {
            $master = PemesananMaster::create([
                'user_id' => $user->user_id,
                'tanggal_checkin' => $checkin,
                'tanggal_checkout' => $checkout,
                'total_harga' => 0,
                'status_pemesanan' => 'menunggu_pembayaran',
                'expired_at' => now()->addHours(2)
            ]);

            $total = 0;

            foreach ($data['items'] as $group) {
                $paket = Paket::find($group['paket_id']);
                $paketPrice = $paket->harga;

                foreach ($group['tent_list'] as $campId) {
                    $tent = Tent::find($campId);

                    if (!$tent) {
                        DB::rollBack();
                        return response()->json([
                            'message' => "Tent id {$campId} tidak ditemukan"
                        ], 404);
                    }

                    $subtotal = $paketPrice * $nights;

                    DetailPemesanan::create([
                        'pemesanan_id' => $master->pemesanan_id,
                        'tent_id' => $campId,
                        'harga_per_malam' => $paketPrice,
                        'subtotal' => $subtotal
                    ]);

                    $total += $subtotal;
                }
            }

            $master->total_harga = $total;
            $master->save();

            // Build response
            $detailResponse = [];
            foreach ($data['items'] as $group) {
                $paket = Paket::find($group['paket_id']);
                $items = [];

                foreach ($group['tent_list'] as $campId) {
                    $dp = DetailPemesanan::where('pemesanan_id', $master->pemesanan_id)
                        ->where('tent_id', $campId)
                        ->first();

                    $items[] = [
                        'tent_id' => $campId,
                        'harga_per_malam' => $dp->harga_per_malam,
                        'subtotal' => $dp->subtotal
                    ];
                }

                $detailResponse[] = [
                    'paket_id' => $group['paket_id'],
                    'paket_nama' => $paket->nama_paket,
                    'tent_list' => $items
                ];
            }

            DB::commit();

            return response()->json([
                'message' => 'Pemesanan berhasil dibuat',
                'data' => [
                    'pemesanan_id' => $master->pemesanan_id,
                    'user_id' => $master->user_id,
                    'tanggal_pemesanan' => $master->created_at,
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
            return response()->json([
                'message' => 'Gagal membuat pemesanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            return PemesananMaster::with('detail.tent.paket')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return PemesananMaster::with('detail.tent.paket')
            ->where('user_id', $user->user_id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();

        $booking = PemesananMaster::with('detail.tent.paket')
            ->where('pemesanan_id', $id)
            ->first();

        if (!$booking)
            return response()->json(['message' => 'Pemesanan tidak ditemukan'], 404);

        if ($booking->user_id !== $user->user_id && $user->role !== 'admin')
            return response()->json(['message' => 'Akses dilarang'], 403);

        return $booking;
    }

    public function konfirmasi(Request $request, $id)
    {
        $this->authorize('admin-action');

        $booking = PemesananMaster::find($id);
        if (!$booking)
            return response()->json(['message' => 'Pemesanan tidak ditemukan'], 404);

        $booking->status_pemesanan = 'telah_dibayar';
        $booking->save();

        return response()->json(['message' => 'Pemesanan ditandai sebagai dibayar']);
    }
}
