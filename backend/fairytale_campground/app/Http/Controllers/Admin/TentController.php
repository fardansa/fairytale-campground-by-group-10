<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tent;
use App\Models\Paket;
use Illuminate\Http\Request;

class TentController extends Controller
{
    public function index()
    {
        $tents = Tent::with('paket')->paginate(10);
        return view('admin.tent.index', compact('tents'));
    }

    public function create()
    {
        $pakets = Paket::all();
        return view('admin.tent.create', compact('pakets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'paket_id' => 'required|exists:paket,paket_id',
            'nomor_tent' => 'required|string|max:10|unique:tent',
            'nomor_loker' => 'required|string|max:10',
            'status' => 'required|in:tersedia,tidak tersedia'
        ]);

        Tent::create($request->all());

        return redirect()->route('admin.tent.index')->with('success', 'Tenda berhasil ditambahkan');
    }

    public function edit($id)
    {
        $tent = Tent::findOrFail($id);
        $pakets = Paket::all();

        return view('admin.tent.edit', compact('tent', 'pakets'));
    }

    public function update(Request $request, $id)
    {
        $tent = Tent::findOrFail($id);

        $request->validate([
            'paket_id' => 'required|exists:paket,paket_id',
            'nomor_tent' => 'required|string|max:10|unique:tent,nomor_tent,' . $tent->tent_id . ',tent_id',
            'nomor_loker' => 'required|string|max:10',
            'status' => 'required|in:tersedia,tidak tersedia'
        ]);

        $tent->update($request->all());

        return redirect()->route('admin.tent.index')->with('success', 'Tenda berhasil diperbarui');
    }

    public function destroy($id)
    {
        Tent::destroy($id);
        return redirect()->route('admin.tent.index')->with('success', 'Tenda berhasil dihapus');
    }
}
