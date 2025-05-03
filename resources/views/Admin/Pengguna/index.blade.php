@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h6>Daftar Pengguna</h6>
                    <a href="{{ route('admin.pengguna.create') }}" class="btn bg-gradient-primary btn-sm">
                        Tambah Pengguna Baru
                    </a>
                </div>

                <form method="GET" class="my-3">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Cari pengguna..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="role" class="form-control">
                                <option value="">Semua Role</option>
                                @foreach ($roles as $roleKey => $roleLabel)
                                    <option value="{{ $roleKey }}" {{ request('role') == $roleKey ? 'selected' : '' }}>
                                        {{ $roleLabel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-control">
                                <option value="">Semua Status</option>
                                @foreach ($status as $statusKey => $statusLabel)
                                    <option value="{{ $statusKey }}"
                                        {{ request('status') == $statusKey ? 'selected' : '' }}>
                                        {{ $statusLabel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary me-2">Filter</button>
                            <a href="{{ route('admin.pengguna.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengguna as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $item->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->email }}</td>
                                    <td>
                                        <span
                                            class="badge 
                                    {{ $item->role == 'admin' ? 'bg-danger' : ($item->role == 'pemilik_wisata' ? 'bg-warning' : 'bg-primary') }}">
                                            {{ ucfirst(str_replace('_', ' ', $item->role)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.pengguna.show', $item->id) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.pengguna.edit', $item->id) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.pengguna.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus pengguna?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada pengguna</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $pengguna->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
