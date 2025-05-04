<header class="site-header">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Wisata HST" height="40">

                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('wisata*') ? 'active' : '' }}" href="{{ url('/wisata') }}">Destinasi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('kategori*') ? 'active' : '' }}" href="{{ url('/kategori') }}">Kategori</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('tentang-kami*') ? 'active' : '' }}" href="{{ url('/tentang-kami') }}">Tentang Kami</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('kontak*') ? 'active' : '' }}" href="{{ url('/kontak') }}">Kontak</a>
                        </li>
                        @guest
                            <li class="nav-item">
                                <a class="nav-link btn btn-primary text-white" href="{{ route('login') }}">Login</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ url('/profil') }}">Profil Saya</a></li>
                                    <li><a class="dropdown-item" href="{{ url('/favorit') }}">Favorit</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ url('/logout') }}">Logout</a>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>