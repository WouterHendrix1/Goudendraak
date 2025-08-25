<x-guest-layout>
  <div class="min-h-screen bg-[#7a0e0e] bg-gradient-to-b from-[#7a0e0e] to-[#5c0a0a] flex items-center justify-center p-6">
    <div class="w-full max-w-md">
      <div class="bg-white/95 backdrop-blur border border-white/40 shadow-xl rounded-2xl p-6 sm:p-7">
        {{-- Logo + title (optional logo path) --}}
        <div class="text-center mb-5">
          <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 mx-auto mb-2">
          <h1 class="text-2xl font-extrabold tracking-tight text-[#611010]">Inloggen</h1>
          <p class="text-sm text-gray-500">Welkom terug 👋</p>
        </div>

        {{-- Session Status --}}
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
          @csrf

          {{-- Email --}}
          <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email"
              class="block mt-1 w-full rounded-xl border-gray-300 focus:border-[#7a0e0e] focus:ring-[#7a0e0e] text-base"
              type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
          </div>

          {{-- Password --}}
          <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password"
              class="block mt-1 w-full rounded-xl border-gray-300 focus:border-[#7a0e0e] focus:ring-[#7a0e0e] text-base"
              type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
          </div>

          {{-- Submit --}}
          <div class="mt-5">
            <x-primary-button
              class="w-full justify-center rounded-xl py-3 text-base bg-[#7a0e0e] hover:bg-[#5c0a0a] focus:ring-[#7a0e0e]">
              {{ __('Log in') }}
            </x-primary-button>
          </div>
        </form>
      </div>

      {{-- footer hint (optional) --}}
      <p class="mt-6 text-center text-sm text-white/80">
        © {{ date('Y') }} {{ config('app.name') }}
      </p>
    </div>
  </div>
</x-guest-layout>
