@extends('layouts.frontend')

@section('title', 'Event Wisata - Miamories')

@push('styles')
<style>
    .event-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 80px 0 60px;
        color: white;
        margin-bottom: 40px;
    }

    .event-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .event-header p {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .filter-section {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }

    .filter-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 20px;
        color: #2d3748;
    }

    .event-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .event-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }

    .event-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.12);
    }

    .event-image {
        position: relative;
        height: 200px;
        overflow: hidden;
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
        background: rgba(255, 255, 255, 0.95);
        color: #667eea;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-align: center;
        min-width: 60px;
        backdrop-filter: blur(10px);
    }

    .event-status {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        background: rgba(40, 167, 69, 0.9);
        color: white;
        backdrop-filter: blur(10px);
    }

    .event-content {
        padding: 20px;
    }

    .event-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 12px;
        color: #2d3748;
        line-height: 1.3;
        min-height: 60px;
    }

    .event-location {
        display: flex;
        align-items: center;
        color: #e53e3e;
        font-size: 14px;
        margin-bottom: 12px;
        font-weight: 500;
    }

    .event-location i {
        margin-right: 8px;
    }

    .event-description {
        color: #4a5568;
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 20px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 60px;
    }

    .event-meta {
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

    .event-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }

    .event-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        color: white;
    }

    .sidebar {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        height: fit-content;
        position: sticky;
        top: 20px;
    }

    .sidebar-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 20px;
        color: #2d3748;
    }

    .stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .stat-item:last-child {
        border-bottom: none;
    }

    .stat-label {
        color: #4a5568;
        font-size: 14px;
    }

    .stat-value {
        color: #667eea;
        font-weight: 700;
        font-size: 16px;
    }

    .popular-event {
        padding: 15px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .popular-event:last-child {
        border-bottom: none;
    }

    .popular-event h6 {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 5px;
        color: #2d3748;
    }

    .popular-event .text-small {
        font-size: 12px;
        color: #718096;
    }

    .no-events {
        text-align: center;
        padding: 80px 20px;
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

    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 40px;
    }

    .active-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
    }

    .filter-tag {
        background: #667eea;
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-tag .remove {
        background: rgba(255,255,255,0.3);
        border-radius: 50%;
        width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 10px;
    }

    @media (max-width: 768px) {
        .event-header h1 {
            font-size: 2rem;
        }
        
        .event-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .filter-section {
            padding: 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="event-header">
    <div class="container">
        <h1>Event Wisata</h1>
        <p>Temukan event-event menarik di berbagai destinasi wisata</p>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <!-- Filter Section -->
            <div class="filter-section">
                <h3 class="filter-title">Filter Event</h3>
                <form method="GET" id="filterForm">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Cari event atau lokasi..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <select name="bulan" class="form-select">
                                <option value="">Semua Bulan</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <select name="waktu" class="form-select">
                                <option value="">Semua Waktu</option>
                                <option value="mendatang" {{ request('waktu') == 'mendatang' ? 'selected' : '' }}>Mendatang</option>
                                <option value="berlangsung" {{ request('waktu') == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                                <option value="selesai" {{ request('waktu') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <button type="submit" class="btn event-btn w-100">Filter</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <select name="sort" class="form-select" onchange="this.form.submit()">
                                <option value="tanggal_terdekat" {{ request('sort') == 'tanggal_terdekat' ? 'selected' : '' }}>Tanggal Terdekat</option>
                                <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                <option value="nama" {{ request('sort') == 'nama' ? 'selected' : '' }}>Nama A-Z</option>
                            </select>
                        </div>
                    </div>
                </form>

                <!-- Active Filters -->
                @if(request()->filled(['search', 'bulan', 'waktu']))
                    <div class="active-filters">
                        @if(request('search'))
                            <div class="filter-tag">
                                Pencarian: "{{ request('search') }}"
                                <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="remove">×</a>
                            </div>
                        @endif
                        @if(request('bulan'))
                            <div class="filter-tag">
                                Bulan: {{ DateTime::createFromFormat('!m', request('bulan'))->format('F') }}
                                <a href="{{ request()->fullUrlWithQuery(['bulan' => null]) }}" class="remove">×</a>
                            </div>
                        @endif
                        @if(request('waktu'))
                            <div class="filter-tag">
                                Waktu: {{ ucfirst(request('waktu')) }}
                                <a href="{{ request()->fullUrlWithQuery(['waktu' => null]) }}" class="remove">×</a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Event Grid -->
            @if($events->count() > 0)
                <div class="mb-3">
                    <small class="text-muted">Menampilkan {{ $events->count() }} dari {{ $events->total() }} event</small>
                </div>
                
                <div class="event-grid">
                    @foreach($events as $event)
                        <div class="event-card">
                            <div class="event-image">
                                <img src="{{ $event->poster ? asset($event->poster) : asset('images/placeholder-event.jpg') }}" 
                                     alt="{{ $event->nama }}">
                                <div class="event-date-badge">
                                    {{ $event->tanggal_mulai->format('d M') }}
                                </div>
                                <div class="event-status">
                                    {{ ucfirst($event->status) }}
                                </div>
                            </div>
                            <div class="event-content">
                                <h3 class="event-title">{{ $event->nama }}</h3>
                                <p class="event-location">
                                    <i class="fas fa-map-marker-alt"></i> 
                                    {{ $event->wisata->nama }}
                                </p>
                                <p class="event-description">
                                    {{ Str::limit($event->deskripsi, 120) }}
                                </p>
                                <div class="event-meta">
                                    <div class="event-duration">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $event->tanggal_mulai->format('d M') }} - {{ $event->tanggal_selesai->format('d M Y') }}
                                    </div>
                                    <a href="{{ route('event.detail', $event->id) }}" class="event-btn">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $events->appends(request()->input())->links() }}
                </div>
            @else
                <div class="no-events">
                    <i class="fas fa-calendar-times"></i>
                    <h4>Tidak Ada Event</h4>
                    <p>Tidak ada event yang sesuai dengan filter yang Anda pilih.</p>
                    <a href="{{ route('event.index') }}" class="event-btn">Reset Filter</a>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="sidebar">
                <h3 class="sidebar-title">Statistik Event</h3>
                <div class="stat-item">
                    <span class="stat-label">Total Event</span>
                    <span class="stat-value">{{ $stats['total_event'] }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Event Mendatang</span>
                    <span class="stat-value">{{ $stats['event_mendatang'] }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Sedang Berlangsung</span>
                    <span class="stat-value">{{ $stats['event_berlangsung'] }}</span>
                </div>
            </div>

            @if($event_populer->count() > 0)
                <div class="sidebar mt-4">
                    <h3 class="sidebar-title">Event Populer</h3>
                    @foreach($event_populer as $event)
                        <div class="popular-event">
                            <h6>{{ Str::limit($event->nama, 50) }}</h6>
                            <div class="text-small">
                                <i class="fas fa-map-marker-alt"></i> {{ $event->wisata->nama }}
                            </div>
                            <div class="text-small">
                                <i class="fas fa-calendar-alt"></i> {{ $event->tanggal_mulai->format('d M Y') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto submit form when filter changes
    const filterInputs = document.querySelectorAll('#filterForm select, #filterForm input');
    
    filterInputs.forEach(input => {
        if (input.type !== 'submit') {
            input.addEventListener('change', function() {
                // Don't auto-submit for text inputs
                if (this.type !== 'text') {
                    document.getElementById('filterForm').submit();
                }
            });
        }
    });

    // Animate cards on scroll
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

    document.querySelectorAll('.event-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease';
        observer.observe(card);
    });
});
</script>
@endpush