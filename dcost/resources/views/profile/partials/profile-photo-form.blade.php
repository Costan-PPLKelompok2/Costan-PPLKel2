<div x-data="{ photoName: null, photoPreview: null, hasPhoto: @json($user->profile_photo_path ? true : false) }" class="col-span-6 sm:col-span-4">
    <!-- Profile Photo File Input -->
    <input type="file" id="photo" class="hidden"
           wire:model="photo"
           x-ref="photo"
           accept="image/jpeg, image/png"
           x-on:change="
                const file = $refs.photo.files[0];
                if (file) {
                    // Cek apakah file memiliki format yang sesuai (JPEG/PNG)
                    if (!['image/jpeg', 'image/png'].includes(file.type)) {
                        alert('Please select a valid image file (JPEG/PNG)');
                        return;  // Batalkan jika file tidak valid
                    }

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        photoPreview = e.target.result;  // Update photo preview after selection
                        hasPhoto = true;  // Update state after photo is selected
                    };
                    reader.readAsDataURL(file);
                }
           " />

    <!-- Label for Photo -->
    <x-label for="photo" value="{{ __('Photo Profile') }}" class="text-2xl font-semibold" />

    <!-- Current Profile Photo -->
    <div class="mt-4 flex justify-center">
        <div class="relative">
            <!-- Display the current photo or the preview if selected -->
            <div class="rounded-full border-4 border-indigo-500 overflow-hidden w-48 h-48">
                <!-- Menampilkan foto profil yang sudah ada atau pratinjau foto yang dipilih -->
                <img :src="photoPreview || '{{ Storage::url(auth()->user()->profile_photo_path) }}'" alt="{{ $user->name }}" class="w-full h-full object-cover">
            </div>
            <!-- Edit Icon -->
            @if($user->profile_photo_path)
                <div class="absolute bottom-0 right-0 bg-indigo-500 p-2 rounded-full cursor-pointer" @click.prevent="$refs.photo.click()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828l2.829 2.828m-2.829-2.828l-9.9 9.9A2 2 0 013 19V5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-1.414-.586l9.9-9.9a2 2 0 012.828 0L18 14.828z"/>
                    </svg>
                </div>
            @endif
        </div>
    </div>

    <!-- New Profile Photo Preview -->
    <div class="mt-4 flex justify-center" x-show="photoPreview && !hasPhoto">
        <div class="rounded-full border-4 border-indigo-500 overflow-hidden w-48 h-48">
            <span class="block w-full h-full bg-cover bg-no-repeat bg-center" :style="'background-image: url(\'' + photoPreview + '\');'"></span>
        </div>
    </div>

    <!-- Button to Select or Edit Photo -->
    <div class="mt-4 flex justify-center">
        <x-secondary-button class="w-48" type="button" x-on:click.prevent="$refs.photo.click()">
            <span x-text="hasPhoto ? '{{ __('Edit Photo') }}' : '{{ __('Select A Photo') }}'"></span>
        </x-secondary-button>
    </div>

    <!-- Button to Save the Selected Photo -->
    <div class="mt-4 flex justify-center">
        <!-- Button to submit form and save photo -->
        <x-primary-button wire:click="save" type="button" class="w-48">
            Save Photo
        </x-primary-button>
    </div>

    @if ($user->profile_photo_path)
        <div class="mt-4 flex justify-center">
            <x-secondary-button type="button" class="w-48" wire:click="deleteProfilePhoto">
                {{ __('Remove Photo') }}
            </x-secondary-button>
        </div>
    @endif

    <!-- Display Error Messages -->
    <x-input-error for="photo" class="mt-2" />
</div>
