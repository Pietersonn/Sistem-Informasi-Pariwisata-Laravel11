{{-- resources/views/frontend/partials/header.blade.php --}}

<header class="site-header fixed-header">
    <div class="container">
        <div class="header-wrapper">
            <nav class="navbar">
                <div class="logo">
                    <a href="{{ url('/') }}">
                        <i class="fas fa-building"></i>
                        <span>Miamories</span>
                    </a>
                </div>

                <ul class="nav-links" id="navLinks">
                    <li><a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ route('wisata.index') }}"
                            class="{{ request()->is('destinasi*') ? 'active' : '' }}">Destinasi</a></li>
                    <li><a href="{{ url('/kategori') }}"
                            class="{{ request()->is('kategori*') ? 'active' : '' }}">Kategori</a></li>
                    <li><a href="{{ url('/event') }}" class="{{ request()->is('event*') ? 'active' : '' }}">Event</a>
                    </li>
                </ul>

                <div class="auth-buttons">
                    @guest
                        <a href="{{ route('login') }}" class="login-btn">LOGIN</a>
                    @else
                        <div class="user-dropdown">
                            <!-- Tambahkan wrapper button jika diperlukan -->
                            <button type="button" class="user-avatar-btn"
                                style="background: none; border: none; padding: 0; cursor: pointer;">
                                <img src="{{ Auth::user()->foto_profil_url }}" alt="{{ Auth::user()->name }}"
                                    class="user-avatar">
                            </button>
                            <div class="dropdown-content">
                                <a href="{{ url('/profil') }}">
                                    <i class="fas fa-user"></i> Profil Saya
                                </a>
                                @if (Auth::user()->role == 'admin')
                                    <a href="{{ url('/dashboard') }}">
                                        <i class="fas fa-tachometer-alt"></i> Dashboard Admin
                                    </a>
                                @endif
                                @if (Auth::user()->role == 'pemilik_wisata')
                                    <a href="{{ route('pemilik.dashboard') }}">
                                        <i class="fas fa-tachometer-alt"></i> Dashboard Pemilik
                                    </a>
                                    <a href="{{ route('pemilik.wisata.index') }}">
                                        <i class="fas fa-map-marked-alt"></i> Wisata Saya
                                    </a>
                                    <a href="{{ route('pemilik.event.index') }}">
                                        <i class="fas fa-calendar-alt"></i> Event Saya
                                    </a>
                                    <a href="{{ route('pemilik.ulasan.index') }}">
                                        <i class="fas fa-comments"></i> Ulasan Wisata
                                    </a>
                                @endif
                                <a href="{{ url('/favorit') }}">
                                    <i class="fas fa-heart"></i> Wishlist
                                </a>
                                <div class="divider"></div>
                                <a href="{{ url('/signout') }}">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </div>
                        </div>
                    @endguest
                </div>

                <div class="mobile-toggle" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </div>
            </nav>
        </div>
    </div>
</header>

<!-- Spacer untuk menggantikan ruang yang diambil oleh fixed header -->
<div class="header-spacer"></div>
