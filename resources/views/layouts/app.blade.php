<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Montres Swiss watches - Mauritania') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-serif bg-gray-50 text-gray-900">
    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <img src="/images/logo.png" alt="Logo" class="h-8 w-auto" loading="lazy">
                <span class="text-xl tracking-wide">Swiss Heritage</span>
            </a>
            <nav class="hidden md:flex items-center gap-6">
                <a href="{{ url('/fr/collections') }}" class="hover:text-swiss-red">Collections</a>
                <a href="{{ url('/fr/products') }}" class="hover:text-swiss-red">Catalogue</a>
                <a href="{{ url('/fr/checkout') }}" class="hover:text-swiss-red">Checkout</a>
            </nav>
            <div class="flex items-center gap-4">
                @include('components.cart-icon')
            </div>
        </div>
    </header>

    <main class="min-h-screen">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <footer class="border-t bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 grid md:grid-cols-3 gap-8">
            <div>
                <h3 class="font-semibold mb-3">Héritage Suisse</h3>
                <p class="text-sm">Montres Swiss watches - Mauritania, précision et élégance. Garantie 2 ans sur tous
                    nos modèles.</p>
            </div>
            <div>
                <h3 class="font-semibold mb-3">Assistance</h3>
                <ul class="text-sm space-y-2">
                    <li><a href="#" class="hover:text-swiss-red">Livraison et retours</a></li>
                    <li><a href="#" class="hover:text-swiss-red">Service client</a></li>
                    <li><a href="#" class="hover:text-swiss-red">Conditions</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-semibold mb-3">Contact</h3>
                <p class="text-sm">info@swiss-heritage.ch<br>+41 22 000 00 00</p>
            </div>
        </div>
        <div class="text-center text-xs py-4">© {{ date('Y') }} Swiss Heritage. Tous droits réservés.</div>
    </footer>
    @livewireScripts
</body>

</html>