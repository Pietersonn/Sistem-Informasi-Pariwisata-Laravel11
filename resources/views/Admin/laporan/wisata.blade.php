@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h6>Laporan Wisata</h6>
                    <div>
                        <a href="{{ route('admin.laporan.wisata.pdf', request()->all()) }}" class="btn btn-success btn-sm"
                            target="_blank">
                            <i class="fas fa-file-pdf"></i> Ekspor PDF
                        </a>
                        <a href="#" class="btn btn-primary btn-sm">
                            <i class="fas fa-file-excel"></i> Ekspor Excel
                        </a>
                    </div>
                </div>

                <form method="GET" class="my-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control" value="{{ $tanggalMulai }}">
                        </div>
                        <div class="col-md-3">
                            <label>Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control" value="{{ $tanggalSelesai }}">
                        </div>
                        <div class="col-md-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">Semua Status</option>
                                <option value="aktif" {{ $statusTerpilih == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ $statusTerpilih == 'nonaktif' ? 'selected' : '' }}>Nonaktif
                                </option>
                                <option value="menunggu_persetujuan"
                                    {{ $statusTerpilih == 'menunggu_persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3 align-self-end">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('admin.laporan.wisata') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Total Wisata</h6>
                                <h3 class="card-text">{{ $statistik['total_wisata'] }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Wisata Aktif</h6>
                                <h3 class="card-text">{{ $statistik['wisata_aktif'] }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Wisata Nonaktif</h6>
                                <h3 class="card-text">{{ $statistik['wisata_nonaktif'] }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Menunggu Persetujuan</h6>
                                <h3 class="card-text">{{ $statistik['wisata_menunggu'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h6>Daftar Wisata</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Nama Wisata</th>
                                                <th>Pemilik</th>
                                                <th>Kategori</th>
                                                <th>Status</th>
                                                <th>Dibuat Pada</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($wisata as $item)
                                                <tr>
                                                    <td>{{ $item->nama }}</td>
                                                    <td>{{ $item->pemilik->name ?? 'Tidak diketahui' }}</td>
                                                    <td>
                                                        @foreach ($item->kategori as $kategori)
                                                            <span class="badge bg-primary me-1">
                                                                {{ $kategori->nama }}
                                                            </span>
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge 
                                                        {{ $item->status == 'aktif' ? 'bg-success' : ($item->status == 'nonaktif' ? 'bg-secondary' : 'bg-warning') }}">
                                                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $item->created_at->format('d M Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <div class="d-flex justify-content-center">
                                        {{ $wisata->appends(request()->input())->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6>Top 5 Wisata Berdasarkan Rating</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    @foreach ($topWisata as $wisata)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $wisata->nama }}
                                            <span class="badge bg-primary">
                                                {{ number_format($wisata->rata_rata_rating, 1) }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Optional: Tambahkan interaksi atau fungsi tambahan untuk laporan
        document.addEventListener('DOMContentLoaded', function() {
            // Contoh: Validasi tanggal
            const tanggalMulai = document.querySelector('input[name="tanggal_mulai"]');
            const tanggalSelesai = document.querySelector('input[name="tanggal_selesai"]');

            tanggalSelesai.addEventListener('change', function() {
                if (tanggalMulai.value && this.value) {
                    const mulai = new Date(tanggalMulai.value);
                    const selesai = new Date(this.value);

                    if (selesai < mulai) {
                        alert('Tanggal selesai harus lebih besar dari tanggal mulai');
                        this.value = '';
                    }
                }
            });
        });
    </script>
@endpush
