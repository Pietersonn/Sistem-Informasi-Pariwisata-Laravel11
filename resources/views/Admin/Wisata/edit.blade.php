@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header pb-0">
            <h6>Edit Wisata</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.wisata.update', $wisata->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama">Nama Wisata <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('nama') is-invalid @enderror" 
                               id="nama" 
                               name="nama" 
                               value="{{ old('nama', $wisata->nama) }}" 
                               required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="kategori">Kategori <span class="text-danger">*</span></label>
                        <select 
                            name="kategori[]" 
                            id="kategori" 
                            class="form-control @error('kategori') is-invalid @enderror" 
                            multiple 
                            required>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->id }}" 
                                    {{ in_array($kat->id, $selectedKategori) ? 'selected' : '' }}>
                                    {{ $kat->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="alamat">Alamat <span class="text-danger">*</span></label>
                    <textarea 
                        class="form-control @error('alamat') is-invalid @enderror" 
                        id="alamat" 
                        name="alamat" 
                        rows="3" 
                        required>{{ old('alamat', $wisata->alamat) }}</textarea>
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea 
                        class="form-control @error('deskripsi') is-invalid @enderror" 
                        id="deskripsi" 
                        name="deskripsi" 
                        rows="4">{{ old('deskripsi', $wisata->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="jam_buka">Jam Buka</label>
                        <input type="time" 
                               class="form-control @error('jam_buka') is-invalid @enderror" 
                               id="jam_buka" 
                               name="jam_buka" 
                               value="{{ old('jam_buka', $wisata->jam_buka ? $wisata->jam_buka->format('H:i') : '') }}">
                        @error('jam_buka')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="jam_tutup">Jam Tutup</label>
                        <input type="time" 
                               class="form-control @error('jam_tutup') is-invalid @enderror" 
                               id="jam_tutup" 
                               name="jam_tutup" 
                               value="{{ old('jam_tutup', $wisata->jam_tutup ? $wisata->jam_tutup->format('H:i') : '') }}">
                        @error('jam_tutup')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="harga_tiket">Harga Tiket</label>
                        <input type="number" 
                               class="form-control @error('harga_tiket') is-invalid @enderror" 
                               id="harga_tiket" 
                               name="harga_tiket" 
                               value="{{ old('harga_tiket', $wisata->harga_tiket) }}" 
                               min="0">
                        @error('harga_tiket')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="status">Status</label>
                        <select 
                            name="status" 
                            id="status" 
                            class="form-control @error('status') is-invalid @enderror" 
                            required>
                            <option value="aktif" {{ $wisata->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ $wisata->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            <option value="menunggu_persetujuan" {{ $wisata->status == 'menunggu_persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="gambar">Gambar Wisata</label>
                        <input type="file" 
                               class="form-control @error('gambar') is-invalid @enderror" 
                               id="gambar" 
                               name="gambar[]" 
                               multiple 
                               accept="image/*">
                        @error('gambar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        @if($wisata->gambar->count() > 0)
                            <div class="mt-3">
                                <strong>Gambar Saat Ini:</strong>
                                <div class="row">
                                    @foreach($wisata->gambar as $gambar)
                                        <div class="col-md-3 mb-2">
                                            <img src="{{ $gambar->url }}" 
                                                 alt="Gambar Wisata" 
                                                 class="img-fluid" 
                                                 style="max-height: 200px; object-fit: cover;">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.wisata.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Perbarui Wisata
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Optional: Tambahkan preview untuk input file gambar
    document.getElementById('gambar').addEventListener('change', function(event) {
        const previewContainer = document.getElementById('preview-gambar');
        if (previewContainer) {
            previewContainer.innerHTML = ''; // Bersihkan preview sebelumnya
        }

        Array.from(event.target.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '200px';
                img.style.margin = '10px';
                
                if (!previewContainer) {
                    const container = document.createElement('div');
                    container.id = 'preview-gambar';
                    container.style.display = 'flex';
                    container.style.flexWrap = 'wrap';
                    event.target.parentNode.appendChild(container);
                }
                
                document.getElementById('preview-gambar').appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush