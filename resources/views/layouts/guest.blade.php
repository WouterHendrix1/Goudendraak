<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @stack('styles')
    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="font-sans antialiased text-gray-900 bg-[#7a0e0e] bg-gradient-to-b from-[#7a0e0e] to-[#5c0a0a]">
    <div class="min-h-screen flex items-center justify-center p-6">
      <div class="w-full max-w-md">
        <a href="/" class="block text-center mb-4">
          <img src="{{ asset('images/dragon-large.png') }}" alt="Logo" class="h-12 mx-auto" />
        </a>

        <!-- Auth card -->
        <div class="bg-white/95 backdrop-blur border border-white/40 shadow-xl rounded-2xl p-6 sm:p-7">
          {{ $slot }}
        </div>

        <!-- Footer (optional) -->
        <p class="mt-6 text-center text-sm text-white/80">
          © {{ date('Y') }} {{ config('app.name') }}
        </p>
      </div>
    </div>

    @stack('scripts')
  </body>
</html>
