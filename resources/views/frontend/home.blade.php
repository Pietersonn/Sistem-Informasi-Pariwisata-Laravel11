@extends('layouts.frontend')

@section('title', 'Wisata Kabupaten Hulu Sungai Tengah')

@push('styles')
    <style>
        .hero-section {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset('images/hero-image.jpg') }}');
            background-size: cover;
            background-position: center;
            height: 85vh;
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-box {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        .category-card {
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            background-color: #fff;
            text-align: center;
            padding: 25px 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            height: 100%;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .category-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: #f8f9fa;
            color: var(--primary-color);
        }

        .category-icon img {
            max-width: 40px;
            max-height: 40px;
        }

        .card-destination {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }

        .card-destination:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .card-destination img {
            height: 240px;
            object-fit: cover;
        }

        .destination-info {
            padding: 20px;
        }

        .event-card {
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            background-color: #fff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            height: 100%;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .event-card .event-date {
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            padding: 10px;
            font-weight: bold;
        }

        .event-card .event-info {
            padding: 20px;
        }

        .section-heading {
            margin-bottom: 40px;
            position: relative;
        }

        .section-heading:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -15px;
            width: 60px;
            height: 3px;
            background-color: var(--primary-color);
        }

        .section-heading-center:after {
            left: 50%;
            transform: translateX(-50%);
        }

        .rating-stars .fas {
            color: #FFC107;
        }

        .badge-category {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 20px;
        }
    </style>
@endpush

@section('content')
    <!-- Hero Section with Search -->
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4 text-white">Jelajahi Keindahan Kabupaten HST</h1>
                    <p class="lead text-white mb-5">Temukan destinasi wisata menakjubkan dan pengalaman tak terlupakan di
                        Hulu Sungai Tengah</p>

                    <div class="search-box">
                        <form action="{{ route('wisata.index') }}" method="GET">
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control border-start-0" name="q"
                                            placeholder="Cari destinasi wisata...">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select" name="kategori">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($kategori as $kat)
                                            <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-2"></i> Cari
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kategori Wisata -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-heading section-heading-center d-inline-block">Kategori Wisata</h2>
                <p class="text-muted mt-4">Pilih kategori wisata yang sesuai keinginan Anda</p>
            </div>

            <div class="row g-4">
                @foreach ($kategori as $kat)
                    <div class="col-lg-3 col-md-4 col-6">
                        <a href="{{ url('/kategori/' . $kat->slug) }}" class="text-decoration-none">
                            <div class="category-card">
                                <div class="category-icon">
                                    @if ($kat->ikon)
                                        <img src="{{ asset('storage/' . $kat->ikon) }}" alt="{{ $kat->nama }}">
                                    @else
                                        <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
                                    @endif
                                </div>
                                <h5 class="mt-3">{{ $kat->nama }}</h5>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Wisata Populer -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h2 class="section-heading mb-0">Wisata Populer</h2>
                <a href="{{ route('wisata.index') }}" class="btn btn-outline-primary">Lihat Semua</a>
            </div>

            <div class="row g-4">
                @foreach ($wisata_populer as $wisata)
                    <div class="col-lg-4 col-md-6">
                        <div class="card-destination h-100">
                            <div class="position-relative">
                                <img src="{{ $wisata->gambarUtama ? $wisata->gambarUtama->url : asset('images/placeholder-wisata.jpg') }}"
                                    class="card-img-top" alt="{{ $wisata->nama }}">
                                <div class="position-absolute top-0 end-0 m-3">
                                    <span class="badge bg-primary rounded-pill">
                                        <i class="fas fa-eye me-1"></i> {{ $wisata->jumlah_dilihat }}
                                    </span>
                                </div>
                            </div>
                            <div class="destination-info">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge-category">{{ $wisata->kategori->first()->nama ?? 'Umum' }}</span>
                                    <div class="rating-stars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $wisata->rata_rata_rating)
                                                <i class="fas fa-star"></i>
                                            @elseif($i - 0.5 <= $wisata->rata_rata_rating)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                        <span class="ms-1 text-muted small">({{ $wisata->ulasan->count() }})</span>
                                    </div>
                                </div>
                                <h5 class="card-title">{{ $wisata->nama }}</h5>
                                <p class="card-text text-muted mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i> {{ Str::limit($wisata->alamat, 50) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    @if ($wisata->harga_tiket > 0)
                                        <p class="mb-0 fw-bold">Rp {{ number_format($wisata->harga_tiket, 0, ',', '.') }}
                                        </p>
                                    @else
                                        <p class="mb-0 fw-bold text-success">Gratis</p>
                                    @endif
                                    <a href="{{ route('wisata.detail', $wisata->slug) }}"
                                        class="btn btn-sm btn-primary">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Event Mendatang -->
    @if ($event_mendatang && $event_mendatang->count() > 0)
        <section class="py-5">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h2 class="section-heading mb-0">Event Mendatang</h2>
                </div>

                <div class="row g-4">
                    @foreach ($event_mendatang as $event)
                        <div class="col-lg-4 col-md-6">
                            <div class="event-card">
                                <div class="event-date">
                                    <div class="day">{{ $event->tanggal_mulai->format('d') }}</div>
                                    <div class="month">{{ $event->tanggal_mulai->format('M Y') }}</div>
                                </div>
                                <div class="event-info">
                                    <h5>{{ $event->nama }}</h5>
                                    <p class="text-muted">
                                        <i class="fas fa-map-marker-alt me-2"></i> {{ $event->wisata->nama }}
                                    </p>
                                    <p>{{ Str::limit($event->deskripsi, 100) }}</p>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted small">
                                            <i class="far fa-calendar me-1"></i>
                                            {{ $event->tanggal_mulai->format('d M') }} -
                                            {{ $event->tanggal_selesai->format('d M Y') }}
                                        </span>
                                        <a href="#" class="btn btn-sm btn-outline-primary">Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Wisata Rekomendasi -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h2 class="section-heading mb-0">Rekomendasi Untuk Anda</h2>
            </div>

            <div class="row g-4">
                @foreach ($wisata_rekomendasi as $wisata)
                    <div class="col-lg-4 col-md-6">
                        <div class="card-destination h-100">
                            <div class="position-relative">
                                <img src="{{ $wisata->gambarUtama ? $wisata->gambarUtama->url : asset('images/placeholder-wisata.jpg') }}"
                                    class="card-img-top" alt="{{ $wisata->nama }}">
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-danger rounded-pill">
                                        <i class="fas fa-heart me-1"></i> Top
                                    </span>
                                </div>
                            </div>
                            <div class="destination-info">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge-category">{{ $wisata->kategori->first()->nama ?? 'Umum' }}</span>
                                    <div class="rating-stars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $wisata->rata_rata_rating)
                                                <i class="fas fa-star"></i>
                                            @elseif($i - 0.5 <= $wisata->rata_rata_rating)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                        <span class="ms-1 text-muted small">({{ $wisata->ulasan->count() }})</span>
                                    </div>
                                </div>
                                <h5 class="card-title">{{ $wisata->nama }}</h5>
                                <p class="card-text text-muted mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i> {{ Str::limit($wisata->alamat, 50) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    @if ($wisata->harga_tiket > 0)
                                        <p class="mb-0 fw-bold">Rp {{ number_format($wisata->harga_tiket, 0, ',', '.') }}
                                        </p>
                                    @else
                                        <p class="mb-0 fw-bold text-success">Gratis</p>
                                    @endif
                                    <a href="{{ route('wisata.detail', $wisata->slug) }}"
                                        class="btn btn-sm btn-primary">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Call to action -->
    <section class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 text-lg-start text-center">
                    <h2 class="mb-3">Ingin menjelajahi lebih banyak destinasi?</h2>
                    <p class="mb-0">Temukan semua destinasi wisata menarik di Kabupaten Hulu Sungai Tengah.</p>
                </div>
                <div class="col-lg-4 text-lg-end text-center mt-4 mt-lg-0">
                    <a href="{{ route('wisata.index') }}" class="btn btn-light btn-lg">Jelajahi Sekarang</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Optional: Add animation effects when scrolling
        document.addEventListener('DOMContentLoaded', function() {
            const animateOnScroll = function() {
                const elements = document.querySelectorAll('.category-card, .card-destination, .event-card');

                elements.forEach(element => {
                    const elementPosition = element.getBoundingClientRect().top;
                    const screenPosition = window.innerHeight;

                    if (elementPosition < screenPosition) {
                        element.classList.add('animate__animated', 'animate__fadeInUp');
                    }
                });
            };

            window.addEventListener('scroll', animateOnScroll);
            animateOnScroll(); // Run once on page load
        });
    </script>
@endpush
