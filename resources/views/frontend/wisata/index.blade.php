@extends('layouts.frontend')

@section('title', 'Jelajahi Destinasi Wisata - Miamories')

@section('content')
    <div class="listing-header">
        <div class="container">
            <h1 class="listing-title">Jelajahi Semua Destinasi</h1>
            <p class="listing-subtitle">Temukan kenangan dan pengalaman baru di berbagai tempat menarik</p>

            <!-- Filter Section Moved Inside listing-header -->
            <div class="listing-search-filter">
                <form action="{{ route('wisata.index') }}" method="GET" id="filterForm">
                    <div class="search-filter-row">
                        <div class="search-filter-input">
                            <input type="text" name="q" placeholder="Nama Wisata" value="{{ request('q') }}" class="filter-text-input">
                        </div>
                        <div class="search-filter-dropdown">
                            <select name="kategori" class="filter-dropdown" id="kategoriFilter">
                                <option value="">All Category</option>
                                @foreach ($kategori as $kat)
                                    <option value="{{ $kat->id }}"
                                        {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                                        {{ $kat->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="search-filter-dropdown">
                            <select name="sort" class="filter-dropdown" id="sortFilter">
                                <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                <option value="terpopuler" {{ request('sort') == 'terpopuler' ? 'selected' : '' }}>Terpopuler</option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                            </select>
                        </div>
                        <div class="search-filter-button">
                            <button type="submit" class="filter-search-btn">Search</button>
                        </div>
                    </div>
                </form>
            </div>

            @if (request()->filled('q') || request()->filled('kategori') || request()->filled('sort'))
                <div class="active-filters">
                    <span class="active-filters-label">Filter aktif:</span>
                    @if (request()->filled('q'))
                        <span class="active-filter-tag">
                            Pencarian: "{{ request('q') }}"
                            <a href="{{ route('wisata.index', request()->except('q')) }}" class="filter-remove">×</a>
                        </span>
                    @endif

                    @if (request()->filled('kategori'))
                        <span class="active-filter-tag">
                            Kategori: {{ $kategori->where('id', request('kategori'))->first()->nama ?? '' }}
                            <a href="{{ route('wisata.index', request()->except('kategori')) }}" class="filter-remove">×</a>
                        </span>
                    @endif

                   
                </div>
            @endif
        </div>
    </div>

    <!-- Rest of the content remains the same -->
    <div class="listing-content">
        <div class="container">
            <div class="row">
                <!-- Daftar Wisata -->
                <div class="col-12">
                    <!-- Informasi Hasil -->
                    <div class="listing-result-info">
                        <span>Menampilkan {{ $wisata->count() }} dari total {{ $wisata->total() }} destinasi</span>
                    </div>

                    <!-- Grid Wisata -->
                    <div class="listing-grid">
                        @forelse($wisata as $item)
                            <div class="listing-item" data-category="{{ $item->kategori->first()->id ?? '' }}">
                                <div class="listing-item-img">
                                    <img src="{{ $item->gambarUtama ? $item->gambarUtama->url : asset('images/placeholder-wisata.jpg') }}"
                                        alt="{{ $item->nama }}">
                                    <div class="listing-item-tag">
                                        <span>{{ $item->kategori->first()->nama ?? 'Umum' }}</span>
                                    </div>
                                    <div class="listing-item-views">
                                        <i class="fas fa-eye"></i> {{ $item->jumlah_dilihat }}
                                    </div>
                                </div>

                                <div class="listing-item-content">
                                    <h3 class="listing-item-title">{{ $item->nama }}</h3>
                                    <p class="listing-item-desc">{{ Str::limit($item->deskripsi, 100) }}</p>

                                    <div class="listing-item-details">
                                        <div class="listing-item-location">
                                            <i class="fas fa-map-marker-alt"></i> {{ Str::limit($item->alamat, 30) }}
                                        </div>
                                        <div class="listing-item-rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $item->rata_rata_rating)
                                                    <i class="fas fa-star"></i>
                                                @elseif($i - 0.5 <= $item->rata_rata_rating)
                                                    <i class="fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                            <span>({{ $item->ulasan->count() }})</span>
                                        </div>
                                    </div>

                                    <div class="listing-item-footer">
                                        @if ($item->harga_tiket > 0)
                                            <span class="listing-item-price">Rp
                                                {{ number_format($item->harga_tiket, 0, ',', '.') }}</span>
                                        @else
                                            <span class="listing-item-price free">Gratis</span>
                                        @endif
                                        <a href="{{ route('wisata.detail', $item->slug) }}" class="listing-item-btn">
                                            Lihat Detail <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="listing-empty">
                                <i class="fas fa-search-minus"></i>
                                <p>Tidak ada wisata yang ditemukan</p>
                                <span>Silakan coba dengan kata kunci atau filter yang berbeda</span>
                                <a href="{{ route('wisata.index') }}" class="btn btn-primary mt-3">Reset Filter</a>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="listing-pagination">
                        {{ $wisata->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/wisata-search.css') }}">
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animasi untuk kartu wisata
            const listingItems = document.querySelectorAll('.listing-item');

            listingItems.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
                item.classList.add('fadeInUp');
            });

            // Auto-submit form ketika filter berubah
            const filterForm = document.getElementById('filterForm');
            const sortFilter = document.getElementById('sortFilter');
            const kategoriFilter = document.getElementById('kategoriFilter');

            if (sortFilter) {
                sortFilter.addEventListener('change', function() {
                    filterForm.submit();
                });
            }

            if (kategoriFilter) {
                kategoriFilter.addEventListener('change', function() {
                    filterForm.submit();
                });
            }

            // Highlight filter aktif
            const currentFilters = new URLSearchParams(window.location.search);
            currentFilters.forEach((value, key) => {
                const element = document.querySelector(`[name="${key}"]`);
                if (element) {
                    element.classList.add('filter-active');
                }
            });
        });
    </script>
@endpush