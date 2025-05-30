<x-app-layout> 
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div>
            <form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST') // Sebaiknya POST atau PUT/PATCH khusus untuk update
                <input type="file" name="photo" class="form-control" id="photo" required>
                <button type="submit" class="btn btn-primary">Simpan Foto</button>
            </form>
            
            <!-- Form untuk Update Foto Profil -->
            <div class="mt-10 sm:mt-0">
                @include('profile.partials.profile-photo-form') <!-- Form Update Foto Profil -->
            </div>
            <x-section-border />
            
            <!-- Form untuk Update Informasi Profil -->
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-profile-information-form') <!-- Komponen Livewire untuk Update Profil -->
                </div>
                <x-section-border />
            @endif

            <!-- Form untuk Update Password -->
            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form') <!-- Komponen Livewire untuk Update Password -->
                </div>
                <x-section-border />
            @endif

            <!-- Form untuk Mengelola Autentikasi Dua Faktor -->
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.two-factor-authentication-form') <!-- Komponen Livewire untuk Two Factor Authentication -->
                </div>
                <x-section-border />
            @endif

            <!-- Form untuk Logout Sesi Browser Lain -->
            <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form') <!-- Komponen Livewire untuk Logout -->
            </div>

            <!-- Form untuk Menghapus Akun -->
            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-section-border />
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.delete-user-form') <!-- Komponen Livewire untuk Hapus Akun -->
                </div>
            @endif
            
        </div>
    </div>
</x-app-layout>
