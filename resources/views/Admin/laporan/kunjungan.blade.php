@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between align-items-center">
                <h6>Laporan Kunjungan Wisata</h6>
                <div>
                    <a href="#" class="btn btn-success btn-sm">
                        <i class="fas fa-file-pdf"></i> Ekspor PDF
                    </a>
                    <a href="#" class="btn btn-primary btn-sm">
                        <i class="fas fa-file-excel"></i> Ekspor Excel
                    </a>
                </div>
            </div>

            <form method="GET" class="my-3">
                <div class="row">
                    <div class="col-md-4">
                        <label>Tanggal Mulai</label>
                        <input type="date" 
                               name="tanggal_mulai" 
                               class="form-control" 
                               value="{{ $tanggalMulai }}">
                    </div>
                    <div class="col-md-4">
                        <label>Tanggal Selesai</label>
                        <input type="date" 
                               name="tanggal_selesai" 
                               class="form-control" 
                               value="{{ $tanggalSelesai }}">
                    </div>
                    <div class="col-md-4 align-self-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.laporan.kunjungan') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Total Kunjungan</h6>
                            <h3 class="card-text">{{ number_format($statistik['total_kunjungan']) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Rata-rata Kunjungan</h6>
                            <h3 class="card-text">{{ number_format($statistik['rata_rata_kunjungan'], 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Wisata Terbanyak Dilihat</h6>
                            <h3 class="card-text">{{ $statistik['wisata_terbanyak_dilihat'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h6>10 Wisata Terbanyak Dilihat</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nama Wisata</th>
                                            <th>Kategori</th>
                                            <th>Jumlah Dilihat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topKunjungan as $item)
                                            <tr>
                                                <td>{{ $item->nama }}</td>
                                                <td>
                                                    @foreach($item->kategori as $kategori)
                                                        <span class="badge bg-primary me-1">
                                                            {{ $kategori->nama }}
                                                        </span>
                                                    @endforeach
                                                </td>
                                                <td>{{ number_format($item->jumlah_dilihat) }}</td>
                                                <td>
                                                    <a href="{{ route('admin.wisata.show', $item->id) }}" 
                                                       class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h6>Grafik Kunjungan</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="kunjunganChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Validasi tanggal
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

        // Grafik Kunjungan
        const ctx = document.getElementById('kunjunganChart').getContext('2d');
        const topKunjungan = @json($topKunjungan);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: topKunjungan.map(item => item.nama),
                datasets: [{
                    label: 'Jumlah Dilihat',
                    data: topKunjungan.map(item => item.jumlah_dilihat),
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Dilihat'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush