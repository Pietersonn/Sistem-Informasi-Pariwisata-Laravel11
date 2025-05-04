@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header pb-0">
            <h6>Tambah Wisata Baru</h6>
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

            <form method="POST" action="{{ route('admin.wisata.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama">Nama Wisata <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                            name="nama" value="{{ old('nama') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="kategori">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori[]" id="kategori"
                            class="form-control select2 @error('kategori') is-invalid @enderror" multiple required>
                            @foreach ($kategori as $kat)
                                <option value="{{ $kat->id }}"
                                    {{ in_array($kat->id, old('kategori', [])) ? 'selected' : '' }}>
                                    {{ $kat->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="alamat">Alamat <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3"
                        required>{{ old('alamat') }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="link_gmaps">Link Google Maps</label>
                        <input type="url" class="form-control @error('link_gmaps') is-invalid @enderror" id="link_gmaps"
                            name="link_gmaps" value="{{ old('link_gmaps') }}" placeholder="https://maps.google.com/...">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror"
                            required>
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif
                            </option>
                            <option value="menunggu_persetujuan"
                                {{ old('status') == 'menunggu_persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan
                            </option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="deskripsi">Deskripsi <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="5"
                        required>{{ old('deskripsi') }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="jam_buka">Jam Buka</label>
                        <input type="time" class="form-control @error('jam_buka') is-invalid @enderror"
                            id="jam_buka" name="jam_buka" value="{{ old('jam_buka') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="jam_tutup">Jam Tutup</label>
                        <input type="time" class="form-control @error('jam_tutup') is-invalid @enderror"
                            id="jam_tutup" name="jam_tutup" value="{{ old('jam_tutup') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Hari Operasional</label>
                        <div class="d-flex flex-wrap">
                            @php
                                $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
                                $hariOperasional = old('hari_operasional', []);
                            @endphp

                            @foreach ($hariList as $hari)
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="checkbox" id="hari_{{ $hari }}"
                                        name="hari_operasional[]" value="{{ $hari }}"
                                        {{ in_array($hari, $hariOperasional) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="hari_{{ $hari }}">
                                        {{ ucfirst($hari) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="harga_tiket">Harga Tiket (Rp)</label>
                        <input type="number" class="form-control @error('harga_tiket') is-invalid @enderror"
                            id="harga_tiket" name="harga_tiket" value="{{ old('harga_tiket') }}"
                            min="0" step="1000">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="kontak">Kontak</label>
                        <input type="text" class="form-control @error('kontak') is-invalid @enderror" id="kontak"
                            name="kontak" value="{{ old('kontak') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="website">Website</label>
                        <input type="url" class="form-control @error('website') is-invalid @enderror"
                            id="website" name="website" value="{{ old('website') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="instagram">Instagram</label>
                        <div class="input-group">
                            <span class="input-group-text">@</span>
                            <input type="text" class="form-control @error('instagram') is-invalid @enderror"
                                id="instagram" name="instagram" value="{{ old('instagram') }}">
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="facebook">Facebook</label>
                        <input type="url" class="form-control @error('facebook') is-invalid @enderror"
                            id="facebook" name="facebook" value="{{ old('facebook') }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="twitter">Twitter</label>
                        <div class="input-group">
                            <span class="input-group-text">@</span>
                            <input type="text" class="form-control @error('twitter') is-invalid @enderror"
                                id="twitter" name="twitter" value="{{ old('twitter') }}">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="fasilitas">Fasilitas</label>
                    <select name="fasilitas[]" id="fasilitas"
                        class="form-control select2 @error('fasilitas') is-invalid @enderror" multiple>
                        @foreach ($fasilitas as $fas)
                            <option value="{{ $fas->id }}"
                                {{ in_array($fas->id, old('fasilitas', [])) ? 'selected' : '' }}>
                                {{ $fas->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Bagian Upload Gambar -->
                <div class="card mt-4 mb-4">
                    <div class="card-header">
                        <h6>Upload Gambar</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="imageUpload">Pilih Gambar <small class="text-muted">(Multiple images allowed)</small></label>
                            <input type="file" class="form-control @error('gambar.*') is-invalid @enderror" 
                                   name="gambar[]" accept="image/*" multiple id="imageUpload">
                            @error('gambar.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Format yang didukung: JPG, JPEG, PNG, GIF, WEBP. Maksimal 5MB per file.
                            </small>
                        </div>

                        <div id="previewContainer" class="row mt-3">
                            <!-- JavaScript akan menampilkan preview gambar di sini -->
                        </div>

                        <div class="">
                            <i class="fas fa-info-circle me-2"></i>
                            Gambar pertama yang diupload akan dijadikan sebagai gambar utama. 
                            Anda dapat mengubahnya nanti di halaman edit.
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.wisata.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Simpan Wisata
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Inisialisasi Select2 untuk dropdown dengan multiple selection
        $('.select2').select2({
            placeholder: "Pilih opsi...",
            allowClear: true
        });

        // Validasi jam buka dan tutup
        $('#jam_tutup').on('change', function() {
            const jamBuka = $('#jam_buka').val();
            const jamTutup = $(this).val();
            
            if (jamBuka && jamTutup && jamBuka >= jamTutup) {
                alert('Jam tutup harus lebih besar dari jam buka');
                $(this).val('');
            }
        });

        // Preview gambar yang akan diupload
        $('#imageUpload').on('change', function(e) {
            const files = e.target.files;
            const previewContainer = $('#previewContainer');

            previewContainer.empty();

            if (files.length > 0) {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const preview = `
                          <div class="col-md-3 mb-3">
                              <div class="card">
                                  <img src="${e.target.result}" class="card-img-top img-thumbnail" alt="Preview" style="height: 180px; object-fit: cover;">
                                  <div class="card-body p-2">
                                      <div class="mb-2">
                                          <input type="text" class="form-control form-control-sm" 
                                                 name="judul_gambar[${i}]" placeholder="Judul (opsional)">
                                      </div>
                                      <div>
                                          <textarea class="form-control form-control-sm" 
                                                    name="deskripsi_gambar[${i}]" 
                                                    placeholder="Deskripsi (opsional)" 
                                                    rows="2"></textarea>
                                      </div>
                                      ${i === 0 ? '<div class="mt-1 text-center"><span class="badge bg-primary">Gambar Utama</span></div>' : ''}
                                  </div>
                              </div>
                          </div>
                      `;

                        previewContainer.append(preview);
                    };

                    reader.readAsDataURL(file);
                }
            }
        });
    });
</script>
@endpush