@extends('layouts.admin')
@section('title','Tambah Paket')
@section('content')
<div class="card">
  <div class="card-header"><strong>Tambah Paket</strong></div>
  <div class="card-body">
    <form action="{{ route('admin.paket.store') }}" method="POST">
      @csrf

      <div class="mb-3">
        <label class="form-label">Nama Paket</label>
        <input type="text" name="nama_paket" class="form-control @error('nama_paket') is-invalid @enderror" value="{{ old('nama_paket') }}">
        @error('nama_paket') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4">{{ old('deskripsi') }}</textarea>
        @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Fasilitas (pisah koma)</label>
        <textarea name="fasilitas" class="form-control @error('fasilitas') is-invalid @enderror" rows="2">{{ old('fasilitas') }}</textarea>
        @error('fasilitas') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Kapasitas</label>
          <input type="number" name="kapasitas" class="form-control @error('kapasitas') is-invalid @enderror" value="{{ old('kapasitas',1) }}" min="1">
          @error('kapasitas') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Harga (Rp)</label>
          <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga',0) }}" min="0">
          @error('harga') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
      </div>

      <div>
        <a href="{{ route('admin.paket.index') }}" class="btn btn-secondary btn-sm">Batal</a>
        <button class="btn btn-solid btn-sm">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection
