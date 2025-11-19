<?php

namespace App\Http\Controllers;

use App\Models\Camp;
use App\Models\DetailPemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CampController extends Controller
{
    // list all camps (optionally filter by paket_id)
    public function index(Request $request)
    {
        $q = Camp::query();

        if ($request->has('paket_id')) {
            $q->where('paket_id', $request->paket_id);
        }

        return response()->json($q->get());
    }

    public function show($id)
    {
        $camp = Camp::with('paket')->find($id);
        if (!$camp) return response()->json(['message' => 'Camp tidak ditemukan'], 404);
        return response()->json($camp);
    }

    // check availability for a date range and paket
    public function checkAvailability(Request $request)
    {
        $data = $request->validate([
            'tanggal_checkin' => 'required|date',
            'tanggal_checkout' => 'required|date|after:tanggal_checkin',
            'paket_id' => 'nullable|integer'
        ]);

        // get camp ids that are already booked for the range (exclude expired/cancel statuses)
        $bookedCampIds = DetailPemesanan::whereHas('pemesanan', function($q) use ($data) {
            $q->where('tanggal_checkin', '<', $data['tanggal_checkout'])
              ->where('tanggal_checkout', '>', $data['tanggal_checkin'])
              ->whereNotIn('status_pemesanan', ['expired', 'dibatalkan']);
        })->pluck('camp_id')->toArray();

        $q = Camp::query();
        if (!empty($data['paket_id'])) {
            $q->where('paket_id', $data['paket_id']);
        }
        $available = $q->whereNotIn('camp_id', $bookedCampIds)->get();

        return response()->json([
            'checkin' => $data['tanggal_checkin'],
            'checkout' => $data['tanggal_checkout'],
            'available_count' => $available->count(),
            'data' => $available
        ]);
    }

    // admin create
    public function store(Request $request)
    {
        $this->authorize('admin-action');

        $data = $request->validate([
            'paket_id' => 'required|exists:paket,paket_id',
            'nomor_camp' => 'required|string|max:10',
            'nomor_loker' => 'nullable|string|max:10',
            'status' => 'nullable|in:tersedia,tidak tersedia'
        ]);

        $camp = Camp::create($data);

        return response()->json(['message' => 'Camp dibuat', 'camp' => $camp], 201);
    }

    // admin update
    public function update(Request $request, $id)
    {
        $this->authorize('admin-action');

        $camp = Camp::find($id);
        if (!$camp) return response()->json(['message' => 'Camp tidak ditemukan'], 404);

        $data = $request->validate([
            'paket_id' => 'nullable|exists:paket,paket_id',
            'nomor_camp' => 'nullable|string|max:10',
            'nomor_loker' => 'nullable|string|max:10',
            'status' => 'nullable|in:tersedia,tidak tersedia'
        ]);

        $camp->update($data);
        return response()->json(['message' => 'Camp diperbarui', 'camp' => $camp]);
    }

    // admin delete
    public function destroy($id)
    {
        $this->authorize('admin-action');

        $camp = Camp::find($id);
        if (!$camp) return response()->json(['message' => 'Camp tidak ditemukan'], 404);

        // Optional: Prevent delete if there are future bookings
        $hasFuture = $camp->detailPemesanan()->whereHas('pemesanan', function($q) {
            $q->where('tanggal_checkout', '>=', now()->toDateString())
              ->whereNotIn('status_pemesanan', ['expired', 'dibatalkan']);
        })->exists();

        if ($hasFuture) {
            return response()->json(['message' => 'Tidak bisa menghapus camp yang memiliki booking aktif/future'], 400);
        }

        $camp->delete();
        return response()->json(['message' => 'Camp dihapus']);
    }
}
