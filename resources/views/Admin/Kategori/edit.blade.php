@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header pb-0">
            <h6>Edit Kategori Wisata</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.kategori.update', $kategori->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-group mb-3">
                    <label for="nama">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('nama') is-invalid @enderror" 
                           id="nama" 
                           name="nama" 
                           value="{{ old('nama', $kategori->nama) }}" 
                           required>
                    @error('nama')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea 
                        class="form-control @error('deskripsi') is-invalid @enderror" 
                        id="deskripsi" 
                        name="deskripsi" 
                        rows="4">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="ikon">Ikon (Opsional)</label>
                    <input type="file" 
                           class="form-control @error('ikon') is-invalid @enderror" 
                           id="ikon" 
                           name="ikon" 
                           accept="image/*">
                    @error('ikon')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    
                    @if($kategori->ikon)
                        <div class="mt-2">
                            <strong>Ikon Saat Ini:</strong>
                            <img src="{{ asset('storage/' . $kategori->ikon) }}" 
                                 alt="Ikon Kategori" 
                                 style="max-width: 200px; max-height: 200px;">
                        </div>
                    @endif
                </div>

                <div class="form-group mb-3">
                    <label for="urutan">Urutan Tampilan</label>
                    <input type="number" 
                           class="form-control @error('urutan') is-invalid @enderror" 
                           id="urutan" 
                           name="urutan" 
                           value="{{ old('urutan', $kategori->urutan ?? 0) }}" 
                           min="0">
                    @error('urutan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <small class="form-text text-muted">Gunakan untuk mengurutkan kategori (0 adalah urutan pertama)</small>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Perbarui Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('ikon').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            const preview = document.getElementById('preview-ikon');
            if (!preview) {
                const img = document.createElement('img');
                img.id = 'preview-ikon';
                img.style.maxWidth = '200px';
                img.style.marginTop = '10px';
                event.target.parentNode.appendChild(img);
            }
            document.getElementById('preview-ikon').src = e.target.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush