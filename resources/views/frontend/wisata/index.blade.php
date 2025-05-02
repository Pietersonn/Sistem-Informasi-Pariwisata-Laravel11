@extends('layouts.frontend')

@section('title', 'Daftar Wisata - HST')

@push('styles')
<style>
    .filter-section {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 5px;
    }
</style>
@endpush

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Filter Sidebar -->
        <div class="col-md-3">
            <div class="filter-section sticky-top">
                <h5>Filter Pencarian</h5>
                <form action="{{ route('wisata.index') }}" method="GET">
                    <!-- Kategori Filter -->
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select">
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
                    <div class="mb-3">
                        <label class="form-label">Urutkan Berdasarkan</label>
                        <select name="sort" class="form-select">
                            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="terpopuler" {{ request('sort') == 'terpopuler' ? 'selected' : '' }}>Terpopuler</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                        </select>
                    </div>

                    <!-- Pencarian -->
                    <div class="mb-3">
                        <label class="form-label">Cari Wisata</label>
                        <input type="text" name="q" class="form-control" 
                               placeholder="Nama wisata atau lokasi"
                               value="{{ request('q') }}">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Terapkan Filter</button>
                </form>
            </div>
        </div>

        <!-- Daftar Wisata -->
        <div class="col-md-9">
            <h1 class="mb-4">Destinasi Wisata</h1>

            <!-- Informasi Hasil -->
            <div class="alert alert-light" role="alert">
                Menampilkan {{ $wisata->count() }} dari total {{ $wisata->total() }} destinasi wisata
            </div>

            <!-- Grid Wisata -->
            <div class="row g-4">
                @forelse($wisata as $item)
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            <img src="{{ $item->gambar->first()->url ?? asset('images/placeholder-wisata.jpg') }}" 
                                 class="card-img-top" 
                                 alt="{{ $item->nama }}"
                                 style="height: 200px; object-fit: cover;">
                            
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-primary">
                                        {{ $item->kategori->first()->nama ?? 'Kategori' }}
                                    </span>
                                    <small class="text-muted">
                                        <i class="fas fa-eye"></i> {{ $item->jumlah_dilihat }}
                                    </small>
                                </div>

                                <h5 class="card-title">{{ $item->nama }}</h5>
                                <p class="card-text text-muted">
                                    {{ Str::limit($item->deskripsi, 100) }}
                                </p>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="{{ route('wisata.detail', $item->slug) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            Detail
                                        </a>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt"></i> {{ $item->lokasi }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning text-center" role="alert">
                            Tidak ada wisata ditemukan
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $wisata->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Optional: Script untuk interaksi filter
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.querySelector('.filter-section form');
        const inputs = filterForm.querySelectorAll('select, input');
        
        inputs.forEach(input => {
            input.addEventListener('change', function() {
                // Optional: Tambahkan animasi atau efek saat filter berubah
            });
        });
    });
</script>
@endpush