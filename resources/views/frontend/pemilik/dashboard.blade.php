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


        </div>
    </div>
@endsection
