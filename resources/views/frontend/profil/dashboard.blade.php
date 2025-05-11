@extends('layouts.frontend')

@section('title', 'Profil Saya')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('content')
<div class="profile-container">
    <div class="profile-wrapper">
        <div class="row">
            <!-- Sidebar Profil -->
            <div class="col-lg-3">
                <div class="profile-sidebar">
                    <div class="user-info">
                        <img src="{{ Auth::user()->foto_profil_url }}" alt="{{ Auth::user()->name }}">
                        <h4>{{ Auth::user()->name }}</h4>
                        <div class="user-role">
                            {{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}
                        </div>
                    </div>
                    
                    <div class="profile-nav">
                        <a href="#" class="profile-nav-item active" data-tab="info">
                            <i class="fas fa-user"></i> Informasi Profil
                        </a>
                        <a href="#" class="profile-nav-item" data-tab="security">
                            <i class="fas fa-lock"></i> Keamanan
                        </a>
                        <a href="#" class="profile-nav-item" data-tab="favorites">
                            <i class="fas fa-heart"></i> Wishlist
                        </a>
                        <a href="#" class="profile-nav-item" data-tab="reviews">
                            <i class="fas fa-star"></i> Ulasan Saya
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Konten Profil -->
            <div class="col-lg-9">
                <div class="profile-content">
                    <!-- Tab Informasi Profil -->
                    <div class="profile-tab active" id="info">
                        <h3 class="section-title">
                            <i class="fas fa-user"></i> Informasi Profil
                            <button class="btn-edit" id="edit-profile-btn">Edit</button>
                        </h3>
                        
                        <div id="profile-info-display">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="form-label">Nama Lengkap</label>
                                        <p>{{ Auth::user()->name }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <p>{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="form-label">Nomor Telepon</label>
                                        <p>{{ Auth::user()->phone ?? 'Belum diatur' }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="form-label">Lokasi</label>
                                        <p>{{ Auth::user()->location ?? 'Belum diatur' }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label class="form-label">Tentang Saya</label>
                                        <p>{{ Auth::user()->about_me ?? 'Belum ada informasi tentang Anda.' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="profile-info-form" style="display: none;">
                            <form action="{{ url('/update-profile') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Nama Lengkap</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="phone" class="form-label">Nomor Telepon</label>
                                            <input type="text" class="form-control" id="phone" name="phone" value="{{ Auth::user()->phone }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="location" class="form-label">Lokasi</label>
                                            <input type="text" class="form-control" id="location" name="location" value="{{ Auth::user()->location }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group">
                                            <label for="about_me" class="form-label">Tentang Saya</label>
                                            <textarea class="form-control" id="about_me" name="about_me" rows="4">{{ Auth::user()->about_me }}</textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group">
                                            <label for="foto_profil" class="form-label">Foto Profil</label>
                                            <input type="file" class="form-control" id="foto_profil" name="foto_profil" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-actions">
                                    <button type="button" class="btn btn-secondary" id="cancel-edit-btn">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Tab Keamanan -->
                    <div class="profile-tab" id="security">
                        <h3 class="section-title"><i class="fas fa-lock"></i> Keamanan</h3>
                        
                        <form action="{{ url('/update-password') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="current_password" class="form-label">Password Saat Ini</label>
                                        <input type="password" class="form-control" id="current_password" name="current_password">
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="password" class="form-label">Password Baru</label>
                                        <input type="password" class="form-control" id="password" name="password">
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Ubah Password</button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Tab Wishlist/Favorit -->
                    <div class="profile-tab" id="favorites">
                        <h3 class="section-title"><i class="fas fa-heart"></i> Wishlist</h3>
                        
                        <div class="favorit-list">
                            @if(Auth::user()->favorit->count() > 0)
                                @foreach(Auth::user()->favorit as $item)
                                    <div class="favorit-card">
                                        <img src="{{ $item->wisata->gambarUtama ? asset($item->wisata->gambarUtama->file_gambar) : asset('images/placeholder-wisata.jpg') }}" alt="{{ $item->wisata->nama }}">
                                        <div class="favorit-card-content">
                                            <h5 class="favorit-card-title">{{ $item->wisata->nama }}</h5>
                                            <div class="favorit-card-location">
                                                <i class="fas fa-map-marker-alt"></i> {{ Str::limit($item->wisata->alamat, 50) }}
                                            </div>
                                            <div class="favorit-card-rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $item->wisata->rata_rata_rating)
                                                        <i class="fas fa-star"></i>
                                                    @elseif($i - 0.5 <= $item->wisata->rata_rata_rating)
                                                        <i class="fas fa-star-half-alt"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                                <span>({{ $item->wisata->rata_rata_rating }})</span>
                                            </div>
                                            <div class="favorit-card-actions">
                                                <a href="{{ route('wisata.detail', $item->wisata->slug) }}" class="favorit-card-btn btn-view">Lihat Detail</a>
                                                <form action="{{ route('wisata.favorit.hapus', $item->wisata->slug) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="favorit-card-btn btn-remove">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-5 w-100">
                                    <i class="fas fa-heart-broken fa-3x text-secondary mb-3"></i>
                                    <p>Anda belum memiliki wisata favorit</p>
                                    <a href="{{ route('wisata.index') }}" class="btn btn-primary mt-3">Jelajahi Wisata</a>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Tab Ulasan -->
                    <div class="profile-tab" id="reviews">
                        <h3 class="section-title"><i class="fas fa-star"></i> Ulasan Saya</h3>
                        
                        <div class="ulasan-list">
                            @if(Auth::user()->ulasan->count() > 0)
                                @foreach(Auth::user()->ulasan as $ulasan)
                                    <div class="ulasan-item">
                                        <div class="ulasan-header">
                                            <a href="{{ route('wisata.detail', $ulasan->wisata->slug) }}" class="ulasan-wisata">{{ $ulasan->wisata->nama }}</a>
                                            <span class="ulasan-date">{{ $ulasan->created_at->format('d M Y') }}</span>
                                        </div>
                                        <div class="ulasan-rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $ulasan->rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <div class="ulasan-text">
                                            {{ $ulasan->komentar }}
                                        </div>
                                        <div class="ulasan-footer">
                                            <span class="ulasan-status 
                                                @if($ulasan->status == 'ditampilkan')
                                                    status-displayed
                                                @elseif($ulasan->status == 'menunggu_moderasi')
                                                    status-pending
                                                @else
                                                    status-hidden
                                                @endif
                                            ">
                                                {{ ucfirst(str_replace('_', ' ', $ulasan->status)) }}
                                            </span>
                                            <div class="ulasan-actions">
                                                <button title="Edit"><i class="fas fa-edit"></i></button>
                                                <button title="Hapus"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-5 w-100">
                                    <i class="fas fa-comment-slash fa-3x text-secondary mb-3"></i>
                                    <p>Anda belum memberikan ulasan</p>
                                    <a href="{{ route('wisata.index') }}" class="btn btn-primary mt-3">Jelajahi Wisata</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab Switching
        const navItems = document.querySelectorAll('.profile-nav-item');
        const tabs = document.querySelectorAll('.profile-tab');
        
        navItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all nav items and tabs
                navItems.forEach(nav => nav.classList.remove('active'));
                tabs.forEach(tab => tab.classList.remove('active'));
                
                // Add active class to clicked nav item
                this.classList.add('active');
                
                // Show corresponding tab
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });
        
        // Toggle Edit Profile Form
        const editProfileBtn = document.getElementById('edit-profile-btn');
        const cancelEditBtn = document.getElementById('cancel-edit-btn');
        const profileInfoDisplay = document.getElementById('profile-info-display');
        const profileInfoForm = document.getElementById('profile-info-form');
        
        if (editProfileBtn) {
            editProfileBtn.addEventListener('click', function() {
                profileInfoDisplay.style.display = 'none';
                profileInfoForm.style.display = 'block';
            });
        }
        
        if (cancelEditBtn) {
            cancelEditBtn.addEventListener('click', function() {
                profileInfoDisplay.style.display = 'block';
                profileInfoForm.style.display = 'none';
            });
        }
        
        // Profile Image Preview
        const imageInput = document.getElementById('foto_profil');
        if (imageInput) {
            imageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        // Find image in sidebar and preview
                        const userImage = document.querySelector('.user-info img');
                        if (userImage) {
                            userImage.src = e.target.result;
                        }
                    }
                    
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
    });
</script>
@endpush