<form method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data" class="col-span-6 sm:col-span-4">
    @csrf
    {{-- @method('POST') --}}  <div x-data="{ photoName: null, photoPreview: null, hasPhoto: @json($user->profile_photo_path ? true : false) }">
        <input type="file" id="photo" name="photo" class="hidden"
                x-ref="photo"
                accept="image/jpeg, image/png"
                x-on:change="
                    photoName = $refs.photo.files[0].name;
                    const file = $refs.photo.files[0];
                    if (file) {
                        if (!['image/jpeg', 'image/png'].includes(file.type)) {
                            alert('Please select a valid image file (JPEG/PNG)');
                            $refs.photo.value = null; // Reset input jika tidak valid
                            return;
                        }
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            photoPreview = e.target.result;
                            hasPhoto = true;
                        };
                        reader.readAsDataURL(file);
                    } else {
                        photoPreview = null;
                        hasPhoto = @json($user->profile_photo_path ? true : false);
                    }
                " />

        <x-label for="photo" value="{{ __('Photo Profile') }}" class="text-2xl font-semibold" />

        <div class="mt-4 flex justify-center">
            <div class="relative">
                <div class="rounded-full border-4 border-indigo-500 overflow-hidden w-48 h-48">
                    <img x-show="!photoPreview" src="{{ $user->profile_photo_path ? Storage::url($user->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=7F9CF5&background=EBF4FF' }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    <span x-show="photoPreview" class="block w-full h-full bg-cover bg-no-repeat bg-center"
                        :style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>
                 <div class="absolute bottom-0 right-0 bg-indigo-500 p-2 rounded-full cursor-pointer" @click.prevent="$refs.photo.click()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="mt-4 flex justify-center">
            <x-secondary-button class="w-48" type="button" x-on:click.prevent="$refs.photo.click()">
                <span x-text="hasPhoto && !photoPreview ? '{{ __('Edit Photo') }}' : '{{ __('Select A Photo') }}'"></span>
            </x-secondary-button>
        </div>

        <div class="mt-4 flex justify-center" x-show="photoPreview || $refs.photo.files.length > 0">
            <x-primary-button type="submit" class="w-48">
                {{ __('Save Photo') }}
            </x-primary-button>
        </div>

         @if (session('success'))
            <div class="mt-2 text-sm text-green-600">
                {{ session('success') }}
            </div>
        @endif

        <x-input-error for="photo" class="mt-2" />
    </div>
</form>