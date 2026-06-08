@props(['title' => 'Rijschool Vierkante Wielen'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 text-gray-800">
    <div class="min-h-screen flex flex-col">

        {{-- Eenvoudige navigatiebalk, werkt voor zowel gasten als ingelogde gebruikers --}}
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-6xl mx-auto px-4 sm:px-6">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center gap-8">
                        <a href="{{ route('home') }}" class="font-semibold text-lg text-gray-800">
                            Rijschool Vierkante Wielen
                        </a>
                        <div class="hidden sm:flex gap-6 text-sm">
                            <a href="{{ route('home') }}"
                               class="{{ request()->routeIs('home') ? 'text-gray-900 font-medium' : 'text-gray-500 hover:text-gray-800' }}">
                                Home
                            </a>
                            <a href="{{ route('driving-packages.index') }}"
                               class="{{ request()->routeIs('driving-packages.*') ? 'text-gray-900 font-medium' : 'text-gray-500 hover:text-gray-800' }}">
                                Lesrijpakketten
                            </a>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-sm">
                        @auth
                            <span class="hidden sm:inline text-gray-500">{{ Auth::user()->name }}</span>
                            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-800">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-gray-500 hover:text-gray-800">Uitloggen</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-800">Inloggen</a>
                            <a href="{{ route('register') }}"
                               class="px-3 py-1.5 bg-gray-800 text-white rounded-md hover:bg-gray-700">Registreren</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        {{-- Flash-meldingen als echte UI-overlay bovenaan de pagina --}}
        @include('partials.flash')

        <main class="flex-1">
            {{ $slot }}
        </main>

        <footer class="bg-white border-t border-gray-200 mt-12">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6 text-sm text-gray-500">
                &copy; {{ date('Y') }} Rijschool Vierkante Wielen
            </div>
        </footer>
    </div>
</body>

</html>
