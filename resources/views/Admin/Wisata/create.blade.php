@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header pb-0">
            <h6>Tambah Wisata Baru</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.wisata.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3">
                    <label for="nama">Nama Wisata</label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                </div>
                
                <div class="form-group mb-3">
                    <label for="alamat">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                </div>
                
                <div class="form-group mb-3">
                    <label for="kategori">Kategori</label>
                    <select class="form-control" id="kategori" name="kategori[]" multiple>
                        @foreach($kategori ?? [] as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5"></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="jam_buka">Jam Buka</label>
                            <input type="time" class="form-control" id="jam_buka" name="jam_buka">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="jam_tutup">Jam Tutup</label>
                            <input type="time" class="form-control" id="jam_tutup" name="jam_tutup">
                        </div>
                    </div>
                </div>
                
                <div class="form-group mb-3">
                    <label for="harga_tiket">Harga Tiket</label>
                    <input type="number" class="form-control" id="harga_tiket" name="harga_tiket" min="0">
                </div>
                
                <div class="form-group mb-3">
                    <label for="kontak">Kontak</label>
                    <input type="text" class="form-control" id="kontak" name="kontak">
                </div>
                
                <div class="form-group mb-3">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                        <option value="menunggu_persetujuan">Menunggu Persetujuan</option>
                    </select>
                </div>
                
                <button type="submit" class="btn bg-gradient-primary">Simpan</button>
                <a href="{{ route('admin.wisata.index') }}" class="btn bg-gradient-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection