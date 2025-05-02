@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Wisata</p>
                            <h5 class="font-weight-bolder">
                                {{ $statistik['total_wisata'] ?? 0 }}
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                            <i class="fas fa-map-marked-alt text-lg opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Pengguna</p>
                            <h5 class="font-weight-bolder">
                                {{ $statistik['total_pengguna'] ?? 0 }}
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                            <i class="fas fa-users text-lg opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Ulasan</p>
                            <h5 class="font-weight-bolder">
                                {{ $statistik['total_ulasan'] ?? 0 }}
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                            <i class="fas fa-comment text-lg opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Event Aktif</p>
                            <h5 class="font-weight-bolder">
                                {{ $statistik['event_aktif'] ?? 0 }}
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle">
                            <i class="fas fa-calendar-alt text-lg opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik dan Tabel -->
<div class="row mt-4">
    <div class="col-lg-7 mb-lg-0 mb-4">
        <div class="card">
            <div class="card-header pb-0 p-3">
                <div class="d-flex justify-content-between">
                    <h6 class="mb-2">Wisata Paling Banyak Dilihat</h6>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table align-items-center">
                    <thead>
                        <tr>
                            <th>Nama Wisata</th>
                            <th>Kategori</th>
                            <th>Jumlah Dilihat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kunjunganWisata as $wisata)
                        <tr>
                            <td>
                                <div class="d-flex px-2 py-1">
                                    <div>
                                        <img src="{{ $wisata->gambar->first()->url ?? asset('images/placeholder.jpg') }}" 
                                             class="avatar avatar-sm me-3" alt="{{ $wisata->nama }}">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">{{ $wisata->nama }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @foreach($wisata->kategori as $kategori)
                                    <span class="badge bg-gradient-primary">{{ $kategori->nama }}</span>
                                @endforeach
                            </td>
                            <td class="align-middle text-center">
                                <span class="text-secondary text-xs font-weight-bold">
                                    {{ $wisata->jumlah_dilihat }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Ulasan Terbaru -->
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-0">Ulasan Terbaru</h6>
            </div>
            <div class="card-body p-3">
                <ul class="list-group">
                    @foreach($ulasanTerbaru as $ulasan)
                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-shape icon-sm me-3 bg-gradient-primary shadow text-center">
                                <i class="fas fa-comment text-white"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm">{{ $ulasan->pengguna->nama }}</h6>
                                <span class="text-xs">{{ Str::limit($ulasan->komentar, 50) }}</span>
                            </div>
                        </div>
                        <div class="d-flex">
                            <span class="badge badge-sm bg-gradient-warning">
                                {{ $ulasan->rating }} <i class="fas fa-star"></i>
                            </span>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Event Mendatang -->
<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-0">Event Mendatang</h6>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th>Nama Event</th>
                                <th>Lokasi Wisata</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eventMendatang as $event)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div>
                                            @if($event->poster)
                                                <img src="{{ asset('storage/' . $event->poster) }}" 
                                                     class="avatar avatar-sm me-3" alt="{{ $event->nama }}">
                                            @endif
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $event->nama }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $event->wisata->nama }}</td>
                                <td>{{ $event->tanggal_mulai->format('d M Y') }}</td>
                                <td>{{ $event->tanggal_selesai->format('d M Y') }}</td>
                                <td>
                                    <span class="badge bg-gradient-{{ $event->status == 'aktif' ? 'success' : 'secondary' }}">
                                        {{ $event->status_label }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Optional: Tambahkan script untuk grafik atau interaksi dashboard
    document.addEventListener('DOMContentLoaded', function() {
        // Contoh: Animasi statistik
        const counters = document.querySelectorAll('.font-weight-bolder');
        counters.forEach(counter => {
            const target = parseInt(counter.textContent);
            counter.textContent = '0';
            
            const updateCounter = () => {
                const value = parseInt(counter.textContent);
                const increment = target / 100;
                
                if (value < target) {
                    counter.textContent = Math.ceil(value + increment);
                    setTimeout(updateCounter, 20);
                } else {
                    counter.textContent = target;
                }
            };
            
            updateCounter();
        });
    });
</script>
@endpush