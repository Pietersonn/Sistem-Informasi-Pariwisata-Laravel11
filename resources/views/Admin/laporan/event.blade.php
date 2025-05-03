@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between align-items-center">
                <h6>Laporan Event Wisata</h6>
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
                    <div class="col-md-3">
                        <label>Tanggal Mulai</label>
                        <input type="date" 
                               name="tanggal_mulai" 
                               class="form-control" 
                               value="{{ $tanggalMulai }}">
                    </div>
                    <div class="col-md-3">
                        <label>Tanggal Selesai</label>
                        <input type="date" 
                               name="tanggal_selesai" 
                               class="form-control" 
                               value="{{ $tanggalSelesai }}">
                    </div>
                    <div class="col-md-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ $statusTerpilih == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="selesai" {{ $statusTerpilih == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ $statusTerpilih == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-3 align-self-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.laporan.event') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Total Event</h6>
                            <h3 class="card-text">{{ $statistik['total_event'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Event Aktif</h6>
                            <h3 class="card-text">{{ $statistik['event_aktif'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Event Selesai</h6>
                            <h3 class="card-text">{{ $statistik['event_selesai'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Event Dibatalkan</h6>
                            <h3 class="card-text">{{ $statistik['event_dibatalkan'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h6>Daftar Event</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nama Event</th>
                                            <th>Wisata</th>
                                            <th>Tanggal Mulai</th>
                                            <th>Tanggal Selesai</th>
                                            <th>Status</th>
                                            <th>Poster</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($event as $item)
                                            <tr>
                                                <td>{{ $item->nama }}</td>
                                                <td>{{ $item->wisata->nama }}</td>
                                                <td>{{ $item->tanggal_mulai->format('d M Y') }}</td>
                                                <td>{{ $item->tanggal_selesai->format('d M Y') }}</td>
                                                <td>
                                                    <span class="badge 
                                                        {{ $item->status == 'aktif' ? 'bg-success' : 
                                                           ($item->status == 'selesai' ? 'bg-secondary' : 'bg-warning') }}">
                                                        {{ $item->status_label }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($item->poster)
                                                        <img src="{{ asset('storage/' . $item->poster) }}" 
                                                             alt="{{ $item->nama }}" 
                                                             style="max-width: 100px; max-height: 100px; object-fit: cover;">
                                                    @else
                                                        <span class="text-muted">Tidak ada poster</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                
                                <div class="d-flex justify-content-center">
                                    {{ $event->appends(request()->input())->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6>Event Mendatang</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                @php
                                    $eventMendatang = $event->where('status', 'aktif')
                                        ->where('tanggal_mulai', '>', now())
                                        ->take(5);
                                @endphp
                                @forelse($eventMendatang as $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $item->nama }}</strong>
                                            <div class="text-muted small">
                                                {{ $item->wisata->nama }}
                                            </div>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">
                                            {{ $item->tanggal_mulai->diffForHumans() }}
                                        </span>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center">
                                        Tidak ada event mendatang
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6>Event Terbaru</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                @php
                                    $eventTerbaru = $event->sortByDesc('created_at')->take(5);
                                @endphp
                                @forelse($eventTerbaru as $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $item->nama }}</strong>
                                            <div class="text-muted small">
                                                {{ $item->wisata->nama }}
                                            </div>
                                        </div>
                                        <span class="badge bg-secondary rounded-pill">
                                            {{ $item->created_at->diffForHumans() }}
                                        </span>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center">
                                        Tidak ada event terbaru
                                    </li>
                                @endforelse
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
    // Validasi tanggal
    document.addEventListener('DOMContentLoaded', function() {
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