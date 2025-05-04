<header class="site-header">
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <div>
                    <span class="fw-bold text-primary">WISATA</span>
                    <span class="fw-bold text-dark d-block" style="margin-top: -8px;">Hulu Sungai Tengah</span>
                </div>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                            <i class="fas fa-home me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('wisata*') ? 'active' : '' }}" href="{{ url('/wisata') }}">
                            <i class="fas fa-map-marked-alt me-1"></i> Destinasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('kategori*') ? 'active' : '' }}" href="{{ url('/kategori') }}">
                            <i class="fas fa-th-large me-1"></i> Kategori
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('event*') ? 'active' : '' }}" href="{{ url('/event') }}">
                            <i class="fas fa-calendar-alt me-1"></i> Event
                        </a>
                    </li>
                    
                    @guest
                        <li class="nav-item ms-2">
                            <a class="btn btn-outline-primary me-2" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> Masuk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i> Daftar
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ Auth::user()->foto_profil_url }}" class="rounded-circle me-2" 
                                    width="32" height="32" alt="{{ Auth::user()->name }}">
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ url('/profil') }}">
                                        <i class="fas fa-user me-2"></i> Profil Saya
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ url('/favorit') }}">
                                        <i class="fas fa-heart me-2"></i> Wishlist
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ url('/logout') }}">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
</header>