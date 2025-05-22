@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header pb-0">
            <h6>Edit Event: {{ $event->nama }}</h6>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.event.update', $event->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama">Nama Event <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                               id="nama" name="nama" value="{{ old('nama', $event->nama) }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="id_wisata">Wisata <span class="text-danger">*</span></label>
                        <select name="id_wisata" id="id_wisata" 
                                class="form-control @error('id_wisata') is-invalid @enderror" required>
                            <option value="">Pilih Wisata</option>
                            @foreach ($wisataList as $wisata)
                                <option value="{{ $wisata->id }}" 
                                    {{ old('id_wisata', $event->id_wisata) == $wisata->id ? 'selected' : '' }}>
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
                              id="deskripsi" name="deskripsi" rows="5" required>{{ old('deskripsi', $event->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="tanggal_mulai">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                               id="tanggal_mulai" name="tanggal_mulai" 
                               value="{{ old('tanggal_mulai', $event->tanggal_mulai->format('Y-m-d\TH:i')) }}" required>
                        @error('tanggal_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="tanggal_selesai">Tanggal Selesai <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                               id="tanggal_selesai" name="tanggal_selesai" 
                               value="{{ old('tanggal_selesai', $event->tanggal_selesai->format('Y-m-d\TH:i')) }}" required>
                        @error('tanggal_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="status">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" 
                                class="form-control @error('status') is-invalid @enderror" required>
                            <option value="aktif" {{ old('status', $event->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="selesai" {{ old('status', $event->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ old('status', $event->status) == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="poster">Poster Event</label>
                    <input type="file" class="form-control @error('poster') is-invalid @enderror" 
                           id="poster" name="poster" accept="image/*">
                    @error('poster')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Format yang didukung: JPG, PNG, WEBP, GIF. Maksimal 5MB.</small>
                    
                    <div id="preview-container" class="mt-2">
                        @if($event->poster)
                            <div class="current-poster">
                                <p><strong>Poster Saat Ini:</strong></p>
                                <img src="{{ asset($event->poster) }}" alt="{{ $event->nama }}" 
                                     style="max-width: 200px; max-height: 200px; object-fit: cover;" 
                                     class="img-fluid rounded border">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.event.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Perbarui Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview poster baru
    document.getElementById('poster').addEventListener('change', function(event) {
        const previewContainer = document.getElementById('preview-container');
        
        // Hapus preview sebelumnya kecuali poster lama
        const existingPreviews = previewContainer.querySelectorAll('.new-preview');
        existingPreviews.forEach(preview => preview.remove());

        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewDiv = document.createElement('div');
                previewDiv.className = 'new-preview mt-2';
                previewDiv.innerHTML = `
                    <p><strong>Preview Poster Baru:</strong></p>
                    <img src="${e.target.result}" style="max-width: 200px; max-height: 200px; object-fit: cover;" 
                         class="img-fluid rounded border">
                `;
                previewContainer.appendChild(previewDiv);
            }
            reader.readAsDataURL(file);
        }
    });

    // Validasi tanggal
    document.getElementById('tanggal_selesai').addEventListener('change', function() {
        const tanggalMulai = document.getElementById('tanggal_mulai').value;
        const tanggalSelesai = this.value;
        
        if (tanggalMulai && tanggalSelesai && tanggalSelesai < tanggalMulai) {
            alert('Tanggal selesai harus lebih besar atau sama dengan tanggal mulai');
            this.value = '';
        }
    });
</script>
@endpush