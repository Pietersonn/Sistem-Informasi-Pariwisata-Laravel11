<footer class="site-footer bg-dark text-white py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="footer-logo d-flex align-items-center mb-4">
                    <div>
                        <h3 class="text-white mb-0">WISATA HST</h3>
                        <p class="text-light mb-0 small">Hulu Sungai Tengah</p>
                    </div>
                </div>
                <p>Jelajahi keindahan alam dan budaya Kabupaten Hulu Sungai Tengah, Kalimantan Selatan.</p>
                <div class="social-links mt-3">
                    <a href="#" class="me-3 text-white"><i class="fab fa-facebook-f fa-lg"></i></a>
                    <a href="#" class="me-3 text-white"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="me-3 text-white"><i class="fab fa-instagram fa-lg"></i></a>
                    <a href="#" class="me-3 text-white"><i class="fab fa-youtube fa-lg"></i></a>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-6">
                <h5 class="mb-4 text-white">Menu Utama</h5>
                <ul class="list-unstyled footer-links">
                    <li class="mb-2">
                        <a href="{{ url('/') }}" class="text-light text-decoration-none">
                            <i class="fas fa-chevron-right me-2 small"></i>Beranda
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/wisata') }}" class="text-light text-decoration-none">
                            <i class="fas fa-chevron-right me-2 small"></i>Destinasi
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/kategori') }}" class="text-light text-decoration-none">
                            <i class="fas fa-chevron-right me-2 small"></i>Kategori
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/event') }}" class="text-light text-decoration-none">
                            <i class="fas fa-chevron-right me-2 small"></i>Event
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <h5 class="mb-4 text-white">Kategori Wisata</h5>
                <ul class="list-unstyled footer-links">
                    <li class="mb-2">
                        <a href="{{ url('/kategori/wisata-alam') }}" class="text-light text-decoration-none">
                            <i class="fas fa-chevron-right me-2 small"></i>Wisata Alam
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/kategori/wisata-budaya') }}" class="text-light text-decoration-none">
                            <i class="fas fa-chevron-right me-2 small"></i>Wisata Budaya
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/kategori/wisata-religi') }}" class="text-light text-decoration-none">
                            <i class="fas fa-chevron-right me-2 small"></i>Wisata Religi
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/kategori/wisata-kuliner') }}" class="text-light text-decoration-none">
                            <i class="fas fa-chevron-right me-2 small"></i>Wisata Kuliner
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <h5 class="mb-4 text-white">Kontak Kami</h5>
                <ul class="list-unstyled footer-contact">
                    <li class="d-flex mb-3">
                        <i class="fas fa-map-marker-alt mt-1 me-3 text-primary"></i>
                        <span>Jl. A. Yani, Barabai, Hulu Sungai Tengah, Kalimantan Selatan</span>
                    </li>
                    <li class="d-flex mb-3">
                        <i class="fas fa-phone-alt mt-1 me-3 text-primary"></i>
                        <span>(0517) 123456</span>
                    </li>
                    <li class="d-flex mb-3">
                        <i class="fas fa-envelope mt-1 me-3 text-primary"></i>
                        <span>info@wisatahst.com</span>
                    </li>
                    <li class="d-flex mb-3">
                        <i class="fas fa-clock mt-1 me-3 text-primary"></i>
                        <span>Senin - Jumat: 08:00 - 16:00</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="copyright text-center text-sm text-muted text-lg-start">
                    © <script>
                        document.write(new Date().getFullYear())
                    </script>, made with <i class="fa fa-heart"></i> by
                    <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim 3</a>
                </div>
            </div>
        </div>
    </div>
</footer>