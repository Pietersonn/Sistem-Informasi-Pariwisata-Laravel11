@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h6>Daftar Wisata</h6>
                <a href="{{ route('admin.wisata.create') }}" class="btn bg-gradient-primary btn-sm">
                    Tambah Wisata Baru
                </a>
            </div>
            
            {{-- Filter dan Pencarian --}}
            <form method="GET" class="my-3">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari wisata..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">Semua Status</option>
                            @foreach($status as $s)
                                <option value="{{ $s }}" 
                                    {{ request('status') == $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.wisata.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Wisata</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pemilik</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wisata as $item)
                        <tr>
                            <td>
                                <div class="d-flex px-2 py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">{{ $item->nama }}</h6>
                                        <p class="text-xs text-secondary mb-0">{{ $item->alamat }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p class="text-xs font-weight-bold mb-0">
                                    {{ $item->pemilik->nama ?? 'Tidak diketahui' }}
                                </p>
                            </td>
                            <td class="align-middle text-center text-sm">
                                <span class="badge badge-sm bg-gradient-{{ 
                                    $item->status == 'aktif' ? 'success' : 
                                    ($item->status == 'nonaktif' ? 'secondary' : 'warning') 
                                }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="align-middle text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.wisata.show', $item->id) }}" 
                                       class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.wisata.edit', $item->id) }}" 
                                       class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.wisata.destroy', $item->id) }}" 
                                          method="POST" class="d-inline" 
                                          onsubmit="return confirm('Yakin ingin menghapus?')">
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
                            <td colspan="4" class="text-center">Tidak ada data wisata</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                
                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-3">
                    {{ $wisata->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection