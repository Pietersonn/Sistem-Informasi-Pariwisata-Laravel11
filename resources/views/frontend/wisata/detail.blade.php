@extends('layouts.frontend')

@section('title', $wisata->nama . ' - Detail Wisata')

@push('styles')
<style>
    /* Gaya dasar */
    .detail-container {
        background-color: #f8f9fa;
        padding: 20px 0;
    }
    
    /* Header section dengan hero image */
    .detail-header {
        position: relative;
        height: 450px;
        background-color: #000;
        margin-bottom: 20px;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .header-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.8;
    }
    
    .header-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0,0,0,0.8));
        padding: 30px;
        color: #fff;
    }
    
    .header-overlay h1 {
        font-size: 2.5rem;
        margin-bottom: 10px;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.7);
    }
    
    .badge-category {
        background-color: rgba(255,255,255,0.2);
        border-radius: 30px;
        padding: 5px 15px;
        margin-right: 10px;
        backdrop-filter: blur(5px);
    }
    
    /* Gallery thumbnails */
    .gallery-thumbnails {
        display: flex;
        position: absolute;
        bottom: 15px;
        right: 20px;
    }
    
    .thumbnail {
        width: 60px;
        height: 40px;
        margin-left: 5px;
        border: 2px solid #fff;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .thumbnail:hover {
        transform: scale(1.1);
    }
    
    /* Layout grid */
    .detail-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }
    
    /* Card styles */
    .detail-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .detail-card h4 {
        font-size: 1.2rem;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }
    
    .detail-card h4 i {
        margin-right: 10px;
        color: #0d6efd;
    }
    
    /* Feature list */
    .feature-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
    
    .feature-item {
        display: flex;
        align-items: center;
    }
    
    .feature-item i {
        color: #28a745;
        margin-right: 10px;
    }
    
    /* Rating & stats */
    .rating-large {
        font-size: 3rem;
        font-weight: bold;
        color: #0d6efd;
    }
    
    .rating-summary {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    
    .rating-status {
        padding: 5px 10px;
        border-radius: 5px;
        color: #fff;
        font-weight: bold;
    }
    
    .status-excellent {
        background-color: #28a745;
    }
    
    /* Reviews */
    .review-item {
        border-bottom: 1px solid #eee;
        padding: 15px 0;
    }
    
    .reviewer-info {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .reviewer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
        object-fit: cover;
    }
    
    /* Action buttons */
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .action-btn {
        width: 100%;
        padding: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .action-btn i {
        margin-right: 10px;
    }
    
    .action-btn.btn-favorite {
        background-color: #fff;
        color: #dc3545;
        border: 1px solid #dc3545;
    }
    
    .action-btn.btn-favorite:hover {
        background-color: #dc3545;
        color: #fff;
    }
    
    .action-btn.btn-primary {
        background-color: #0d6efd;
    }
    
    .action-btn.btn-success {
        background-color: #28a745;
    }
    
    /* Location & map */
    .map-container {
        height: 200px;
        border-radius: 8px;
        overflow: hidden;
        margin-top: 15px;
    }
    
    /* Opening hours widget */
    .opening-hours {
        margin-top: 15px;
    }
    
    .day-row {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
        border-bottom: 1px dashed #eee;
    }
    
    .day-current {
        font-weight: bold;
        background-color: rgba(13, 110, 253, 0.1);
        border-radius: 4px;
        padding: 5px;
    }
    
    /* Review modal */
    .rating-select {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
        margin-bottom: 20px;
    }
    
    .rating-select input {
        display: none;
    }
    
    .rating-select label {
        cursor: pointer;
        color: #ddd;
        font-size: 30px;
        margin: 0 5px;
    }
    
    .rating-select label:hover,
    .rating-select label:hover ~ label,
    .rating-select input:checked ~ label {
        color: #ffc107;
    }
    
    /* Media queries for responsive design */
    @media (max-width: 992px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
        
        .detail-header {
            height: 350px;
        }
    }
    
    @media (max-width: 768px) {
        .header-overlay h1 {
            font-size: 1.8rem;
        }
        
        .feature-list {
            grid-template-columns: 1fr;
        }
        
        .gallery-thumbnails {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="detail-container">
    <div class="container">
        <!-- Header with Hero Image -->
        <div class="detail-header">
            @if($wisata->gambar && $wisata->gambar->count() > 0)
                <img src="{{ asset($wisata->gambar->first()->file_gambar) }}" alt="{{ $wisata->nama }}" class="header-image" id="mainImage">
            @else
                <img src="{{ asset('images/placeholder-wisata.jpg') }}" alt="{{ $wisata->nama }}" class="header-image">
            @endif
            
            <div class="header-overlay">
                <h1>{{ $wisata->nama }}</h1>
                <div class="mb-3">
                    @foreach($wisata->kategori as $kategori)
                        <span class="badge-category">{{ $kategori->nama }}</span>
                    @endforeach
                    
                    <div class="rating-stars d-inline-block ms-3">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $wisata->rata_rata_rating)
                                <i class="fas fa-star text-warning"></i>
                            @elseif($i - 0.5 <= $wisata->rata_rata_rating)
                                <i class="fas fa-star-half-alt text-warning"></i>
                            @else
                                <i class="far fa-star text-warning"></i>
                            @endif
                        @endfor
                        <span class="text-white ms-2">({{ $wisata->ulasan->count() }})</span>
                    </div>
                </div>
                
                <p><i class="fas fa-map-marker-alt me-2"></i>{{ $wisata->alamat }}</p>
            </div>
            
            <!-- Gallery Thumbnails -->
            @if($wisata->gambar && $wisata->gambar->count() > 1)
                <div class="gallery-thumbnails">
                    @foreach($wisata->gambar->take(4) as $index => $gambar)
                        <img src="{{ asset($gambar->file_gambar) }}" alt="{{ $wisata->nama }}" 
                            class="thumbnail" onclick="changeMainImage('{{ asset($gambar->file_gambar) }}')">
                    @endforeach
                    @if($wisata->gambar->count() > 4)
                        <div class="thumbnail d-flex align-items-center justify-content-center bg-dark text-white">
                            +{{ $wisata->gambar->count() - 4 }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
        
        <!-- Main Content Grid -->
        <div class="detail-grid">
            <!-- Left Column -->
            <div class="left-column">
                <!-- Description -->
                <div class="detail-card">
                    <h4><i class="fas fa-info-circle"></i> Tentang {{ $wisata->nama }}</h4>
                    <p>{{ $wisata->deskripsi }}</p>
                </div>
                
                <!-- Facilities -->
                <div class="detail-card">
                    <h4><i class="fas fa-concierge-bell"></i> Fasilitas</h4>
                    @if(is_array($wisata->fasilitas) && count($wisata->fasilitas) > 0)
                        <div class="feature-list">
                            @foreach($wisata->fasilitas as $fasilitas)
                                <div class="feature-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>{{ $fasilitas }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Tidak ada informasi fasilitas tersedia</p>
                    @endif
                </div>
                
                <!-- Reviews -->
                <div class="detail-card">
                    <h4><i class="fas fa-star"></i> Ulasan Pengunjung</h4>
                    
                    <div class="rating-summary">
                        <div>
                            <span class="rating-large">{{ number_format($wisata->rata_rata_rating, 1) }}</span>
                            <span class="ms-2">/5</span>
                        </div>
                        <div>
                            @php
                                $ratingText = "Excellent";
                                $ratingClass = "status-excellent";
                                if ($wisata->rata_rata_rating < 4) {
                                    $ratingText = "Good";
                                    $ratingClass = "bg-info";
                                } elseif ($wisata->rata_rata_rating < 3) {
                                    $ratingText = "Average";
                                    $ratingClass = "bg-warning";
                                } elseif ($wisata->rata_rata_rating < 2) {
                                    $ratingText = "Poor";
                                    $ratingClass = "bg-danger";
                                }
                            @endphp
                            <span class="rating-status {{ $ratingClass }}">{{ $ratingText }}</span>
                            <div class="text-muted mt-1">{{ $wisata->ulasan->count() }} reviews</div>
                        </div>
                    </div>
                    
                    @forelse($ulasan as $review)
                        <div class="review-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="reviewer-info">
                                    <img src="{{ $review->pengguna->foto_profil_url ?? asset('images/default-avatar.png') }}" 
                                         alt="{{ $review->pengguna->name }}" 
                                         class="reviewer-avatar">
                                    <div>
                                        <h6 class="mb-0">{{ $review->pengguna->name }}</h6>
                                        <div class="rating-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $review->rating ? ' text-warning' : ' text-secondary' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <div class="text-muted small">
                                    {{ $review->created_at->format('d M Y') }}
                                </div>
                            </div>
                            <div class="mt-2">
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
                            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#allReviewsModal">
                                Lihat Semua Ulasan
                            </button>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Right Column -->
            <div class="right-column">
                <!-- Actions Card -->
                <div class="detail-card">
                    <div class="action-buttons">
                        @auth
                            @if(!$sudahDifavorit)
                                <form action="{{ route('wisata.favorit', $wisata->slug) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="action-btn btn-favorite">
                                        <i class="far fa-heart"></i> Tambah ke Favorit
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('wisata.favorit.hapus', $wisata->slug) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn btn-favorite">
                                        <i class="fas fa-heart-broken"></i> Hapus dari Favorit
                                    </button>
                                </form>
                            @endif
                            
                            <button type="button" class="action-btn btn-success" data-bs-toggle="modal" data-bs-target="#ulasanModal">
                                <i class="far fa-comment"></i> Tulis Ulasan
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="action-btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i> Login untuk Aksi Lebih
                            </a>
                        @endauth
                        
                        @if($wisata->link_gmaps)
                            <a href="{{ $wisata->link_gmaps }}" target="_blank" class="action-btn btn-primary">
                                <i class="fas fa-directions"></i> Petunjuk Arah
                            </a>
                        @endif
                        
                        <button class="action-btn btn-outline-secondary" onclick="shareWisata()">
                            <i class="fas fa-share-alt"></i> Bagikan
                        </button>
                    </div>
                </div>
                
                <!-- Price Card -->
                <div class="detail-card">
                    <h4><i class="fas fa-tag"></i> Informasi Harga</h4>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted">Harga Tiket Masuk</span>
                        </div>
                        <div>
                            <span class="fs-5 fw-bold">
                                @if($wisata->harga_tiket > 0)
                                    Rp {{ number_format($wisata->harga_tiket, 0, ',', '.') }}
                                @else
                                    GRATIS
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Operating Hours -->
                <div class="detail-card">
                    <h4><i class="fas fa-clock"></i> Jam Operasional</h4>
                    @if($wisata->jam_buka && $wisata->jam_tutup)
                        <div class="mb-3">
                            <span class="badge {{ $wisata->sedangBuka() ? 'bg-success' : 'bg-danger' }} p-2">
                                <i class="fas fa-door-{{ $wisata->sedangBuka() ? 'open' : 'closed' }} me-1"></i>
                                {{ $wisata->sedangBuka() ? 'Buka Sekarang' : 'Tutup' }}
                            </span>
                        </div>
                        
                        <div class="opening-hours">
                            @php
                                $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
                                $hariIni = strtolower(date('l'));
                                switch ($hariIni) {
                                    case 'monday': $hariIni = 'senin'; break;
                                    case 'tuesday': $hariIni = 'selasa'; break;
                                    case 'wednesday': $hariIni = 'rabu'; break;
                                    case 'thursday': $hariIni = 'kamis'; break;
                                    case 'friday': $hariIni = 'jumat'; break;
                                    case 'saturday': $hariIni = 'sabtu'; break;
                                    case 'sunday': $hariIni = 'minggu'; break;
                                }
                            @endphp
                            
                            @foreach($hariList as $hari)
                                <div class="day-row {{ $hari == $hariIni ? 'day-current' : '' }}">
                                    <div>{{ ucfirst($hari) }}</div>
                                    <div>
                                        @if(is_array($wisata->hari_operasional) && in_array($hari, $wisata->hari_operasional))
                                            {{ $wisata->jam_buka->format('H:i') }} - {{ $wisata->jam_tutup->format('H:i') }}
                                        @else
                                            Tutup
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Informasi jam operasional tidak tersedia</p>
                    @endif
                </div>
                
                <!-- Location Card -->
                <div class="detail-card">
                    <h4><i class="fas fa-map-marked-alt"></i> Lokasi</h4>
                    <p>{{ $wisata->alamat }}</p>
                    
                    @if($wisata->link_gmaps)
                        <div class="map-container">
                            <iframe
                                width="100%"
                                height="100%"
                                frameborder="0"
                                style="border:0"
                                src="https://www.google.com/maps/embed/v1/place?key=YOUR_API_KEY&q={{ urlencode($wisata->alamat) }}"
                                allowfullscreen>
                            </iframe>
                        </div>
                    @endif
                </div>
                
                <!-- Contact Card -->
                <div class="detail-card">
                    <h4><i class="fas fa-address-book"></i> Informasi Kontak</h4>
                    <ul class="list-unstyled">
                        @if($wisata->kontak)
                            <li class="mb-2">
                                <a href="tel:{{ $wisata->kontak }}" class="text-decoration-none">
                                    <i class="fas fa-phone text-success me-2"></i>
                                    {{ $wisata->kontak }}
                                </a>
                            </li>
                        @endif
                        
                        @if($wisata->email)
                            <li class="mb-2">
                                <a href="mailto:{{ $wisata->email }}" class="text-decoration-none">
                                    <i class="fas fa-envelope text-danger me-2"></i>
                                    {{ $wisata->email }}
                                </a>
                            </li>
                        @endif
                        
                        @if($wisata->website)
                            <li class="mb-2">
                                <a href="{{ $wisata->website }}" target="_blank" class="text-decoration-none">
                                    <i class="fas fa-globe text-primary me-2"></i>
                                    {{ $wisata->website }}
                                </a>
                            </li>
                        @endif
                    </ul>
                    
                    @if($wisata->instagram || $wisata->facebook || $wisata->twitter)
                        <div class="social-media mt-3">
                            <h6>Media Sosial:</h6>
                            <div class="d-flex gap-2">
                                @if($wisata->instagram)
                                    <a href="https://instagram.com/{{ $wisata->instagram }}" target="_blank" 
                                       class="btn btn-sm btn-outline-danger">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                @endif
                                
                                @if($wisata->facebook)
                                    <a href="{{ $wisata->facebook }}" target="_blank" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                @endif
                                
                                @if($wisata->twitter)
                                    <a href="https://twitter.com/{{ $wisata->twitter }}" target="_blank" 
                                       class="btn btn-sm btn-outline-info">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ulasan -->
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
                        <div class="rating-select">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" />
                                <label for="star{{ $i }}" title="{{ $i }} stars">
                                    <i class="fas fa-star"></i>
                                </label>
                            @endfor
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

<!-- Modal Semua Ulasan -->
<div class="modal fade" id="allReviewsModal" tabindex="-1" aria-labelledby="allReviewsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="allReviewsModalLabel">Semua Ulasan {{ $wisata->nama }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="rating-summary mb-4">
                    <div>
                        <span class="rating-large">{{ number_format($wisata->rata_rata_rating, 1) }}</span>
                        <span class="ms-2">/5</span>
                    </div>
                    <div>
                        <span class="rating-status {{ $ratingClass }}">{{ $ratingText }}</span>
                        <div class="text-muted mt-1">{{ $wisata->ulasan->count() }} reviews</div>
                    </div>
                </div>
                
                @foreach($wisata->ulasan as $review)
                    <div class="review-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="reviewer-info">
                                <img src="{{ $review->pengguna->foto_profil_url ?? asset('images/default-avatar.png') }}" 
                                     alt="{{ $review->pengguna->name }}" 
                                     class="reviewer-avatar">
                                <div>
                                    <h6 class="mb-0">{{ $review->pengguna->name }}</h6>
                                    <div class="rating-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= $review->rating ? ' text-warning' : ' text-secondary' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <div class="text-muted small">
                                {{ $review->created_at->format('d M Y') }}
                            </div>
                        </div>
                        <div class="mt-2">
                            <p>{{ $review->komentar }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fungsi untuk mengubah gambar utama
    function changeMainImage(src) {
        document.getElementById('mainImage').src = src;
    }
    
    // Fungsi share
    function shareWisata() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $wisata->nama }} - Wisata HST',
                text: 'Kunjungi wisata {{ $wisata->nama }} di Kabupaten Hulu Sungai Tengah',
                url: window.location.href
            })
            .catch(error => console.log('Error sharing:', error));
        } else {
            // Fallback untuk browser yang tidak mendukung Web Share API
            const tempInput = document.createElement('input');
            document.body.appendChild(tempInput);
            tempInput.value = window.location.href;
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            
            alert('Link telah disalin ke clipboard! Bagikan ke teman-teman Anda.');
        }
    }
    
    // Inisialisasi star rating
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
    
    // Carousel untuk gambar
    document.addEventListener('DOMContentLoaded', function() {
        const headerImage = document.getElementById('mainImage');
        const thumbnails = document.querySelectorAll('.thumbnail');
        
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                headerImage.src = this.src;
            });
        });
        
        // Inisialisasi modal jika ada
        if (document.getElementById('ulasanModal')) {
            const ulasanModal = new bootstrap.Modal(document.getElementById('ulasanModal'));
        }
        
        if (document.getElementById('allReviewsModal')) {
            const allReviewsModal = new bootstrap.Modal(document.getElementById('allReviewsModal'));
        }
    });
</script>
@endpush