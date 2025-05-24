@extends('layouts.frontend')

@section('title', 'Kelola Ulasan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pemilik.css') }}">
    <style>
        .ulasan-management-container {
            background-color: #f8f9fa;
            min-height: 100vh;
            padding-top: 30px;
        }

        .page-header {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .ulasan-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .ulasan-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .ulasan-header {
            display: flex;
            justify-content: between;
            align-items: flex-start;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e2e8f0;
        }

        .reviewer-info {
            display: flex;
            align-items: center;
            flex: 1;
        }

        .reviewer-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
        }

        .reviewer-details h5 {
            margin: 0 0 5px 0;
            font-weight: 600;
            color: #2d3748;
        }

        .review-meta {
            font-size: 14px;
            color: #718096;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .rating-stars {
            color: #f59e0b;
            font-size: 16px;
        }

        .wisata-badge {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            margin-left: auto;
        }

        .ulasan-content {
            margin-bottom: 20px;
        }

        .ulasan-text {
            color: #4a5568;
            line-height: 1.6;
            font-size: 15px;
            margin-bottom: 10px;
        }

        .visit-date {
            font-size: 13px;
            color: #718096;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .balasan-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 15px;
        }

        .balasan-existing {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .balasan-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 10px;
        }

        .balasan-author {
            font-weight: 600;
            color: #1976d2;
            font-size: 14px;
        }

        .balasan-date {
            font-size: 12px;
            color: #666;
        }

        .balasan-text {
            color: #424242;
            line-height: 1.5;
            font-size: 14px;
        }

        .reply-form textarea {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            width: 100%;
            min-height: 80px;
            resize: vertical;
            font-family: inherit;
            transition: border-color 0.3s ease;
        }

        .reply-form textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-reply {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-reply:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .btn-toggle-reply {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-toggle-reply:hover {
            background: #667eea;
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-ditampilkan {
            background: #d4edda;
            color: #155724;
        }

        .status-menunggu_moderasi {
            background: #fff3cd;
            color: #856404;
        }

        .status-disembunyikan {
            background: #f8d7da;
            color: #721c24;
        }

        @media (max-width: 768px) {
            .ulasan-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .wisata-badge {
                margin-left: 0;
            }

            .reviewer-info {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="ulasan-management-container">
        <div class="container py-5">
            <div class="page-header">
                <h1 class="page-title">Kelola Ulasan</h1>
                <p class="page-subtitle">Lihat dan tanggapi ulasan untuk wisata Anda</p>

                <!-- Quick Stats -->
                <div class="quick-stats">
                    <div class="stat-card">
                        <div class="stat-number">{{ $ulasan->total() }}</div>
                        <div class="stat-label">Total Ulasan</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ $ulasan->where('status', 'ditampilkan')->count() }}</div>
                        <div class="stat-label">Ulasan Ditampilkan</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ $ulasan->where('status', 'menunggu_moderasi')->count() }}</div>
                        <div class="stat-label">Menunggu Moderasi</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ number_format($ulasan->avg('rating'), 1) }}</div>
                        <div class="stat-label">Rating Rata-rata</div>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Filter Section -->
            <div class="filter-section">
                <form method="GET" class="row align-items-end">
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Wisata</label>
                        <select name="wisata" class="form-select">
                            <option value="">Semua Wisata</option>
                            @foreach ($ulasan->groupBy('wisata.nama') as $namaWisata => $ulasanGroup)
                                <option value="{{ $ulasanGroup->first()->wisata->id }}"
                                    {{ request('wisata') == $ulasanGroup->first()->wisata->id ? 'selected' : '' }}>
                                    {{ $namaWisata }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="form-label">Rating</label>
                        <select name="rating" class="form-select">
                            <option value="">Semua Rating</option>
                            @for ($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                    {{ $i }} Bintang
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="ditampilkan" {{ request('status') == 'ditampilkan' ? 'selected' : '' }}>
                                Ditampilkan</option>
                            <option value="menunggu_moderasi"
                                {{ request('status') == 'menunggu_moderasi' ? 'selected' : '' }}>Menunggu Moderasi</option>
                            <option value="disembunyikan" {{ request('status') == 'disembunyikan' ? 'selected' : '' }}>
                                Disembunyikan</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('pemilik.ulasan.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-refresh me-1"></i>Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Ulasan List -->
            @if ($ulasan->count() > 0)
                @foreach ($ulasan as $review)
                    <div class="ulasan-card">
                        <div class="ulasan-header">
                            <div class="reviewer-info">
                                <img src="{{ $review->pengguna->foto_profil_url }}" alt="{{ $review->pengguna->name }}"
                                    class="reviewer-avatar">
                                <div class="reviewer-details">
                                    <h5>{{ $review->pengguna->name }}</h5>
                                    <div class="review-meta">
                                        <div class="meta-item">
                                            <i class="fas fa-calendar-alt"></i>
                                            {{ $review->created_at->diffForHumans() }}
                                        </div>
                                        <div class="meta-item rating-stars">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? '' : 'text-muted' }}"></i>
                                            @endfor
                                            <span class="ms-1 text-dark">{{ $review->rating }}/5</span>
                                        </div>
                                        <div class="meta-item">
                                            <span class="status-badge status-{{ $review->status }}">
                                                {{ ucfirst(str_replace('_', ' ', $review->status)) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="wisata-badge">
                                {{ $review->wisata->nama }}
                            </div>
                        </div>

                        <div class="ulasan-content">
                            <p class="ulasan-text">{{ $review->komentar }}</p>
                            @if ($review->tanggal_kunjungan)
                                <div class="visit-date">
                                    <i class="fas fa-map-marker-alt"></i>
                                    
                                </div>
                            @endif
                        </div>

                        <!-- Balasan Section -->
                        <div class="balasan-section">
                            <h6 class="mb-3">
                                <i class="fas fa-reply me-2"></i>Balasan Anda
                            </h6>

                            <!-- Existing Reply -->
                            @if ($review->balasan->where('id_pengguna', auth()->id())->first())
                                @php $balasan = $review->balasan->where('id_pengguna', auth()->id())->first(); @endphp
                                <div class="balasan-existing">
                                    <div class="balasan-header">
                                        <span class="balasan-author">{{ $balasan->pengguna->name }}</span>
                                        <span class="balasan-date">{{ $balasan->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="balasan-text">{{ $balasan->balasan }}</div>
                                </div>
                            @endif

                            <!-- Reply Form -->
                            <div class="reply-form" id="replyForm{{ $review->id }}"
                                style="{{ $review->balasan->where('id_pengguna', auth()->id())->first() ? 'display:none;' : '' }}">
                                <form action="{{ route('pemilik.ulasan.balas', $review->id) }}" method="POST">
                                    @csrf
                                    <textarea name="balasan" placeholder="Tulis balasan Anda untuk ulasan ini..." required>{{ $review->balasan->where('id_pengguna', auth()->id())->first()?->balasan ?? '' }}</textarea>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Balasan akan membantu meningkatkan kepercayaan
                                            pengunjung</small>
                                        <button type="submit" class="btn-reply">
                                            <i class="fas fa-paper-plane me-2"></i>
                                            {{ $review->balasan->where('id_pengguna', auth()->id())->first() ? 'Perbarui Balasan' : 'Kirim Balasan' }}
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Toggle Reply Button -->
                            @if ($review->balasan->where('id_pengguna', auth()->id())->first())
                                <button type="button" class="btn-toggle-reply"
                                    onclick="toggleReplyForm({{ $review->id }})">
                                    <i class="fas fa-edit me-1"></i>Edit Balasan
                                </button>
                            @else
                                <button type="button" class="btn-toggle-reply"
                                    onclick="toggleReplyForm({{ $review->id }})">
                                    <i class="fas fa-reply me-1"></i>Balas Ulasan
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $ulasan->appends(request()->input())->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-comments fa-4x text-secondary mb-3"></i>
                    </div>
                    <h3>Belum Ada Ulasan</h3>
                    <p>Wisata Anda belum memiliki ulasan dari pengunjung.</p>
                    <a href="{{ route('pemilik.wisata.index') }}" class="btn btn-primary">
                        <i class="fas fa-map-marked-alt me-2"></i>Kelola Wisata
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleReplyForm(reviewId) {
            const form = document.getElementById('replyForm' + reviewId);
            const isVisible = form.style.display !== 'none';

            if (isVisible) {
                form.style.display = 'none';
            } else {
                form.style.display = 'block';
                // Focus on textarea
                const textarea = form.querySelector('textarea');
                textarea.focus();
            }
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

            document.querySelectorAll('.ulasan-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.6s ease';
                observer.observe(card);
            });
        });
    </script>
@endpush
