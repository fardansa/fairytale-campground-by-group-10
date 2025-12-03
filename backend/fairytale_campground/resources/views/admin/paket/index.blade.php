@extends('layouts.admin')
@section('title','Manage Paket')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>Manage Paket</h4>
  <a href="{{ route('admin.paket.create') }}" class="btn btn-solid btn-sm">Tambah Paket</a>
</div>

<div class="card">
  <div class="card-body p-0">
    <table class="table mb-0">
      <thead>
        <tr>
          <th>#</th>
          <th>Nama Paket</th>
          <th>Kapasitas</th>
          <th>Harga</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($pakets as $p)
        <tr>
          <td>{{ $p->paket_id }}</td>
          <td>{{ $p->nama_paket }}</td>
          <td>{{ $p->kapasitas }}</td>
          <td>Rp {{ number_format($p->harga,0,',','.') }}</td>
          <td>
            <a href="{{ route('admin.paket.edit', $p->paket_id) }}" class="btn btn-sm btn-outline-primary">Edit</a>

            <form action="{{ route('admin.paket.destroy', $p->paket_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus paket ini?');">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Hapus</button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center">Belum ada paket.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="card-footer">
    {{ $pakets->links() }}
  </div>
</div>
@endsection
