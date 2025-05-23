@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h6>Daftar Event Wisata</h6>
                    <a href="{{ route('admin.event.create') }}" class="btn bg-gradient-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Event
                    </a>
                </div>

                <!-- Filter Section -->
                <form method="GET" class="my-3">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control"
                                placeholder="Cari event atau wisata..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-control">
                                <option value="">Semua Status</option>
                                @foreach ($statusList as $status)
                                    <option value="{{ $status }}"
                                        {{ request('status') == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="wisata" class="form-control">
                                <option value="">Semua Wisata</option>
                                @foreach ($wisataList as $wisata)
                                    <option value="{{ $wisata->id }}"
                                        {{ request('wisata') == $wisata->id ? 'selected' : '' }}>
                                        {{ $wisata->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="tanggal_mulai" class="form-control"
                                value="{{ request('tanggal_mulai') }}" placeholder="Tanggal Mulai">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="tanggal_selesai" class="form-control"
                                value="{{ request('tanggal_selesai') }}" placeholder="Tanggal Selesai">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-info btn-sm">Filter</button>
                            <a href="{{ route('admin.event.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body px-0 pt-0 pb-2">
                @if (session('success'))
                    <div class="alert alert-success mx-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Event</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Wisata
                                </th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Tanggal</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $event)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                @if ($event->poster)
                                                    <img src="{{ asset($event->poster) }}" class="avatar avatar-sm me-3"
                                                        alt="{{ $event->nama }}">
                                                @else
                                                    <div class="avatar avatar-sm me-3 bg-gradient-secondary">
                                                        <i class="fas fa-calendar-alt text-white"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $event->nama }}</h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    {{ Str::limit($event->deskripsi, 50) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $event->wisata->nama }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ Str::limit($event->wisata->alamat, 30) }}
                                        </p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">
                                            {{ $event->tanggal_mulai instanceof \Carbon\Carbon ? $event->tanggal_mulai->format('d M Y') : 'Tanggal tidak tersedia' }}
                                            -
                                            {{ $event->tanggal_selesai instanceof \Carbon\Carbon ? $event->tanggal_selesai->format('d M Y') : 'Tanggal tidak tersedia' }}
                                        </span>
                                    </td>

                                    <td class="align-middle text-center text-sm">
                                        <span
                                            class="badge badge-sm bg-gradient-{{ $event->status == 'aktif' ? 'success' : ($event->status == 'selesai' ? 'secondary' : 'warning') }}">
                                            {{ ucfirst($event->status) }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group" role="group">
                                            <a href="#"
                                                class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.event.edit', $event->id) }}"
                                                class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit Event">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <!-- Quick Status Update -->
                                            @if ($event->status != 'aktif')
                                                <form action="{{ route('admin.event.update-status', $event->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="aktif">
                                                    <button type="submit" class="btn btn-success btn-sm"
                                                        data-bs-toggle="tooltip" title="Aktifkan">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if ($event->status != 'selesai')
                                                <form action="{{ route('admin.event.update-status', $event->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="selesai">
                                                    <button type="submit" class="btn btn-secondary btn-sm"
                                                        data-bs-toggle="tooltip" title="Tandai Selesai">
                                                        <i class="fas fa-flag-checkered"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('admin.event.destroy', $event->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus event ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    data-bs-toggle="tooltip" title="Hapus Event">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <p class="text-sm text-secondary mb-0">Tidak ada event ditemukan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $events->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Event Statistics Cards -->
        <div class="row mt-4">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Event</p>
                                    <h5 class="font-weight-bolder">{{ $events->total() }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                    <i class="fas fa-calendar-alt text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Event Aktif</p>
                                    <h5 class="font-weight-bolder">{{ $events->where('status', 'aktif')->count() }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                    <i class="fas fa-play text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Event Selesai</p>
                                    <h5 class="font-weight-bolder">{{ $events->where('status', 'selesai')->count() }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div
                                    class="icon icon-shape bg-gradient-secondary shadow-secondary text-center rounded-circle">
                                    <i class="fas fa-flag-checkered text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Event Mendatang</p>
                                    <h5 class="font-weight-bolder">
                                        {{ $events->where('status', 'aktif')->where('tanggal_mulai', '>', now())->count() }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle">
                                    <i class="fas fa-clock text-lg opacity-10"></i>
                                </div>
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
        // Aktifkan tooltip
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush
