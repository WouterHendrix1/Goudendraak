<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>De Guldendraak</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
</head>
<body>

    {{-- Navigatie --}}
    <header class="main-header">
        <h1>De Guldendraak</h1>
        <nav class="main-nav">
            <a href="{{ url('/') }}">Home</a>
            <a href="{{ url('/menu') }}">Menu</a>
            <a href="{{ url('/nieuws') }}">Nieuws</a>
            <a href="{{ url('/contact') }}">Contact</a>
            @auth
                <a href="{{ route('admin.index') }}">Admin</a>
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">
                        Logout
                    </button>
                </form>
            @endauth
        </nav>
    </header>

    {{-- Pagina-inhoud --}}
    <main class="main-content">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="main-footer">
        <p><strong>De Guldendraak</strong></p>
        <p>Marktstraat 12, 1234 AB, Stad</p>
        <p>📞 012-3456789 | ✉ info@guldendraak.nl</p>
        <p>
            <a href="https://maps.google.com/?q=Marktstraat+12+1234+AB" target="_blank">Bekijk op Google Maps</a>
        </p>
    </footer>
    @stack('scripts')
</body>
@vite('resources/js/app.js')
</html>
