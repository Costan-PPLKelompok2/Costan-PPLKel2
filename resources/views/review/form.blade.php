@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Review Kos</h1>
            <h2 class="text-xl text-gray-600">{{ $kos->name ?? 'Nama Kos' }}</h2>
            <div class="w-24 h-1 bg-blue-600 mx-auto mt-4 rounded-full"></div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Review Form -->
            <div class="bg-white rounded-xl shadow-lg p-6 h-fit">
                <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                    Tulis Review Anda
                </h3>

                <form action="{{ route('review.submit', $kos->id ?? 1) }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Rating Section -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Rating <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-2">
                            <div class="flex space-x-1" id="star-rating">
                                @for ($i = 1; $i <= 5; $i++)
                                <button type="button" class="star text-gray-300 hover:text-yellow-400 transition-colors duration-200" data-rating="{{ $i }}">
                                    <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                </button>
                                @endfor
                            </div>
                            <span id="rating-text" class="text-sm text-gray-500 ml-3">Pilih rating</span>
                        </div>
                        <input type="hidden" name="rating" id="rating-input" required>
                        @error('rating')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Comment Section -->
                    <div>
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                            Ulasan <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="comment" 
                            id="comment"
                            rows="4" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 resize-none"
                            placeholder="Ceritakan pengalaman Anda tinggal di kos ini..."
                            required
                        ></textarea>
                        <div class="flex justify-between items-center mt-1">
                            @error('comment')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                            @else
                            <p class="text-sm text-gray-500">Minimal 10 karakter</p>
                            @enderror
                            <span id="char-count" class="text-sm text-gray-400">0 karakter</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        id="submit-btn"
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Kirim Review
                        </span>
                    </button>
                </form>
            </div>

            <!-- Previous Reviews -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    Review Sebelumnya
                </h3>

                <div class="space-y-4 max-h-96 overflow-y-auto">
                    @forelse ($reviews ?? [] as $review)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <div class="flex text-yellow-400">
                                    @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= ($review->rating ?? 0) ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    @endfor
                                </div>
                                <span class="ml-2 text-sm font-medium text-gray-700">{{ $review->rating ?? 0 }}/5</span>
                            </div>
                            <span class="text-xs text-gray-500">
                                {{ isset($review->created_at) ? $review->created_at->diffForHumans() : 'Baru saja' }}
                            </span>
                        </div>
                        <p class="text-gray-700 leading-relaxed">{{ $review->comment ?? 'Review kosong' }}</p>
                        @if(isset($review->user))
                        <div class="mt-2 text-xs text-gray-500">
                            oleh {{ $review->user->name ?? 'Anonim' }}
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p class="text-gray-500">Belum ada review untuk kos ini.</p>
                        <p class="text-sm text-gray-400 mt-1">Jadilah yang pertama memberikan review!</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript untuk interaktivitas -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('rating-input');
    const ratingText = document.getElementById('rating-text');
    const commentTextarea = document.getElementById('comment');
    const charCount = document.getElementById('char-count');
    const submitBtn = document.getElementById('submit-btn');
    
    let selectedRating = 0;
    
    // Rating labels
    const ratingLabels = {
        1: 'Sangat Buruk',
        2: 'Buruk', 
        3: 'Cukup',
        4: 'Bagus',
        5: 'Sangat Bagus'
    };
    
    // Star rating functionality
    stars.forEach((star, index) => {
        star.addEventListener('mouseenter', function() {
            highlightStars(index + 1);
        });
        
        star.addEventListener('mouseleave', function() {
            highlightStars(selectedRating);
        });
        
        star.addEventListener('click', function() {
            selectedRating = index + 1;
            ratingInput.value = selectedRating;
            ratingText.textContent = ratingLabels[selectedRating];
            highlightStars(selectedRating);
            validateForm();
        });
    });
    
    function highlightStars(rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    }
    
    // Character counter
    commentTextarea.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = length + ' karakter';
        
        if (length < 10) {
            charCount.classList.add('text-red-500');
            charCount.classList.remove('text-gray-400');
        } else {
            charCount.classList.remove('text-red-500');
            charCount.classList.add('text-gray-400');
        }
        
        validateForm();
    });
    
    // Form validation
    function validateForm() {
        const hasRating = selectedRating > 0;
        const hasComment = commentTextarea.value.length >= 10;
        
        if (hasRating && hasComment) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
    
    // Initial validation
    validateForm();
});
</script>

<style>
/* Custom scrollbar untuk review list */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Smooth transitions */
* {
    transition: color 0.2s ease, background-color 0.2s ease, border-color 0.2s ease;
}
</style>
@endsection