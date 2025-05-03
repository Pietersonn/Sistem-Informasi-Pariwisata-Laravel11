@extends('layouts.user_type.auth')


@section('content')

<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header">
            <h5>Tambah Wisata Baru</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.wisata.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Nama Wisata</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Alamat</label>
                        <input type="text" name="alamat" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Kategori</label>
                        <select name="kategori[]" class="form-control" multiple required>
                            @foreach($kategori as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                            <option value="menunggu_persetujuan">Menunggu Persetujuan</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="4" required></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Jam Buka</label>
                        <input type="time" name="jam_buka" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Jam Tutup</label>
                        <input type="time" name="jam_tutup" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Harga Tiket</label>
                        <input type="number" name="harga_tiket" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Kontak</label>
                        <input type="text" name="kontak" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label>Gambar Wisata</label>
                    <input type="file" name="gambar[]" class="form-control" multiple accept="image/*">
                </div>

                <button type="submit" class="btn btn-primary">Simpan Wisata</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Optional: Preview Image
    document.querySelector('input[name="gambar[]"]').addEventListener('change', function(e) {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = '';
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100px';
                img.style.margin = '5px';
                preview.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush