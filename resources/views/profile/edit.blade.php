<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-black-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    {{-- Tambahkan x-data di sini untuk mengelola state modal --}}
    <div class="py-12" x-data="profileRoleHandler('{{ Auth::user()->role }}', '{{ route('profile.setRole') }}', '{{ csrf_token() }}')">

        {{-- Pop-up Modal untuk Pemilihan Peran --}}
        <div x-show="showRoleModal"
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-title" role="dialog" aria-modal="true"
             style="display: none;" {{-- Alpine.js akan mengontrol display --}}
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                {{-- Background overlay, klik untuk menutup (opsional) --}}
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75" aria-hidden="true" @click="showRoleModal = false"></div>

                {{-- Konten Modal --}}
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-gray-800 rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div>
                        <div class="mt-3 text-center sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-black-100" id="modal-title">
                                Konfirmasi Peran Anda
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-black-400">
                                    Untuk memberikan pengalaman terbaik, mohon pilih peran Anda di platform kami:
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                        <button @click="submitRole('penyewa')" type="button" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-1 sm:text-sm dark:focus:ring-offset-gray-800">
                            Saya Penyewa Kos
                        </button>
                        <button @click="submitRole('pemilik_kos')" type="button" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:col-start-2 sm:text-sm dark:focus:ring-offset-gray-800">
                            Saya Pemilik Kos
                        </button>
                    </div>
                    <div x-show="isLoading" class="mt-4 text-sm text-center text-gray-500 dark:text-black-400">
                        Menyimpan pilihan...
                    </div>
                </div>
            </div>
        </div>
        {{-- Akhir Pop-up Modal --}}


        {{-- Konten Halaman Profil Utama --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Form untuk Update Foto Profil --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg"> {{-- INI DIA! --}}
                <div class="max-w-xl">
                     @if(class_exists(\App\Livewire\ProfilePhotoForm::class))
                        <livewire:profile-photo-form />
                    @else
                        @include('profile.partials.profile-photo-form')
                    @endif
                </div>
            </div>

            {{-- Form untuk Update Informasi Profil (Data Diri & Preferensi) --}}
            {{-- Variabel $user dan $isOwner diteruskan dari ProfileController@edit --}}
            {{-- dan akan tersedia di partial update-profile-information-form --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Form untuk Update Password --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Form untuk Hapus User --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function profileRoleHandler(initialRole, setRoleUrl, csrfToken) {
            return {
                showRoleModal: !initialRole, // Tampilkan modal jika initialRole kosong/null/undefined
                userRole: initialRole,
                setRoleUrl: setRoleUrl,
                csrfToken: csrfToken,
                isLoading: false,

                submitRole: function(roleValue) {
                    if (this.isLoading) return;
                    this.isLoading = true;

                    fetch(this.setRoleUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json', // Penting agar Laravel tahu ini AJAX
                        },
                        body: JSON.stringify({ role: roleValue })
                    })
                    .then(response => {
                        if (!response.ok) {
                            // Jika status HTTP bukan 2xx, lempar error untuk ditangkap di .catch()
                            return response.json().then(err => { throw err; });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            this.userRole = data.newRole;
                            this.showRoleModal = false;
                            window.location.reload();
                        } else {
                            // Tampilkan pesan error dari server jika ada
                            alert(data.message || 'Gagal menyimpan peran. Silakan coba lagi.');
                            console.error('Error setting role:', data);
                        }
                    })
                    .catch(error => {
                        alert('Terjadi kesalahan jaringan atau server. Silakan coba lagi.');
                        console.error('Fetch error:', error);
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
                }
            }
        }
    </script>
    @endpush
</x-app-layout>