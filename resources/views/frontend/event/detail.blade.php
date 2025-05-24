@extends('layouts.frontend')

@section('title', $event->nama . ' - Event Wisata')

@push('styles')
<style>
    .event-hero {
        position: relative;
        height: 400px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.8), rgba(118, 75, 162, 0.8));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-bottom: 40px;
        overflow: hidden;
    }

    .event-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: url('{{ $event->poster ? asset($event->poster) : asset("images/placeholder-event.jpg") }}');
        background-size: cover;
        background-position: center;
        opacity: 0.3;
        z-index: 1;
    }

    .event-hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
        max-width: 800px;
        padding: 0 20px;
    }

    .event-hero h1 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .event-hero-meta {
        display: flex;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .hero-meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 1.1rem;
        font-weight: 500;
    }

    .hero-meta-item i {
        font-size: 1.2rem;
    }

    .event-status-badge {
        display: inline-block;
        padding: 8px 20px;
        border-radius: 25px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 14px;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
    }

    .event-content {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        margin-bottom: 30px;
    }

    .event-details-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 40px;
        margin-bottom: 40px;
    }

    .event-description {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #4a5568;
        margin-bottom: 30px;
    }

    .event-info-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 25px;
        height: fit-content;
        position: sticky;
        top: 20px;
    }

    .info-card-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 20px;
        color: #2d3748;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
        padding: 12px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .info-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .info-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
    }

    .info-content h6 {
        margin: 0 0 5px 0;
        color: #2d3748;
        font-weight: 600;
        font-size: 14px;
    }

    .info-content p {
        margin: 0;
        color: #4a5568;
        font-size: 14px;
    }

    .event-poster {
        text-align: center;
        margin-bottom: 30px;
    }

    .event-poster img {
        max-width: 100%;
        height: 300px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .wisata-info {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 30px;
    }

    .wisata-info h4 {
        margin-bottom: 15px;
        font-weight: 700;
    }

    .wisata-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
    }

    .wisata-meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .btn-visit-wisata {
        background: rgba(255,255,255,0.2);
        color: white;
        border: 2px solid rgba(255,255,255,0.3);
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .btn-visit-wisata:hover {
        background: rgba(255,255,255,0.3);
        color: white;
        transform: translateY(-2px);
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 25px;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: #667eea;
    }

    .related-events {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }

    .related-event-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }

    .related-event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .related-event-image {
        height: 150px;
        overflow: hidden;
        position: relative;
    }

    .related-event-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .related-event-content {
        padding: 15px;
    }

    .related-event-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 8px;
        color: #2d3748;
    }

    .related-event-date {
        font-size: 12px;
        color: #718096;
        margin-bottom: 10px;
    }

    .related-event-location {
        font-size: 13px;
        color: #e53e3e;
        font-weight: 500;
    }

    .share-section {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        margin: 30px 0;
    }

    .share-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 15px;
    }

    .share-btn {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 18px;
    }

    .share-btn.facebook { background: #1877f2; }
    .share-btn.twitter { background: #1da1f2; }
    .share-btn.whatsapp { background: #25d366; }
    .share-btn.copy { background: #6c757d; }

    .share-btn:hover {
        transform: scale(1.1);
        color: white;
    }

    @media (max-width: 768px) {
        .event-hero h1 {
            font-size: 2rem;
        }
        
        .event-hero-meta {
            flex-direction: column;
            gap: 15px;
        }
        
        .event-details-grid {
            grid-template-columns: 1fr;
            gap: 30px;
        }
        
        .event-content {
            padding: 20px;
        }
        
        .related-events {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="event-hero">
    <div class="event-hero-content">
        <h1>{{ $event->nama }}</h1>
        <div class="event-hero-meta">
            <div class="hero-meta-item">
                <i class="fas fa-calendar-alt"></i>
                {{ $event->tanggal_mulai->format('d M') }} - {{ $event->tanggal_selesai->format('d M Y') }}
            </div>
            <div class="hero-meta-item">
                <i class="fas fa-map-marker-alt"></i>
                {{ $event->wisata->nama }}
            </div>
        </div>
        <div class="event-status-badge">
            {{ ucfirst($event->status) }}
        </div>
    </div>
</div>

<div class="container">
    <div class="event-details-grid">
        <div class="main-content">
            <div class="event-content">
                <h3 class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Tentang Event
                </h3>
                <div class="event-description">
                    {!! nl2br(e($event->deskripsi)) !!}
                </div>

                @if($event->poster)
                    <div class="event-poster">
                        <img src="{{ asset($event->poster) }}" alt="{{ $event->nama }}">
                    </div>
                @endif
            </div>

            <!-- Informasi Wisata -->
            <div class="wisata-info">
                <h4><i class="fas fa-map-marked-alt me-2"></i>Lokasi Event</h4>
                <h5>{{ $event->wisata->nama }}</h5>
                <p class="mb-3">{{ $event->wisata->alamat }}</p>
                
                <div class="wisata-meta">
                    @if($event->wisata->kategori->count() > 0)
                        <div class="wisata-meta-item">
                            <i class="fas fa-tags"></i>
                            @foreach($event->wisata->kategori as $kategori)
                                <span class="badge bg-light text-dark">{{ $kategori->nama }}</span>
                            @endforeach
                        </div>
                    @endif
                    
                    @if($event->wisata->harga_tiket)
                        <div class="wisata-meta-item">
                            <i class="fas fa-ticket-alt"></i>
                            @if($event->wisata->harga_tiket > 0)
                                Rp {{ number_format($event->wisata->harga_tiket, 0, ',', '.') }}
                            @else
                                Gratis
                            @endif
                        </div>
                    @endif
                </div>
                
                <a href="{{ route('wisata.detail', $event->wisata->slug) }}" class="btn-visit-wisata">
                    <i class="fas fa-external-link-alt me-2"></i>Kunjungi Wisata
                </a>
            </div>

            <!-- Share Section -->
            <div class="share-section">
                <h5>Bagikan Event Ini</h5>
                <p class="text-muted">Beritahu teman dan keluarga tentang event menarik ini</p>
                <div class="share-buttons">
                    <a href="#" class="share-btn facebook" onclick="shareToFacebook()">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="share-btn twitter" onclick="shareToTwitter()">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="share-btn whatsapp" onclick="shareToWhatsApp()">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="#" class="share-btn copy" onclick="copyToClipboard()">
                        <i class="fas fa-copy"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="sidebar-content">
            <div class="event-info-card">
                <h4 class="info-card-title">Detail Event</h4>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="info-content">
                        <h6>Tanggal Mulai</h6>
                        <p>{{ $event->tanggal_mulai->format('d F Y, H:i') }} WIB</p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="info-content">
                        <h6>Tanggal Selesai</h6>
                        <p>{{ $event->tanggal_selesai->format('d F Y, H:i') }} WIB</p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="info-content">
                        <h6>Durasi</h6>
                        <p>{{ $event->tanggal_mulai->diffInDays($event->tanggal_selesai) + 1 }} Hari</p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="info-content">
                        <h6>Lokasi</h6>
                        <p>{{ $event->wisata->nama }}</p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="info-content">
                        <h6>Status</h6>
                        <p>{{ ucfirst($event->status) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Lainnya di Wisata yang Sama -->
    @if($event_lainnya->count() > 0)
        <div class="event-content">
            <h3 class="section-title">
                <i class="fas fa-calendar-plus"></i>
                Event Lainnya di {{ $event->wisata->nama }}
            </h3>
            <div class="related-events">
                @foreach($event_lainnya as $related)
                    <div class="related-event-card">
                        <div class="related-event-image">
                            <img src="{{ $related->poster ? asset($related->poster) : asset('images/placeholder-event.jpg') }}" 
                                 alt="{{ $related->nama }}">
                        </div>
                        <div class="related-event-content">
                            <h5 class="related-event-title">{{ Str::limit($related->nama, 50) }}</h5>
                            <div class="related-event-date">
                                <i class="fas fa-calendar-alt"></i>
                                {{ $related->tanggal_mulai->format('d M Y') }}
                            </div>
                            <div class="related-event-location">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $related->wisata->nama }}
                            </div>
                            <a href="{{ route('event.detail', $related->id) }}" class="btn btn-outline-primary btn-sm mt-2">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function shareToFacebook() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent('{{ $event->nama }}');
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank', 'width=600,height=400');
}

function shareToTwitter() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent('{{ $event->nama }} - {{ Str::limit($event->deskripsi, 100) }}');
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank', 'width=600,height=400');
}

function shareToWhatsApp() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent('{{ $event->nama }} - {{ Str::limit($event->deskripsi, 100) }} ' + url);
    window.open(`https://wa.me/?text=${text}`, '_blank');
}

function copyToClipboard() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        alert('Link berhasil disalin ke clipboard!');
    });
}

// Smooth scroll for internal links
document.addEventListener('DOMContentLoaded', function() {
    // Add animation to cards on scroll
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

    document.querySelectorAll('.related-event-card, .event-content').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease';
        observer.observe(card);
    });
});
</script>
@endpush