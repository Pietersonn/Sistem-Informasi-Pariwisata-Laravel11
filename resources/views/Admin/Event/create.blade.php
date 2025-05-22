@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header pb-0">
            <h6>Tambah Event Wisata Baru</h6>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.event.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama">Nama Event <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                               id="nama" name="nama" value="{{ old('nama') }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="id_wisata">Wisata <span class="text-danger">*</span></label>
                        <select name="id_wisata" id="id_wisata" 
                                class="form-control @error('id_wisata') is-invalid @enderror" required>
                            <option value="">Pilih Wisata</option>
                            @foreach($wisataList as $wisata)
                                <option value="{{ $wisata->id }}" {{ old('id_wisata') == $wisata->id ? 'selected' : '' }}>
                                    {{ $wisata->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_wisata')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="deskripsi">Deskripsi Event <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                              id="deskripsi" name="deskripsi" rows="5" required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Berikan deskripsi lengkap tentang event ini</small>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_mulai">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                               id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required>
                        @error('tanggal_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="tanggal_selesai">Tanggal Selesai <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                               id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required>
                        @error('tanggal_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="status">Status Event <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="">Pilih Status</option>
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ old('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="poster">Poster Event</label>
                        <input type="file" class="form-control @error('poster') is-invalid @enderror" 
                               id="poster" name="poster" accept="image/*">
                        @error('poster')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Format: JPG, PNG, WEBP, GIF. Maksimal 5MB</small>
                        <div id="preview-container" class="mt-2"></div>
                    </div>
                </div>

                <!-- Event Information Card -->
                <div class="card mt-4 mb-4" id="event-info-card" style="display: none;">
                    <div class="card-header">
                        <h6>Informasi Wisata Terpilih</h6>
                    </div>
                    <div class="card-body" id="wisata-info">
                        <!-- Akan diisi via JavaScript -->
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.event.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Simpan Event
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
    // Preview poster
    document.getElementById('poster').addEventListener('change', function(event) {
        const previewContainer = document.getElementById('preview-container');
        previewContainer.innerHTML = '';

        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '200px';
                img.style.maxHeight = '200px';
                img.style.objectFit = 'cover';
                img.className = 'img-fluid rounded mt-2';
                previewContainer.appendChild(img);
            }
            reader.readAsDataURL(file);
        }
    });

    // Validasi tanggal
    const tanggalMulai = document.getElementById('tanggal_mulai');
    const tanggalSelesai = document.getElementById('tanggal_selesai');

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

    // Tampilkan info wisata saat dipilih
    const wisataSelect = document.getElementById('id_wisata');
    const wisataInfo = document.getElementById('wisata-info');
    const infoCard = document.getElementById('event-info-card');

    // Data wisata dari server (simplified - dalam implementasi nyata bisa via AJAX)
    const wisataData = @json($wisataList->keyBy('id'));

    wisataSelect.addEventListener('change', function() {
        const wisataId = this.value;
        if (wisataId && wisataData[wisataId]) {
            const wisata = wisataData[wisataId];
            wisataInfo.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <strong>Nama:</strong> ${wisata.nama}<br>
                        <strong>Alamat:</strong> ${wisata.alamat}
                    </div>
                    <div class="col-md-6">
                        <strong>Status:</strong> <span class="badge bg-${wisata.status === 'aktif' ? 'success' : 'secondary'}">${wisata.status}</span><br>
                        <strong>Harga Tiket:</strong> ${wisata.harga_tiket ? 'Rp ' + parseInt(wisata.harga_tiket).toLocaleString('id-ID') : 'Gratis'}
                    </div>
                </div>
            `;
            infoCard.style.display = 'block';
        } else {
            infoCard.style.display = 'none';
        }
    });

    // Set minimum date to today
    const today = new Date();
    const todayString = today.toISOString().slice(0, 16);
    tanggalMulai.min = todayString;
});
</script>