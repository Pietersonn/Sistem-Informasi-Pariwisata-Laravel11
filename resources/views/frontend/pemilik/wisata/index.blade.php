@extends('layouts.frontend')

@section('title', 'Kelola Wisata Saya')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pemilik.css') }}">
@endpush

@section('content')
<div class="pemilik-wisata-container">
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-lg-6">
                <h1 class="page-title">Kelola Wisata Saya</h1>
                <p class="page-subtitle">Tambah dan kelola destinasi wisata yang Anda miliki</p>
            </div>
            <div class="col-lg-6 text-end">
                <a href="{{ route('pemilik.wisata.create') }}" class="btn btn-primary btn-add-wisata">
                    <i class="fas fa-plus-circle"></i> Tambah Wisata Baru
                </a>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="wisata-filter mb-4">
            <form action="{{ route('pemilik.wisata.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" name="search" placeholder="Cari nama wisata..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Ditolak</option>
                        <option value="menunggu_persetujuan" {{ request('status') == 'menunggu_persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="sort" class="form-select">
                        <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="terpopuler" {{ request('sort') == 'terpopuler' ? 'selected' : '' }}>Terpopuler</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>

        <!-- Wisata List -->
        <div class="wisata-list">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(count($wisata) > 0)
                <div class="row">
                    @foreach($wisata as $item)
                        <div class="col-lg-6 mb-4">
                            <div class="wisata-card">
                                <div class="wisata-card-header">
                                    <img src="{{ $item->gambarUtama ? asset($item->gambarUtama->file_gambar) : asset('images/placeholder-wisata.jpg') }}" alt="{{ $item->nama }}" class="wisata-image">
                                    <div class="wisata-status 
                                        @if($item->status == 'aktif') status-active
                                        @elseif($item->status == 'nonaktif') status-rejected
                                        @else status-pending @endif">
                                        <i class="fas {{ $item->status == 'aktif' ? 'fa-check-circle' : ($item->status == 'nonaktif' ? 'fa-times-circle' : 'fa-clock') }}"></i>
                                        {{ $item->status == 'aktif' ? 'Aktif' : ($item->status == 'nonaktif' ? 'Ditolak' : 'Menunggu Persetujuan') }}
                                    </div>
                                </div>
                                <div class="wisata-card-body">
                                    <h3 class="wisata-title">{{ $item->nama }}</h3>
                                    <div class="wisata-meta">
                                        <span class="meta-item"><i class="fas fa-map-marker-alt"></i> {{ Str::limit($item->alamat, 50) }}</span>
                                        <span class="meta-item"><i class="fas fa-eye"></i> {{ $item->jumlah_dilihat }} dilihat</span>
                                    </div>
                                    <div class="wisata-rating mb-3">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $item->rata_rata_rating)
                                                <i class="fas fa-star"></i>
                                            @elseif ($i - 0.5 <= $item->rata_rata_rating)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                        <span class="rating-value">{{ number_format($item->rata_rata_rating, 1) }}</span>
                                        <span class="review-count">({{ $item->ulasan->count() }} ulasan)</span>
                                    </div>
                                    <div class="wisata-categories">
                                        @foreach($item->kategori as $kategori)
                                            <span class="category-badge">{{ $kategori->nama }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="wisata-card-footer">
                                    <a href="{{ route('pemilik.wisata.show', $item->id) }}" class="btn btn-view">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <a href="{{ route('pemilik.wisata.edit', $item->id) }}" class="btn btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="{{ route('wisata.detail', $item->slug) }}" class="btn btn-preview" target="_blank">
                                        <i class="fas fa-external-link-alt"></i> Preview
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="pagination-container">
                    {{ $wisata->withQueryString()->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3>Belum Ada Wisata</h3>
                    <p>Anda belum memiliki destinasi wisata yang terdaftar</p>
                    <a href="{{ route('pemilik.wisata.create') }}" class="btn btn-primary">Tambah Wisata Sekarang</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection