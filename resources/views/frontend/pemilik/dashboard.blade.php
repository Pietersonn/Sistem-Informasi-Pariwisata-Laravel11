@extends('layouts.frontend')

@section('title', 'Dashboard Pemilik Wisata')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pemilik.css') }}">
@endpush

@section('content')
    <div class="pemilik-dashboard-container">
        <div class="container py-5">
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="dashboard-title">Dashboard Pemilik Wisata</h1>
                    <p class="dashboard-subtitle">Kelola destinasi wisata dan lihat statistik kinerja</p>
                </div>
            </div>

            <!-- Statistik Cards -->
            <div class="row mb-5">
                <!-- Total Wisata -->
                <div class="col-md-4 mb-4">
                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div class="stat-card-info">
                                <h3 class="stat-card-title">Total Wisata</h3>
                                <span class="stat-card-value">{{ $statistik['total_wisata'] }}</span>
                            </div>
                            <div class="stat-card-icon">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                        </div>
                        <div class="stat-card-footer">
                            <a href="{{ route('pemilik.wisata.index') }}" class="stat-card-link">Lihat Semua <i
                                    class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Total Kunjungan -->
                <div class="col-md-4 mb-4">
                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div class="stat-card-info">
                                <h3 class="stat-card-title">Total Kunjungan</h3>
                                <span class="stat-card-value">{{ $statistik['total_kunjungan'] }}</span>
                            </div>
                            <div class="stat-card-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rating Rata-rata -->
                <div class="col-md-4 mb-4">
                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div class="stat-card-info">
                                <h3 class="stat-card-title">Rating Rata-rata</h3>
                                <span class="stat-card-value">{{ number_format($statistik['rata_rata_rating'], 1) }}</span>
                            </div>
                            <div class="stat-card-icon rating-icon">
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Ulasan -->
                <div class="col-md-4 mb-4">
                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div class="stat-card-info">
                                <h3 class="stat-card-title">Total Ulasan</h3>
                                <span class="stat-card-value">{{ $statistik['total_ulasan'] }}</span>
                            </div>
                            <div class="stat-card-icon">
                                <i class="fas fa-comment-alt"></i>
                            </div>
                        </div>
                        <div class="stat-card-footer">
                            <a href="{{ route('pemilik.ulasan.index') }}" class="stat-card-link">Lihat Semua <i
                                    class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Total Event -->
                <div class="col-md-4 mb-4">
                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div class="stat-card-info">
                                <h3 class="stat-card-title">Total Event</h3>
                                <span class="stat-card-value">{{ $statistik['total_event'] }}</span>
                            </div>
                            <div class="stat-card-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                        <div class="stat-card-footer">
                            <a href="{{ route('pemilik.event.index') }}" class="stat-card-link">Lihat Semua <i
                                    class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <!-- Ulasan Terbaru -->
                <div class="col-lg-6 mb-4">
                    <div class="dashboard-card">
                        <div class="dashboard-card-header">
                            <h3><i class="fas fa-comments"></i> Ulasan Terbaru</h3>
                        </div>
                        <div class="dashboard-card-body">
                            @if (count($ulasanTerbaru) > 0)
                                <div class="recent-review-list">
                                    @foreach ($ulasanTerbaru as $ulasan)
                                        <div class="recent-review-item">
                                            <div class="recent-review-header">
                                                <div class="reviewer-info">
                                                    <img src="{{ $ulasan->pengguna->foto_profil_url ?? asset('images/default.png') }}"
                                                        alt="{{ $ulasan->pengguna->name }}" class="reviewer-avatar">
                                                    <div>
                                                        <h5>{{ $ulasan->pengguna->name }}</h5>
                                                        <div class="review-meta">
                                                            <span
                                                                class="review-date">{{ $ulasan->created_at->format('d M Y') }}</span>
                                                            <span class="review-separator">•</span>
                                                            <span class="review-place">{{ $ulasan->wisata->nama }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="review-rating">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $ulasan->rating)
                                                            <i class="fas fa-star"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                            <div class="review-content">
                                                <p>{{ Str::limit($ulasan->komentar, 150) }}</p>
                                            </div>
                                            <div class="review-actions">
                                                <a href="{{ route('pemilik.ulasan.index') }}#ulasan-{{ $ulasan->id }}"
                                                    class="btn-reply"><i class="fas fa-reply"></i> Balas</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-state">
                                    <i class="fas fa-comment-alt"></i>
                                    <p>Belum ada ulasan untuk ditampilkan</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
