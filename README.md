error pada header route register gabisa di akses sama routes/web.php
tampilan home masi sampah ya allah

    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Jelajahi Keindahan Kabupaten Hulu Sungai Tengah</h1>
            <p class="hero-subtitle">Temukan destinasi wisata menakjubkan dan pengalaman tak terlupakan</p>
            
            <div class="search-box">
                <form action="{{ route('wisata.index') }}" method="GET" class="search-form">
                    <div class="search-input">
                        <input type="text" name="q" placeholder="Cari Destinasi Wisata...">
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


detail.blade.php
 <!-- Upcoming Events -->
            @if($eventMendatang && $eventMendatang->count() > 0)
            <div class="wisata-info-box">
                <h4><i class="fas fa-calendar-alt me-2"></i>Event Mendatang</h4>
                @foreach($eventMendatang as $event)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $event->nama }}</h5>
                        <div class="text-muted mb-2">
                            <i class="far fa-calendar me-1"></i>
                            {{ $event->tanggal_mulai->format('d M Y') }} - {{ $event->tanggal_selesai->format('d M Y') }}
                        </div>
                        <p class="card-text">{{ Str::limit($event->deskripsi, 100) }}</p>
                        <a href="#" class="btn btn-sm btn-outline-primary">Detail Event</a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
            
            <!-- Nearby Places -->
            @if($wisataTerdekat && $wisataTerdekat->count() > 0)
            <div class="wisata-info-box">
                <h4><i class="fas fa-map-signs me-2"></i>Wisata Terdekat</h4>
                @foreach($wisataTerdekat as $nearby)
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-4">
                            <img src="{{ $nearby->gambarUtama ? asset($nearby->gambarUtama->file_gambar) : asset('images/placeholder-wisata.jpg') }}" 
                                 class="img-fluid rounded-start" alt="{{ $nearby->nama }}"
                                 style="height: 100%; object-fit: cover;">
                        </div>
                        <div class="col-8">
                            <div class="card-body p-2">
                                <h6 class="card-title mb-1">{{ $nearby->nama }}</h6>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="rating-stars" style="font-size: 0.8rem;">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= $nearby->rata_rata_rating ? '' : '-o' }}"></i>
                                        @endfor
                                    </div>
                                    <small class="text-muted">{{ $nearby->jarak }} km</small>
                                </div>
                                <a href="{{ route('wisata.detail', $nearby->slug) }}" class="btn btn-sm btn-outline-primary mt-1">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>

tambahkan field latitude longitude di create.blade