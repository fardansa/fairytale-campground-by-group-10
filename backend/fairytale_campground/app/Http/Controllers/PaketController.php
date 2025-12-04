<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    // Fungsi untuk API JSON
    public function index()
    {
        return response()->json(Paket::all());
    }

    public function show($id)
    {
        $paket = Paket::find($id);
        if (!$paket) return response()->json(['message' => 'Paket tidak ditemukan'], 404);
        return response()->json($paket);
    }

    // Halaman paket untuk user
    public function packagePage()
    {
        $paketList = Paket::all(); // ambil semua paket
        return view('paket', compact('paketList'));
    }

    // admin actions: store, update, destroy tetap seperti sekarang
}
