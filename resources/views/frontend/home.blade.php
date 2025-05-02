@extends('layouts.app')

@section('content')
<div class="hero bg-cover bg-center" style="background-image: url('{{ asset('images/hero-bg.jpg') }}')">
    <div class="hero-overlay bg-opacity-60 bg-black"></div>
    <div class="hero-content text-center text-white">
        <div class="max-w-lg">
            <h1 class="text-4xl font-bold mb-4">Temukan Destinasi Wisata Terbaik</h1>
            <p class="mb-6">Jelajahi keindahan tersembunyi di Kabupaten HST</p>
            
            <!-- Kotak Pencarian Terinspirasi Agoda -->
            <div class="search-box bg-white text-gray-800 p-6 rounded-lg shadow-lg">
                <form action="{{ route('wisata.index') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <input type="text" name="keyword" placeholder="Cari Destinasi" 
                               class="input input-bordered w-full">
                        <select name="kategori" class="select select-bordered w-full">
                            <option value="">Semua Kategori</option>
                            @foreach($kategori as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary w-full">
                            Cari Wisata
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Rekomendasi Wisata -->
<section class="py-12">
    <div class="container mx-auto">
        <h2 class="text-3xl font-bold text-center mb-8">Rekomendasi Wisata</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($wisata_rekomendasi as $wisata)
                <div class="card bg-white shadow-lg rounded-lg overflow-hidden">
                    <img src="{{ $wisata->gambar_utama }}" alt="{{ $wisata->nama }}" 
                         class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2">{{ $wisata->nama }}</h3>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">{{ $wisata->kategori->nama }}</span>
                            <span class="text-yellow-500">
                                ★ {{ number_format($wisata->rata_rata_rating, 1) }}
                            </span>
                        </div>
                        <a href="{{ route('wisata.detail', $wisata->slug) }}" 
                           class="btn btn-outline mt-4 w-full">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection