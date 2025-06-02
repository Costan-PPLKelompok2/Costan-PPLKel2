{{-- resources/views/owner-reviews/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Review untuk ' . $owner->name)

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            {{-- Header dengan info owner --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between flex-wrap">
                        <div class="d-flex align-items-center">
                            <div class="avatar me-3">
                                @if($owner->profile_picture)
                                    <img src="{{ asset('storage/' . $owner->profile_picture) }}" 
                                         alt="{{ $owner->name }}" 
                                         class="rounded-circle" 
                                         width="80" height="80">
                                @else
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 80px; height: 80px;">
                                        <i class="fas fa-user text-white fa-2x"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h2 class="mb-1">{{ $owner->name }}</h2>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-home me-1"></i>
                                    Pemilik Kos
                                </p>
                                @if($reviews->count() > 0)
                                    <div class="d-flex align-items-center">
                                        <div class="rating-display me-2">
                                            @php
                                                $avgRating = $reviews->avg('rating');
                                                $fullStars = floor($avgRating);
                                                $hasHalfStar = ($avgRating - $fullStars) >= 0.5;
                                            @endphp
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $fullStars)
                                                    <i class="fas fa-star text-warning"></i>
                                                @elseif($i == $fullStars + 1 && $hasHalfStar)
                                                    <i class="fas fa-star-half-alt text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-muted"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-muted">
                                            {{ number_format($avgRating, 1) }} dari {{ $reviews->count() }} review
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="mt-3 mt-md-0">
                            <a href="{{ route('owner-reviews.create', $owner->id) }}" 
                               class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                Tulis Review
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Success message --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Reviews Section --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">
                        <i class="fas fa-comments me-2"></i>
                        Review dari Penyewa
                        <span class="badge bg-primary ms-2">{{ $reviews->count() }}</span>
                    </h4>
                </div>
                
                <div class="card-body">
                    @if($reviews->isEmpty())
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-comment-slash fa-3x text-muted"></i>
                            </div>
                            <h5 class="text-muted">Belum ada review</h5>
                            <p class="text-muted mb-4">
                                Jadilah yang pertama memberikan review untuk pemilik kos ini!
                            </p>
                            <a href="{{ route('owner-reviews.create', $owner->id) }}" 
                               class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                Tulis Review Pertama
                            </a>
                        </div>
                    @else
                        {{-- Filter dan sorting --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle" 
                                            type="button" 
                                            id="filterDropdown" 
                                            data-bs-toggle="dropdown">
                                        <i class="fas fa-filter me-1"></i>
                                        Filter Rating
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item filter-rating" href="#" data-rating="all">Semua Rating</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        @for($i = 5; $i >= 1; $i--)
                                            <li>
                                                <a class="dropdown-item filter-rating" href="#" data-rating="{{ $i }}">
                                                    {{ $i }} Bintang
                                                    <span class="badge bg-light text-dark ms-1">
                                                        {{ $reviews->where('rating', $i)->count() }}
                                                    </span>
                                                </a>
                                            </li>
                                        @endfor
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-secondary sort-btn active" data-sort="newest">
                                        <i class="fas fa-clock me-1"></i>
                                        Terbaru
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary sort-btn" data-sort="oldest">
                                        <i class="fas fa-history me-1"></i>
                                        Terlama
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary sort-btn" data-sort="highest">
                                        <i class="fas fa-arrow-up me-1"></i>
                                        Rating Tertinggi
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Reviews list --}}
                        <div id="reviewsList">
                            @foreach($reviews as $review)
                                <div class="review-item mb-4 p-4 border rounded-3" 
                                     data-rating="{{ $review->rating }}"
                                     data-date="{{ $review->created_at->timestamp }}">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="reviewer-avatar me-3">
                                                @if($review->reviewer && $review->reviewer->profile_picture)
                                                    <img src="{{ asset('storage/' . $review->reviewer->profile_picture) }}" 
                                                         alt="{{ $review->reviewer->name }}" 
                                                         class="rounded-circle" 
                                                         width="50" height="50">
                                                @else
                                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px;">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-bold">
                                                    {{ $review->reviewer->name ?? 'Pengguna Anonim' }}
                                                </h6>
                                                <div class="rating-stars mb-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <i class="fas fa-star text-warning"></i>
                                                        @else
                                                            <i class="far fa-star text-muted"></i>
                                                        @endif
                                                    @endfor
                                                    <span class="ms-2 text-muted small">
                                                        {{ $review->rating }}/5
                                                    </span>
                                                </div>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    {{ $review->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                        </div>
                                        
                                        {{-- Action buttons --}}
                                        <div class="dropdown">
                                            <button class="btn btn-link text-muted btn-sm" 
                                                    type="button" 
                                                    data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                {{-- Show edit/delete only for review owner --}}
                                                @if(auth()->check() && auth()->id() === $review->reviewer_id)
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('owner-reviews.edit', $review->id) }}">
                                                            <i class="fas fa-edit me-1 text-warning"></i>
                                                            Edit Review
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item text-danger" 
                                                                onclick="deleteReview({{ $review->id }})">
                                                            <i class="fas fa-trash me-1"></i>
                                                            Hapus Review
                                                        </button>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                @endif
                                                <li>
                                                    <a class="dropdown-item text-danger" href="#" 
                                                       onclick="reportReview({{ $review->id }})">
                                                        <i class="fas fa-flag me-1"></i>
                                                        Laporkan Review
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    @if($review->comment)
                                        <div class="review-comment">
                                            <p class="mb-0">{{ $review->comment }}</p>
                                        </div>
                                    @endif
                                    
                                    {{-- Helpful buttons --}}
                                    <div class="review-actions mt-3 pt-3 border-top">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="helpful-buttons">
                                                <button class="btn btn-sm btn-outline-success me-2" 
                                                        onclick="markHelpful({{ $review->id }}, 'helpful')">
                                                    <i class="fas fa-thumbs-up me-1"></i>
                                                    Membantu
                                                    <span class="badge bg-success ms-1">0</span>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" 
                                                        onclick="markHelpful({{ $review->id }}, 'not_helpful')">
                                                    <i class="fas fa-thumbs-down me-1"></i>
                                                    Tidak Membantu
                                                </button>
                                            </div>
                                            <small class="text-muted">
                                                Review #{{ $review->id }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        {{-- Pagination jika diperlukan --}}
                        @if($reviews->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $reviews->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus review ini?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    <small>Tindakan ini tidak dapat dibatalkan.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Batal
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>
                        Hapus Review
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Custom CSS --}}
<style>
.avatar img {
    object-fit: cover;
}

.reviewer-avatar img {
    object-fit: cover;
}

.review-item {
    transition: all 0.3s ease;
    background: #fff;
}

.review-item:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.rating-stars i {
    font-size: 0.9rem;
}

.rating-display i {
    font-size: 1.1rem;
}

.btn-group .btn.active {
    background-color: #0d6efd;
    color: white;
    border-color: #0d6efd;
}

.review-comment {
    padding: 15px;
    background-color: #f8f9fa;
    border-left: 4px solid #0d6efd;
    border-radius: 8px;
}

.helpful-buttons .btn {
    border-radius: 20px;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.dropdown-item.text-danger:hover {
    background-color: #f8d7da;
    color: #721c24 !important;
}

@media (max-width: 768px) {
    .btn-group {
        width: 100%;
        flex-direction: column;
    }
    
    .btn-group .btn {
        border-radius: 5px !important;
        margin-bottom: 5px;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 15px;
    }
}
</style>

{{-- JavaScript untuk interaktivitas --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const reviewItems = document.querySelectorAll('.review-item');
    const filterButtons = document.querySelectorAll('.filter-rating');
    const sortButtons = document.querySelectorAll('.sort-btn');
    
    // Filter by rating
    filterButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const filterRating = this.getAttribute('data-rating');
            
            reviewItems.forEach(item => {
                if (filterRating === 'all' || item.getAttribute('data-rating') === filterRating) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
    
    // Sort reviews
    sortButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            sortButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const sortType = this.getAttribute('data-sort');
            const reviewsList = document.getElementById('reviewsList');
            const reviews = Array.from(reviewItems);
            
            reviews.sort((a, b) => {
                switch(sortType) {
                    case 'newest':
                        return parseInt(b.getAttribute('data-date')) - parseInt(a.getAttribute('data-date'));
                    case 'oldest':
                        return parseInt(a.getAttribute('data-date')) - parseInt(b.getAttribute('data-date'));
                    case 'highest':
                        return parseInt(b.getAttribute('data-rating')) - parseInt(a.getAttribute('data-rating'));
                    default:
                        return 0;
                }
            });
            
            // Re-append sorted reviews
            reviews.forEach(review => reviewsList.appendChild(review));
        });
    });
});

// Function untuk delete review
function deleteReview(reviewId) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/owner-reviews/${reviewId}`;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// Function untuk mark helpful
function markHelpful(reviewId, type) {
    // Implementasi AJAX untuk mark helpful/not helpful
    console.log(`Marking review ${reviewId} as ${type}`);
    // Bisa ditambahkan AJAX call ke backend
}

// Function untuk report review
function reportReview(reviewId) {
    if (confirm('Apakah Anda yakin ingin melaporkan review ini?')) {
        // Implementasi AJAX untuk report review
        console.log(`Reporting review ${reviewId}`);
        // Bisa ditambahkan AJAX call ke backend
    }
}
</script>
@endsection