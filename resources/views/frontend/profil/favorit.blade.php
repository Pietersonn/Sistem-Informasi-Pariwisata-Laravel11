{{-- resources/views/frontend/profil/favorit.blade.php --}}

@extends('layouts.frontend')

@section('title', 'Wishlist Saya - Destinasi Favorit')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/favorit-custom.css') }}">
@endpush

@section('content')
<div class="favorit-header">
    <div class="container">
        <h1 class="favorit-title">Daftar Favorit Saya</h1>
        <p class="favorit-subtitle">Kumpulan destinasi yang Anda simpan untuk rencana perjalanan Anda selanjutnya</p>
    </div>
</div>

<div class="favorit-content">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        @if($favorit->count() > 0)
            <div class="favorit-grid">
                @foreach($favorit as $item)
                <div class="favorit-item">
                    <div class="favorit-image">
                        <img src="{{ $item->wisata->gambarUtama ? asset($item->wisata->gambarUtama->file_gambar) : asset('images/placeholder-wisata.jpg') }}" 
                             alt="{{ $item->wisata->nama }}">
                        <div class="favorit-overlay">
                            <a href="{{ route('wisata.detail', $item->wisata->slug) }}" class="favorit-view-btn">
                                <i class="fas fa-eye"></i> Lihat Detail
                            </a>
                        </div>
                        <div class="favorit-actions">
                            <button type="button" class="favorit-note-btn" data-bs-toggle="modal" data-bs-target="#noteModal{{ $item->id }}">
                                <i class="fas fa-sticky-note"></i>
                            </button>
                            <form action="{{ route('favorit.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="favorit-remove-btn" onclick="return confirm('Yakin ingin menghapus dari favorit?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="favorit-info">
                        <h3 class="favorit-name">{{ $item->wisata->nama }}</h3>
                        <div class="favorit-kategori">
                            @foreach($item->wisata->kategori as $kategori)
                                <span class="badge">{{ $kategori->nama }}</span>
                            @endforeach
                        </div>
                        <div class="favorit-meta">
                            <span><i class="fas fa-map-marker-alt"></i> {{ Str::limit($item->wisata->alamat, 40) }}</span>
                            <span><i class="fas fa-star"></i> {{ number_format($item->wisata->rata_rata_rating, 1) }}</span>
                        </div>
                        @if($item->catatan)
                            <div class="favorit-note">
                                <i class="fas fa-sticky-note"></i> 
                                <span>{{ Str::limit($item->catatan, 100) }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Modal untuk Catatan -->
                    <div class="modal fade" id="noteModal{{ $item->id }}" tabindex="-1" aria-labelledby="noteModalLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="noteModalLabel{{ $item->id }}">Catatan untuk {{ $item->wisata->nama }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('favorit.update-catatan', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="catatan{{ $item->id }}" class="form-label">Catatan Pribadi</label>
                                            <textarea class="form-control" id="catatan{{ $item->id }}" name="catatan" rows="5" 
                                                placeholder="Tulis catatan pribadi Anda tentang destinasi ini...">{{ $item->catatan }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Catatan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="pagination-container">
                {{ $favorit->links() }}
            </div>
        @else
            <div class="favorit-empty">
                <div class="favorit-empty-icon">
                    <i class="fas fa-heart-broken"></i>
                </div>
                <h3>Daftar Favorit Kosong</h3>
                <p>Anda belum menambahkan destinasi wisata ke daftar favorit</p>
                <a href="{{ route('wisata.index') }}" class="btn-explore">Jelajahi Destinasi</a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Optional: Animasi saat scroll
    document.addEventListener('DOMContentLoaded', function() {
        const favoritItems = document.querySelectorAll('.favorit-item');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fadeIn');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        favoritItems.forEach(item => {
            observer.observe(item);
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
        });
    });
</script>
@endpush