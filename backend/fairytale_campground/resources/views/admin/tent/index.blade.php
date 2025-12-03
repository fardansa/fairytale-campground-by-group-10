@extends('layouts.admin')

@section('custom_css')
<style>
.pagination {
    margin-top: 10px;
}
.pagination .page-link {
    border-radius: 6px !important;
    color: #1d4807;
    font-weight: 600;
}
.pagination .page-link:hover {
    background-color: #bbf7d0;
    color: #1d4807;
}
.pagination .active .page-link {
    background-color: #1d4807 !important;
    border-color: #1d4807 !important;
    color: #fff !important;
}
</style>
@endsection

@section('content')
<h3 class="mb-4">Manajemen Tenda</h3>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<a href="{{ route('admin.tent.create') }}" class="btn btn-success mb-3">
    <i class="bi bi-plus-circle"></i> Tambah Tenda
</a>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead class="table-success">
                <tr>
                    <th>Paket</th>
                    <th>Nomor Tenda</th>
                    <th>Nomor Loker</th>
                    <th>Status</th>
                    <th width="130">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tents as $tent)
                <tr>
                    <td>{{ $tent->paket->nama_paket }}</td>
                    <td>{{ $tent->nomor_tent }}</td>
                    <td>{{ $tent->nomor_loker }}</td>
                    <td>
                        <span class="badge {{ $tent->status === 'tersedia' ? 'bg-success' : 'bg-danger' }}">
                            {{ $tent->status }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.tent.edit', $tent->tent_id) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.tent.destroy', $tent->tent_id) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-end">
            {{ $tents->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
