@extends('layouts.frontend')

@section('title', 'Kelola Event Saya')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pemilik.css') }}">
<style>
    .event-management-container {
        background-color: #f8f9fa;
        min-height: 100vh;
        padding-top: 30px;
    }

    .page-header {
        background: white;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .page-title {
        font-size: 2.2rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 10px;
    }

    .page-subtitle {
        color: #718096;
        font-size: 1.1rem;
        margin-bottom: 20px;
    }

    .quick-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border-left: 4px solid #667eea;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 5px;
    }

    .stat-label {
        color: #718096;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
    }

    .btn-create-event {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 12px 25px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        font-size: 16px;
    }

    .btn-create-event:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        color: white;
    }

    .events-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
    }

    .event-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        position: relative;
    }

    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .event-image {
        height: 200px;
        position: relative;
        overflow: hidden;
    }

    .event-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .event-card:hover .event-image img {
        transform: scale(1.05);
    }

    .event-status {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        backdrop-filter: blur(10px);
    }

    .status-aktif {
        background: rgba(40, 167, 69, 0.9);
        color: white;
    }

    .status-selesai {
        background: rgba(108, 117, 125, 0.9);
        color: white;
    }

    .status-dibatalkan {
        background: rgba(220, 53, 69, 0.9);
        color: white;
    }

    .status-menunggu_persetujuan {
        background: rgba(255, 193, 7, 0.9);
        color: #333;
    }

    .event-date-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: rgba(255, 255, 255, 0.95);
        color: #667eea;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-align: center;
        min-width: 60px;
        backdrop-filter: blur(10px);
    }

    .event-content {
        padding: 20px;
    }

    .event-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: #2d3748;
        line-height: 1.3;
    }

    .event-location {
        display: flex;
        align-items: center;
        color: #e53e3e;
        font-size: 14px;
        margin-bottom: 12px;
        font-weight: 500;
    }

    .event-location i {
        margin-right: 8px;
    }

    .event-description {
        color: #4a5568;
        font-size: 14px;
        line-height: 1.5;
        margin-bottom: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .event-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: #718096;
        margin-bottom: 15px;
        padding-top: 15px;
        border-top: 1px solid #e2e8f0;
    }

    .event-actions {
        display: flex;
        gap: 8px;
    }

    .btn-event-action {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-edit {
        background: #ffd700;
        color: #333;
    }

    .btn-edit:hover {
        background: #ffed4e;
        color: #333;
    }

    .btn-view {
        background: #667eea;
        color: white;
    }

    .btn-view:hover {
        background: #5a67d8;
        color: white;
    }

    .btn-delete {
        background: #e53e3e;
        color: white;
    }

    .btn-delete:hover {
        background: #c53030;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .empty-state-icon {
        font-size: 4rem;
        color: #cbd5e0;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        color: #4a5568;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #718096;
        margin-bottom: 30px;
    }

    .filter-section {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .alert-pending {
        background: linear-gradient(135deg, #ffeaa7, #fab1a0);
        border: none;
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 20px;
        color: #2d3748;
    }

    .alert-pending .alert-icon {
        font-size: 1.2rem;
        margin-right: 10px;
    }

    @media (max-width: 768px) {
        .events-grid {
            grid-template-columns: 1fr;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .quick-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endpush

@section('content')
<div class="event-management-container">
    <div class="container py-5">
        <div class="page-header">
            <h1 class="page-title">Kelola Event Saya</h1>
            <p class="page-subtitle">Tambah dan kelola event untuk destinasi wisata Anda</p>
            
            <!-- Quick Stats -->
            <div class="quick-stats">
                <div class="stat-card">
                    <div class="stat-number">{{ $events->total() }}</div>
                    <div class="stat-label">Total Event</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $events->where('status', 'aktif')->count() }}</div>
                    <div class="stat-label">Event Aktif</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $events->where('status', 'menunggu_persetujuan')->count() }}</div>
                    <div class="stat-label">Menunggu Persetujuan</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $events->where('tanggal_mulai', '>', now())->count() }}</div>
                    <div class="stat-label">Event Mendatang</div>
                </div>
            </div>

            <div class="action-buttons">
                <a href="{{ route('pemilik.event.create') }}" class="btn-create-event">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Event Baru
                </a>
                <a href="{{ route('pemilik.wisata.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-map-marked-alt me-2"></i>Kelola Wisata
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($events->where('status', 'menunggu_persetujuan')->count() > 0)
            <div class="alert-pending">
                <i class="fas fa-clock alert-icon"></i>
                <strong>Menunggu Persetujuan:</strong> 
                Anda memiliki {{ $events->where('status', 'menunggu_persetujuan')->count() }} event yang menunggu persetujuan admin.
                Event akan ditampilkan setelah disetujui.
            </div>
        @endif

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" class="row align-items-end">
                <div class="col-md-3 mb-2">
                    <label class="form-label">Cari Event</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Nama event..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        <option value="menunggu_persetujuan" {{ request('status') == 'menunggu_persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label">Bulan</label>
                    <select name="bulan" class="form-select">
                        <option value="">Semua Bulan</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('pemilik.event.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-refresh me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Events Grid -->
        @if($events->count() > 0)
            <div class="events-grid">
                @foreach($events as $event)
                    <div class="event-card">
                        <div class="event-image">
                            <img src="{{ $event->poster ? asset($event->poster) : asset('images/placeholder-event.jpg') }}" 
                                 alt="{{ $event->nama }}">
                            <div class="event-date-badge">
                                {{ $event->tanggal_mulai->format('d M') }}
                            </div>
                            <div class="event-status status-{{ $event->status }}">
                                @if($event->status == 'menunggu_persetujuan')
                                    Menunggu
                                @else
                                    {{ ucfirst($event->status) }}
                                @endif
                            </div>
                        </div>
                        <div class="event-content">
                            <h3 class="event-title">{{ $event->nama }}</h3>
                            <p class="event-location">
                                <i class="fas fa-map-marker-alt"></i> 
                                {{ $event->wisata->nama }}
                            </p>
                            <p class="event-description">
                                {{ Str::limit($event->deskripsi, 100) }}
                            </p>
                            <div class="event-meta">
                                <div>
                                    <i class="fas fa-calendar-alt"></i>
                                    {{ $event->tanggal_mulai->format('d M') }} - {{ $event->tanggal_selesai->format('d M Y') }}
                                </div>
                                <div>
                                    <i class="fas fa-clock"></i>
                                    {{ $event->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <div class="event-actions">
                                <a href="{{ route('pemilik.event.edit', $event->id) }}" class="btn-event-action btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                @if($event->status == 'aktif')
                                    <a href="{{ route('event.detail', $event->id) }}" class="btn-event-action btn-view" target="_blank">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                @endif
                                <button class="btn-event-action btn-delete" onclick="confirmDelete({{ $event->id }}, '{{ $event->nama }}')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $events->appends(request()->input())->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h3>Belum Ada Event</h3>
                <p>Anda belum memiliki event yang terdaftar. Mulai buat event pertama Anda!</p>
                <a href="{{ route('pemilik.event.create') }}" class="btn-create-event">
                    <i class="fas fa-plus-circle me-2"></i>Buat Event Pertama
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus event <strong id="eventName"></strong>?</p>
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus Event</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(eventId, eventName) {
    document.getElementById('eventName').textContent = eventName;
    document.getElementById('deleteForm').action = `/pemilik/event/${eventId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Auto submit form when filters change
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.querySelector('.filter-section form');
    const selectInputs = filterForm.querySelectorAll('select');
    
    selectInputs.forEach(input => {
        input.addEventListener('change', function() {
            filterForm.submit();
        });
    });

    // Animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.event-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease';
        observer.observe(card);
    });
});
</script>
@endpush