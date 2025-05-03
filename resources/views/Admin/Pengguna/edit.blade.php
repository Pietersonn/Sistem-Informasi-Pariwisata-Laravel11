@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header pb-0">
            <h6>Edit Pengguna</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.pengguna.update', $pengguna->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $pengguna->name) }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $pengguna->email) }}" 
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password">Kata Sandi Baru (Kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" 
                               class="form-control" 
                               id="password_confirmation" 
                               name="password_confirmation">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="role">Role <span class="text-danger">*</span></label>
                        <select name="role" 
                                id="role" 
                                class="form-control @error('role') is-invalid @enderror" 
                                required>
                            @foreach($roles as $roleKey => $roleLabel)
                                <option value="{{ $roleKey }}" 
                                    {{ old('role', $pengguna->role) == $roleKey ? 'selected' : '' }}>
                                    {{ $roleLabel }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="foto_profil">Foto Profil</label>
                        <input type="file" 
                               class="form-control @error('foto_profil') is-invalid @enderror" 
                               id="foto_profil" 
                               name="foto_profil" 
                               accept="image/*">
                        @error('foto_profil')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="preview-container" class="mt-2">
                            @if($pengguna->foto_profil && $pengguna->foto_profil != 'default.jpg')
                                <img src="{{ asset('storage/' . $pengguna->foto_profil) }}" 
                                     alt="Foto Profil" 
                                     style="max-width: 200px; max-height: 200px; object-fit: cover;" 
                                     class="img-fluid rounded">
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.pengguna.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Perbarui Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview foto profil
    document.getElementById('foto_profil').addEventListener('change', function(event) {
        const previewContainer = document.getElementById('preview-container');
        previewContainer.innerHTML = ''; // Bersihkan preview sebelumnya

        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '200px';
                img.style.maxHeight = '200px';
                img.style.objectFit = 'cover';
                img.className = 'img-fluid rounded';
                previewContainer.appendChild(img);
            }
            reader.readAsDataURL(file);
        }
    });

    // Validasi form
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const passwordInput = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');

        form.addEventListener('submit', function(event) {
            // Validasi panjang kata sandi jika diisi
            if (passwordInput.value.length > 0 && passwordInput.value.length < 8) {
                event.preventDefault();
                alert('Kata sandi minimal 8 karakter');
                return;
            }

            // Validasi konfirmasi kata sandi
            if (passwordInput.value !== passwordConfirmation.value) {
                event.preventDefault();
                alert('Konfirmasi kata sandi tidak cocok');
                return;
            }
        });
    });
</script>
@endpush