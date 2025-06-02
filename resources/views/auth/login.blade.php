<x-guest-layout>
  <div class="min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('/images/hero-bg.jpg')">
    {{-- overlay gelap tipis --}}
    <div class="absolute inset-0 bg-black/40"></div>

    <div class="relative w-full max-w-md bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl p-8 space-y-6 z-10">
      
      {{-- Logo --}}
      <div class="flex justify-center">
        <x-authentication-card-logo class="h-12 w-auto"/>
      </div>

      {{-- Judul --}}
      <h2 class="text-center text-2xl font-bold text-gray-900">
        Masuk ke Cost'an
      </h2>

      {{-- Errors & Status --}}
      <x-validation-errors class="mb-4"/>
      @if (session('status'))
        <div class="text-sm text-green-800 bg-green-100 rounded px-4 py-2">
          {{ session('status') }}
        </div>
      @endif

      {{-- Form --}}
      <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
          <x-label for="email" value="{{ __('Email') }}" class="block text-gray-700 mb-1"/>
          <x-input id="email"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#A8BB21] focus:border-transparent"
                   type="email" name="email" :value="old('email')" required autofocus
                   placeholder="you@example.com"/>
        </div>

        <div>
          <x-label for="password" value="{{ __('Password') }}" class="block text-gray-700 mb-1"/>
          <x-input id="password"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#A8BB21] focus:border-transparent"
                   type="password" name="password" required
                   placeholder="••••••••"/>
        </div>

        <div class="flex items-center justify-between text-sm">
          <label class="flex items-center">
            <x-checkbox id="remember_me" name="remember" class="h-4 w-4 text-[#A8BB21]"/>
            <span class="ml-2 text-gray-600">{{ __('Remember me') }}</span>
          </label>
          @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}"
               class="text-[#A8BB21] hover:underline">
              {{ __('Forgot password?') }}
            </a>
          @endif
        </div>

        <div>
          <x-button class="w-full py-2 font-semibold rounded-lg bg-[#A8BB21] hover:bg-[#95A11B] text-white">
            {{ __('Log in') }}
          </x-button>
        </div>
      </form>

      {{-- Footer --}}
      <p class="text-center text-sm text-gray-700">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-[#A8BB21] hover:underline font-medium">
          Daftar di sini
        </a>
      </p>
    </div>
  </div>
</x-guest-layout>
