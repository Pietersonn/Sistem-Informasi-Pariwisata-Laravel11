<!-- Contoh view: resources/views/admin/wisata/edit.blade.php -->

@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0">
                <h6>Edit Wisata</h6>
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

                <form method="POST" action="{{ route('admin.wisata.update', $wisata->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama">Nama Wisata <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                name="nama" value="{{ old('nama', $wisata->nama) }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="kategori">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori[]" id="kategori"
                                class="form-control select2 @error('kategori') is-invalid @enderror" multiple required>
                                @foreach ($kategori as $kat)
                                    <option value="{{ $kat->id }}"
                                        {{ in_array($kat->id, $selectedKategori) ? 'selected' : '' }}>
                                        {{ $kat->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alamat">Alamat <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3"
                            required>{{ old('alamat', $wisata->alamat) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5" required>{{ old('deskripsi', $wisata->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="jam_buka">Jam Buka</label>
                            <input type="time" class="form-control @error('jam_buka') is-invalid @enderror"
                                id="jam_buka" name="jam_buka"
                                value="{{ old('jam_buka', $wisata->jam_buka ? $wisata->jam_buka->format('H:i') : '') }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="jam_tutup">Jam Tutup</label>
                            <input type="time" class="form-control @error('jam_tutup') is-invalid @enderror"
                                id="jam_tutup" name="jam_tutup"
                                value="{{ old('jam_tutup', $wisata->jam_tutup ? $wisata->jam_tutup->format('H:i') : '') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Hari Operasional</label>
                            <div class="d-flex flex-wrap">
                                @php
                                    $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
                                    $hariOperasional = old('hari_operasional', $wisata->hari_operasional) ?? [];
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
                                id="harga_tiket" name="harga_tiket" value="{{ old('harga_tiket', $wisata->harga_tiket) }}"
                                min="0" step="1000">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="kontak">Kontak</label>
                            <input type="text" class="form-control @error('kontak') is-invalid @enderror" id="kontak"
                                name="kontak" value="{{ old('kontak', $wisata->kontak) }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email', $wisata->email) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="website">Website</label>
                            <input type="url" class="form-control @error('website') is-invalid @enderror" id="website"
                                name="website" value="{{ old('website', $wisata->website) }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="instagram">Instagram</label>
                            <div class="input-group">
                                <span class="input-group-text">@</span>
                                <input type="text" class="form-control @error('instagram') is-invalid @enderror"
                                    id="instagram" name="instagram" value="{{ old('instagram', $wisata->instagram) }}">
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="facebook">Facebook</label>
                            <input type="url" class="form-control @error('facebook') is-invalid @enderror"
                                id="facebook" name="facebook" value="{{ old('facebook', $wisata->facebook) }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="twitter">Twitter</label>
                            <div class="input-group">
                                <span class="input-group-text">@</span>
                                <input type="text" class="form-control @error('twitter') is-invalid @enderror"
                                    id="twitter" name="twitter" value="{{ old('twitter', $wisata->twitter) }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="fasilitas">Fasilitas</label>
                        <div class="row">
                            @php
                                $selectedFasilitas = old('fasilitas', $wisata->fasilitas) ?? [];
                            @endphp
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fasilitas[]"
                                        id="fasilitas_parkir" value="Parkir"
                                        {{ in_array('Parkir', $selectedFasilitas) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="fasilitas_parkir">Parkir</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fasilitas[]"
                                        id="fasilitas_toilet" value="Toilet"
                                        {{ in_array('Toilet', $selectedFasilitas) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="fasilitas_toilet">Toilet</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fasilitas[]"
                                        id="fasilitas_mushola" value="Mushola"
                                        {{ in_array('Mushola', $selectedFasilitas) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="fasilitas_mushola">Mushola</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fasilitas[]"
                                        id="fasilitas_warung_makan" value="Warung Makan"
                                        {{ in_array('Warung Makan', $selectedFasilitas) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="fasilitas_warung_makan">Warung Makan</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fasilitas[]"
                                        id="fasilitas_penginapan" value="Penginapan"
                                        {{ in_array('Penginapan', $selectedFasilitas) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="fasilitas_penginapan">Penginapan</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fasilitas[]"
                                        id="fasilitas_toko_souvenir" value="Toko Souvenir"
                                        {{ in_array('Toko Souvenir', $selectedFasilitas) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="fasilitas_toko_souvenir">Toko Souvenir</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fasilitas[]"
                                        id="fasilitas_wifi" value="WiFi"
                                        {{ in_array('WiFi', $selectedFasilitas) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="fasilitas_wifi">WiFi</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fasilitas[]"
                                        id="fasilitas_permainan_anak" value="Permainan Anak"
                                        {{ in_array('Permainan Anak', $selectedFasilitas) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="fasilitas_permainan_anak">Permainan Anak</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fasilitas[]"
                                        id="fasilitas_spot_foto" value="Spot Foto"
                                        {{ in_array('Spot Foto', $selectedFasilitas) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="fasilitas_spot_foto">Spot Foto</label>
                                </div>
                            </div>
                        </div>
                        @error('fasilitas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Pilih satu atau lebih fasilitas yang tersedia</small>
                    </div>


                    <div class="mb-3">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror"
                            required>
                            <option value="aktif" {{ $wisata->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ $wisata->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif
                            </option>
                            <option value="menunggu_persetujuan"
                                {{ $wisata->status == 'menunggu_persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan
                            </option>
                        </select>
                    </div>

                    <!-- Bagian peta -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="latitude">Latitude</label>
                            <input type="number" step="any"
                                class="form-control @error('latitude') is-invalid @enderror" id="latitude"
                                name="latitude" value="{{ old('latitude', $wisata->latitude) }}">
                            <small class="form-text text-muted">Contoh: -2.6151</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="longitude">Longitude</label>
                            <input type="number" step="any"
                                class="form-control @error('longitude') is-invalid @enderror" id="longitude"
                                name="longitude" value="{{ old('longitude', $wisata->longitude) }}">
                            <small class="form-text text-muted">Contoh: 115.4161</small>
                        </div>
                    </div>

                    <!-- Bagian Galeri Gambar -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6>Galeri Gambar</h6>
                        </div>
                        <div class="card-body">
                            <!-- Gambar Yang Sudah Ada -->
                            @if ($wisata->gambar->count() > 0)
                                <div class="mb-4">
                                    <h6 class="mb-3">Gambar Saat Ini</h6>
                                    <div class="row" id="existingImagesContainer">
                                        @foreach ($wisata->gambar as $img)
                                            <div class="col-md-3 mb-3" data-id="{{ $img->id }}">
                                                <div class="card">
                                                    <img src="{{ $img->url }}" class="card-img-top img-thumbnail"
                                                        alt="{{ $img->judul ?? $wisata->nama }}">
                                                    <div class="card-body p-2">
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input main-image-radio"
                                                                type="radio" name="gambar_utama"
                                                                value="{{ $img->id }}"
                                                                {{ $img->is_utama ? 'checked' : '' }}>
                                                            <label class="form-check-label">Gambar Utama</label>
                                                        </div>
                                                        <input type="hidden" name="urutan_gambar[{{ $img->id }}]"
                                                            value="{{ $img->urutan }}">
                                                        <div class="d-flex justify-content-between">
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger delete-image"
                                                                data-id="{{ $img->id }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-info edit-image"
                                                                data-id="{{ $img->id }}"
                                                                data-judul="{{ $img->judul }}"
                                                                data-deskripsi="{{ $img->deskripsi }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Upload Gambar Baru -->
                            <div class="mb-3">
                                <h6>Tambah Gambar Baru</h6>
                                <input type="file" class="form-control" name="gambar[]" accept="image/*" multiple
                                    id="gambar">
                                <div id="previewContainer" class="row mt-3">
                                    <!-- Preview gambar baru di sini -->
                                </div>
                            </div>
                        </div>
                    </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
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

    <!-- Modal Edit Gambar -->
    <div class="modal fade" id="editImageModal" tabindex="-1" aria-labelledby="editImageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editImageModalLabel">Edit Informasi Gambar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editImageId">
                    <div class="mb-3">
                        <label for="editJudul" class="form-label">Judul Gambar</label>
                        <input type="text" class="form-control" id="editJudul">
                    </div>
                    <div class="mb-3">
                        <label for="editDeskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="editDeskripsi" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="saveImageChanges">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <script>
        // Inisialisasi Select2
        $(document).ready(function() {
            $('.select2').select2();

            // Sortable untuk gambar yang sudah ada
            const sortableContainer = document.getElementById('existingImagesContainer');
            if (sortableContainer) {
                new Sortable(sortableContainer, {
                    animation: 150,
                    ghostClass: 'bg-light',
                    onEnd: function(evt) {
                        // Update urutan setelah drag & drop
                        updateImageOrder();
                    }
                });
            }

            // Fungsi untuk memperbarui urutan
            function updateImageOrder() {
                const items = document.querySelectorAll('#existingImagesContainer > div');
                items.forEach((item, index) => {
                    const id = item.dataset.id;
                    const input = item.querySelector(`input[name="urutan_gambar[${id}]"]`);
                    input.value = index + 1;
                });
            }

            // Hapus gambar
            $('.delete-image').on('click', function() {
                const id = $(this).data('id');
                if (confirm('Yakin ingin menghapus gambar ini?')) {
                    $.ajax({
                        url: "{{ url('admin/wisata/hapus-gambar') }}/" + id,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                $(`div[data-id="${id}"]`).remove();
                                // Jika tidak ada gambar utama, set yang pertama sebagai utama
                                if ($('.main-image-radio:checked').length === 0) {
                                    $('.main-image-radio').first().prop('checked', true);
                                }
                            }
                        }
                    });
                }
            });

            // Edit gambar
            $('.edit-image').on('click', function() {
                const id = $(this).data('id');
                const judul = $(this).data('judul') || '';
                const deskripsi = $(this).data('deskripsi') || '';

                $('#editImageId').val(id);
                $('#editJudul').val(judul);
                $('#editDeskripsi').val(deskripsi);

                $('#editImageModal').modal('show');
            });

            // Simpan perubahan gambar
            $('#saveImageChanges').on('click', function() {
                const id = $('#editImageId').val();
                const judul = $('#editJudul').val();
                const deskripsi = $('#editDeskripsi').val();

                $.ajax({
                    url: "{{ url('admin/wisata/update-info-gambar') }}/" + id,
                    type: 'PUT',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "judul": judul,
                        "deskripsi": deskripsi
                    },
                    success: function(response) {
                        if (response.success) {
                            $(`button[data-id="${id}"].edit-image`)
                                .data('judul', judul)
                                .data('deskripsi', deskripsi);

                            $('#editImageModal').modal('hide');
                        }
                    }
                });
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
                                                  <img src="${e.target.result}" class="card-img-top img-thumbnail" alt="Preview">
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
