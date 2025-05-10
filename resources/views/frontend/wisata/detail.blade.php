@extends('layouts.frontend')

@section('title', $wisata->nama . ' - Detail Wisata')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<style>
    /* Gallery styling */
    .wisata-gallery {
        position: relative;
        overflow: hidden;
        border-radius: 15px;
        margin-bottom: 30px;
    }
    
    .carousel-item img {
        height: 500px;
        object-fit: cover;
        width: 100%;
    }
    
    /* Info box styling */
    .wisata-info-box {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        padding: 25px;
        margin-bottom: 25px;
    }
    
    .wisata-info-box h4 {
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 15px;
        margin-bottom: 20px;
        font-weight: 600;
        color: #333;
    }
    
    /* Rating styling */
    .rating-stars {
        color: #ffc107;
    }
    
    .rating-count {
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    /* Map styling */
    #map {
        height: 400px;
        width: 100%;
        border-radius: 10px;
    }
    
    /* Facilities styling */
    .facility-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .facility-item i {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #e9f7fe;
        color: #4A98CF;
        border-radius: 50%;
        margin-right: 10px;
    }
    
    /* Action buttons */
    .action-btn {
        width: 100%;
        padding: 12px;
        border-radius: 30px;
        font-weight: 600;
        margin-bottom: 10px;
    }
    
    /* Testimonials */
    .review-item {
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }
    
    .review-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    
    .reviewer-info {
        display: flex;
        align-items: center;
    }
    
    .reviewer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 10px;
    }
    
    .review-date {
        color: #6c757d;
        font-size: 0.8rem;
    }
</style>
@endpush

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Breadcrumb -->
        <div class="col-12 mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('wisata.index') }}">Destinasi</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $wisata->nama }}</li>
                </ol>
            </nav>
        </div>
        
        <!-- Main Content Column -->
        <div class="col-lg-8">
            <!-- Gallery -->
            <div class="wisata-gallery">
                <div id="wisataCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @forelse($wisata->gambar as $index => $gambar)
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                <img src="{{ asset($gambar->file_gambar) }}" class="d-block w-100" alt="{{ $wisata->nama }}">
                            </div>
                        @empty
                            <div class="carousel-item active">
                                <img src="{{ asset('images/placeholder-wisata.jpg') }}" class="d-block w-100" alt="{{ $wisata->nama }}">
                            </div>
                        @endforelse
                    </div>
                    @if(count($wisata->gambar) > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#wisataCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#wisataCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    @endif
                </div>
            </div>
            
            <!-- Wisata Title and Info -->
            <div class="wisata-info-box">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h2 class="mb-2">{{ $wisata->nama }}</h2>
                        <div class="d-flex align-items-center">
                            <div class="rating-stars me-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $wisata->rata_rata_rating)
                                        <i class="fas fa-star"></i>
                                    @elseif($i - 0.5 <= $wisata->rata_rata_rating)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                                <span class="rating-count ms-1">({{ $wisata->ulasan->count() }} ulasan)</span>
                            </div>
                            <span class="text-muted ms-3">
                                <i class="fas fa-eye"></i> {{ $wisata->jumlah_dilihat }} kali dilihat
                            </span>
                        </div>
                    </div>
                    <div class="text-end">
                        @if($wisata->harga_tiket > 0)
                            <div class="badge bg-primary p-2 fs-6">
                                Rp {{ number_format($wisata->harga_tiket, 0, ',', '.') }}
                            </div>
                        @else
                            <div class="badge bg-success p-2 fs-6">Gratis</div>
                        @endif
                    </div>
                </div>
                
                <div class="d-flex flex-wrap mb-3">
                    @foreach($wisata->kategori as $kategori)
                        <span class="badge bg-info me-2 mb-2 p-2">
                            <i class="fas fa-tag me-1"></i> {{ $kategori->nama }}
                        </span>
                    @endforeach
                </div>
                
                <div class="mb-3">
                    <h5><i class="fas fa-map-marker-alt text-danger me-2"></i>Lokasi</h5>
                    <p>{{ $wisata->alamat }}</p>
                    @if($wisata->link_gmaps)
                        <a href="{{ $wisata->link_gmaps }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-directions me-1"></i> Petunjuk Arah di Google Maps
                        </a>
                    @endif
                </div>
                
                <div class="mb-3">
                    <h5><i class="fas fa-clock text-success me-2"></i>Jam Operasional</h5>
                    @if($wisata->jam_buka && $wisata->jam_tutup)
                        <p class="mb-1">
                            <strong>Jam:</strong> {{ $wisata->jam_buka->format('H:i') }} - {{ $wisata->jam_tutup->format('H:i') }} WIB
                        </p>
                        <p>
                            <strong>Hari:</strong>
                            @if(is_array($wisata->hari_operasional) && count($wisata->hari_operasional) > 0)
                                {{ implode(', ', array_map('ucfirst', $wisata->hari_operasional)) }}
                            @else
                                Setiap hari
                            @endif
                        </p>
                        
                        <p class="mt-2">
                            <span class="badge {{ $wisata->sedangBuka() ? 'bg-success' : 'bg-danger' }} p-2">
                                <i class="fas fa-door-{{ $wisata->sedangBuka() ? 'open' : 'closed' }} me-1"></i>
                                {{ $wisata->sedangBuka() ? 'Buka Sekarang' : 'Tutup' }}
                            </span>
                        </p>
                    @else
                        <p class="text-muted">Informasi jam operasional tidak tersedia</p>
                    @endif
                </div>
            </div>
            
            <!-- Description -->
            <div class="wisata-info-box">
                <h4><i class="fas fa-info-circle me-2"></i>Deskripsi</h4>
                <p>{{ $wisata->deskripsi }}</p>
            </div>
            
            <!-- Facility -->
            <div class="wisata-info-box">
                <h4><i class="fas fa-list-check me-2"></i>Fasilitas</h4>
                @if(is_array($wisata->fasilitas) && count($wisata->fasilitas) > 0)
                    <div class="row">
                        @foreach($wisata->fasilitas as $fasilitas)
                            <div class="col-md-6">
                                <div class="facility-item">
                                    <i class="fas fa-check"></i>
                                    <span>{{ $fasilitas }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Tidak ada informasi fasilitas tersedia</p>
                @endif
            </div>
            
            <!-- Map -->
            <div class="wisata-info-box">
                <h4><i class="fas fa-map me-2"></i>Lokasi pada Peta</h4>
                <div id="map"></div>
            </div>
            
            <!-- Reviews -->
            <div class="wisata-info-box">
                <h4><i class="fas fa-comments me-2"></i>Ulasan Pengunjung ({{ $ulasan->count() }})</h4>
                
                @forelse($ulasan as $review)
                <div class="review-item">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <img src="{{ $review->pengguna->foto_profil_url ?? asset('images/default-avatar.png') }}" 
                                 alt="{{ $review->pengguna->name }}" 
                                 class="reviewer-avatar">
                            <div>
                                <h6 class="mb-0">{{ $review->pengguna->name }}</h6>
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="review-date">
                            {{ $review->created_at->format('d M Y') }}
                        </div>
                    </div>
                    <div class="review-content">
                        <p>{{ $review->komentar }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="fas fa-comment-slash fa-3x text-muted mb-3"></i>
                    <p>Belum ada ulasan untuk wisata ini</p>
                </div>
                @endforelse
                
                @if($ulasan->count() > 3)
                <div class="text-center mt-3">
                    <a href="#" class="btn btn-outline-primary">Lihat Semua Ulasan</a>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Action Box -->
            <div class="wisata-info-box">
                <h4>Aksi</h4>
                @auth
                    @if(!$sudahDifavorit)
                        <form action="{{ route('wisata.favorit', $wisata->slug) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary action-btn">
                                <i class="far fa-heart me-2"></i> Tambah ke Favorit
                            </button>
                        </form>
                    @else
                        <form action="{{ route('wisata.favorit.hapus', $wisata->slug) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger action-btn">
                                <i class="fas fa-heart-broken me-2"></i> Hapus dari Favorit
                            </button>
                        </form>
                    @endif
                    
                    <button type="button" class="btn btn-success action-btn" data-bs-toggle="modal" data-bs-target="#ulasanModal">
                        <i class="far fa-comment me-2"></i> Tulis Ulasan
                    </button>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <a href="{{ route('login') }}" class="alert-link">Login</a> untuk menambahkan wisata ke favorit atau menulis ulasan
                    </div>
                @endauth
                
                <div class="mt-3">
                    <a href="{{ $wisata->link_gmaps }}" target="_blank" class="btn btn-primary action-btn">
                        <i class="fas fa-map-marked-alt me-2"></i> Lihat di Google Maps
                    </a>
                    
                    <button class="btn btn-info action-btn" onclick="shareWisata()">
                        <i class="fas fa-share-alt me-2"></i> Bagikan
                    </button>
                </div>
            </div>
            
            <!-- Contact Info -->
            <div class="wisata-info-box">
                <h4><i class="fas fa-address-card me-2"></i>Informasi Kontak</h4>
                <ul class="list-unstyled">
                    @if($wisata->kontak)
                    <li class="mb-2">
                        <i class="fas fa-phone text-success me-2"></i>
                        <a href="tel:{{ $wisata->kontak }}">{{ $wisata->kontak }}</a>
                    </li>
                    @endif
                    
                    @if($wisata->email)
                    <li class="mb-2">
                        <i class="fas fa-envelope text-danger me-2"></i>
                        <a href="mailto:{{ $wisata->email }}">{{ $wisata->email }}</a>
                    </li>
                    @endif
                    
                    @if($wisata->website)
                    <li class="mb-2">
                        <i class="fas fa-globe text-primary me-2"></i>
                        <a href="{{ $wisata->website }}" target="_blank">{{ $wisata->website }}</a>
                    </li>
                    @endif
                </ul>
                
                <!-- Social Media -->
                @if($wisata->instagram || $wisata->facebook || $wisata->twitter)
                <div class="mt-3">
                    <h6>Media Sosial:</h6>
                    <div class="d-flex gap-2">
                        @if($wisata->instagram)
                        <a href="https://instagram.com/{{ $wisata->instagram }}" target="_blank" class="btn btn-sm btn-outline-danger">
                            <i class="fab fa-instagram"></i>
                        </a>
                        @endif
                        
                        @if($wisata->facebook)
                        <a href="{{ $wisata->facebook }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        @endif
                        
                        @if($wisata->twitter)
                        <a href="https://twitter.com/{{ $wisata->twitter }}" target="_blank" class="btn btn-sm btn-outline-info">
                            <i class="fab fa-twitter"></i>
                        </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            
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

<!-- Review Modal -->
@auth
<div class="modal fade" id="ulasanModal" tabindex="-1" aria-labelledby="ulasanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ulasanModalLabel">Tulis Ulasan untuk {{ $wisata->nama }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('wisata.ulasan', $wisata->slug) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <div class="d-flex justify-content-center">
                            <div class="rating-select">
                                @for($i = 5; $i >= 1; $i--)
                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" />
                                <label for="star{{ $i }}" title="{{ $i }} stars">
                                    <i class="fas fa-star fa-2x"></i>
                                </label>
                                @endfor
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tanggal_kunjungan" class="form-label">Tanggal Kunjungan</label>
                        <input type="date" class="form-control" id="tanggal_kunjungan" name="tanggal_kunjungan" 
                               max="{{ date('Y-m-d') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="komentar" class="form-label">Komentar Anda</label>
                        <textarea class="form-control" id="komentar" name="komentar" rows="5" 
                                  placeholder="Bagikan pengalaman Anda mengunjungi wisata ini..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endauth
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the map
        @if(isset($wisata->latitude) && isset($wisata->longitude))
        const map = L.map('map').setView([{{ $wisata->latitude }}, {{ $wisata->longitude }}], 15);
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        // Add a marker
        L.marker([{{ $wisata->latitude }}, {{ $wisata->longitude }}])
            .addTo(map)
            .bindPopup("{{ $wisata->nama }}")
            .openPopup();
        @else
        // If coordinates are not available, try to geocode the address
        const map = L.map('map').setView([-3.318061, 114.590143], 10); // Default to Banjarmasin
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        // Add a note about approximate location
        L.marker([-3.318061, 114.590143])
            .addTo(map)
            .bindPopup("Lokasi perkiraan: {{ $wisata->alamat }}")
            .openPopup();
        @endif
    });
    
    // Review rating stars
    const starInputs = document.querySelectorAll('input[name="rating"]');
    const starLabels = document.querySelectorAll('.rating-select label');
    
    starInputs.forEach(input => {
        input.addEventListener('change', function() {
            const rating = this.value;
            
            starLabels.forEach(label => {
                const labelFor = label.getAttribute('for');
                const labelRating = labelFor.replace('star', '');
                
                if (labelRating <= rating) {
                    label.classList.add('selected');
                } else {
                    label.classList.remove('selected');
                }
            });
        });
    });
    
    // Share function
    function shareWisata() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $wisata->nama }} - Wisata HST',
                text: 'Kunjungi wisata {{ $wisata->nama }} di Kabupaten Hulu Sungai Tengah',
                url: window.location.href
            })
            .catch(error => console.log('Error sharing:', error));
        } else {
            // Fallback for browsers that don't support the Web Share API
            const tempInput = document.createElement('input');
            document.body.appendChild(tempInput);
            tempInput.value = window.location.href;
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            
            alert('Link telah disalin ke clipboard! Bagikan ke teman-teman Anda.');
        }
    }
</script>
<style>
    /* Styling for star rating in the modal */
    .rating-select {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
    }
    
    .rating-select input {
        display: none;
    }
    
    .rating-select label {
        cursor: pointer;
        color: #ddd;
        margin: 0 5px;
    }
    
    .rating-select label:hover,
    .rating-select label:hover ~ label,
    .rating-select input:checked ~ label {
        color: #ffc107;
    }
    
    .rating-select .selected {
        color: #ffc107;
    }
</style>
@endpush
