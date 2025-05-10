<footer class="footer">
    <div class="container">
        <div class="footer-container">
            <!-- Bagian Logo dan Deskripsi -->
            <div class="footer-logo">
                <h2>WISATA HST</h2>
                <p class="tagline">Hulu Sungai Tengah</p>
                <p class="deskripsi">Jelajahi keindahan alam dan budaya Kabupaten Hulu Sungai Tengah, Kalimantan Selatan.</p>
                <div class="sosial-media">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            
            <!-- Menu Utama -->
            <div class="footer-menu">
                <h3>Menu Utama</h3>
                <ul>
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li><a href="{{ url('/wisata') }}">Destinasi</a></li>
                    <li><a href="{{ url('/kategori') }}">Kategori</a></li>
                    <li><a href="{{ url('/event') }}">Event</a></li>
                </ul>
            </div>
            
            <!-- Kategori Wisata -->
            <div class="footer-menu">
                <h3>Kategori Wisata</h3>
                <ul>
                    <li><a href="{{ url('/kategori/wisata-alam') }}">Wisata Alam</a></li>
                    <li><a href="{{ url('/kategori/wisata-budaya') }}">Wisata Budaya</a></li>
                    <li><a href="{{ url('/kategori/wisata-religi') }}">Wisata Religi</a></li>
                    <li><a href="{{ url('/kategori/wisata-kuliner') }}">Wisata Kuliner</a></li>
                </ul>
            </div>
            
            <!-- Kontak -->
            <div class="footer-kontak">
                <h3>Kontak Kami</h3>
                <div class="kontak-item">
                    <div class="kontak-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="kontak-text">
                        Jl. A. Yani, Barabai, Hulu Sungai Tengah, Kalimantan Selatan
                    </div>
                </div>
                <div class="kontak-item">
                    <div class="kontak-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div class="kontak-text">
                        (0517) 123456
                    </div>
                </div>
                <div class="kontak-item">
                    <div class="kontak-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="kontak-text">
                        info@wisatahst.com
                    </div>
                </div>
                <div class="kontak-item">
                    <div class="kontak-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="kontak-text">
                        Senin - Jumat: 08:00 - 16:00
                    </div>
                </div>
            </div>
        </div>
        <!-- Garis Pembatas -->
        <div class="divider"></div>
        
        <!-- Copyright -->
        <div class="copyright">
            <div class="copyright-text">
                © <script>document.write(new Date().getFullYear())</script>, made with <i class="fa fa-heart"></i> by
                <a href="https://www.creative-tim.com" target="_blank">Creative Tim 3</a>
            </div>
        </div>
    </div>
</footer>