@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h6>Laporan Ulasan</h6>
                    <div>
                        <a href="{{ route('admin.laporan.ulasan.pdf', request()->all()) }}" class="btn btn-success btn-sm"
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
                                <option value="ditampilkan" {{ $statusTerpilih == 'ditampilkan' ? 'selected' : '' }}>
                                    Ditampilkan</option>
                                <option value="disembunyikan" {{ $statusTerpilih == 'disembunyikan' ? 'selected' : '' }}>
                                    Disembunyikan</option>
                                <option value="menunggu_moderasi"
                                    {{ $statusTerpilih == 'menunggu_moderasi' ? 'selected' : '' }}>Menunggu Moderasi
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3 align-self-end">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('admin.laporan.ulasan') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Total Ulasan</h6>
                                <h3 class="card-text">{{ $statistik['total_ulasan'] }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Ulasan Ditampilkan</h6>
                                <h3 class="card-text">{{ $statistik['ulasan_ditampilkan'] }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Ulasan Disembunyikan</h6>
                                <h3 class="card-text">{{ $statistik['ulasan_disembunyikan'] }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Menunggu Moderasi</h6>
                                <h3 class="card-text">{{ $statistik['ulasan_menunggu'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h6>Daftar Ulasan</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Wisata</th>
                                                <th>Pengunjung</th>
                                                <th>Rating</th>
                                                <th>Status</th>
                                                <th>Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($ulasan as $item)
                                                <tr>
                                                    <td>{{ $item->wisata->nama }}</td>
                                                    <td>{{ $item->pengguna->name }}</td>
                                                    <td>
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i
                                                                class="fas fa-star {{ $i <= $item->rating ? 'text-warning' : 'text-secondary' }}"></i>
                                                        @endfor
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge 
                                                        {{ $item->status == 'ditampilkan'
                                                            ? 'bg-success'
                                                            : ($item->status == 'disembunyikan'
                                                                ? 'bg-secondary'
                                                                : 'bg-warning') }}">
                                                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $item->created_at->format('d M Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <div class="d-flex justify-content-center">
                                        {{ $ulasan->appends(request()->input())->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6>Distribusi Rating</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="ratingChart"></canvas>
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

            // Grafik Distribusi Rating
            const ctx = document.getElementById('ratingChart').getContext('2d');
            const distribusiRating = @json($distribusiRating);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: distribusiRating.map(item => item.rating + ' Bintang'),
                    datasets: [{
                        label: 'Jumlah Ulasan',
                        data: distribusiRating.map(item => item.jumlah),
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
                                text: 'Jumlah Ulasan'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Rating'
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
