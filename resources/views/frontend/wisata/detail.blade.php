@extends('layouts.frontend')

@section('title', $wisata->nama . ' - Detail Wisata')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<style>
    .wisata-gallery {
        max-height: 500px;
        overflow: hidden;
    }
    #map { height: 400px; }
</style>
@endpush

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-8">
            <!-- Galeri Gambar -->
            <div id="wisataCarousel" class="carousel slide wisata-gallery" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($wisata->gambar as $index => $gambar)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <img src="{{ $gambar->url }}" class="d-block w-100" alt="{{ $wisata->nama }}">
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#wisataCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#wisataCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>

            <!-- Informasi Wisata -->
            <div class="my-4">
                <h1>{{ $wisata->nama }}</h1>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        @foreach($wisata->kategori as $kategori)
                            <span class="badge bg-primary me-2">{{ $kategori->nama }}</span>
                        @endforeach
                    </div>
                    <div>
                        <span class="text-muted">
                            <i class="fas fa-eye"></i> {{ $wisata->jumlah_dilihat }} kali dilihat
                        </span>
                    </div>
                </div>

                <p>{{ $wisata->deskripsi }}</p>

                <!-- Detail Informasi -->
                <div class="row">
                    <div class="col-md-6">
                        <h5>Informasi</h5>
                        <ul class="list-unstyled">
                            <li><strong>Alamat:</strong> {{ $wisata->alamat }}</li>
                            <li><strong>Kontak:</strong> {{ $wisata->kontak ?? 'Tidak tersedia' }}</li>
                            <li><strong>Harga Tiket:</strong> 
                                {{ $wisata->harga_tiket ? 'Rp ' . number_format($wisata->harga_tiket, 0, ',', '.') : 'Gratis' }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5>Fasilitas</h5>
                        <ul>
                            @foreach($wisata->fasilitas as $fasilitas)
                                <li>{{ $fasilitas->nama }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Peta Lokasi -->
                <div class="my-4">
                    <h5>Lokasi</h5>
                    <div id="map"></div>
                </div>
            </div>

            <!-- Event Terkait -->
            @if($wisata->event->count() > 0)
            <div class="my-4">
                <h5>Event Mendatang</h5>
                @foreach($wisata->event as $event)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="card-title">{{ $event->nama }}</h6>
                            <p class="card-text">
                                {{ $event->deskripsi }}<br>
                                <small class="text-muted">
                                    {{ $event->tanggal_mulai->format('d M Y') }} - 
                                    {{ $event->tanggal_selesai->format('d M Y') }}
                                </small>
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Sidebar Ulasan dan Aksi -->
        <div class="col-md-4">
            <!-- Tombol Favorit dan Ulasan -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    @auth
                        @if(!$sudahDifavorit)
                            <form action="{{ route('wisata.favorit', $wisata->slug) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-heart"></i> Tambah Favorit
                                </button>
                            </form>
                        @else
                            <form action="{{ route('wisata.favorit.hapus', $wisata->slug) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-heart-broken"></i> Hapus Favorit
                                </button>
                            </form>
                        @endif

                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ulasanModal">
                            <i class="fas fa-comment"></i> Tulis Ulasan
                        </button>
                    @else
                        <p class="text-muted">Login untuk menambahkan ulasan atau favorit</p>
                        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                    @endauth
                </div>
            </div>

            <!-- Statistik Ulasan -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5>Statistik Ulasan</h5>
                    <div class="progress">
                        <div class="progress-bar bg-warning" role="progressbar" 
                             style="width: {{ $wisata->rating_rata * 20 }}%" 
                             aria-valuenow="{{ $wisata->rating_rata }}" 
                             aria-valuemin="0" aria-valuemax="5">
                        </div>
                    </div>
                    <div class="text-center mt-2">
                        <strong>{{ number_format($wisata->rating_rata, 1) }}/5</strong> 
                        ({{ $wisata->jumlah_ulasan }} ulasan)
                    </div>
                </div>
            </div>

            <!-- Ulasan Terbaru -->
            <div class="card">
                <div class="card-header">Ulasan Terbaru</div>
                <div class="card-body">
                    @forelse($ulasan as $review)
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $review->pengguna->nama }}</strong>
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mt-2">{{ $review->komentar }}</p>
                        </div>
                    @empty
                        <p class="text-center text-muted">Belum ada ulasan</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ulasan -->
@auth
<div class="modal fade" id="ulasanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tulis Ulasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('wisata.ulasan', $wisata->slug) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <div class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <input type="radio" name="rating" value="{{ $i }}" id="rating-{{ $i }}" class="d-none">
                                <label for="rating-{{ $i }}" class="star">
                                    <i class="fas fa-star"></i>
                                </label>
                            @endfor
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Komentar</label>
                        <textarea name