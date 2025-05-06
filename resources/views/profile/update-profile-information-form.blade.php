<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" id="photo" class="hidden"
                            wire:model.live="photo"
                            x-ref="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-label for="photo" value="{{ __('Photo') }}" />

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-secondary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Select A New Photo') }}
                </x-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Remove Photo') }}
                    </x-secondary-button>
                @endif

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Name') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" required autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required autocomplete="username" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="text-sm mt-2">
                    {{ __('Your email address is unverified.') }}

                    <button type="button" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" wire:click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 font-medium text-sm text-green-600">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            @endif
        </div>

        <!-- Phone Number -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="phone" value="{{ __('Phone Number') }}" />
            <x-input id="phone" type="text" class="mt-1 block w-full" wire:model="state.phone" required />
            <x-input-error for="phone" class="mt-2" />
        </div>

        <!-- Address -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="address" value="{{ __('Address') }}" />
            <x-input id="address" type="text" class="mt-1 block w-full" wire:model="state.address" required />
            <x-input-error for="address" class="mt-2" />
        </div>

        <!-- Preferred Location -->
        <div class="col-span-6 sm:col-span-4">
                    <x-label for="preferred_location" value="{{ __('Preferred Location') }}" />
                    <x-input id="preferred_location" type="text" class="mt-1 block w-full" wire:model="state.preferred_location" required />
                    <x-input-error for="preferred_location" class="mt-2" />
        </div>

         <!-- Price Range -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="price" value="{{ __('Price Range') }}" />
            <select id="price" wire:model="state.price" class="mt-1 block w-full" required>
                <option value="">-- Pilih Rentang Harga --</option>
                <option value="500000-1000000">Rp 500.000 - Rp 1.000.000 / bulan</option>
                <option value="1000000-1500000">Rp 1.000.000 - Rp 1.500.000 / bulan</option>
                <option value="1500000-2000000">Rp 1.500.000 - Rp 2.000.000 / bulan</option>
                <option value="2000000-2500000">Rp 2.000.000 - Rp 2.500.000 / bulan</option>
                <option value="2500000-3000000">Rp 2.500.000 - Rp 3.000.000 / bulan</option>
                <option value="3000000-4000000">Rp 3.000.000 - Rp 4.000.000 / bulan</option>
            </select>
            <x-input-error for="price" class="mt-2" />
        </div>

        <!-- Preferred Kos Type -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="preferred_kos_type" value="{{ __('Preferred Kos Type') }}" />
            <select id="preferred_kos_type" wire:model="state.preferred_kos_type" class="mt-1 block w-full">
                <option value="kosan_campur">{{ __('Kosan Campur') }}</option>
                <option value="kosan_pria">{{ __('Kosan Khusus Pria') }}</option>
                <option value="kosan_wanita">{{ __('Kosan Khusus Wanita') }}</option>
            </select>
            <x-input-error for="preferred_kos_type" class="mt-2" />
        </div>

        <!-- Preferred Facilities -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="preferred_facilities" value="{{ __('Preferred Facilities') }}" />
            <div class="flex flex-col mt-2 space-y-2 max-h-60 overflow-y-auto">
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="state.preferred_facilities" value="AC" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm">{{ __('AC') }}</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="state.preferred_facilities" value="WiFi" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm">{{ __('WiFi') }}</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="state.preferred_facilities" value="Kamar Mandi Dalam" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm">{{ __('Kamar Mandi Dalam') }}</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="state.preferred_facilities" value="Kasur" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm">{{ __('Kasur') }}</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="state.preferred_facilities" value="Lemari" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm">{{ __('Lemari') }}</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="state.preferred_facilities" value="Meja Belajar" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm">{{ __('Meja Belajar') }}</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="state.preferred_facilities" value="Kursi Belajar" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm">{{ __('Kursi Belajar') }}</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="state.preferred_facilities" value="Dapur Bersama" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm">{{ __('Dapur Bersama') }}</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="state.preferred_facilities" value="Laundry" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm">{{ __('Laundry') }}</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="state.preferred_facilities" value="Tempat Parkir Motor" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm">{{ __('Tempat Parkir Motor') }}</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="state.preferred_facilities" value="Tempat Parkir Mobil" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm">{{ __('Tempat Parkir Mobil') }}</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="state.preferred_facilities" value="Keamanan 24 Jam" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm">{{ __('Keamanan 24 Jam') }}</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="state.preferred_facilities" value="CCTV" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm">{{ __('CCTV') }}</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="state.preferred_facilities" value="Akses Kartu" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm">{{ __('Akses Kartu') }}</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="state.preferred_facilities" value="Air Panas" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm">{{ __('Air Panas') }}</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="state.preferred_facilities" value="TV" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm">{{ __('TV') }}</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="state.preferred_facilities" value="Dispenser" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm">{{ __('Dispenser') }}</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="state.preferred_facilities" value="Kulkas" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm">{{ __('Kulkas') }}</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="state.preferred_facilities" value="Balkon" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm">{{ __('Balkon') }}</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="state.preferred_facilities" value="Jemuran" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm">{{ __('Jemuran') }}</span>
                </label>
            </div>
            <x-input-error for="preferred_facilities" class="mt-2" />
        </div>

    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>
