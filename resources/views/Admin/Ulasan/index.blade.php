@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between align-items-center">
                <h6>Daftar Ulasan</h6>
            </div>
            
            <form method="GET" class="my-3">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari ulasan..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">Semua Status</option>
                            @foreach($status as $s)
                                <option value="{{ $s }}" 
                                    {{ request('status') == $s ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $s)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="rating" class="form-control">
                            <option value="">Semua Rating</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" 
                                    {{ request('rating') == $i ? 'selected' : '' }}>
                                    {{ $i }} Bintang
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="{{ route('admin.ulasan.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th>Wisata</th>
                            <th>Pengunjung</th>
                            <th>Rating</th>
                            <th>Komentar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ulasan as $item)
                        <tr>
                            <td>
                                <a href="{{ route('admin.wisata.show', $item->wisata->id) }}">
                                    {{ $item->wisata->nama }}
                                </a>
                            </td>
                            <td>{{ $item->pengguna->name }}</td>
                            <td>
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $item->rating ? 'text-warning' : 'text-secondary' }}"></i>
                                @endfor
                            </td>
                            <td>{{ Str::limit($item->komentar, 50) }}</td>
                            <td>
                                <span class="badge 
                                    {{ $item->status == 'ditampilkan' ? 'bg-success' : 
                                       ($item->status == 'disembunyikan' ? 'bg-secondary' : 'bg-warning') }}">
                                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.ulasan.show', $item->id) }}" 
                                       class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.ulasan.destroy', $item->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Yakin ingin menghapus ulasan?')">
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
                            <td colspan="6" class="text-center">Tidak ada ulasan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <div class="d-flex justify-content-center mt-3">
                    {{ $ulasan->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection