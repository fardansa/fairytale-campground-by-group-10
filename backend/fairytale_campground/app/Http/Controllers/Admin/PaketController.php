<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paket;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    public function index() { $pakets = Paket::paginate(12); return view('admin.paket.index', compact('pakets')); }
    public function create() { return view('admin.paket.create'); }
    public function store(Request $r) {
        $data = $r->validate([
            'nama_paket'=>'required|string|max:50',
            'deskripsi'=>'nullable|string',
            'fasilitas'=>'nullable|string',
            'kapasitas'=>'required|integer|min:1',
            'harga'=>'required|integer|min:0'
        ]);
        Paket::create($data);
        return redirect()->route('admin.paket.index')->with('success','Paket dibuat.');
    }
    public function edit($id) { $paket = Paket::findOrFail($id); return view('admin.paket.edit', compact('paket')); }
    public function update(Request $r,$id) {
        $paket = Paket::findOrFail($id);
        $data = $r->validate([
            'nama_paket'=>'required|string|max:50',
            'deskripsi'=>'nullable|string',
            'fasilitas'=>'nullable|string',
            'kapasitas'=>'required|integer|min:1',
            'harga'=>'required|integer|min:0'
        ]);
        $paket->update($data);
        return redirect()->route('admin.paket.index')->with('success','Paket diperbarui.');
    }
    public function destroy($id) { Paket::findOrFail($id)->delete(); return back()->with('success','Paket dihapus.'); }
}
