{{-- resources/views/owner-reviews/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Berikan Review untuk ' . $owner->name)

@push('styles')
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<!-- Bootstrap CSS jika belum ada -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-star me-2"></i>
                        Berikan Review untuk {{ $owner->name }}
                    </h4>
                </div>
                
                <div class="card-body">
                    {{-- Alert untuk error validation --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Oops!</strong> Ada beberapa masalah dengan input Anda:
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Info owner --}}
                    <div class="mb-4 p-3 bg-light rounded">
                        <div class="d-flex align-items-center">
                            <div class="avatar me-3">
                                @if($owner->profile_picture)
                                    <img src="{{ asset('storage/' . $owner->profile_picture) }}" 
                                         alt="{{ $owner->name }}" 
                                         class="rounded-circle" 
                                         width="60" height="60">
                                @else
                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-user text-white fa-2x"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h5 class="mb-1">{{ $owner->name }}</h5>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-home me-1"></i>
                                    Pemilik Kos
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Form review --}}
                    <form method="POST" action="{{ route('owner-reviews.store') }}" id="reviewForm">
                        @csrf
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <input type="hidden" name="owner_id" value="{{ $owner->id }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        
                        {{-- Rating dengan bintang --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                ⭐ Rating <span class="text-danger">*</span>
                            </label>
                            <div class="rating-container">
                                <div class="star-rating mb-2" id="starRating">
                                    <span class="star" data-rating="1">★</span>
                                    <span class="star" data-rating="2">★</span>
                                    <span class="star" data-rating="3">★</span>
                                    <span class="star" data-rating="4">★</span>
                                    <span class="star" data-rating="5">★</span>
                                </div>
                                <input type="hidden" name="rating" id="rating" value="{{ old('rating') }}" required>
                                <small class="form-text text-muted">
                                    Klik bintang untuk memberikan rating (1-5 bintang)
                                </small>
                                <div id="ratingText" class="mt-2 fw-bold text-primary" style="display: none;"></div>
                            </div>
                        </div>

                        {{-- Komentar --}}
                        <div class="mb-4">
                            <label for="comment" class="form-label fw-bold">
                                💬 Komentar
                            </label>
                            <textarea name="comment" 
                                      id="comment" 
                                      class="form-control @error('comment') is-invalid @enderror" 
                                      rows="5" 
                                      placeholder="Ceritakan pengalaman Anda dengan pemilik kos ini... (opsional)"
                                      maxlength="1000">{{ old('comment') }}</textarea>
                            <div class="form-text">
                                <span id="charCount">0</span>/1000 karakter
                            </div>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('owner-reviews.store', $owner->id) }}" 
                               class="btn btn-outline-secondary">
                                ← Kembali
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                📤 Kirim Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Custom CSS --}}
<style>
.star-rating {
    display: flex;
    gap: 8px;
    font-size: 2.5rem;
    justify-content: center;
    margin: 15px 0;
}

.star {
    cursor: pointer;
    transition: all 0.3s ease;
    color: #ddd;
    user-select: none;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
}

.star:hover {
    color: #ffc107;
    transform: scale(1.2);
    text-shadow: 2px 2px 4px rgba(255,193,7,0.3);
}

.star.active {
    color: #ffc107;
    animation: starGlow 0.3s ease;
}

@keyframes starGlow {
    0% { transform: scale(1); }
    50% { transform: scale(1.3); }
    100% { transform: scale(1.1); }
}

.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.btn {
    border-radius: 8px;
    padding: 10px 25px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.form-control {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    padding: 12px 15px;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    transform: translateY(-1px);
}

.avatar img {
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.bg-light {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
    border: 1px solid #dee2e6;
}

#ratingText {
    font-size: 1.1rem;
    text-align: center;
    padding: 8px;
    border-radius: 8px;
    background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);
}

@media (max-width: 768px) {
    .star-rating {
        font-size: 2rem;
        gap: 5px;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
        gap: 10px;
    }
    
    .btn {
        width: 100%;
        padding: 12px;
    }
    
    .card-body {
        padding: 20px 15px;
    }
}

/* Loading animation */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loading-spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid #ffffff40;
    border-radius: 50%;
    border-top-color: #ffffff;
    animation: spin 1s ease-in-out infinite;
}
</style>

{{-- JavaScript untuk interaktivitas --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('rating');
    const ratingText = document.getElementById('ratingText');
    const commentTextarea = document.getElementById('comment');
    const charCount = document.getElementById('charCount');
    const submitBtn = document.getElementById('submitBtn');
    
    // Rating messages
    const ratingMessages = {
        1: '😞 Sangat Tidak Puas',
        2: '😕 Tidak Puas', 
        3: '😐 Biasa Saja',
        4: '😊 Puas',
        5: '🤩 Sangat Puas'
    };
    
    // Rating bintang click handler
    stars.forEach((star, index) => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            ratingInput.value = rating;
            
            // Update tampilan bintang
            updateStarDisplay(rating);
            
            // Show rating text
            ratingText.textContent = ratingMessages[rating];
            ratingText.style.display = 'block';
            ratingText.style.animation = 'slideIn 0.3s ease';
        });
        
        // Hover effect
        star.addEventListener('mouseenter', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            highlightStars(rating);
        });
    });
    
    // Reset hover effect
    document.querySelector('.star-rating').addEventListener('mouseleave', function() {
        const currentRating = parseInt(ratingInput.value) || 0;
        updateStarDisplay(currentRating);
    });
    
    // Functions
    function updateStarDisplay(rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }
    
    function highlightStars(rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.style.color = '#ffc107';
                star.style.transform = 'scale(1.15)';
            } else {
                star.style.color = '#ddd';
                star.style.transform = 'scale(1)';
            }
        });
    }
    
    // Character counter
    function updateCharCount() {
        const currentLength = commentTextarea.value.length;
        charCount.textContent = currentLength;
        
        if (currentLength > 950) {
            charCount.style.color = '#dc3545';
            charCount.style.fontWeight = 'bold';
        } else if (currentLength > 800) {
            charCount.style.color = '#fd7e14';
            charCount.style.fontWeight = 'bold';
        } else {
            charCount.style.color = '#6c757d';
            charCount.style.fontWeight = 'normal';
        }
    }
    
    commentTextarea.addEventListener('input', updateCharCount);
    
    // Form validation dan submit
    document.getElementById('reviewForm').addEventListener('submit', function(e) {
        if (!ratingInput.value) {
            e.preventDefault();
            alert('⭐ Silakan pilih rating terlebih dahulu!');
            
            // Highlight rating section
            document.querySelector('.star-rating').style.animation = 'shake 0.5s ease';
            setTimeout(() => {
                document.querySelector('.star-rating').style.animation = '';
            }, 500);
            
            return false;
        }
        
        // Refresh CSRF token jika diperlukan
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        document.querySelector('input[name="_token"]').value = token;
        
        // Loading state
        submitBtn.innerHTML = '<span class="loading-spinner"></span> Mengirim Review...';
        submitBtn.disabled = true;
        
        // Disable form elements
        const formElements = this.querySelectorAll('input, textarea, button, select');
        formElements.forEach(element => {
            if (element.name !== '_token' && element.name !== 'owner_id' && element.name !== 'rating' && element.name !== 'comment') {
                element.disabled = true;
            }
        });
    });
    
    // Set rating awal jika ada old value
    const oldRating = "{{ old('rating') }}";
    if (oldRating) {
        updateStarDisplay(parseInt(oldRating));
        ratingText.textContent = ratingMessages[oldRating];
        ratingText.style.display = 'block';
    }
    
    // Set character count awal
    updateCharCount();
    
    // Animasi masuk untuk elemen
    const animateElements = document.querySelectorAll('.card, .star-rating');
    animateElements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            element.style.transition = 'all 0.6s ease';
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, index * 200);
    });
});

// CSS animations keyframes
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
`;
document.head.appendChild(style);
</script>

@push('scripts')
<!-- Bootstrap JS jika belum ada -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush
@endsection