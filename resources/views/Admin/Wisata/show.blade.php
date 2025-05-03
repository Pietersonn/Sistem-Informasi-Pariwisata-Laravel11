@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between align-items-center">
                <h6>Detail Wisata</h6>
                <div>
                    <a href="{{ route('admin.wisata.edit', $wisata->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('admin.wisata.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h4>{{ $wisata->nama }}</h4>
                    
                    <div class="mb-3">
                        <strong>Kategori:</strong>
                        @foreach($wisata->kategori as $kategori)
                            <span class="badge bg-primary me-1">{{ $kategori->nama }}</span>
                        @endforeach
                    </div>

                    <div class="mb-3">
                        <strong>Alamat:</strong> {{ $wisata->alamat }}
                    </div>

                    <div class="mb-3">
                        <strong>Deskripsi:</strong>
                        <p>{{ $wisata->deskripsi }}</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <strong>Jam Operasional:</strong>
                            <p>
                                @if($wisata->jam_buka && $wisata->jam_tutup)
                                    {{ $wisata->jam_buka->format('H:i') }} - 
                                    {{ $wisata->jam_tutup->format('H:i') }}
                                @else
                                    Tidak ditentukan
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <strong>Harga Tiket:</strong>
                            <p>
                                @if($wisata->harga_tiket)
                                    Rp {{ number_format($wisata->harga_tiket, 0, ',', '.') }}
                                @else
                                    Gratis
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Status:</strong>
                        <span class="badge 
                            {{ $wisata->status == 'aktif' ? 'bg-success' : 
                               ($wisata->status == 'nonaktif' ? 'bg-secondary' : 'bg-warning') }}">
                            {{ ucfirst(str_replace('_', ' ', $wisata->status)) }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <strong>Fasilitas:</strong>
                        @if($wisata->fasilitas->count() > 0)
                            <ul class="list-group">
                                @foreach($wisata->fasilitas as $fasilitas)
                                    <li class="list-group-item">{{ $fasilitas->nama }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p>Tidak ada fasilitas yang didaftarkan</p>
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h6>Gambar Wisata</h6>
                        </div>
                        <div class="card-body">
                            @if($wisata->gambar->count() > 0)
                                <div id="wisataCarousel" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        @foreach($wisata->gambar as $index => $gambar)
                                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                                <img src="{{ $gambar->url }}" 
                                                     class="d-block w-100" 
                                                     alt="{{ $wisata->nama }}"
                                                     style="max-height: 250px; object-fit: cover;">
                                                @if($gambar->is_utama)
                                                    <div class="carousel-caption d-none d-md-block">
                                                        <span class="badge bg-primary">Gambar Utama</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    @if($wisata->gambar->count() > 1)
                                        <button class="carousel-control-prev" type="button" data-bs-target="#wisataCarousel" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#wisataCarousel" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    @endif
                                </div>
                            @else
                                <p class="text-center">Tidak ada gambar</p>
                            @endif
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h6>Informasi Tambahan</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <strong>Kontak:</strong> 
                                {{ $wisata->kontak ?? 'Tidak tersedia' }}
                            </div>
                            <div class="mb-2">
                                <strong>Email:</strong> 
                                {{ $wisata->email ?? 'Tidak tersedia' }}
                            </div>
                            <div class="mb-2">
                                <strong>Website:</strong> 
                                @if($wisata->website)
                                    <a href="{{ $wisata->website }}" target="_blank">{{ $wisata->website }}</a>
                                @else
                                    Tidak tersedia
                                @endif
                            </div>
                            <div class="mb-2">
                                <strong>Media Sosial:</strong>
                                <div>
                                    @if($wisata->instagram)
                                        <a href="https://instagram.com/{{ $wisata->instagram }}" target="_blank" class="me-2">
                                            <i class="fab fa-instagram"></i> Instagram
                                        </a>
                                    @endif
                                    @if($wisata->facebook)
                                        <a href="{{ $wisata->facebook }}" target="_blank" class="me-2">
                                            <i class="fab fa-facebook"></i> Facebook
                                        </a>
                                    @endif
                                    @if($wisata->twitter)
                                        <a href="https://twitter.com/{{ $wisata->twitter }}" target="_blank">
                                            <i class="fab fa-twitter"></i> Twitter
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6>Event Terkait</h6>
                        </div>
                        <div class="card-body">
                            @if($wisata->event->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Nama Event</th>
                                                <th>Tanggal Mulai</th>
                                                <th>Tanggal Selesai</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($wisata->event as $event)
                                            <tr>
                                                <td>{{ $event->nama }}</td>
                                                <td>{{ $event->tanggal_mulai->format('d M Y') }}</td>
                                                <td>{{ $event->tanggal_selesai->format('d M Y') }}</td>
                                                <td>
                                                    <span class="badge 
                                                        {{ $event->status == 'aktif' ? 'bg-success' : 
                                                           ($event->status == 'selesai' ? 'bg-secondary' : 'bg-warning') }}">
                                                        {{ $event->status_label }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-center">Tidak ada event terkait</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6>Ulasan Wisata</h6>
                        </div>
                        <div class="card-body">
                            @if($wisata->ulasan->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Pengunjung</th>
                                                <th>Rating</th>
                                                <th>Komentar</th>
                                                <th>Tanggal Kunjungan</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($wisata->ulasan as $ulasan)
                                            <tr>
                                                <td>{{ $ulasan->pengguna->name }}</td>
                                                <td>
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $ulasan->rating ? 'text-warning' : 'text-secondary' }}"></i>
                                                    @endfor
                                                </td>
                                                <td>{{ Str::limit($ulasan->komentar, 50) }}</td>
                                                <td>{{ $ulasan->tanggal_kunjungan->format('d M Y') }}</td>
                                                <td>
                                                    <span class="badge 
                                                        {{ $ulasan->status == 'ditampilkan' ? 'bg-success' : 
                                                           ($ulasan->status == 'disembunyikan' ? 'bg-secondary' : 'bg-warning') }}">
                                                        {{ $ulasan->status_label }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-center">Belum ada ulasan</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection