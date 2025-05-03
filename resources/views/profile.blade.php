@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid">
        <div class="page-header min-height-300 border-radius-xl mt-4"
            style="background-image: url('../assets/img/curved-images/curved0.jpg'); background-position-y: 50%;">
            <span class="mask bg-gradient-primary opacity-6"></span>
        </div>
        <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <img src="{{ auth()->user()->foto_profil_url }}" alt="Profile Image"
                            class="w-100 border-radius-lg shadow-sm">
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            {{ auth()->user()->name }}
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">
                            {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid py-4">
            <div class="card">
                <div class="card-header pb-0 px-3">
                    <h6 class="mb-0">Informasi Profil</h6>
                </div>
                <div class="card-body pt-4 p-3">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Foto Profil</label>
                                <input type="file" class="form-control @error('foto_profil') is-invalid @enderror"
                                    name="foto_profil" accept="image/*">
                                @error('foto_profil')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="preview-container" class="mt-2">
                                    @if (auth()->user()->foto_profil && auth()->user()->foto_profil != 'default.jpg')
                                        <img src="{{ auth()->user()->foto_profil_url }}" alt="Foto Profil"
                                            style="max-width: 200px; max-height: 200px; object-fit: cover;"
                                            class="img-fluid rounded">
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Role</label>
                                <input type="text" class="form-control"
                                    value="{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kata Sandi Baru (Kosongkan jika tidak ingin mengubah)</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Perbarui Profil</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Preview foto profil
        document.querySelector('input[name="foto_profil"]').addEventListener('change', function(event) {
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
        document.querySelector('form').addEventListener('submit', function(event) {
            const passwordInput = document.querySelector('input[name="password"]');
            const passwordConfirmation = document.querySelector('input[name="password_confirmation"]');

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
    </script>
@endpush
