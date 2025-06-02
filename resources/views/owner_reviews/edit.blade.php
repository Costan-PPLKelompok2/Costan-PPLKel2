{{-- resources/views/owner-reviews/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Review untuk ' . $review->owner->name)

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Header --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="mb-1">
                                <i class="fas fa-edit me-2 text-warning"></i>
                                Edit Review
                            </h3>
                            <p class="text-muted mb-0">
                                Untuk pemilik kos: <strong>{{ $review->owner->name }}</strong>
                            </p>
                        </div>
                        <a href="{{ route('owner-reviews.show', $review->owner_id) }}" 
                           class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            {{-- Form Edit Review --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-star me-2"></i>
                        Edit Review Anda
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('owner-reviews.update', $review->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        {{-- Rating Section --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-star text-warning me-1"></i>
                                Rating <span class="text-danger">*</span>
                            </label>
                            <div class="rating-input mb-2">
                                <div class="star-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="star" data-rating="{{ $i }}">
                                            <i class="far fa-star"></i>
                                        </span>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="rating" value="{{ old('rating', $review->rating) }}">
                            </div>
                            <div class="rating-text">
                                <small class="text-muted" id="ratingText">
                                    Pilih rating dari 1 hingga 5 bintang
                                </small>
                            </div>
                            @error('rating')
                                <div class="text-danger small mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Comment Section --}}
                        <div class="mb-4">
                            <label for="comment" class="form-label fw-bold">
                                <i class="fas fa-comment me-1"></i>
                                Komentar
                            </label>
                            <textarea class="form-control @error('comment') is-invalid @enderror" 
                                      id="comment" 
                                      name="comment" 
                                      rows="5" 
                                      placeholder="Bagikan pengalaman Anda dengan pemilik kos ini... (opsional)"
                                      maxlength="1000">{{ old('comment', $review->comment) }}</textarea>
                            <div class="form-text">
                                <span id="charCount">{{ strlen(old('comment', $review->comment ?? '')) }}</span>/1000 karakter
                            </div>
                            @error('comment')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Preview Section --}}
                        <div class="mb-4" id="previewSection" style="display: none;">
                            <label class="form-label fw-bold">
                                <i class="fas fa-eye me-1"></i>
                                Preview Review
                            </label>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="reviewer-avatar me-3">
                                            @if(auth()->user()->profile_picture)
                                                <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" 
                                                     alt="{{ auth()->user()->name }}" 
                                                     class="rounded-circle" 
                                                     width="40" height="40">
                                            @else
                                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-1">{{ auth()->user()->name }}</h6>
                                            <div class="preview-stars mb-1">
                                                <!-- Stars will be populated by JavaScript -->
                                            </div>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                Sekarang
                                            </small>
                                        </div>
                                    </div>
                                    <div class="preview-comment" style="display: none;">
                                        <div class="mt-2 p-2 bg-white border-start border-primary border-3 rounded">
                                            <p class="mb-0" id="previewCommentText"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-info" id="togglePreview">
                                <i class="fas fa-eye me-1"></i>
                                <span id="previewButtonText">Lihat Preview</span>
                            </button>
                            <div>
                                <a href="{{ route('owner-reviews.show', $review->owner_id) }}" 
                                   class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-times me-1"></i>
                                    Batal
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-1"></i>
                                    Update Review
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tips Section --}}
            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-lightbulb me-1"></i>
                        Tips Menulis Review yang Baik
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled small">
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Berikan rating yang jujur dan objektif
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Ceritakan pengalaman spesifik Anda
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Sebutkan aspek positif dan negatif
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled small">
                                <li class="mb-2">
                                    <i class="fas fa-times-circle text-danger me-2"></i>
                                    Hindari kata-kata kasar atau ofensif
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-times-circle text-danger me-2"></i>
                                    Jangan menulis informasi pribadi
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-times-circle text-danger me-2"></i>
                                    Jangan memberikan review palsu
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.reviewer-avatar img {
    object-fit: cover;
}

.star-rating {
    font-size: 2rem;
    margin-bottom: 10px;
}

.star {
    cursor: pointer;
    margin-right: 5px;
    transition: all 0.2s ease;
}

.star:hover {
    transform: scale(1.1);
}

.star i {
    color: #ddd;
    transition: color 0.2s ease;
}

.star.active i,
.star.hover i {
    color: #ffc107;
}

.star.active i {
    content: '\f005';
}

.preview-stars i {
    color: #ffc107;
    font-size: 0.9rem;
}

.card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.form-control:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

.btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
}

.btn-warning:hover {
    background-color: #e0a800;
    border-color: #d39e00;
}

#previewSection {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('rating');
    const ratingText = document.getElementById('ratingText');
    const commentTextarea = document.getElementById('comment');
    const charCount = document.getElementById('charCount');
    const togglePreviewBtn = document.getElementById('togglePreview');
    const previewSection = document.getElementById('previewSection');
    const previewButtonText = document.getElementById('previewButtonText');
    const previewStars = document.querySelector('.preview-stars');
    const previewComment = document.querySelector('.preview-comment');
    const previewCommentText = document.getElementById('previewCommentText');
    
    // Rating texts
    const ratingTexts = {
        1: 'Sangat Buruk - Pengalaman yang sangat mengecewakan',
        2: 'Buruk - Banyak masalah yang perlu diperbaiki',
        3: 'Cukup - Standar, ada yang baik dan buruk',
        4: 'Baik - Pengalaman yang memuaskan',
        5: 'Sangat Baik - Pengalaman yang luar biasa'
    };
    
    // Initialize rating display
    function updateRatingDisplay(rating) {
        stars.forEach((star, index) => {
            const starIcon = star.querySelector('i');
            if (index < rating) {
                star.classList.add('active');
                starIcon.classList.remove('far');
                starIcon.classList.add('fas');
            } else {
                star.classList.remove('active');
                starIcon.classList.remove('fas');
                starIcon.classList.add('far');
            }
        });
        
        if (rating > 0) {
            ratingText.textContent = ratingTexts[rating];
            ratingText.className = 'text-warning';
        } else {
            ratingText.textContent = 'Pilih rating dari 1 hingga 5 bintang';
            ratingText.className = 'text-muted';
        }
        
        ratingInput.value = rating;
    }
    
    // Set initial rating from old value or existing review
    const initialRating = parseInt(ratingInput.value) || 0;
    if (initialRating > 0) {
        updateRatingDisplay(initialRating);
    }
    
    // Star rating interaction
    stars.forEach((star, index) => {
        const rating = index + 1;
        
        star.addEventListener('mouseenter', function() {
            stars.forEach((s, i) => {
                if (i < rating) {
                    s.classList.add('hover');
                    s.querySelector('i').classList.remove('far');
                    s.querySelector('i').classList.add('fas');
                } else {
                    s.classList.remove('hover');
                    if (!s.classList.contains('active')) {
                        s.querySelector('i').classList.remove('fas');
                        s.querySelector('i').classList.add('far');
                    }
                }
            });
        });
        
        star.addEventListener('mouseleave', function() {
            stars.forEach(s => {
                s.classList.remove('hover');
                if (!s.classList.contains('active')) {
                    s.querySelector('i').classList.remove('fas');
                    s.querySelector('i').classList.add('far');
                }
            });
        });
        
        star.addEventListener('click', function() {
            updateRatingDisplay(rating);
            updatePreview();
        });
    });
    
    // Character count
    function updateCharCount() {
        const count = commentTextarea.value.length;
        charCount.textContent = count;
        
        if (count > 800) {
            charCount.parentElement.className = 'form-text text-warning';
        } else if (count > 950) {
            charCount.parentElement.className = 'form-text text-danger';
        } else {
            charCount.parentElement.className = 'form-text text-muted';
        }
    }
    
    commentTextarea.addEventListener('input', function() {
        updateCharCount();
        updatePreview();
    });
    
    // Initialize char count
    updateCharCount();
    
    // Preview functionality
    function updatePreview() {
        const rating = parseInt(ratingInput.value) || 0;
        const comment = commentTextarea.value.trim();
        
        // Update preview stars
        let starsHtml = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= rating) {
                starsHtml += '<i class="fas fa-star"></i>';
            } else {
                starsHtml += '<i class="far fa-star text-muted"></i>';
            }
        }
        starsHtml += `<span class="ms-2 text-muted small">${rating}/5</span>`;
        previewStars.innerHTML = starsHtml;
        
        // Update preview comment
        if (comment) {
            previewCommentText.textContent = comment;
            previewComment.style.display = 'block';
        } else {
            previewComment.style.display = 'none';
        }
    }
    
    // Toggle preview
    let previewVisible = false;
    togglePreviewBtn.addEventListener('click', function() {
        previewVisible = !previewVisible;
        
        if (previewVisible) {
            updatePreview();
            previewSection.style.display = 'block';
            previewButtonText.textContent = 'Sembunyikan Preview';
            togglePreviewBtn.querySelector('i').className = 'fas fa-eye-slash me-1';
        } else {
            previewSection.style.display = 'none';
            previewButtonText.textContent = 'Lihat Preview';
            togglePreviewBtn.querySelector('i').className = 'fas fa-eye me-1';
        }
    });
    
    // Form validation before submit
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const rating = parseInt(ratingInput.value);
        
        if (!rating || rating < 1 || rating > 5) {
            e.preventDefault();
            alert('Silakan pilih rating dari 1 hingga 5 bintang');
            document.querySelector('.star-rating').scrollIntoView({ behavior: 'smooth' });
            return false;
        }
        
        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...';
        
        // If form validation fails, restore button
        setTimeout(() => {
            if (submitBtn.disabled) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }, 3000);
    });
    
    // Auto-save draft functionality (optional)
    let saveTimeout;
    function saveDraft() {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(() => {
            const draftData = {
                rating: ratingInput.value,
                comment: commentTextarea.value,
                timestamp: Date.now()
            };
            // Save to sessionStorage instead of localStorage
            sessionStorage.setItem('review_draft_' + {{ $review->id }}, JSON.stringify(draftData));
        }, 1000);
    }
    
    // Load draft on page load
    const savedDraft = sessionStorage.getItem('review_draft_' + {{ $review->id }});
    if (savedDraft) {
        try {
            const draftData = JSON.parse(savedDraft);
            // Only load draft if it's newer than the current review
            if (draftData.timestamp > {{ $review->updated_at->timestamp * 1000 }}) {
                if (confirm('Ditemukan draft yang belum disimpan. Apakah Anda ingin memulihkannya?')) {
                    if (draftData.rating) {
                        updateRatingDisplay(parseInt(draftData.rating));
                    }
                    if (draftData.comment) {
                        commentTextarea.value = draftData.comment;
                        updateCharCount();
                    }
                }
            }
        } catch (e) {
            console.log('Error loading draft:', e);
        }
    }
    
    // Save draft on changes
    ratingInput.addEventListener('change', saveDraft);
    commentTextarea.addEventListener('input', saveDraft);
    
    // Clear draft on successful submit
    form.addEventListener('submit', function() {
        sessionStorage.removeItem('review_draft_' + {{ $review->id }});
    });
});
</script>
@endsection