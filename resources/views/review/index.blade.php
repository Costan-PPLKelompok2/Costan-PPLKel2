@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        
        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-8 bg-emerald-50 border-l-4 border-emerald-400 p-4 rounded-r-xl shadow-sm animate-fade-in">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Header Section -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Review Kos</h1>
            <h2 class="text-2xl text-gray-600 font-medium">{{ $kos->name }}</h2>
            <div class="w-32 h-1 bg-gradient-to-r from-blue-500 to-indigo-500 mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            
            <!-- Review Form Section -->
            <div class="xl:col-span-1">
                @auth
                <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 sticky top-8">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full mb-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Tulis Review Anda</h3>
                        <p class="text-gray-600 text-sm mt-1">Bagikan pengalaman Anda</p>
                    </div>

                    <form action="{{ route('review.store') }}" method="POST" class="space-y-6" id="reviewForm">
                        @csrf
                        <input type="hidden" name="kos_id" value="{{ $kos->id ?? 1 }}">
                        
                        <!-- Rating Section -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Rating <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center justify-center space-x-2 mb-2">
                                <div class="flex space-x-1" id="star-rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                    <button type="button" class="star text-gray-300 hover:text-yellow-400 transition-all duration-200 transform hover:scale-110" data-rating="{{ $i }}">
                                        <svg class="w-10 h-10 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </button>
                                    @endfor
                                </div>
                            </div>
                            <div class="text-center">
                                <span id="rating-text" class="text-sm text-gray-500 font-medium">Klik untuk memberikan rating</span>
                            </div>
                            <input type="hidden" name="rating" id="rating-input" required>
                            @error('rating')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Comment Section -->
                        <div>
                            <label for="comment" class="block text-sm font-semibold text-gray-700 mb-2">
                                Ulasan <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                name="comment" 
                                id="comment"
                                rows="4" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none placeholder-gray-400"
                                placeholder="Ceritakan pengalaman Anda tinggal di kos ini. Bagaimana fasilitasnya? Bagaimana lingkungannya?"
                                required
                            ></textarea>
                            <div class="flex justify-between items-center mt-2">
                                @error('comment')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                                @else
                                <p class="text-xs text-gray-500">Minimal 20 karakter</p>
                                @enderror
                                <span id="char-count" class="text-xs text-gray-400">0 karakter</span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            id="submit-btn"
                            class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none shadow-lg hover:shadow-xl"
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
                @else
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-dashed border-blue-200 rounded-2xl p-8 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Login Diperlukan</h3>
                    <p class="text-gray-600 mb-6">Silakan login terlebih dahulu untuk memberikan review dan berbagi pengalaman Anda.</p>
                    <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Login Sekarang
                    </a>
                </div>
                @endauth
            </div>

            <!-- Reviews List Section -->
            <div class="xl:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                                    <svg class="w-6 h-6 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    Ulasan Pengguna
                                </h3>
                                <p class="text-gray-600 text-sm mt-1">
                                    {{ count($reviews ?? []) }} {{ count($reviews ?? []) === 1 ? 'review' : 'reviews' }} dari penghuni
                                </p>
                            </div>
                            <div class="text-right">
                                @if(count($reviews ?? []) > 0)
                                <div class="flex items-center text-2xl font-bold text-yellow-500">
                                    <svg class="w-6 h-6 mr-1 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    {{ number_format($reviews->avg('rating') ?? 0, 1) }}
                                </div>
                                <p class="text-xs text-gray-500">rata-rata rating</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="divide-y divide-gray-100 max-h-[600px] overflow-y-auto">
                        @forelse($reviews ?? [] as $review)
                        <div class="p-8 hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex items-start space-x-4">
                                <!-- Avatar -->
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                        {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                </div>
                                
                                <!-- Review Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-900">
                                                {{ $review->user->name ?? 'User' }}
                                            </h4>
                                            <div class="flex items-center mt-1">
                                                <div class="flex text-yellow-400 mr-2">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                    <svg class="w-5 h-5 {{ $i <= ($review->rating ?? 0) ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 24 24">
                                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                    </svg>
                                                    @endfor
                                                </div>
                                                <span class="text-sm font-medium text-gray-700">{{ $review->rating ?? 0 }}/5</span>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-sm text-gray-500">
                                                {{ isset($review->created_at) ? $review->created_at->diffForHumans() : 'Baru saja' }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <p class="text-gray-700 leading-relaxed text-base mb-4">
                                        {{ $review->comment ?? 'Tidak ada komentar' }}
                                    </p>
                                    
                                    <!-- Action Buttons for Review Owner -->
                                    @auth
                                    @if(($review->user_id ?? 0) == Auth::id())
                                    <div class="flex items-center space-x-3 pt-3 border-t border-gray-100">
                                        <a href="{{ route('review.edit', $review->id ?? 1) }}" 
                                           class="inline-flex items-center px-3 py-2 text-sm font-medium text-amber-700 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('review.destroy', $review->id ?? 1) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus review ini? Tindakan ini tidak dapat dibatalkan.')"
                                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-12 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Review</h4>
                            <p class="text-gray-600 max-w-md mx-auto">
                                Jadilah yang pertama memberikan review untuk kos ini dan bantu calon penghuni lainnya!
                            </p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('rating-input');
    const ratingText = document.getElementById('rating-text');
    const commentTextarea = document.getElementById('comment');
    const charCount = document.getElementById('char-count');
    const submitBtn = document.getElementById('submit-btn');
    
    let selectedRating = 0;
    
    // Rating labels dengan emoji
    const ratingLabels = {
        1: '😞 Sangat Buruk',
        2: '😐 Buruk', 
        3: '🙂 Cukup',
        4: '😊 Bagus',
        5: '🤩 Sangat Bagus'
    };

    // Star rating functionality dengan animasi
    stars.forEach((star, index) => {
        star.addEventListener('mouseenter', function() {
            highlightStars(index + 1);
            if (!selectedRating) {
                ratingText.textContent = ratingLabels[index + 1];
                ratingText.classList.add('text-gray-700');
            }
        });
        
        star.addEventListener('mouseleave', function() {
            highlightStars(selectedRating);
            if (!selectedRating) {
                ratingText.textContent = 'Klik untuk memberikan rating';
                ratingText.classList.remove('text-gray-700');
            }
        });
        
        star.addEventListener('click', function() {
            selectedRating = index + 1;
            ratingInput.value = selectedRating;
            ratingText.textContent = ratingLabels[selectedRating];
            ratingText.classList.add('text-gray-700', 'font-semibold');
            highlightStars(selectedRating);
            validateForm();
            
            // Animasi feedback
            star.style.transform = 'scale(1.3)';
            setTimeout(() => {
                star.style.transform = 'scale(1.1)';
            }, 150);
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
    
    // Character counter dengan validasi
    if (commentTextarea && charCount) {
        commentTextarea.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = length + ' karakter';
            
            if (length < 20) {
                charCount.classList.add('text-red-500');
                charCount.classList.remove('text-green-500', 'text-gray-400');
            } else if (length >= 20 && length <= 500) {
                charCount.classList.add('text-green-500');
                charCount.classList.remove('text-red-500', 'text-gray-400');
            } else {
                charCount.classList.add('text-gray-400');
                charCount.classList.remove('text-red-500', 'text-green-500');
            }
            
            validateForm();
        });
    }
    
    // Form validation dengan feedback visual
    function validateForm() {
        if (!submitBtn) return;
        
        const hasRating = selectedRating > 0;
        const hasValidComment = commentTextarea ? commentTextarea.value.length >= 20 : false;
        
        if (hasRating && hasValidComment) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
    
    // Animasi fade-in untuk success message
    const successAlert = document.querySelector('.animate-fade-in');
    if (successAlert) {
        setTimeout(() => {
            successAlert.style.opacity = '0';
            successAlert.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                successAlert.remove();
            }, 500);
        }, 5000);
    }
    
    // Initial validation
    validateForm();
});
</script>

<style>
/* Animasi dan efek khusus */
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.5s ease-out;
}

/* Custom scrollbar */
.overflow-y-auto::-webkit-scrollbar {
    width: 8px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f8fafc;
    border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Smooth transitions untuk semua elemen */
* {
    transition: all 0.2s ease;
}

/* Hover effect untuk cards */
.hover\:bg-gray-50:hover {
    background-color: #f9fafb;
}

/* Rating stars hover effect */
.star:hover {
    filter: drop-shadow(0 0 8px rgba(251, 191, 36, 0.5));
}
</style>
@endsection