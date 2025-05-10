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
                            @foreach ($wisata->kategori as $kategori)
                                <span class="badge bg-primary me-1">{{ $kategori->nama }}</span>
                            @endforeach
                        </div>

                        <div class="mb-3">
                            <strong>Alamat:</strong> {{ $wisata->alamat }}
                            @if ($wisata->link_gmaps)
                                <a href="{{ $wisata->link_gmaps }}" target="_blank"
                                    class="ms-2 btn btn-sm btn-outline-primary">
                                    <i class="fas fa-map-marker-alt"></i> Lihat di Google Maps
                                </a>
                            @endif
                        </div>

                        <div class="mb-3">
                            <strong>Deskripsi:</strong>
                            <p>{{ $wisata->deskripsi }}</p>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <strong>Jam Operasional:</strong>
                                <p>
                                    @if ($wisata->jam_buka && $wisata->jam_tutup)
                                        {{ $wisata->jam_buka->format('H:i') }} -
                                        {{ $wisata->jam_tutup->format('H:i') }}
                                        <br>
                                        <small class="text-muted">
                                            Hari:
                                            @if (is_array($wisata->hari_operasional) && count($wisata->hari_operasional) > 0)
                                                {{ implode(', ', array_map('ucfirst', $wisata->hari_operasional)) }}
                                            @else
                                                Setiap hari
                                            @endif
                                        </small>
                                    @else
                                        Tidak ditentukan
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <strong>Harga Tiket:</strong>
                                <p>
                                    @if ($wisata->harga_tiket)
                                        Rp {{ number_format($wisata->harga_tiket, 0, ',', '.') }}
                                    @else
                                        Gratis
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <strong>Status:</strong>
                            <span
                                class="badge 
                            {{ $wisata->status == 'aktif' ? 'bg-success' : ($wisata->status == 'nonaktif' ? 'bg-secondary' : 'bg-warning') }}">
                                {{ ucfirst(str_replace('_', ' ', $wisata->status)) }}
                            </span>
                        </div>

                        <div class="mb-3">
                            <strong>Fasilitas:</strong>
                            @if ($wisata->fasilitas && count($wisata->fasilitas) > 0)
                                <ul class="list-group">
                                    @foreach ($wisata->fasilitas as $fasilitas)
                                        <li class="list-group-item d-flex align-items-center">
                                            <i class="fas fa-check-circle me-2 text-success"></i>
                                            {{ $fasilitas }}
                                        </li>
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
                                <h6>Galeri Wisata</h6>
                            </div>
                            <div class="card-body">
                                @if ($wisata->gambar && $wisata->gambar->count() > 0)
                                    <div id="wisataCarousel" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            @foreach ($wisata->gambar as $index => $gambar)
                                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                                    <img src="{{ asset($gambar->file_gambar) }}" class="d-block w-100"
                                                        alt="{{ $wisata->nama }}"
                                                        style="max-height: 300px; object-fit: cover;">
                                                </div>
                                            @endforeach
                                        </div>
                                        @if ($wisata->gambar->count() > 1)
                                            <button class="carousel-control-prev" type="button"
                                                data-bs-target="#wisataCarousel" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button"
                                                data-bs-target="#wisataCarousel" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        @endif
                                    </div>
                                    <div class="mt-3">
                                        <p class="text-center">
                                            <small class="text-muted">Total {{ $wisata->gambar->count() }} gambar</small>
                                        </p>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-image fa-3x text-secondary mb-3"></i>
                                        <p class="text-muted">Belum ada gambar untuk wisata ini</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h6>Informasi Kontak</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <strong><i class="fas fa-phone me-2"></i>Kontak:</strong>
                                    {{ $wisata->kontak ?? 'Tidak tersedia' }}
                                </div>
                                <div class="mb-2">
                                    <strong><i class="fas fa-envelope me-2"></i>Email:</strong>
                                    {{ $wisata->email ?? 'Tidak tersedia' }}
                                </div>
                                <div class="mb-2">
                                    <strong><i class="fas fa-globe me-2"></i>Website:</strong>
                                    @if ($wisata->website)
                                        <a href="{{ $wisata->website }}" target="_blank">{{ $wisata->website }}</a>
                                    @else
                                        Tidak tersedia
                                    @endif
                                </div>
                                <div class="mb-2">
                                    <strong><i class="fas fa-share-alt me-2"></i>Media Sosial:</strong>
                                    <div class="mt-2">
                                        @if ($wisata->instagram)
                                            <a href="https://instagram.com/{{ $wisata->instagram }}" target="_blank"
                                                class="btn btn-sm btn-outline-danger me-1">
                                                <i class="fab fa-instagram"></i> {{ $wisata->instagram }}
                                            </a>
                                        @endif
                                        @if ($wisata->facebook)
                                            <a href="{{ $wisata->facebook }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary me-1">
                                                <i class="fab fa-facebook"></i> Facebook
                                            </a>
                                        @endif
                                        @if ($wisata->twitter)
                                            <a href="https://twitter.com/{{ $wisata->twitter }}" target="_blank"
                                                class="btn btn-sm btn-outline-info">
                                                <i class="fab fa-twitter"></i> {{ $wisata->twitter }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Statistik</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-eye me-2"></i> Jumlah Dilihat</span>
                                        <span class="badge bg-info rounded-pill">{{ $wisata->jumlah_dilihat }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-star me-2"></i> Rating Rata-rata</span>
                                        <span class="badge bg-warning rounded-pill">
                                            {{ number_format($wisata->rata_rata_rating, 1) }}/5
                                        </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-calendar-alt me-2"></i> Tanggal Ditambahkan</span>
                                        <span class="text-muted">{{ $wisata->created_at->format('d M Y') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-clock me-2"></i> Terakhir Diupdate</span>
                                        <span class="text-muted">{{ $wisata->updated_at->format('d M Y H:i') }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section untuk Event Terkait -->
                @if ($wisata->event && $wisata->event->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6>Event Terkait</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Nama Event</th>
                                                    <th>Tanggal Mulai</th>
                                                    <th>Tanggal Selesai</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($wisata->event as $event)
                                                    <tr>
                                                        <td>{{ $event->nama }}</td>
                                                        <td>{{ $event->tanggal_mulai->format('d M Y') }}</td>
                                                        <td>{{ $event->tanggal_selesai->format('d M Y') }}</td>
                                                        <td>
                                                            <span
                                                                class="badge 
                                                    {{ $event->status == 'aktif' ? 'bg-success' : ($event->status == 'selesai' ? 'bg-secondary' : 'bg-warning') }}">
                                                                {{ $event->status }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="#" class="btn btn-info btn-sm">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Section untuk Ulasan -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6>Ulasan Wisata</h6>
                                <span class="badge bg-primary">{{ $wisata->ulasan->count() }} Ulasan</span>
                            </div>
                            <div class="card-body">
                                @if ($wisata->ulasan->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Pengunjung</th>
                                                    <th>Rating</th>
                                                    <th>Komentar</th>
                                                    <th>Tanggal Kunjungan</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($wisata->ulasan as $ulasan)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <img src="{{ $ulasan->pengguna->foto_profil_url ?? asset('assets/img/default-avatar.png') }}"
                                                                    class="avatar avatar-sm me-2"
                                                                    alt="{{ $ulasan->pengguna->name }}">
                                                                {{ $ulasan->pengguna->name }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <i
                                                                    class="fas fa-star {{ $i <= $ulasan->rating ? 'text-warning' : 'text-secondary' }}"></i>
                                                            @endfor
                                                        </td>
                                                        <td>{{ Str::limit($ulasan->komentar, 50) }}</td>
                                                        <td>{{ $ulasan->tanggal_kunjungan ? $ulasan->tanggal_kunjungan->format('d M Y') : '-' }}
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge 
                                                        {{ $ulasan->status == 'ditampilkan'
                                                            ? 'bg-success'
                                                            : ($ulasan->status == 'disembunyikan'
                                                                ? 'bg-secondary'
                                                                : 'bg-warning') }}">
                                                                {{ ucfirst(str_replace('_', ' ', $ulasan->status)) }}
                                                            </span>
                                                        </td>
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
                                    <div class="text-center py-4">
                                        <i class="fas fa-comments fa-3x text-secondary mb-3"></i>
                                        <p class="text-muted">Belum ada ulasan untuk wisata ini</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi carousel
            var myCarousel = document.querySelector('#wisataCarousel')
            if (myCarousel) {
                new bootstrap.Carousel(myCarousel, {
                    interval: 5000,
                    wrap: true
                })
            }
        });
    </script>
@endpush
