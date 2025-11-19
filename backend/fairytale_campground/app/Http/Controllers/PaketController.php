<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    // public list
    public function index()
    {
        return response()->json(Paket::all());
    }

    public function show($id)
    {
        $paket = Paket::find($id);
        if (!$paket) {
            return response()->json(['message' => 'Paket tidak ditemukan'], 404);
        }
        return response()->json($paket);
    }

    // admin: create
    public function store(Request $request)
    {
        $this->authorize('admin-action'); // or rely on admin middleware
        $data = $request->validate([
            'nama_paket' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'fasilitas' => 'nullable|string',
            'kapasitas' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0'
        ]);

        $paket = Paket::create($data);

        return response()->json(['message' => 'Paket dibuat', 'paket' => $paket], 201);
    }

    // admin: update
    public function update(Request $request, $id)
    {
        $this->authorize('admin-action');
        $paket = Paket::find($id);
        if (!$paket) return response()->json(['message' => 'Paket tidak ditemukan'], 404);

        $data = $request->validate([
            'nama_paket' => 'nullable|string|max:50',
            'deskripsi' => 'nullable|string',
            'fasilitas' => 'nullable|string',
            'kapasitas' => 'nullable|integer|min:1',
            'harga' => 'nullable|numeric|min:0'
        ]);

        $paket->update($data);
        return response()->json(['message' => 'Paket diperbarui', 'paket' => $paket]);
    }

    // admin: delete
    public function destroy($id)
    {
        $this->authorize('admin-action');
        $paket = Paket::find($id);
        if (!$paket) return response()->json(['message' => 'Paket tidak ditemukan'], 404);
        $paket->delete();
        return response()->json(['message' => 'Paket dihapus']);
    }
}
