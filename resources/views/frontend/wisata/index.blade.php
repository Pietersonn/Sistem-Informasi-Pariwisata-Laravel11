@extends('layouts.frontend')

@section('title', 'Jelajahi Destinasi Wisata - Miamories')

@section('content')
<div class="listing-header">
    <div class="container">
        <h1 class="listing-title">Jelajahi Semua Destinasi</h1>
        <p class="listing-subtitle">Temukan kenangan dan pengalaman baru di berbagai tempat menarik</p>
    </div>
</div>

<div class="listing-content">
    <div class="container">
        <div class="row">
            <!-- Filter Sidebar -->
            <div class="col-md-3">
                <div class="listing-filter">
                    <h5 class="filter-heading">Filter Pencarian</h5>
                    <form action="{{ route('wisata.index') }}" method="GET">
                        <!-- Kategori Filter -->
                        <div class="filter-item">
                            <label class="filter-item-label">Kategori</label>
                            <select name="kategori" class="filter-dropdown">
                                <option value="">Semua Kategori</option>
                                @foreach($kategori as $kat)
                                    <option value="{{ $kat->id }}" 
                                        {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                                        {{ $kat->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sorting -->
                        <div class="filter-item">
                            <label class="filter-item-label">Urutkan Berdasarkan</label>
                            <select name="sort" class="filter-dropdown">
                                <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                <option value="terpopuler" {{ request('sort') == 'terpopuler' ? 'selected' : '' }}>Terpopuler</option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                            </select>
                        </div>

                        <!-- Pencarian -->
                        <div class="filter-item">
                            <label class="filter-item-label">Cari Wisata</label>
                            <div class="filter-search">
                                <input type="text" name="q" class="filter-text-input" 
                                       placeholder="Nama wisata atau lokasi"
                                       value="{{ request('q') }}">
                                <button type="submit" class="filter-search-btn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="filter-apply-btn">Terapkan Filter</button>
                    </form>
                </div>
            </div>

            <!-- Daftar Wisata -->
            <div class="col-md-9">
                <!-- Informasi Hasil -->
                <div class="listing-result-info">
                    <span>Menampilkan {{ $wisata->count() }} dari total {{ $wisata->total() }} destinasi</span>
                </div>

                <!-- Grid Wisata -->
                <div class="listing-grid">
                    @forelse($wisata as $item)
                        <div class="listing-item">
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
                                        <span class="listing-item-price">Rp {{ number_format($item->harga_tiket, 0, ',', '.') }}</span>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animasi untuk kartu wisata
        const listingItems = document.querySelectorAll('.listing-item');
        
        listingItems.forEach((item, index) => {
            item.style.animationDelay = `${index * 0.1}s`;
            item.classList.add('fadeInUp');
        });
        
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