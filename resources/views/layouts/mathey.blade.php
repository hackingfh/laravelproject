<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Mathey-Tissot') }} - Swiss watches - Mauritania Since 1886</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&family=Cormorant+Garamond:wght@400;600&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>

<body class="font-sans bg-mathey-white text-mathey-text antialiased">
    <!-- Page Loader -->
    <div id="page-loader" class="fixed inset-0 z-[9999] bg-mathey-blue flex items-center justify-center">
        <div class="relative">
            <div class="w-24 h-24 border-2 border-mathey-gold/20 rounded-full"></div>
            <div class="absolute top-0 left-0 w-24 h-24 border-t-2 border-mathey-gold rounded-full animate-spin"></div>
            <img src="/images/logo-mathey.png" alt="M-T"
                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 h-8 w-auto" width="128"
                height="32" loading="eager">
        </div>
    </div>
    <!-- Header -->
    <div id="searchBar"
        class="hidden fixed inset-0 z-[60] bg-mathey-blue/95 backdrop-blur-lg px-4 flex items-center justify-center">
        <div class="max-w-3xl w-full relative">
            <button onclick="toggleSearch()" class="absolute -top-16 right-0 text-white/70 hover:text-white">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
            <livewire:front.global-search />
        </div>
    </div>

    <header id="header"
        class="sticky top-0 z-50 bg-mathey-white/95 backdrop-blur-md border-b border-mathey-border transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="flex items-center gap-3 group">
                    <div class="relative">
                        <img src="/images/logo-mathey.png" alt="Mathey-Tissot"
                            class="h-10 w-auto transition-transform duration-300 group-hover:scale-105" width="160"
                            height="40" loading="eager">
                        <span
                            class="absolute -top-1 -right-1 text-xs font-bold text-mathey-gold tracking-wider">1886</span>
                    </div>
                    <div class="hidden sm:block">
                        <h1 class="text-xl font-serif font-semibold text-mathey-blue">Mathey-Tissot</h1>
                        <p class="text-xs tracking-wider text-mathey-gray uppercase">Swiss watches - Mauritania</p>
                    </div>
                </a>

                <!-- Navigation Desktop -->
                <nav class="hidden lg:flex items-center gap-8">
                    <a href="{{ route('collections.index', ['locale' => app()->getLocale()]) }}"
                        class="text-mathey-text hover:text-mathey-gold transition-colors duration-300 font-medium tracking-wide">
                        Collections
                    </a>
                    <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}"
                        class="text-mathey-text hover:text-mathey-gold transition-colors duration-300 font-medium tracking-wide">
                        Montres
                    </a>
                    <a href="{{ route('history', ['locale' => app()->getLocale()]) }}"
                        class="text-mathey-text hover:text-mathey-gold transition-colors duration-300 font-medium tracking-wide">
                        Notre Histoire
                    </a>
                    <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}"
                        class="text-mathey-text hover:text-mathey-gold transition-colors duration-300 font-medium tracking-wide">
                        Contact
                    </a>
                </nav>

                <!-- Actions -->
                <div class="flex items-center gap-4">
                    <!-- Search -->
                    <button class="p-2 text-mathey-gray hover:text-mathey-gold transition-colors duration-300"
                        onclick="toggleSearch()">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>

                    <!-- Cart -->
                    @include('components.cart-icon')

                    <!-- User Account -->
                    @auth
                        <div class="relative group">
                            <button
                                class="p-2 text-mathey-gray hover:text-mathey-gold transition-colors duration-300 flex items-center gap-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="text-xs font-medium hidden md:block">{{ Auth::user()->name }}</span>
                            </button>
                            <div
                                class="absolute right-0 top-full mt-2 w-48 bg-white border border-mathey-border rounded-xl shadow-xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-[70]">
                                <a href="{{ route('account.index', ['locale' => app()->getLocale()]) }}"
                                    class="block px-4 py-2 text-sm text-mathey-blue hover:bg-mathey-cream transition">Paramètres</a>
                                <a href="{{ route('orders.index', ['locale' => app()->getLocale()]) }}"
                                    class="block px-4 py-2 text-sm text-mathey-blue hover:bg-mathey-cream transition">Mes
                                    Commandes</a>
                                @if(Auth::user()->is_admin)
                                    <hr class="my-1 border-mathey-border">
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="block px-4 py-2 text-sm text-mathey-gold font-bold hover:bg-mathey-cream transition">Administration</a>
                                @endif
                                <hr class="my-1 border-mathey-border">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">Déconnexion</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            class="p-2 text-mathey-gray hover:text-mathey-gold transition-colors duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </a>
                    @endauth

                    <!-- Mobile Menu Toggle -->
                    <button class="lg:hidden p-2 text-mathey-gray hover:text-mathey-gold transition-colors duration-300"
                        onclick="toggleMobileMenu()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden lg:hidden border-t border-mathey-border bg-mathey-white">
            <nav class="px-4 py-6 space-y-4">
                <a href="{{ route('collections.index', ['locale' => app()->getLocale()]) }}"
                    class="block text-mathey-text hover:text-mathey-gold transition-colors duration-300 font-medium">
                    Collections
                </a>
                <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}"
                    class="block text-mathey-text hover:text-mathey-gold transition-colors duration-300 font-medium">
                    Montres
                </a>
                <a href="{{ route('history', ['locale' => app()->getLocale()]) }}"
                    class="block text-mathey-text hover:text-mathey-gold transition-colors duration-300 font-medium">
                    Notre Histoire
                </a>
                <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}"
                    class="block text-mathey-text hover:text-mathey-gold transition-colors duration-300 font-medium">
                    Contact
                </a>
                <hr class="border-mathey-border">
                @auth
                    <a href="{{ route('account.index', ['locale' => app()->getLocale()]) }}"
                        class="block text-mathey-blue hover:text-mathey-gold transition-colors duration-300 font-medium">
                        Mon Compte
                    </a>
                    <a href="{{ route('orders.index', ['locale' => app()->getLocale()]) }}"
                        class="block text-mathey-blue hover:text-mathey-gold transition-colors duration-300 font-medium">
                        Mes Commandes
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left text-red-600 hover:text-red-700 transition-colors duration-300 font-medium">
                            Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="block text-mathey-text hover:text-mathey-gold transition-colors duration-300 font-medium">
                        Se connecter
                    </a>
                @endauth
            </nav>
        </div>

        <!-- Search Bar (Hidden by default) -->
        <div id="searchBar" class="hidden border-t border-mathey-border bg-mathey-cream">
            <div class="max-w-3xl mx-auto px-4 py-4">
                <form class="relative">
                    <input type="text" placeholder="Rechercher une montre, une collection..."
                        class="w-full px-4 py-3 pr-12 border border-mathey-border rounded-lg focus:outline-none focus:border-mathey-gold transition-colors duration-300">
                    <button type="submit"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-mathey-gray hover:text-mathey-gold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </header>

    @if(session('success') || session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            @if(session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded-lg border border-green-200">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 bg-red-100 text-red-700 rounded-lg border border-red-200">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    @endif

    <!-- Main Content -->
    <main class="min-h-screen">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-mathey-cream border-t border-mathey-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Brand -->
                <div class="lg:col-span-1">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="/images/logo-mathey.png" alt="Mathey-Tissot" class="h-8 w-auto" width="128"
                            height="32" loading="lazy">
                        <div>
                            <h3 class="font-serif font-semibold text-mathey-blue">Mathey-Tissot</h3>
                            <p class="text-xs tracking-wider text-mathey-gray uppercase">Swiss watches - Mauritania
                                Since 1886</p>
                        </div>
                    </div>
                    <p class="text-sm text-mathey-gray mb-4">
                        L'excellence horlogère suisse au service de l'élégance intemporelle. Des montres d'exception,
                        héritières d'un savoir-faire unique.
                    </p>
                    <div class="flex gap-3">
                        <a href="#" class="text-mathey-gray hover:text-mathey-gold transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <a href="#" class="text-mathey-gray hover:text-mathey-gold transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </a>
                        <a href="#" class="text-mathey-gray hover:text-mathey-gold transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM5.838 12a6.162 6.162 0 1112.324 0 6.162 6.162 0 01-12.324 0zM12 16a4 4 0 110-8 4 4 0 010 8zm4.965-10.405a1.44 1.44 0 112.881.001 1.44 1.44 0 01-2.881-.001z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Collections -->
                <div>
                    <h4 class="font-serif font-semibold text-mathey-blue mb-4">Collections</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('collections.show', ['locale' => app()->getLocale(), 'slug' => 'evasion-open-heart']) }}"
                                class="text-mathey-gray hover:text-mathey-gold transition-colors duration-300">Evasion
                                Open Heart</a></li>
                        <li><a href="{{ route('collections.show', ['locale' => app()->getLocale(), 'slug' => 'zeus-chrono-auto']) }}"
                                class="text-mathey-gray hover:text-mathey-gold transition-colors duration-300">Zeus
                                Chrono Auto</a></li>
                        <li><a href="{{ route('collections.show', ['locale' => app()->getLocale(), 'slug' => 'zeus-lady-diamond']) }}"
                                class="text-mathey-gray hover:text-mathey-gold transition-colors duration-300">Zeus Lady
                                Diamond</a></li>
                        <li><a href="{{ route('collections.index', ['locale' => app()->getLocale()]) }}"
                                class="text-mathey-gray hover:text-mathey-gold transition-colors duration-300">Limited
                                Editions</a></li>
                        <li><a href="{{ route('collections.index', ['locale' => app()->getLocale()]) }}"
                                class="text-mathey-gray hover:text-mathey-gold transition-colors duration-300">Vintage
                                Collection</a></li>
                    </ul>
                </div>

                <!-- Services -->
                <div>
                    <h4 class="font-serif font-semibold text-mathey-blue mb-4">Services</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#"
                                class="text-mathey-gray hover:text-mathey-gold transition-colors duration-300">Livraison
                                Express</a></li>
                        <li><a href="#"
                                class="text-mathey-gray hover:text-mathey-gold transition-colors duration-300">Garantie
                                2 Ans</a></li>
                        <li><a href="#"
                                class="text-mathey-gray hover:text-mathey-gold transition-colors duration-300">Service
                                Après-Vente</a></li>
                        <li><a href="#"
                                class="text-mathey-gray hover:text-mathey-gold transition-colors duration-300">Personnalisation</a>
                        </li>
                        <li><a href="#"
                                class="text-mathey-gray hover:text-mathey-gold transition-colors duration-300">Guide des
                                Tailles</a></li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div>
                    <h4 class="font-serif font-semibold text-mathey-blue mb-4">Newsletter</h4>
                    <p class="text-sm text-mathey-gray mb-4">
                        Inscrivez-vous pour recevoir nos actualités et offres exclusives.
                    </p>
                    <form class="space-y-3" onsubmit="subscribeNewsletter(event)">
                        <input type="email" placeholder="Votre adresse email" required
                            class="w-full px-4 py-2 border border-mathey-border rounded-lg focus:outline-none focus:border-mathey-gold transition-colors duration-300">
                        <button type="submit"
                            class="w-full bg-mathey-gold text-white px-4 py-2 rounded-lg hover:bg-opacity-90 transition-all duration-300 font-medium tracking-wide">
                            S'inscrire
                        </button>
                    </form>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-mathey-border mt-12 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-mathey-gray text-center md:text-left">
                        © {{ date('Y') }} Mathey-Tissot - Swiss watches - Mauritania Since 1886. Tous droits réservés.
                    </p>
                    <div class="flex gap-6 text-sm">
                        <a href="#"
                            class="text-mathey-gray hover:text-mathey-gold transition-colors duration-300">Mentions
                            Légales</a>
                        <a href="#"
                            class="text-mathey-gray hover:text-mathey-gold transition-colors duration-300">CGV</a>
                        <a href="#"
                            class="text-mathey-gray hover:text-mathey-gold transition-colors duration-300">Politique de
                            Confidentialité</a>
                        <a href="#"
                            class="text-mathey-gray hover:text-mathey-gold transition-colors duration-300">FAQ</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    @livewireScripts

    <script>
        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        // Search Toggle
        function toggleSearch() {
            const searchBar = document.getElementById('searchBar');
            searchBar.classList.toggle('hidden');
            if (!searchBar.classList.contains('hidden')) {
                searchBar.querySelector('input').focus();
            }
        }

        // Newsletter Subscribe
        function subscribeNewsletter(event) {
            event.preventDefault();
            const email = event.target.querySelector('input[type="email"]').value;
            // Add your newsletter subscription logic here
            alert('Merci pour votre inscription ! Vous recevrez bientôt nos actualités.');
            event.target.reset();
        }

        // Initialize GSAP
        document.addEventListener('DOMContentLoaded', () => {
            gsap.registerPlugin(ScrollTrigger);

            // Hide Loader
            gsap.to("#page-loader", {
                opacity: 0,
                duration: 1,
                ease: "power2.inOut",
                onComplete: () => {
                    document.getElementById('page-loader').style.display = 'none';
                    // Trigger entrance animations
                    initEntranceAnimations();
                }
            });
        });

        function initEntranceAnimations() {
            // Header entrance
            gsap.from("#header", {
                y: -100,
                opacity: 0,
                duration: 1.2,
                ease: "power4.out"
            });
        }

        // Header Scroll Effect
        window.addEventListener('scroll', function () {
            const header = document.getElementById('header');
            if (window.scrollY > 50) {
                header.classList.add('shadow-lg', 'py-1');
                header.classList.remove('py-0');
            } else {
                header.classList.remove('shadow-lg', 'py-1');
            }
        });

        // Live Sync / Auto-reload
        (function ()  {
            let lastSync = null;
            const checkSync = async () => {
                try {
                    const response = await fetch('/storage/sync.json?cache-bust=' + Date.now());
                    if (response.ok) {
                        const data = await response.json();
                        if (lastSync && data.timestamp > lastSync) {
                            console.log('Sync detected, reloading...');
                            window.location.reload();
                        }
                        lastSync = data.timestamp;
                    }
                } catch (e) {
                    // Ignore errors (file might not exist yet)
                }
            };
            // Initial check to set the baseline
            checkSync();
            // Polling every 5 seconds
            setInterval(checkSync, 5000);
        })();
    </script>
</body>

</html>