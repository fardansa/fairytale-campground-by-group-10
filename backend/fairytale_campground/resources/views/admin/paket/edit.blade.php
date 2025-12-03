@extends('layouts.admin')
@section('title','Edit Paket')
@section('content')
<div class="card">
  <div class="card-header"><strong>Edit Paket</strong></div>
  <div class="card-body">
    <form action="{{ route('admin.paket.update', $paket->paket_id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label">Nama Paket</label>
        <input type="text" name="nama_paket" class="form-control @error('nama_paket') is-invalid @enderror" value="{{ old('nama_paket', $paket->nama_paket) }}">
        @error('nama_paket') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4">{{ old('deskripsi', $paket->deskripsi) }}</textarea>
        @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Fasilitas (pisah koma)</label>
        <textarea name="fasilitas" class="form-control @error('fasilitas') is-invalid @enderror" rows="2">{{ old('fasilitas', $paket->fasilitas) }}</textarea>
        @error('fasilitas') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Kapasitas</label>
          <input type="number" name="kapasitas" class="form-control @error('kapasitas') is-invalid @enderror" value="{{ old('kapasitas', $paket->kapasitas) }}" min="1">
          @error('kapasitas') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Harga (Rp)</label>
          <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga', $paket->harga) }}" min="0">
          @error('harga') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
      </div>

      <div>
        <a href="{{ route('admin.paket.index') }}" class="btn btn-secondary btn-sm">Batal</a>
        <button class="btn btn-solid btn-sm">Perbarui</button>
      </div>
    </form>
  </div>
</div>
@endsection
