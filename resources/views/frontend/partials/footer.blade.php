<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="footer-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="Wisata HST" height="50">
                    <h3>Wisata HST</h3>
                </div>
                <p>Jelajahi keindahan alam dan budaya Kabupaten Hulu Sungai Tengah, Kalimantan Selatan.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <h4>Tautan</h4>
                <ul class="footer-links">
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li><a href="{{ url('/wisata') }}">Destinasi</a></li>
                    <li><a href="{{ url('/kategori') }}">Kategori</a></li>
                    <li><a href="{{ url('/tentang-kami') }}">Tentang Kami</a></li>
                    <li><a href="{{ url('/kontak') }}">Kontak</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4>Kategori Wisata</h4>
                <ul class="footer-links">
                    <li><a href="{{ url('/kategori/wisata-alam') }}">Wisata Alam</a></li>
                    <li><a href="{{ url('/kategori/wisata-budaya') }}">Wisata Budaya</a></li>
                    <li><a href="{{ url('/kategori/wisata-religi') }}">Wisata Religi</a></li>
                    <li><a href="{{ url('/kategori/wisata-kuliner') }}">Wisata Kuliner</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4>Kontak Kami</h4>
                <ul class="footer-contact">
                    <li><i class="fas fa-map-marker-alt"></i> Jl. A. Yani, Barabai, Hulu Sungai Tengah, Kalimantan Selatan</li>
                    <li><i class="fas fa-phone"></i> (0517) 123456</li>
                    <li><i class="fas fa-envelope"></i> info@wisatahst.com</li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; {{ date('Y') }} Wisata HST. All rights reserved.</p>
        </div>
    </div>
</footer>