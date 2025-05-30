<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-black-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    {{-- Form untuk mengirim ulang email verifikasi (jika diperlukan) --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- Profile Photo --}}
        {{-- Bagian ini akan ditampilkan jika Anda menggunakan Jetstream untuk manajemen foto profil --}}
        {{-- ATAU jika Anda mengaktifkan fitur ini di config Jetstream. --}}
        {{-- Jika Anda tidak menggunakan Jetstream, Anda bisa menghapus kondisi @if Jetstream ini --}}
        {{-- dan pastikan ProfileController@update menghandle 'photo' jika input ini ada. --}}
        @if (class_exists(Laravel\Jetstream\Jetstream::class) && Laravel\Jetstream\Jetstream::managesProfilePhotos())
        <div>
            <x-input-label for="photo" :value="__('Photo')" />
            <input type="file" id="photo" name="photo" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" />
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
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Phone --}}
        <div>
            <x-input-label for="phone" :value="__('Phone Number')" />
            {{-- Dibuat tidak required di HTML, karena di backend nullable --}}
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        {{-- Address --}}
        <div>
            <x-input-label for="address" :value="__('Address')" />
            {{-- Dibuat tidak required di HTML, karena di backend nullable --}}
            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $user->address)" autocomplete="street-address"/>
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>


        {{-- AWAL: Bagian Preferensi Pencarian (HANYA UNTUK PENYEWA) --}}
        {{-- Variabel $user dan $isOwner didapat dari ProfileController@edit --}}
        @if (isset($user) && $user->role === 'penyewa') {{-- Atau bisa juga: @if (isset($isOwner) && !$isOwner) --}}
            <div class="pt-6 border-t border-gray-200 dark:border-gray-700 mt-6">
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-black-100">
                        {{ __('Preferensi Pencarian Kos') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Isi preferensi ini jika Anda sedang mencari kos.') }}
                    </p>
                </header>

                <div class="mt-6 space-y-6">
                    {{-- Preferred Location --}}
                    <div>
                        <x-input-label for="preferred_location" :value="__('Preferred Location')" />
                        {{-- Dibuat tidak required, karena di backend nullable --}}
                        <x-text-input id="preferred_location" name="preferred_location" type="text" class="mt-1 block w-full" :value="old('preferred_location', $user->preferred_location)" />
                        <x-input-error class="mt-2" :messages="$errors->get('preferred_location')" />
                    </div>

                    {{-- Price Range --}}
                    <div>
                        <x-input-label for="price" :value="__('Price Range')" />
                        {{-- Dibuat tidak required, karena di backend nullable --}}
                        <select id="price" name="price" class="mt-1 block w-full border-gray-300 border-gray-700 bg-gray-900 text-black-300 focus:border-indigo-500 focus:border-indigo-600 focus:ring-indigo-500 focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">-- {{ __('Pilih Rentang Harga') }} --</option>
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
                        {{-- Dibuat tidak required, karena di backend nullable --}}
                        <select id="preferred_kos_type" name="preferred_kos_type" class="mt-1 block w-full border-gray-300 border-gray-700 bg-gray-900 text-black-300 focus:border-indigo-500 focus:border-indigo-600 focus:ring-indigo-500 focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">-- {{ __('Pilih Tipe Kos') }} --</option> {{-- Tambahkan opsi default kosong --}}
                            <option value="Campur" @selected(old('preferred_kos_type', $user->preferred_kos_type) == 'Campur')>{{ __('Kosan Campur') }}</option>
                            <option value="Putra" @selected(old('preferred_kos_type', $user->preferred_kos_type) == 'Putra')>{{ __('Kosan Khusus Pria') }}</option>
                            <option value="Putri" @selected(old('preferred_kos_type', $user->preferred_kos_type) == 'Putri')>{{ __('Kosan Khusus Wanita') }}</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('preferred_kos_type')" />
                    </div>

                    {{-- Preferred Facilities --}}
                    <div>
                        <x-input-label :value="__('Preferred Facilities')" /> {{-- Tidak perlu for="preferred_facilities" jika bukan untuk satu input spesifik --}}
                        <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-x-4 gap-y-2">
                            @php
                                $facilities = [
                                    'AC', 'WiFi', 'Kamar Mandi Dalam', 'Kasur', 'Lemari', 'Meja Belajar', 'Kursi Belajar',
                                    'Dapur Bersama', 'Laundry', 'Tempat Parkir Motor', 'Tempat Parkir Mobil', 'Keamanan 24 Jam', 'CCTV', 'Akses Kartu'
                                ];
                                $userFacilities = old('preferred_facilities', $user->preferred_facilities ?? []);
                                // Pastikan $userFacilities adalah array
                                if (is_string($userFacilities)) {
                                    $userFacilities = json_decode($userFacilities, true) ?? [];
                                }
                                if (!is_array($userFacilities)) { $userFacilities = []; }
                            @endphp
                            @foreach ($facilities as $facility)
                                <label class="flex items-center">
                                    <input type="checkbox" name="preferred_facilities[]" value="{{ $facility }}"
                                           class="rounded border-gray-300 border-black-700 text-indigo-600 shadow-sm dark:focus:ring-indigo-500 focus:ring-indigo-600 bg-gray-900 dark:focus:ring-offset-gray-800"
                                           @if (in_array($facility, $userFacilities)) checked @endif>
                                    <span class="ml-2 text-sm text-black-600 dark:text-gray-400">{{ __($facility) }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('preferred_facilities')" />
                        <x-input-error class="mt-2" :messages="$errors->get('preferred_facilities.*')" /> {{-- Untuk error per item array --}}
                    </div>
                </div>
            </div>
        @endif
        {{-- AKHIR: Bagian Preferensi Pencarian --}}


        <div class="flex items-center gap-4 mt-6">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated' || session('success'))
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm text-green-600 dark:text-green-400"
                >{{ session('success') ?: __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>