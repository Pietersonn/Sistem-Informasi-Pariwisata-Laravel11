@extends('layouts.frontend')

@section('title', 'Wisata Kabupaten Hulu Sungai Tengah')

@push('styles')
    <style>
        .hero-section {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset('images/hero-image.jpg') }}');
            background-size: cover;
            background-position: center;
            padding: 150px 0;
            color: white;
        }

        .search-box {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        .category-card {
            transition: all 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .category-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--primary-color);
        }

        .category-card img {
            height: 200px;
            object-fit: cover;
        }

        .featured-destinations {
            background-color: #f8f9fa;
            padding: 80px 0;
        }

        .card-destination {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
        }

        .card-destination:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .card-destination img {
            height: 240px;
            object-fit: cover;
        }

        .rating {
            color: #ffc107;
        }

        .testimonial-section {
            background-color: white;
            padding: 80px 0;
        }

        .testimonial-card {
            padding: 30px;
            border-radius: 10px;
            background: white;
            box-shadow: var(--card-shadow);
        }

        .testimonial-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
        }

        .city-card {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: var(--card-shadow);
        }

        .city-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .city-card:hover img {
            transform: scale(1.05);
        }

        .city-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0));
            color: white;
        }

        .steps-section {
            padding: 80px 0;
            background-color: #f8f9fa;
        }

        .step-card {
            text-align: center;
            padding: 30px;
            border-radius: 10px;
            background: white;
            box-shadow: var(--card-shadow);
            height: 100%;
        }

        .step-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #e6f7ff;
            border-radius: 50%;
            color: var(--primary-color);
            font-size: 2rem;
        }
    </style>
@endpush

@section('content')
    <!-- Hero Section with Search -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Jelajahi Keindahan Kabupaten HST</h1>
            <p class="lead mb-5">Temukan destinasi wisata menakjubkan dan pengalaman tak terlupakan di Hulu Sungai Tengah</p>

            <div class="search-box">
                <form action="{{ route('wisata.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <input type="text" class="form-control form-control-lg" name="q"
                                placeholder="Cari destinasi wisata...">
                        </div>
                        <div class="col-md-4">
                            <select class="form-select form-select-lg" name="kategori">
                                <option value="">Semua Kategori</option>
                                @foreach ($kategori as $kat)
                                    <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-search me-2"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Featured Cities -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Temukan Properti di Kota Ini</h2>

            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="city-card">
                        <img src="{{ asset('images/cities/chicago.jpg') }}" alt="Chicago">
                        <div class="city-info">
                            <h4 class="mb-0">Chicago</h4>
                            <p class="mb-0">5 Properti</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="city-card">
                        <img src="{{ asset('images/cities/los-angeles.jpg') }}" alt="Los Angeles">
                        <div class="city-info">
                            <h4 class="mb-0">Los Angeles</h4>
                            <p class="mb-0">3 Properti</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="city-card">
                        <img src="{{ asset('images/cities/miami.jpg') }}" alt="Miami">
                        <div class="city-info">
                            <h4 class="mb-0">Miami</h4>
                            <p class="mb-0">4 Properti</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="city-card">
                        <img src="{{ asset('images/cities/new-york.jpg') }}" alt="New York">
                        <div class="city-info">
                            <h4 class="mb-0">New York</h4>
                            <p class="mb-0">6 Properti</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="steps-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="steps-image mb-4 mb-md-0">
                        <div class="row">
                            <div class="col-6 mb-4">
                                <img src="{{ asset('images/steps/step1.jpg') }}" alt="Finding Home"
                                    class="img-fluid rounded shadow">
                            </div>
                            <div class="col-6">
                                <img src="{{ asset('images/steps/step2.jpg') }}" alt="Happy Family"
                                    class="img-fluid rounded shadow">
                            </div>
                            <div class="col-12 mt-4">
                                <img src="{{ asset('images/steps/step3.jpg') }}" alt="Modern Home"
                                    class="img-fluid rounded shadow">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="ps-md-5">
                        <h2>How It Works?</h2>
                        <h3 class="mb-4">Find a perfect home</h3>
                        <p class="text-muted mb-5">Temukan rumah impian dengan mudah. Layanan kami menyediakan berbagai
                            properti yang sesuai dengan kebutuhan Anda.</p>

                        <div class="d-flex mb-4">
                            <div
                                class="step-icon-sm bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i class="fas fa-search"></i>
                            </div>
                            <div>
                                <h5>Find Real Estate</h5>
                                <p class="text-muted">Cari properti yang sesuai dengan kebutuhan Anda</p>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div
                                class="step-icon-sm bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div>
                                <h5>Meet Realtor</h5>
                                <p class="text-muted">Bertemu dengan agen properti profesional</p>
                            </div>
                        </div>

                        <div class="d-flex">
                            <div
                                class="step-icon-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i class="fas fa-key"></i>
                            </div>
                            <div>
                                <h5>Take The Keys</h5>
                                <p class="text-muted">Dapatkan kunci dan nikmati rumah baru Anda</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2>Apa yang Pelanggan Kami Katakan?</h2>
                    <div class="d-flex mb-3">
                        <h3 class="me-3">10M+</h3>
                        <p class="text-muted">Happy People</p>
                    </div>
                    <div class="d-flex">
                        <h3 class="me-3">4.8</h3>
                        <p class="text-muted">Overall Rating</p>
                    </div>
                    <div class="mt-4 d-flex">
                        <img src="{{ asset('images/testimonials/user1.jpg') }}" alt="User"
                            class="rounded-circle me-2" width="40" height="40">
                        <blockquote class="blockquote">
                            <p class="mb-0">"Layanan yang sangat memuaskan! Saya bisa menemukan properti sesuai keinginan
                                dengan cepat."</p>
                            <footer class="blockquote-footer mt-2">Cameron Williamson</footer>
                        </blockquote>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-sm btn-light rounded-circle me-2">&larr;</button>
                        <button class="btn btn-sm btn-light rounded-circle">&rarr;</button>
                    </div>
                </div>
                <div class="col-md-6 mt-4 mt-md-0">
                    <img src="{{ asset('images/testimonials/family.jpg') }}" alt="Happy Family"
                        class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>

    <!-- Partner Logos -->
    <section class="py-5 bg-light">
        <div class="container">
            <p class="text-center text-muted mb-5">Thousands of world's leading companies trust Space</p>
            <div class="row justify-content-center align-items-center">
                <div class="col-md-2 col-6 text-center mb-4 mb-md-0">
                    <img src="{{ asset('images/partners/amazon.png') }}" alt="Amazon" height="30">
                </div>
                <div class="col-md-2 col-6 text-center mb-4 mb-md-0">
                    <img src="{{ asset('images/partners/amd.png') }}" alt="AMD" height="30">
                </div>
                <div class="col-md-2 col-6 text-center mb-4 mb-md-0">
                    <img src="{{ asset('images/partners/cisco.png') }}" alt="Cisco" height="30">
                </div>
                <div class="col-md-2 col-6 text-center mb-4 mb-md-0">
                    <img src="{{ asset('images/partners/dropbox.png') }}" alt="Dropbox" height="30">
                </div>
                <div class="col-md-2 col-6 text-center">
                    <img src="{{ asset('images/partners/spotify.png') }}" alt="Spotify" height="30">
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Destinations -->
    <section class="featured-destinations">
        <div class="container">
            <h2 class="text-center mb-5">Wisata Populer</h2>

            <div class="row">
                @foreach ($wisata_populer as $wisata)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card card-destination h-100">
                            <img src="{{ $wisata->gambar->first()->url ?? asset('images/placeholder-wisata.jpg') }}"
                                class="card-img-top" alt="{{ $wisata->nama }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-primary">{{ $wisata->kategori->first()->nama ?? 'Umum' }}</span>
                                    <div class="rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $wisata->rata_rata_rating)
                                                <i class="fas fa-star"></i>
                                            @elseif($i - 0.5 <= $wisata->rata_rata_rating)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                        <span class="ms-1 text-muted">({{ $wisata->ulasan->count() }})</span>
                                    </div>
                                </div>
                                <h5 class="card-title">{{ $wisata->nama }}</h5>
                                <p class="card-text text-muted mb-3">
                                    <i class="fas fa-map-marker-alt me-1"></i> {{ Str::limit($wisata->alamat, 50) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    @if ($wisata->harga_tiket > 0)
                                        <p class="mb-0 fw-bold">Rp {{ number_format($wisata->harga_tiket, 0, ',', '.') }}
                                        </p>
                                    @else
                                        <p class="mb-0 fw-bold text-success">Gratis</p>
                                    @endif
                                    <a href="{{ route('wisata.detail', $wisata->slug) }}"
                                        class="btn btn-outline-primary">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('wisata.index') }}" class="btn btn-primary">Lihat Semua Destinasi</a>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Kategori Wisata</h2>

            <div class="row">
                @foreach ($kategori as $kat)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card category-card h-100">
                            <img src="{{ asset('storage/' . $kat->ikon) ?? asset('images/kategori/' . $kat->slug . '.jpg') }}"
                                class="card-img-top" alt="{{ $kat->nama }}">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $kat->nama }}</h5>
                                <p class="card-text text-muted">{{ Str::limit($kat->deskripsi, 60) }}</p>
                                <a href="{{ route('kategori.detail', $kat->slug) }}"
                                    class="btn btn-outline-primary">Jelajahi</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
