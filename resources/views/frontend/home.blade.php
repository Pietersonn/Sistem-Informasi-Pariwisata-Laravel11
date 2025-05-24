@extends('layouts.frontend')

@section('title', 'Wisata Kabupaten Hulu Sungai Tengah')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
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

    /* Event Carousel Section */
    .event-carousel-section {
        padding: 20px 0;
        background-color: #f8f9fa;
    }

    .event-section-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .event-main-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 15px;
    }

    /* Carousel Container */
    .event-carousel-container {
        position: relative;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 60px;
    }

    /* Navigation Arrows */
    .carousel-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: #fff;
        border: none;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10;
        color: #667eea;
        font-size: 18px;
    }

    .carousel-nav:hover {
        background: #667eea;
        color: #fff;
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
    }

    .carousel-nav-prev {
        left: 0;
    }

    .carousel-nav-next {
        right: 0;
    }

    /* Carousel Track */
    .event-carousel-track {
        display: flex;
        gap: 25px;
        overflow-x: auto;
        scroll-behavior: smooth;
        padding: 10px 5px;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .event-carousel-track::-webkit-scrollbar {
        display: none;
    }

    /* Event Cards */
    .event-carousel-card {
        flex: 0 0 280px;
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .event-carousel-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
    }

    /* Card Image */
    .event-card-image {
        height: 350px;
        position: relative;
        overflow: hidden;
    }

    .event-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .event-carousel-card:hover .event-card-image img {
        transform: scale(1.05);
    }

    /* Placeholder Image */
    .event-placeholder-image {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.8);
        font-size: 3rem;
    }

    /* Card Content */
    .event-card-content {
        padding: 20px;
        text-align: left;
    }

    .event-count {
        font-size: 14px;
        color: #718096;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .event-card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2d3748;
        margin: 0;
        line-height: 1.3;
    }

    /* No Events State */
    .no-events-message {
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
        max-width: 500px;
        margin: 0 auto;
    }

    .no-events-icon {
        font-size: 4rem;
        color: #cbd5e0;
        margin-bottom: 20px;
    }

    .no-events-message h4 {
        font-size: 1.5rem;
        color: #4a5568;
        margin-bottom: 10px;
    }

    .no-events-message p {
        color: #718096;
        margin-bottom: 0;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .event-carousel-container {
            padding: 0 50px;
        }
        
        .event-carousel-card {
            flex: 0 0 260px;
        }
    }

    @media (max-width: 768px) {
        .event-main-title {
            font-size: 2rem;
        }
        
        .event-carousel-container {
            padding: 0 40px;
        }
        
        .carousel-nav {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }
        
        .event-carousel-card {
            flex: 0 0 240px;
        }
        
        .event-card-image {
            height: 160px;
        }
        
        .event-card-content {
            padding: 15px;
        }
    }

    @media (max-width: 480px) {
        .event-carousel-section {
            padding: 60px 0;
        }
        
        .event-carousel-container {
            padding: 0 20px;
        }
        
        .carousel-nav {
            display: none;
        }
        
        .event-carousel-card {
            flex: 0 0 200px;
        }
        
        .event-card-title {
            font-size: 1.1rem;
        }
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

        <!-- Event Section-->
    <section class="event-carousel-section">
        <div class="container">
            <div class="event-section-header">
                <h2 class="event-main-title">Event Wisata</h2>
            </div>

            @if(count($event_mendatang) > 0)
                <div class="event-carousel-container">
                    <!-- Navigation Arrows -->
                    <button class="carousel-nav carousel-nav-prev" id="prevBtn">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="carousel-nav carousel-nav-next" id="nextBtn">
                        <i class="fas fa-chevron-right"></i>
                    </button>

                    <!-- Carousel Track -->
                    <div class="event-carousel-track" id="eventCarousel">
                        @foreach($event_mendatang as $event)
                            <div class="event-carousel-card" data-event-id="{{ $event->id }}">
                                <div class="event-card-image">
                                    @if($event->poster && file_exists(public_path($event->poster)))
                                        <img src="{{ asset($event->poster) }}" alt="{{ $event->nama }}">
                                    @else
                                        <div class="event-placeholder-image">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="event-card-content">
                                    <div class="event-count">1 Event</div>
                                    <h3 class="event-card-title">{{ Str::limit($event->nama, 25) }}</h3>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="no-events-message">
                    <div class="no-events-icon">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <h4>Belum Ada Event</h4>
                    <p>Saat ini belum ada event yang dijadwalkan.</p>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Event Carousel JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        const carousel = document.getElementById('eventCarousel');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        
        if (!carousel || !prevBtn || !nextBtn) return;
        
        const cardWidth = 305; // 280px width + 25px gap
        let currentPosition = 0;
        const maxScroll = carousel.scrollWidth - carousel.clientWidth;
        
        // Next button functionality
        nextBtn.addEventListener('click', function() {
            const newPosition = Math.min(currentPosition + cardWidth * 2, maxScroll);
            carousel.scrollTo({
                left: newPosition,
                behavior: 'smooth'
            });
            currentPosition = newPosition;
            updateButtonStates();
        });
        
        // Previous button functionality
        prevBtn.addEventListener('click', function() {
            const newPosition = Math.max(currentPosition - cardWidth * 2, 0);
            carousel.scrollTo({
                left: newPosition,
                behavior: 'smooth'
            });
            currentPosition = newPosition;
            updateButtonStates();
        });
        
        // Update button states
        function updateButtonStates() {
            prevBtn.style.opacity = currentPosition <= 0 ? '0.5' : '1';
            prevBtn.style.pointerEvents = currentPosition <= 0 ? 'none' : 'auto';
            
            nextBtn.style.opacity = currentPosition >= maxScroll ? '0.5' : '1';
            nextBtn.style.pointerEvents = currentPosition >= maxScroll ? 'none' : 'auto';
        }
        
        // Handle manual scrolling
        carousel.addEventListener('scroll', function() {
            currentPosition = carousel.scrollLeft;
            updateButtonStates();
        });
        
        // Initial button state
        updateButtonStates();
        
        // Add click functionality to cards
        const eventCards = document.querySelectorAll('.event-carousel-card');
        eventCards.forEach(card => {
            card.addEventListener('click', function() {
                const eventId = this.dataset.eventId;
                if (eventId) {
                    window.location.href = `/event/${eventId}`;
                }
            });
        });

        // Animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Apply animation to cards
        document.querySelectorAll('.event-carousel-card, .wisata-card, .kategori-item').forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = `all 0.6s ease ${index * 0.1}s`;
            observer.observe(card);
        });
    });
</script>
@endpush