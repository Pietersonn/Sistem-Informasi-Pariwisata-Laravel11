@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header pb-0">
            <h6>Edit Pengguna</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.pengguna.update', $pengguna->id) }}">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama">Nama <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('nama') is-invalid @enderror" 
                               id="nama" 
                               name="nama" 
                               value="{{ old('nama', $pengguna->nama) }}" 
                               required>
                        @error('nama')
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
                        <label for="kata_sandi">Kata Sandi Baru (Kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" 
                               class="form-control @error('kata_sandi') is-invalid @enderror" 
                               id="kata_sandi" 
                               name="kata_sandi">
                        @error('kata_sandi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="kata_sandi_confirmation">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" 
                               class="form-control" 
                               id="kata_sandi_confirmation" 
                               name="kata_sandi_confirmation">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nomor_telepon">Nomor Telepon</label>
                        <input type="text" 
                               class="form-control @error('nomor_telepon') is-invalid @enderror" 
                               id="nomor_telepon" 
                               name="nomor_telepon" 
                               value="{{ old('nomor_telepon', $pengguna->nomor_telepon) }}">
                        @error('nomor_telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="role">Role <span class="text-danger">*</span></label>
                        <select 
                            name="role" 
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
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="status">Status <span class="text-danger">*</span></label>
                        <select 
                            name="status" 
                            id="status" 
                            class="form-control @error('status') is-invalid @enderror" 
                            required>
                            <option value="aktif" {{ old('status', $pengguna->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status', $pengguna->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            <option value="menunggu_verifikasi" {{ old('status', $pengguna->status) == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="alamat">Alamat</label>
                        <textarea 
                            class="form-control @error('alamat') is-invalid @enderror" 
                            id="alamat" 
                            name="alamat" 
                            rows="3">{{ old('alamat', $pengguna->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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