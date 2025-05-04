<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Wisata HST')</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">
    
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="site-header">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo HST" height="40">
                    <span>Wisata HST</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('wisata*') ? 'active' : '' }}" href="{{ route('wisata.index') }}">Wisata</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('kategori*') ? 'active' : '' }}" href="{{ route('kategori.index') }}">Kategori</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('tentang*') ? 'active' : '' }}" href="{{ route('tentang') }}">Tentang</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('kontak*') ? 'active' : '' }}" href="{{ route('kontak') }}">Kontak</a>
                        </li>
                        
                        @guest
                            <li class="nav-item ms-3">
                                <a class="btn btn-primary" href="{{ route('login') }}">Masuk</a>
                            </li>
                            <li class="nav-item ms-2">
                                <a class="btn btn-outline-primary" href="{{ route('register') }}">Daftar</a>
                            </li>
                        @else
                            <li class="nav-item dropdown ms-3">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                    <img src="{{ auth()->user()->foto_profil_url }}" class="rounded-circle me-2" width="32" height="32" alt="Foto Profil">
                                    {{ auth()->user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('profil') }}">Profil Saya</a></li>
                                    <li><a class="dropdown-item" href="{{ route('favorit') }}">Favorit</a></li>
                                    @if(auth()->user()->role == 'admin')
                                        <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Keluar</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="site-footer mt-5 py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>Tentang Wisata HST</h5>
                    <p>Sistem Informasi Pariwisata Kabupaten Hulu Sungai Tengah (HST) - Menyajikan informasi wisata terbaik di Kabupaten HST untuk pengalaman berwisata yang menyenangkan.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Link Cepat</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="{{ route('wisata.index') }}">Wisata</a></li>
                        <li><a href="{{ route('kategori.index') }}">Kategori</a></li>
                        <li><a href="{{ route('tentang') }}">Tentang</a></li>
                        <li><a href="{{ route('kontak') }}">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Hubungi Kami</h5>
                    <p>
                        <i class="fas fa-map-marker-alt me-2"></i> Barabai, Kabupaten HST<br>
                        <i class="fas fa-phone me-2"></i> (0517) 123456<br>
                        <i class="fas fa-envelope me-2"></i> info@wisatahst.com
                    </p>
                    <div class="social-icons mt-3">
                        <a href="#" class="me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="me-2"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Sistem Informasi Pariwisata HST. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>