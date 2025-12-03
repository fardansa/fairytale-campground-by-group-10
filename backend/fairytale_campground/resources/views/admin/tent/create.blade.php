@extends('layouts.admin')

@section('content')
<h3 class="mb-4">Tambah Tenda</h3>

<form action="{{ route('admin.tent.store') }}" method="POST">
@csrf

<div class="mb-3">
    <label class="form-label">Paket</label>
    <select name="paket_id" class="form-select" required>
        @foreach ($pakets as $paket)
        <option value="{{ $paket->paket_id }}">{{ ucfirst($paket->nama_paket) }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Nomor Tenda</label>
    <input type="text" name="nomor_tent" class="form-control" required>
</div>

<div class="mb-3">
    <label class="form-label">Nomor Loker</label>
    <input type="text" name="nomor_loker" class="form-control" required>
</div>

<div class="mb-3">
    <label class="form-label">Status</label>
    <select name="status" class="form-select" required>
        <option value="tersedia">Tersedia</option>
        <option value="tidak tersedia">Tidak Tersedia</option>
    </select>
</div>

<button type="submit" class="btn btn-success">Simpan</button>
<a href="{{ route('admin.tent.index') }}" class="btn btn-secondary">Kembali</a>

</form>
@endsection
