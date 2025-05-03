@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between align-items-center">
                <h6>Detail Ulasan</h6>
                <div>
                    <form action="{{ route('admin.ulasan.update-status', $ulasan->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        @if($ulasan->status == 'menunggu_moderasi')
                            <button type="submit" name="status" value="ditampilkan" class="btn btn-success btn-sm">
                                <i class="fas fa-check"></i> Tampilkan
                            </button>
                            <button type="submit" name="status" value="disembunyikan" class="btn btn-secondary btn-sm">
                                <i class="fas fa-eye-slash"></i> Sembunyikan
                            </button>
                        @elseif($ulasan->status == 'ditampilkan')
                            <button type="submit" name="status" value="disembunyikan" class="btn btn-secondary btn-sm">
                                <i class="fas fa-eye-slash"></i> Sembunyikan
                            </button>
                        @else
                            <button type="submit" name="status" value="ditampilkan" class="btn btn-success btn-sm">
                                <i class="fas fa-check"></i> Tampilkan
                            </button>
                        @endif
                    </form>
                    <a href="{{ route('admin.ulasan.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6>Informasi Ulasan</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Wisata:</strong>
                                    <a href="{{ route('admin.wisata.show', $ulasan->wisata->id) }}">
                                        {{ $ulasan->wisata->nama }}
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <strong>Pengunjung:</strong> 
                                    {{ $ulasan->pengguna->name }}
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <strong>Rating:</strong>
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $ulasan->rating ? 'text-warning' : 'text-secondary' }}"></i>
                                    @endfor
                                </div>
                                <div class="col-md-6">
                                    <strong>Tanggal Kunjungan:</strong>
                                    {{ $ulasan->tanggal_kunjungan->format('d M Y') }}
                                </div>
                            </div>
                            <div class="mt-3">
                                <strong>Komentar:</strong>
                                <p>{{ $ulasan->komentar }}</p>
                            </div>
                            <div>
                                <strong>Status:</strong>
                                <span class="badge 
                                    {{ $ulasan->status == 'ditampilkan' ? 'bg-success' : 
                                       ($ulasan->status == 'disembunyikan' ? 'bg-secondary' : 'bg-warning') }}">
                                    {{ ucfirst(str_replace('_', ' ', $ulasan->status)) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($ulasan->balasan->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h6>Balasan Ulasan</h6>
                        </div>
                        <div class="card-body">
                            @foreach($ulasan->balasan as $balasan)
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $balasan->pengguna->name }}</strong>
                                    <small class="text-muted">
                                        {{ $balasan->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <p class="mt-2">{{ $balasan->balasan }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h6>Informasi Tambahan</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <strong>Dibuat pada:</strong>
                                {{ $ulasan->created_at->format('d M Y H:i') }}
                            </div>
                            <div class="mb-2">
                                <strong>Terakhir diperbarui:</strong>
                                {{ $ulasan->updated_at->format('d M Y H:i') }}
                            </div>
                            <div class="mb-2">
                                <strong>IP Address:</strong>
                                {{ request()->ip() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection