@extends('layouts.frontend')

@section('title', 'Edit Wisata - ' . $wisata->nama)


@section('content')
<div class="pemilik-wisata-edit-container">
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="page-title">Edit Wisata</h1>
                <p class="page-subtitle">Perbarui informasi tentang destinasi wisata Anda</p>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('pemilik.wisata.update', $wisata->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Informasi Umum -->
            <div class="form-section">
                <h3 class="form-section-title"><i class="fas fa-info-circle"></i> Informasi Umum</h3>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama" class="form-label required-field">Nama Wisata</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $wisata->nama) }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="kategori" class="form-label required-field">Kategori</label>
                        <select class="form-select select2 @error('kategori') is-invalid @enderror" id="kategori" name="kategori[]" multiple required>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->id }}" {{ in_array($kat->id, $selectedKategori) ? 'selected' : '' }}>
                                    {{ $kat->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-12 mb-3">
                        <label for="alamat" class="form-label required-field">Alamat</label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="2" required>{{ old('alamat', $wisata->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-12 mb-3">
                        <label for="deskripsi" class="form-label required-field">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="5" required>{{ old('deskripsi', $wisata->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <p class="note-text">Berikan deskripsi yang menarik tentang wisata Anda, termasuk sejarah, keunikan, dan pengalaman yang ditawarkan.</p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="harga_tiket" class="form-label">Harga Tiket (Rp)</label>
                        <input type="number" class="form-control @error('harga_tiket') is-invalid @enderror" id="harga_tiket" name="harga_tiket" value="{{ old('harga_tiket', $wisata->harga_tiket) }}" min="0">
                        @error('harga_tiket')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <p class="note-text">Kosongkan jika tidak ada biaya tiket masuk.</p>
                    </div>
                </div>
            </div>
            
            <!-- Galeri Foto -->
            <div class="form-section">
                <h3 class="form-section-title"><i class="fas fa-images"></i> Galeri Foto</h3>
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label">Tambah Foto Baru</label>
                        <input type="file" class="form-control @error('gambar.*') is-invalid @enderror" id="gambar" name="gambar[]" accept="image/*" multiple>
                        @error('gambar.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <p class="note-text">Format yang didukung: JPG, PNG, WEBP. Maksimal 5MB per gambar. Anda dapat memilih beberapa foto sekaligus.</p>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Foto Saat Ini</label>
                        @if($gambar->count() > 0)
                            <div class="galeri-container">
                                @foreach($gambar as $img)
                                    <div class="galeri-item">
                                        @if($img->is_utama)
                                            <span class="utama-badge">Utama</span>
                                        @endif
                                        <img src="{{ asset($img->file_gambar) }}" alt="{{ $img->judul ?? $wisata->nama }}" class="galeri-img">
                                        <div class="galeri-actions">
                                            @if(!$img->is_utama)
                                                <button type="button" class="galeri-btn btn-utama" onclick="setUtama({{ $img->id }})">
                                                    <i class="fas fa-star"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="galeri-btn btn-hapus" onclick="hapusGambar({{ $img->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        <input type="hidden" name="existing_images[]" value="{{ $img->id }}">
                                        @if($img->is_utama)
                                            <input type="hidden" name="gambar_utama" value="{{ $img->id }}">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">Belum ada foto untuk wisata ini. Silakan tambahkan beberapa foto.</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Lokasi -->
            <div class="form-section">
                <h3 class="form-section-title"><i class="fas fa-map-marker-alt"></i> Lokasi</h3>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="latitude" class="form-label">Latitude</label>
                        <input type="text" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude', $wisata->latitude) }}">
                        @error('latitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="longitude" class="form-label">Longitude</label>
                        <input type="text" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude', $wisata->longitude) }}">
                        @error('longitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-12">
                        <p class="note-text mb-2">Klik pada peta untuk menentukan lokasi atau masukkan koordinat secara manual.</p>
                        <div id="map"></div>
                    </div>
                </div>
            </div>
            
            <!-- Jam Operasional -->
            <div class="form-section">
                <h3 class="form-section-title"><i class="fas fa-clock"></i> Jam Operasional</h3>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="jam_buka" class="form-label">Jam Buka</label>
                        <input type="time" class="form-control @error('jam_buka') is-invalid @enderror" id="jam_buka" name="jam_buka" value="{{ old('jam_buka', $wisata->jam_buka ? $wisata->jam_buka->format('H:i') : '') }}">
                        @error('jam_buka')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="jam_tutup" class="form-label">Jam Tutup</label>
                        <input type="time" class="form-control @error('jam_tutup') is-invalid @enderror" id="jam_tutup" name="jam_tutup" value="{{ old('jam_tutup', $wisata->jam_tutup ? $wisata->jam_tutup->format('H:i') : '') }}">
                        @error('jam_tutup')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-12 mb-3">
                        <label class="form-label">Hari Operasional</label>
                        <div class="hari-list">
                            @php
                                $hariList = [
                                    'senin' => 'Senin',
                                    'selasa' => 'Selasa',
                                    'rabu' => 'Rabu',
                                    'kamis' => 'Kamis',
                                    'jumat' => 'Jumat',
                                    'sabtu' => 'Sabtu',
                                    'minggu' => 'Minggu'
                                ];
                                $hariOperasional = old('hari_operasional', $wisata->hari_operasional ?? []);
                            @endphp
                            
                            @foreach($hariList as $key => $hari)
                                <div class="hari-item">
                                    <input type="checkbox" id="hari_{{ $key }}" name="hari_operasional[]" value="{{ $key }}" 
                                        {{ is_array($hariOperasional) && in_array($key, $hariOperasional) ? 'checked' : '' }}>
                                    <label for="hari_{{ $key }}">{{ $hari }}</label>
                                </div>
                            @endforeach
                        </div>
                        @error('hari_operasional')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Fasilitas -->
            <div class="form-section">
                <h3 class="form-section-title"><i class="fas fa-concierge-bell"></i> Fasilitas</h3>
                
                <div class="fasilitas-list">
                    @php
                        $fasilitasList = [
                            'Parkir', 'Toilet', 'Mushola', 'Warung Makan', 'Penginapan',
                            'Toko Souvenir', 'WiFi', 'Permainan Anak', 'Spot Foto'
                        ];
                        $fasilitasWisata = old('fasilitas', $wisata->fasilitas ?? []);
                    @endphp
                    
                    @foreach($fasilitasList as $fasilitas)
                        <div class="fasilitas-item">
                            <input type="checkbox" id="fasilitas_{{ Str::slug($fasilitas) }}" name="fasilitas[]" value="{{ $fasilitas }}" 
                                {{ is_array($fasilitasWisata) && in_array($fasilitas, $fasilitasWisata) ? 'checked' : '' }}>
                            <label for="fasilitas_{{ Str::slug($fasilitas) }}">{{ $fasilitas }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Informasi Kontak -->
            <div class="form-section">
                <h3 class="form-section-title"><i class="fas fa-address-book"></i> Informasi Kontak</h3>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="kontak" class="form-label">Nomor Telepon</label>
                        <input type="text" class="form-control @error('kontak') is-invalid @enderror" id="kontak" name="kontak" value="{{ old('kontak', $wisata->kontak) }}">
                        @error('kontak')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $wisata->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="website" class="form-label">Website</label>
                        <input type="url" class="form-control @error('website') is-invalid @enderror" id="website" name="website" value="{{ old('website', $wisata->website) }}">
                        @error('website')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="instagram" class="form-label">Instagram</label>
                        <div class="input-group">
                            <span class="input-group-text">@</span>
                            <input type="text" class="form-control @error('instagram') is-invalid @enderror" id="instagram" name="instagram" value="{{ old('instagram', $wisata->instagram) }}" placeholder="username">
                        </div>
                        @error('instagram')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="facebook" class="form-label">Facebook</label>
                        <input type="url" class="form-control @error('facebook') is-invalid @enderror" id="facebook" name="facebook" value="{{ old('facebook', $wisata->facebook) }}" placeholder="https://facebook.com/...">
                        @error('facebook')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="twitter" class="form-label">Twitter</label>
                        <div class="input-group">
                            <span class="input-group-text">@</span>
                            <input type="text" class="form-control @error('twitter') is-invalid @enderror" id="twitter" name="twitter" value="{{ old('twitter', $wisata->twitter) }}" placeholder="username">
                        </div>
                        @error('twitter')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Tombol Submit -->
            <div class="text-end">
                <a href="{{ route('pemilik.wisata.index') }}" class="btn btn-cancel me-2">Batal</a>
                <button type="submit" class="btn btn-primary btn-submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Gambar -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus gambar ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    $(document).ready(function() {
        // Inisialisasi Select2
        $('.select2').select2({
            placeholder: 'Pilih kategori...',
            width: '100%'
        });
        
        // Inisialisasi peta
        const map = L.map('map').setView([
            {{ $wisata->latitude ?? '-2.6151' }}, 
            {{ $wisata->longitude ?? '115.4161' }}
        ], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        // Tambahkan marker jika sudah ada koordinat
        let marker;
        
        @if($wisata->latitude && $wisata->longitude)
            marker = L.marker([{{ $wisata->latitude }}, {{ $wisata->longitude }}]).addTo(map);
        @endif
        
        // Fungsi untuk update marker dan nilai input
        function updateMarker(lat, lng) {
            // Hapus marker lama jika ada
            if (marker) {
                map.removeLayer(marker);
            }
            
            // Tambah marker baru
            marker = L.marker([lat, lng]).addTo(map);
            
            // Update nilai input
            $('#latitude').val(lat);
            $('#longitude').val(lng);
        }
        
        // Event listener untuk klik pada peta
        map.on('click', function(e) {
            updateMarker(e.latlng.lat, e.latlng.lng);
        });
        
        // Event listener untuk perubahan manual pada input
        $('#latitude, #longitude').change(function() {
            const lat = parseFloat($('#latitude').val());
            const lng = parseFloat($('#longitude').val());
            
            if (!isNaN(lat) && !isNaN(lng)) {
                updateMarker(lat, lng);
                map.setView([lat, lng], 13);
            }
        });
    });
    
    // Fungsi untuk mengatur gambar utama
    function setUtama(imageId) {
        $('input[name="gambar_utama"]').remove();
        $('<input>').attr({
            type: 'hidden',
            name: 'gambar_utama',
            value: imageId
        }).appendTo('form');
        
        $('.utama-badge').remove();
        $('.galeri-item').find('.btn-utama').show();
        
        const item = $(`.galeri-item input[value="${imageId}"]`).parent();
        item.prepend('<span class="utama-badge">Utama</span>');
        item.find('.btn-utama').hide();
        
        alert('Gambar telah diatur sebagai gambar utama.');
    }
    
    // Fungsi untuk menampilkan konfirmasi hapus gambar
    function hapusGambar(imageId) {
        const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        
        $('#confirmDeleteBtn').off('click').on('click', function() {
            // Kirim request AJAX untuk menghapus gambar
            $.ajax({
                url: "{{ url('pemilik/gambar') }}/" + imageId,
                type: 'DELETE',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(result) {
                    // Hapus elemen dari DOM
                    $(`.galeri-item input[value="${imageId}"]`).parent().remove();
                    modal.hide();
                    
                    // Periksa apakah masih ada gambar
                    if ($('.galeri-item').length === 0) {
                        $('.galeri-container').html('<p class="text-muted">Belum ada foto untuk wisata ini. Silakan tambahkan beberapa foto.</p>');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan saat menghapus gambar. Silakan coba lagi.');
                    console.error(xhr.responseText);
                    modal.hide();
                }
            });
        });
        
        modal.show();
    }
</script>
@endpush