<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\PemesananMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    // user submit payment (bukti optional if WA used)
    public function store(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'pemesanan_id' => 'required|exists:pemesanan_master,pemesanan_id',
            'total_pembayaran' => 'required|numeric|min:0',
            'metode_pembayaran' => 'nullable|in:transfer_bank,qris',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);

        $pemesanan = PemesananMaster::find($data['pemesanan_id']);
        if (!$pemesanan) return response()->json(['message' => 'Pemesanan tidak ditemukan'], 404);
        if ($pemesanan->user_id != $user->user_id) return response()->json(['message' => 'Akses dilarang'], 403);

        DB::beginTransaction();
        try {
            $buktiPath = null;
            if ($request->hasFile('bukti')) {
                $buktiPath = $request->file('bukti')->store('public/payments');
                // convert to public path
                $buktiPath = Storage::url($buktiPath);
            }

            $payment = Pembayaran::create([
                'pemesanan_id' => $pemesanan->pemesanan_id,
                'total_pembayaran' => $data['total_pembayaran'],
                'status_pembayaran' => 'menunggu_verifikasi',
                'tanggal_pembayaran' => now()
            ]);

            // update pemesanan status to waiting admin
            $pemesanan->status_pemesanan = 'menunggu_konfirmasi';
            $pemesanan->save();

            DB::commit();

            return response()->json(['message' => 'Pembayaran dikirim, menunggu verifikasi admin', 'pembayaran' => $payment], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal submit pembayaran', 'error' => $e->getMessage()], 500);
        }
    }

    // show payment status
    public function show($id)
    {
        $payment = Pembayaran::with('pemesanan')->find($id);
        if (!$payment) return response()->json(['message' => 'Pembayaran tidak ditemukan'], 404);
        return response()->json($payment);
    }

    // admin: verify payment
    public function verifikasi(Request $request, $id)
    {
        $this->authorize('admin-action');

        $data = $request->validate([
            'status' => 'required|in:diterima,ditolak'
        ]);

        $payment = Pembayaran::find($id);
        if (!$payment) return response()->json(['message' => 'Pembayaran tidak ditemukan'], 404);

        DB::beginTransaction();
        try {
            $payment->status_pembayaran = $data['status'];
            $payment->save();

            // update pemesanan master
            $pemesanan = PemesananMaster::find($payment->pemesanan_id);
            if (!$pemesanan) {
                DB::rollBack();
                return response()->json(['message' => 'Pemesanan terkait tidak ditemukan'], 404);
            }

            if ($data['status'] === 'diterima') {
                $pemesanan->status_pemesanan = 'telah_dibayar';
                // optionally you can set a paid_at field if you add it
            } else {
                $pemesanan->status_pemesanan = 'dibatalkan';
            }

            $pemesanan->save();

            DB::commit();
            return response()->json(['message' => 'Status pembayaran diperbarui', 'pembayaran' => $payment, 'pemesanan' => $pemesanan]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal verifikasi pembayaran', 'error' => $e->getMessage()], 500);
        }
    }
}
