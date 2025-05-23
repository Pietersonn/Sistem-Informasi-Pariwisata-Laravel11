@extends('layouts.frontend')

@section('title', 'Tambah Event Baru')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pemilik.css') }}">
<style>
    .event-create-container {
        background-color: #f8f9fa;
        min-height: 100vh;
        padding-top: 30px;
    }

    .form-container {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }

    .page-title {
        font-size: 2.2rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 10px;
    }

    .page-subtitle {
        color: #718096;
        font-size: 1.1rem;
        margin-bottom: 30px;
    }

    .form-section {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 25px;
        border-left: 4px solid #667eea;
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: #667eea;
    }

    .form-label {
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 8px;
    }

    .required-field::after {
        content: "*";
        color: #e53e3e;
        margin-left: 4px;
    }

    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px 15px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-text {
        font-size: 0.85rem;
        color: #718096;
        margin-top: 5px;
    }

    .poster-upload-area {
        border: 2px dashed #cbd5e0;
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .poster-upload-area:hover {
        border-color: #667eea;
        background-color: #f7fafc;
    }

    .poster-upload-area.dragover {
        border-color: #667eea;
        background-color: #edf2f7;
    }

    .upload-icon {
        font-size: 3rem;
        color: #cbd5e0;
        margin-bottom: 15px;
    }

    .upload-text {
        color: #4a5568;
        font-size: 1.1rem;
        margin-bottom: 10px;
    }

    .upload-hint {
        color: #718096;
        font-size: 0.9rem;
    }

    .poster-preview {
        max-width: 300px;
        max-height: 200px;
        border-radius: 8px;
        margin-top: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .remove-poster {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #e53e3e;
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 14px;
    }

    .date-input-wrapper {
        position: relative;
    }

    .date-input-wrapper::after {
        content: "\f073";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #718096;
        pointer-events: none;
    }

    .wisata-info-card {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-radius: 12px;
        padding: 20px;
        margin-top: 15px;
        display: none;
    }

    .wisata-info-card h5 {
        margin-bottom: 10px;
        font-weight: 700;
    }

    .wisata-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        font-size: 14px;
    }

    .wisata-meta-item {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 30px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 16px;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        color: white;
    }

    .btn-cancel {
        background: #e2e8f0;
        color: #4a5568;
        padding: 15px 30px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 16px;
        border: none;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: #cbd5e0;
        color: #2d3748;
    }

    .notice-card {
        background: linear-gradient(135deg, #ffeaa7, #fab1a0);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        border: none;
    }

    .notice-icon {
        font-size: 1.5rem;
        margin-right: 15px;
        color: #d63031;
    }

    .notice-title {
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 8px;
    }

    .notice-text {
        color: #4a5568;
        margin-bottom: 0;
        line-height: 1.5;
    }

    @media (max-width: 768px) {
        .form-container {
            padding: 20px;
        }
        
        .page-title {
            font-size: 1.8rem;
        }
        
        .form-section {
            padding: 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="event-create-container">
    <div class="container py-5">
        <div class="form-container">
            <h1 class="page-title">Tambah Event Baru</h1>
            <p class="page-subtitle">Buat event menarik untuk destinasi wisata Anda</p>

            <!-- Notice Card -->
            <div class="notice-card">
                <div class="d-flex align-items-start">
                    <i class="fas fa-info-circle notice-icon"></i>
                    <div>
                        <h5 class="notice-title">Perhatian Penting</h5>
                        <p class="notice-text">
                            Event yang Anda buat akan menunggu persetujuan dari admin terlebih dahulu sebelum ditampilkan di website. 
                            Pastikan informasi yang Anda masukkan sudah benar dan lengkap.
                        </p>
                    </div>
                </div>
            </div>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Terdapat kesalahan:</h6>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('pemilik.event.store') }}" method="POST" enctype="multipart/form-data" id="eventForm">
                @csrf

                <!-- Informasi Dasar -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        Informasi Dasar Event
                    </h3>
                    
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="nama" class="form-label required-field">Nama Event</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                   id="nama" name="nama" value="{{ old('nama') }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Berikan nama yang menarik dan mudah diingat</div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="id_wisata" class="form-label required-field">Lokasi Wisata</label>
                            <select name="id_wisata" id="id_wisata" class="form-select @error('id_wisata') is-invalid @enderror" required>
                                <option value="">Pilih Wisata</option>
                                @foreach($wisata as $w)
                                    <option value="{{ $w->id }}" {{ old('id_wisata') == $w->id ? 'selected' : '' }}>
                                        {{ $w->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_wisata')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <!-- Wisata Info Card -->
                            <div class="wisata-info-card" id="wisataInfoCard">
                                <h5 id="wisataName">-</h5>
                                <div class="wisata-meta">
                                    <div class="wisata-meta-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span id="wisataAddress">-</span>
                                    </div>
                                    <div class="wisata-meta-item">
                                        <i class="fas fa-ticket-alt"></i>
                                        <span id="wisataPrice">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label required-field">Deskripsi Event</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                  id="deskripsi" name="deskripsi" rows="5" required>{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Jelaskan detail event, aktivitas yang akan dilakukan, dan hal menarik lainnya</div>
                    </div>
                </div>

                <!-- Waktu Event -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-calendar-alt"></i>
                        Waktu Pelaksanaan
                    </h3>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_mulai" class="form-label required-field">Tanggal & Waktu Mulai</label>
                            <div class="date-input-wrapper">
                                <input type="datetime-local" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                       id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required>
                            </div>
                            @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_selesai" class="form-label required-field">Tanggal & Waktu Selesai</label>
                            <div class="date-input-wrapper">
                                <input type="datetime-local" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                       id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required>
                            </div>
                            @error('tanggal_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>Tips:</strong> Pastikan waktu event tidak bertabrakan dengan event lain di lokasi yang sama
                    </div>
                </div>

                <!-- Poster Event -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-image"></i>
                        Poster Event
                    </h3>
                    
                    <div class="poster-upload-area" onclick="document.getElementById('poster').click()">
                        <div class="upload-content">
                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            <div class="upload-text">Klik untuk upload poster event</div>
                            <div class="upload-hint">atau drag & drop file ke sini</div>
                            <div class="upload-hint mt-2">Format: JPG, PNG, WEBP, GIF (Maks: 5MB)</div>
                        </div>
                        <input type="file" class="d-none @error('poster') is-invalid @enderror" 
                               id="poster" name="poster" accept="image/*">
                        <img id="posterPreview" class="poster-preview d-none" alt="Preview">
                        <button type="button" class="remove-poster d-none" onclick="removePoster()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    @error('poster')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Poster yang menarik akan meningkatkan antusiasme pengunjung</div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('pemilik.event.index') }}" class="btn-cancel">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane me-2"></i>Kirim untuk Persetujuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Wisata data for info card
    const wisataData = @json($wisata->keyBy('id'));
    
    // Wisata selection handler
    document.getElementById('id_wisata').addEventListener('change', function() {
        const wisataId = this.value;
        const infoCard = document.getElementById('wisataInfoCard');
        
        if (wisataId && wisataData[wisataId]) {
            const wisata = wisataData[wisataId];
            
            document.getElementById('wisataName').textContent = wisata.nama;
            document.getElementById('wisataAddress').textContent = wisata.alamat;
            
            let price = 'Gratis';
            if (wisata.harga_tiket && wisata.harga_tiket > 0) {
                price = 'Rp ' + parseInt(wisata.harga_tiket).toLocaleString('id-ID');
            }
            document.getElementById('wisataPrice').textContent = price;
            
            infoCard.style.display = 'block';
        } else {
            infoCard.style.display = 'none';
        }
    });
    
    // Date validation
    const tanggalMulai = document.getElementById('tanggal_mulai');
    const tanggalSelesai = document.getElementById('tanggal_selesai');
    
    // Set minimum date to today
    const today = new Date();
    const todayString = today.toISOString().slice(0, 16);
    tanggalMulai.min = todayString;
    
    tanggalMulai.addEventListener('change', function() {
        tanggalSelesai.min = this.value;
        if (tanggalSelesai.value && tanggalSelesai.value < this.value) {
            tanggalSelesai.value = this.value;
        }
    });
    
    tanggalSelesai.addEventListener('change', function() {
        if (this.value < tanggalMulai.value) {
            alert('Tanggal selesai harus setelah tanggal mulai');
            this.value = tanggalMulai.value;
        }
    });
    
    // Poster upload handling
    const posterInput = document.getElementById('poster');
    const posterPreview = document.getElementById('posterPreview');
    const uploadArea = document.querySelector('.poster-upload-area');
    const uploadContent = document.querySelector('.upload-content');
    const removeButton = document.querySelector('.remove-poster');
    
    posterInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                posterPreview.src = e.target.result;
                posterPreview.classList.remove('d-none');
                uploadContent.style.display = 'none';
                removeButton.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        }
    });
    
    // Drag and drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });
    
    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });
    
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            posterInput.files = files;
            posterInput.dispatchEvent(new Event('change'));
        }
    });
    
    // Form validation
    document.getElementById('eventForm').addEventListener('submit', function(e) {
        let isValid = true;
        
        // Check required fields
        const requiredFields = ['nama', 'id_wisata', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai'];
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        // Check date logic
        if (tanggalSelesai.value < tanggalMulai.value) {
            isValid = false;
            alert('Tanggal selesai harus setelah tanggal mulai');
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi');
        }
    });
});

function removePoster() {
    const posterInput = document.getElementById('poster');
    const posterPreview = document.getElementById('posterPreview');
    const uploadContent = document.querySelector('.upload-content');
    const removeButton = document.querySelector('.remove-poster');
    
    posterInput.value = '';
    posterPreview.classList.add('d-none');
    uploadContent.style.display = 'block';
    removeButton.classList.add('d-none');
}
</script>
@endpush