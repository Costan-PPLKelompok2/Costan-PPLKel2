<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Profile Photo --}}
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
        <div>
            <x-input-label for="photo" :value="__('Photo')" />
            <input type="file" id="photo" name="photo" class="mt-1 block w-full" />
            <x-input-error class="mt-2" :messages="$errors->get('photo')" />
            @if ($user->profile_photo_path)
                <div class="mt-2">
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="rounded-full h-20 w-20 object-cover">
                </div>
            @endif
        </div>
        @endif

        {{-- Name --}}
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Phone --}}
        <div>
            <x-input-label for="phone" :value="__('Phone Number')" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" required />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        {{-- Address --}}
        <div>
            <x-input-label for="address" :value="__('Address')" />
            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $user->address)" required />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        {{-- Preferred Location --}}
        <div>
            <x-input-label for="preferred_location" :value="__('Preferred Location')" />
            <x-text-input id="preferred_location" name="preferred_location" type="text" class="mt-1 block w-full" :value="old('preferred_location', $user->preferred_location)" required />
            <x-input-error class="mt-2" :messages="$errors->get('preferred_location')" />
        </div>

        {{-- Price Range --}}
        <div>
            <x-input-label for="price" :value="__('Price Range')" />
            <select id="price" name="price" class="mt-1 block w-full" required>
                <option value="">-- Pilih Rentang Harga --</option>
                @foreach ([
                    '500000-1000000' => 'Rp 500.000 - Rp 1.000.000 / bulan',
                    '1000000-1500000' => 'Rp 1.000.000 - Rp 1.500.000 / bulan',
                    '1500000-2000000' => 'Rp 1.500.000 - Rp 2.000.000 / bulan',
                    '2000000-2500000' => 'Rp 2.000.000 - Rp 2.500.000 / bulan',
                    '2500000-3000000' => 'Rp 2.500.000 - Rp 3.000.000 / bulan',
                    '3000000-4000000' => 'Rp 3.000.000 - Rp 4.000.000 / bulan',
                ] as $value => $label)
                    <option value="{{ $value }}" @selected(old('price', $user->price) == $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('price')" />
        </div>

        {{-- Preferred Kos Type --}}
        <div>
            <x-input-label for="preferred_kos_type" :value="__('Preferred Kos Type')" />
            <select id="preferred_kos_type" name="preferred_kos_type" class="mt-1 block w-full" required>
                <option value="kosan_campur" @selected(old('preferred_kos_type', $user->preferred_kos_type) == 'kosan_campur')>{{ __('Kosan Campur') }}</option>
                <option value="kosan_pria" @selected(old('preferred_kos_type', $user->preferred_kos_type) == 'kosan_pria')>{{ __('Kosan Khusus Pria') }}</option>
                <option value="kosan_wanita" @selected(old('preferred_kos_type', $user->preferred_kos_type) == 'kosan_wanita')>{{ __('Kosan Khusus Wanita') }}</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('preferred_kos_type')" />
        </div>

        {{-- Preferred Facilities --}}
        <div>
            <x-input-label for="preferred_facilities" :value="__('Preferred Facilities')" />
            <div class="mt-2 space-y-2">
                @php
                    $facilities = [
                        'AC', 'WiFi', 'Kamar Mandi Dalam', 'Kasur', 'Lemari', 'Meja Belajar', 'Kursi Belajar',
                        'Dapur Bersama', 'Laundry', 'Tempat Parkir Motor', 'Tempat Parkir Mobil', 'Keamanan 24 Jam', 'CCTV', 'Akses Kartu'
                    ];
                    $userFacilities = old('preferred_facilities', $user->preferred_facilities ?? []);
                @endphp
                @foreach ($facilities as $facility)
                    <label class="flex items-center">
                        <input type="checkbox" name="preferred_facilities[]" value="{{ $facility }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            @if (is_array($userFacilities) && in_array($facility, $userFacilities)) checked @endif>
                        <span class="ml-2 text-sm">{{ __($facility) }}</span>
                    </label>
                @endforeach
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('preferred_facilities')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
