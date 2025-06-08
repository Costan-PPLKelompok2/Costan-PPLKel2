@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-red-50">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        
        <!-- Header Section -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-amber-100 to-orange-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Edit Review</h1>
            <p class="text-lg text-gray-600">Perbarui pengalaman dan penilaian Anda</p>
            <div class="w-24 h-1 bg-gradient-to-r from-amber-500 to-orange-500 mx-auto mt-4 rounded-full"></div>
        </div>

        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ url()->previous() }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 transition-colors duration-200">Reviews</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Edit</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Review Info Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 sticky top-8">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full mb-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Info Review</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-600">Kos:</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $review->kos->name ?? 'Nama Kos' }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-600">Rating Saat Ini:</span>
                            <div class="flex items-center">
                                <div class="flex text-yellow-400 mr-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= ($review->rating ?? 0) ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    @endfor
                                </div>
                                <span class="text-sm font-semibold text-gray-900">{{ $review->rating ?? 0 }}/5</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-600">Dibuat:</span>
                            <span class="text-sm font-semibold text-gray-900">
                                {{ isset($review->created_at) ? $review->created_at->format('d M Y') : 'Tidak diketahui' }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-600">Terakhir Diubah:</span>
                            <span class="text-sm font-semibold text-gray-900">
                                {{ isset($review->updated_at) ? $review->updated_at->format('d M Y') : 'Belum pernah' }}
                            </span>
                        </div>
                    </div>

                    <!-- Tips Section -->
                    <div class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-semibold text-blue-900 mb-1">Tips Review yang Baik</h4>
                                <ul class="text-xs text-blue-700 space-y-1">
                                    <li>• Berikan detail tentang fasilitas</li>
                                    <li>• Ceritakan pengalaman nyata</li>
                                    <li>• Gunakan bahasa yang sopan</li>
                                    <li>• Minimal 20 karakter</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 px-8 py-6 border-b border-gray-200">
                        <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 text-amber-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Review Anda
                        </h3>
                        <p class="text-gray-600 text-sm mt-1">Perbarui penilaian dan komentar Anda tentang kos ini</p>
                    </div>

                    <form action="{{ route('review.update', $review->id ?? 1) }}" method="POST" class="p-8 space-y-8" id="editReviewForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Rating Section -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-4">
                                Rating Baru <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center justify-center space-x-2 mb-4">
                                <div class="flex space-x-1" id="star-rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                    <button type="button" class="star text-gray-300 hover:text-yellow-400 transition-all duration-200 transform hover:scale-110 {{ $i <= ($review->rating ?? 0) ? 'text-yellow-400' : '' }}" data-rating="{{ $i }}">
                                        <svg class="w-12 h-12 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </button>
                                    @endfor
                                </div>
                            </div>
                            <div class="text-center">
                                <span id="rating-text" class="text-lg font-medium text-gray-700">
                                    @php
                                        $ratingLabels = [
                                            1 => '😞 Sangat Buruk',
                                            2 => '😐 Buruk',
                                            3 => '🙂 Cukup',
                                            4 => '😊 Bagus',
                                            5 => '🤩 Sangat Bagus'
                                        ];
                                        echo $ratingLabels[$review->rating ?? 3] ?? 'Pilih rating';
                                    @endphp
                                </span>
                            </div>
                            <input type="hidden" name="rating" id="rating-input" value="{{ $review->rating ?? 3 }}" required>
                            @error('rating')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Comment Section -->
                        <div>
                            <label for="comment" class="block text-sm font-semibold text-gray-700 mb-3">
                                Ulasan Baru <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <textarea 
                                    name="comment" 
                                    id="comment"
                                    rows="6" 
                                    class="w-full px-4 py-4 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200 resize-none placeholder-gray-400 text-base leading-relaxed"
                                    placeholder="Perbarui pengalaman Anda tinggal di kos ini. Apa yang berubah? Bagaimana fasilitas dan lingkungannya sekarang?"
                                    required
                                >{{ $review->comment ?? '' }}</textarea>
                                <div class="absolute bottom-3 right-3 opacity-50">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex justify-between items-center mt-3">
                                <div class="flex items-center text-sm">
                                    @error('comment')
                                    <p class="text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @else
                                    <p class="text-gray-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Minimal 20 karakter
                                    </p>
                                    @enderror
                                </div>
                                <span id="char-count" class="text-sm text-gray-400">
                                    {{ strlen($review->comment ?? '') }} karakter
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                            <button 
                                type="submit" 
                                id="submit-btn"
                                class="flex-1 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                            >
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Simpan Perubahan
                                </span>
                            </button>
                            
                            <a href="{{ url()->previous() }}" 
                               class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 text-center">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Batal
                                </span>
                            </a>
                        </div>

                        <!-- Change Detection Info -->
                        <div id="changes-indicator" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-blue-700 text-sm font-medium">Ada perubahan yang belum disimpan</p>
                            </div>
                        </div>
                    </form>
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
    const changesIndicator = document.getElementById('changes-indicator');
    
    // Original values for change detection
    const originalRating = {{ $review->rating ?? 3 }};
    const originalComment = `{{ $review->comment ?? '' }}`;
    
    let selectedRating = originalRating;
    
    // Rating labels dengan emoji
    const ratingLabels = {
        1: '😞 Sangat Buruk',
        2: '😐 Buruk', 
        3: '🙂 Cukup',
        4: '😊 Bagus',
        5: '🤩 Sangat Bagus'
    };

    // Initialize stars based on current rating
    highlightStars(selectedRating);

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
            checkForChanges();
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
    
    // Character counter
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
            
            checkForChanges();
            validateForm();
        });
    }
    
    // Check for changes
    function checkForChanges() {
        const currentRating = selectedRating;
        const currentComment = commentTextarea ? commentTextarea.value : '';
        
        const hasChanges = (currentRating !== originalRating) || (currentComment !== originalComment);
        
        if (hasChanges) {
            changesIndicator.classList.remove('hidden');
        } else {
            changesIndicator.classList.add('hidden');
        }
    }
    
    // Form validation
    function validateForm() {
        if (!submitBtn) return;
        
        const hasValidComment = commentTextarea ? commentTextarea.value.length >= 20 : true;
        const hasValidRating = selectedRating >= 1 && selectedRating <= 5;
        
        if (hasValidComment && hasValidRating) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
    
    // Prevent accidental navigation away
    let hasUnsavedChanges = false;
    
    function updateUnsavedChanges() {
        const currentRating = selectedRating;
        const currentComment = commentTextarea ? commentTextarea.value : '';
        hasUnsavedChanges = (currentRating !== originalRating) || (currentComment !== originalComment);
    }
    
    // Listen for changes
    if (commentTextarea) {
        commentTextarea.addEventListener('input', updateUnsavedChanges);
    }
    
    // Add event listeners to stars for change tracking
    stars.forEach(star => {
        star.addEventListener('click', updateUnsavedChanges);
    });
    
    // Warn before leaving page with unsaved changes
    window.addEventListener('beforeunload', function(e) {
        if (hasUnsavedChanges) {
            e.preventDefault();
            e.returnValue = 'Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman?';
            return e.returnValue;
        }
    });
    
    // Clear unsaved changes flag on form submit
    const form = document.getElementById('editReviewForm');
    if (form) {
        form.addEventListener('submit', function() {
            hasUnsavedChanges = false;
        });
    }
    
    // Initial validation
    validateForm();
    
    // Auto-save draft (optional - uncomment if needed)
    /*
    let autoSaveTimer;
    function autoSaveDraft() {
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(() => {
            // Implement auto-save logic here
            console.log('Auto-saving draft...');
        }, 3000);
    }
    
    if (commentTextarea) {
        commentTextarea.addEventListener('input', autoSaveDraft);
    }
    */
});
</script>

<style>
/* Enhanced animations */
@keyframes pulse-success {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.8;
    }
}

.pulse-success {
    animation: pulse-success 2s infinite;
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

/* Smooth transitions */
* {
    transition: all 0.2s ease;
}

/* Enhanced hover effects */
.star:hover {
    filter: drop-shadow(0 0 8px rgba(251, 191, 36, 0.6));
}

/* Form focus styles */
textarea:focus {
    box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.1);
}

/* Button hover effects */
button:hover:not(:disabled) {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .star svg {
        width: 2.5rem;
        height: 2.5rem;
    }
}
</style>
@endsection