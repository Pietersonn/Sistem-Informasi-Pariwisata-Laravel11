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

        /* Event Section Styles */
        .event-card {
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
        }

        .event-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .event-image {
            position: relative;
            overflow: hidden;
            height: 200px;
        }

        .event-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .event-card:hover .event-image img {
            transform: scale(1.05);
        }

        .event-date-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            min-width: 60px;
        }

        .event-status {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .event-status.aktif {
            background: rgba(40, 167, 69, 0.9);
            color: white;
        }

        .event-status.selesai {
            background: rgba(108, 117, 125, 0.9);
            color: white;
        }

        .event-info {
            padding: 20px;
        }

        .event-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #2d3748;
            line-height: 1.3;
        }

        .event-location {
            display: flex;
            align-items: center;
            color: #718096;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .event-location i {
            margin-right: 8px;
            color: #e53e3e;
        }

        .event-description {
            color: #4a5568;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .event-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
        }

        .event-duration {
            font-size: 12px;
            color: #718096;
            font-weight: 500;
        }

        .view-event {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .view-event:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .no-events {
            text-align: center;
            padding: 60px 20px;
            color: #718096;
        }

        .no-events i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .no-events h4 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #4a5568;
        }

        .no-events p {
            font-size: 1rem;
            margin-bottom: 20px;
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

    <!-- Event Mendatang Section -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <div>
                    <h2 class="section-title1">Event Mendatang</h2>
                    <p class="section-subtitle">Jangan lewatkan event-event menarik di destinasi wisata terbaik</p>
                </div>
                @if(count($event_mendatang) > 0)
                    <a href="{{ route('event.index') }}" class="view-all">
                        Lihat Semua Event <i class="fas fa-arrow-right"></i>
                    </a>
                @endif
            </div>

            @if(count($event_mendatang) > 0)
                <div class="wisata-grid">
                    @foreach($event_mendatang as $event)
                        <div class="event-card">
                            <div class="event-image">
                                <img src="{{ $event->poster ? asset($event->poster) : asset('images/placeholder-event.jpg') }}" 
                                     alt="{{ $event->nama }}">
                                <div class="event-date-badge">
                                    {{ $event->tanggal_mulai->format('d M') }}
                                </div>
                                <div class="event-status {{ $event->status }}">
                                    {{ ucfirst($event->status) }}
                                </div>
                            </div>
                            <div class="event-info">
                                <h3 class="event-title">{{ $event->nama }}</h3>
                                <p class="event-location">
                                    <i class="fas fa-map-marker-alt"></i> 
                                    {{ $event->wisata->nama }}
                                </p>
                                <p class="event-description">
                                    {{ Str::limit($event->deskripsi, 100) }}
                                </p>
                                <div class="event-footer">
                                    <div class="event-duration">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $event->tanggal_mulai->format('d M') }} - {{ $event->tanggal_selesai->format('d M Y') }}
                                    </div>
                                    <a href="{{ route('event.detail', $event->id) }}" class="view-event">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-events">
                    <i class="fas fa-calendar-times"></i>
                    <h4>Belum Ada Event</h4>
                    <p>Saat ini belum ada event yang dijadwalkan. Pantau terus untuk event-event menarik!</p>
                    <a href="{{ route('wisata.index') }}" class="cta-button">Jelajahi Wisata</a>
                </div>
            @endif
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
                const elements = document.querySelectorAll('.kategori-item, .wisata-card, .event-card');

                elements.forEach(element => {
                    const elementPosition = element.getBoundingClientRect().top;
                    const screenPosition = window.innerHeight;

                    if (elementPosition < screenPosition) {
                        element.style.opacity = '1';
                        element.style.transform = 'translateY(0)';
                    }
                });
            };

            // Initial styles for animation
            const elements = document.querySelectorAll('.kategori-item, .wisata-card, .event-card');
            elements.forEach(element => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';
                element.style.transition = 'all 0.3s ease';
            });

            window.addEventListener('scroll', animateOnScroll);
            animateOnScroll(); // Run once on page load
        });
    </script>
@endpush