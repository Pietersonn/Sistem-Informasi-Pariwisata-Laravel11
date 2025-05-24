@extends('layouts.frontend')

@section('title', 'Event Wisata - Miamories')

@section('content')
    <div class="event-header">
        <div class="container">
            <h1>Event Wisata</h1>
            <p>Temukan event-event menarik di berbagai destinasi wisata</p>
            <div class="custom-filter-section">
                <form method="GET" id="filterForm">
                    <div class="custom-search-filter-row">
                        <div class="custom-search-filter-input">
                            <input type="text" name="search" class="custom-filter-text-input"
                                placeholder="Cari event atau lokasi..." value="{{ request('search') }}">
                        </div>
                        <div class="custom-search-filter-dropdown">
                            <select name="bulan" class="custom-filter-dropdown">
                                <option value="">Semua Bulan</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="custom-search-filter-dropdown">
                            <select name="waktu" class="custom-filter-dropdown">
                                <option value="">Semua Waktu</option>
                                <option value="mendatang" {{ request('waktu') == 'mendatang' ? 'selected' : '' }}>Mendatang
                                </option>
                                <option value="berlangsung" {{ request('waktu') == 'berlangsung' ? 'selected' : '' }}>
                                    Berlangsung</option>
                                <option value="selesai" {{ request('waktu') == 'selesai' ? 'selected' : '' }}>Selesai
                                </option>
                            </select>
                        </div>
                        <div class="custom-search-filter-dropdown">
                            <select name="sort" class="custom-filter-dropdown">
                                <option value="tanggal_terdekat"
                                    {{ request('sort') == 'tanggal_terdekat' ? 'selected' : '' }}>Tanggal Terdekat</option>
                                <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru
                                </option>
                                <option value="nama" {{ request('sort') == 'nama' ? 'selected' : '' }}>Nama A-Z</option>
                            </select>
                        </div>
                        <div class="custom-search-filter-button">
                            <button type="submit" class="custom-filter-search-btn">Filter</button>
                        </div>
                    </div>
                </form>
            </div>




            <!-- Active Filters -->
            @if (request()->filled(['search', 'bulan', 'waktu']))
                <div class="active-filters">
                    @if (request('search'))
                        <div class="filter-tag1">
                            Pencarian: "{{ request('search') }}"
                            <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="remove">×</a>
                        </div>
                    @endif
                    @if (request('bulan'))
                        <div class="filter-tag1">
                            Bulan: {{ DateTime::createFromFormat('!m', request('bulan'))->format('F') }}
                            <a href="{{ request()->fullUrlWithQuery(['bulan' => null]) }}" class="remove">×</a>
                        </div>
                    @endif
                    @if (request('waktu'))
                        <div class="filter-tag1">
                            Waktu: {{ ucfirst(request('waktu')) }}
                            <a href="{{ request()->fullUrlWithQuery(['waktu' => null]) }}" class="remove">×</a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-8">

                <!-- Event Grid -->
                @if ($events->count() > 0)
                    <div class="mb-3">
                        <small class="text-muted">Menampilkan {{ $events->count() }} dari {{ $events->total() }}
                            event</small>
                    </div>

                    <div class="event-grid">
                        @foreach ($events as $event)
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
                                            {{ $event->tanggal_mulai->format('d M') }} -
                                            {{ $event->tanggal_selesai->format('d M Y') }}
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
