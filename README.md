error pada header route register gabisa di akses sama routes/web.php
tampilan home masi sampah ya allah

    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Jelajahi Keindahan Kabupaten Hulu Sungai Tengah</h1>
            <p class="hero-subtitle">Temukan destinasi wisata menakjubkan dan pengalaman tak terlupakan</p>
            
            <div class="search-box">
                <form action="{{ route('wisata.index') }}" method="GET" class="search-form">
                    <div class="search-input">
                        <input type="text" name="q" placeholder="Cari Destinasi Wisata...">
                    </div>
                    <button type="submit" class="search-button">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            
            <div class="kategori-section">
                <h2 class="kategori-title">Kategori</h2>
                <div class="kategori-container">
                    <a href="{{ url('/kategori/alam') }}" class="kategori-item">
                        <div class="kategori-icon">
                            <i class="fas fa-tree"></i>
                        </div>
                        <span class="kategori-name">Alam</span>
                    </a>
                    <a href="{{ url('/kategori/budaya') }}" class="kategori-item">
                        <div class="kategori-icon">
                            <i class="fas fa-landmark"></i>
                        </div>
                        <span class="kategori-name">Budaya</span>
                    </a>
                    <a href="{{ url('/kategori/religi') }}" class="kategori-item">
                        <div class="kategori-icon">
                            <i class="fas fa-place-of-worship"></i>
                        </div>
                        <span class="kategori-name">Religi</span>
                    </a>
                    <a href="{{ url('/kategori/kuliner') }}" class="kategori-item">
                        <div class="kategori-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <span class="kategori-name">Kuliner</span>
                    </a>
                </div>
            </div>
        </div>
    </section>


    create wisata fasilitas jika ingin check box
    
    <div class="mb-3">
    <label>Fasilitas</label>
    <div class="row">
        <div class="col-md-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="fasilitas[]" id="fasilitas_parkir" value="Parkir">
                <label class="form-check-label" for="fasilitas_parkir">Parkir</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="fasilitas[]" id="fasilitas_toilet" value="Toilet">
                <label class="form-check-label" for="fasilitas_toilet">Toilet</label>
            </div>
            <!-- Checkbox lainnya -->
        </div>
        <!-- Kolom lainnya -->
    </div>
</div>
