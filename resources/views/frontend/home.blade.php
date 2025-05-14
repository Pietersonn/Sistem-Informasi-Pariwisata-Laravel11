@extends('layouts.frontend')

@section('title', 'Wisata Kabupaten Hulu Sungai Tengah')

@push('styles')
    <style>
        .hero-section {
            background-image: url('{{ asset('images/hero-image.png') }}');
            background-size: cover;
            background-position: center;
            height: 780px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #fff;
            padding: 0 20px;
        }

    </style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Jelajahi Keindahan Kabupaten Hulu Sungai Tengah</h1>
            <p class="hero-subtitle">Temukan destinasi wisata menakjubkan dan pengalaman tak terlupakan</p>

            <div class="search-box">
                <form action="{{ route('wisata.index') }}" method="GET" class="search-form">
                    <div class="search-input">
                        <input type="text" name="q" placeholder="searching for memories...">
                    </div>
                    <button type="submit" class="search-button">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <div class="kategori-section">
                <h2 class="kategori-title">Kategori</h2>
                <div class="kategori-container">
                    <a href="{{ url('/kategori/alam') }}" class="kategori-item">
                        <div class="kategori-icon">
                            <i class="fas fa-tree"></i>
                        </div>
                        <span class="kategori-name">Alam</span>
                    </a>
                    <a href="{{ url('/kategori/budaya') }}" class="kategori-item">
                        <div class="kategori-icon">
                            <i class="fas fa-landmark"></i>
                        </div>
                        <span class="kategori-name">Budaya</span>
                    </a>
                    <a href="{{ url('/kategori/religi') }}" class="kategori-item">
                        <div class="kategori-icon">
                            <i class="fas fa-place-of-worship"></i>
                        </div>
                        <span class="kategori-name">Religi</span>
                    </a>
                    <a href="{{ url('/kategori/kuliner') }}" class="kategori-item">
                        <div class="kategori-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <span class="kategori-name">Kuliner</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Wisata Populer -->
    <section class="section section-light">
        <div class="container">
            <div class="section-header">
                <div>
                    <h2 class="section-title1">Wisata Populer</h2>
                    <p class="section-subtitle">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
            </div>

            <div class="wisata-grid">
                @foreach ($wisata_populer as $wisata)
                    <div class="wisata-card">
                        <div class="wisata-image">
                            <img src="{{ $wisata->gambarUtama ? $wisata->gambarUtama->url : asset('images/placeholder-wisata.jpg') }}"
                                alt="{{ $wisata->nama }}">
                            @if ($wisata->kategori->first())
                                <div class="wisata-tags">
                                    <span class="tag">{{ $wisata->kategori->first()->nama }}</span>
                                </div>
                            @endif
                            <div class="wisata-stats">
                                <i class="fas fa-eye"></i> {{ $wisata->jumlah_dilihat }}
                            </div>
                        </div>
                        <div class="wisata-info">
                            <h3 class="wisata-title">{{ $wisata->nama }}</h3>
                            <p class="wisata-location">
                                <i class="fas fa-map-marker-alt"></i> {{ Str::limit($wisata->alamat, 50) }}
                            </p>
                            <div class="wisata-rating">
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
                                </div>
                                <span class="rating-count">({{ $wisata->ulasan->count() }})</span>
                            </div>
                            <div class="wisata-footer">
                                @if ($wisata->harga_tiket > 0)
                                    <p class="wisata-price">Rp {{ number_format($wisata->harga_tiket, 0, ',', '.') }}</p>
                                @else
                                    <p class="wisata-price free">Gratis</p>
                                @endif
                                <a href="{{ route('wisata.detail', $wisata->slug) }}" class="btn btn-primary view-details">
                                    <i class="fas fa-eye me-1"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    

    <!-- Call to Action -->
    <section class="cta-section">
        <div class="cta-container">
            <div class="cta-content">
                <h2>Ingin menjelajahi lebih banyak destinasi?</h2>
                <p>Temukan semua destinasi wisata menarik di Kabupaten Hulu Sungai Tengah.</p>
            </div>
            <a href="{{ route('wisata.index') }}" class="cta-button">Jelajahi Sekarang</a>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Optional: Add animation effects when scrolling
        document.addEventListener('DOMContentLoaded', function() {
            const animateOnScroll = function() {
                const elements = document.querySelectorAll('.kategori-item, .wisata-card');

                elements.forEach(element => {
                    const elementPosition = element.getBoundingClientRect().top;
                    const screenPosition = window.innerHeight;

                    if (elementPosition < screenPosition) {
                        element.style.opacity = '1';
                        element.style.transform = element.classList.contains('kategori-item') ?
                            'translateY(0)' : 'translateY(0)';
                    }
                });
            };

            // Initial styles for animation
            const elements = document.querySelectorAll('.kategori-item, .wisata-card');
            elements.forEach(element => {
                element.style.opacity = '0';
                element.style.transform = element.classList.contains('kategori-item') ? 'translateY(20px)' :
                    'translateY(20px)';
                element.style.transition = 'all 0.3s ease';
            });

            window.addEventListener('scroll', animateOnScroll);
            animateOnScroll(); // Run once on page load
        });
    </script>
@endpush
