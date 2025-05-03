@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h6>Detail Pengguna</h6>
                    <div>
                        <a href="{{ route('admin.pengguna.edit', $pengguna->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.pengguna.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <img src="{{ $pengguna->foto_profil_url }}" class="rounded-circle img-fluid"
                                    style="max-width: 200px;" alt="{{ $pengguna->nama }}">
                                <h4 class="mt-3">{{ $pengguna->nama }}</h4>
                                <p class="text-muted">{{ $pengguna->email }}</p>
                                <div class="mb-2">
                                    <span
                                        class="badge 
                                    {{ $pengguna->role == 'admin'
                                        ? 'bg-danger'
                                        : ($pengguna->role == 'pemilik_wisata'
                                            ? 'bg-warning'
                                            : 'bg-primary') }}">
                                        {{ ucfirst(str_replace('_', ' ', $pengguna->role)) }}
                                    </span>
                                    <span
                                        class="badge 
                                    {{ $pengguna->status == 'aktif'
                                        ? 'bg-success'
                                        : ($pengguna->status == 'nonaktif'
                                            ? 'bg-secondary'
                                            : 'bg-warning') }}">
                                        {{ ucfirst(str_replace('_', ' ', $pengguna->status)) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h6>Informasi Kontak</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <strong>Nomor Telepon:</strong>
                                    {{ $pengguna->nomor_telepon ?? 'Tidak tersedia' }}
                                </div>
                                <div>
                                    <strong>Alamat:</strong>
                                    {{ $pengguna->alamat ?? 'Tidak tersedia' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        @if ($pengguna->role == 'pemilik_wisata')
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6>Wisata Milik Pengguna</h6>
                                </div>
                                <div class="card-body">
                                    @if ($pengguna->wisata->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Nama Wisata</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($pengguna->wisata as $wisata)
                                                        <tr>
                                                            <td>{{ $wisata->nama }}</td>
                                                            <td>
                                                                <span
                                                                    class="badge 
                                                        {{ $wisata->status == 'aktif' ? 'bg-success' : ($wisata->status == 'nonaktif' ? 'bg-secondary' : 'bg-warning') }}">
                                                                    {{ ucfirst($wisata->status) }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('admin.wisata.show', $wisata->id) }}"
                                                                    class="btn btn-info btn-sm">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-center">Tidak memiliki wisata</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="card mb-3">
                            <div class="card-header">
                                <h6>Ulasan Pengguna</h6>
                            </div>
                            <div class="card-body">
                                @if ($pengguna->ulasan->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Wisata</th>
                                                    <th>Rating</th>
                                                    <th>Komentar</th>
                                                    <th>Tanggal</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($pengguna->ulasan as $ulasan)
                                                    <tr>
                                                        <td>{{ $ulasan->wisata->nama }}</td>
                                                        <td>
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <i
                                                                    class="fas fa-star {{ $i <= $ulasan->rating ? 'text-warning' : 'text-secondary' }}"></i>
                                                            @endfor
                                                        </td>
                                                        <td>{{ Str::limit($ulasan->komentar, 50) }}</td>
                                                        <td>{{ $ulasan->created_at->format('d M Y') }}</td>
                                                        <td>
                                                            <a href="{{ route('admin.ulasan.show', $ulasan->id) }}"
                                                                class="btn btn-info btn-sm">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-center">Tidak ada ulasan</p>
                                @endif
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6>Wisata Favorit</h6>
                            </div>
                            <div class="card-body">
                                @if ($pengguna->favorit->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Nama Wisata</th>
                                                    <th>Kategori</th>
                                                    <th>Catatan</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($pengguna->favorit as $favorit)
                                                    <tr>
                                                        <td>{{ $favorit->wisata->nama }}</td>
                                                        <td>
                                                            @foreach ($favorit->wisata->kategori as $kategori)
                                                                <span class="badge bg-primary me-1">
                                                                    {{ $kategori->nama }}
                                                                </span>
                                                            @endforeach
                                                        </td>
                                                        <td>{{ $favorit->catatan ?? '-' }}</td>
                                                        <td>
                                                            <a href="{{ route('admin.wisata.show', $favorit->wisata->id) }}"
                                                                class="btn btn-info btn-sm">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-center">Tidak ada wisata favorit</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
