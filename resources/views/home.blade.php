@extends('layouts.mathey')

@section('content')
    <!-- Hero Section -->
    <section class="relative h-screen flex items-center justify-center overflow-hidden">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 z-0 scale-110" id="hero-bg">
            <img src="/images/hero-mathey-tissot.jpg" alt="Mathey-Tissot Swiss Watches" class="w-full h-full object-cover"
                width="1920" height="1080" loading="eager">
            <div class="absolute inset-0 bg-gradient-to-r from-mathey-blue/80 via-mathey-blue/60 to-transparent"></div>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center lg:text-left">
            <div class="max-w-3xl opacity-0 translate-y-10" id="hero-content">
                <!-- Heritage Badge -->
                <div
                    class="inline-flex items-center gap-2 bg-mathey-gold/20 backdrop-blur-sm border border-mathey-gold/30 rounded-full px-4 py-2 mb-6 transition-all duration-500 hover:bg-mathey-gold/30">
                    <span class="text-xs font-bold tracking-wider text-mathey-gold uppercase">Swiss watches - Mauritania
                        Since 1886</span>
                </div>

                <!-- Main Title -->
                <h1
                    class="font-serif text-4xl md:text-5xl lg:text-6xl xl:text-hero font-bold text-white mb-6 leading-tight">
                    Timepieces<br>
                    <span class="text-mathey-gold italic overflow-hidden inline-block">
                        <span class="inline-block animate-slide-up-slow">Influenced by History</span>
                    </span>
                </h1>

                <!-- Subtitle -->
                <p class="text-xl md:text-2xl text-white/90 mb-8 font-light leading-relaxed">
                    Crafted for present. Designed for eternity.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('collections.index', ['locale' => app()->getLocale()]) }}"
                        class="btn-primary inline-flex items-center justify-center gap-2 group overflow-hidden relative">
                        <span class="relative z-10">Découvrir</span>
                        <svg class="w-5 h-5 relative z-10 transition-transform duration-300 group-hover:translate-x-1"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                    <a href="{{ route('history', ['locale' => app()->getLocale()]) }}"
                        class="btn-secondary inline-flex items-center justify-center gap-2 border-white text-white hover:bg-white hover:text-mathey-blue transition-all duration-500">
                        Notre Histoire
                    </a>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white animate-bounce">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>

    <!-- Collections Section -->
    <section class="py-20 bg-mathey-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16 animate-slide-up">
                <h2 class="font-serif text-3xl md:text-4xl lg:text-title font-bold text-mathey-blue mb-4">
                    Nos Collections
                </h2>
                <p class="text-lg text-mathey-gray max-w-2xl mx-auto">
                    Découvrez nos créations horlogères, alliant tradition suisse et innovation contemporaine.
                </p>
            </div>

            <!-- Collections Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Evasion Open Heart -->
                <div class="card-luxury relative">
                    <div class="aspect-w-4 aspect-h-3 relative h-96">
                        <img src="/images/collections/evasion-open-heart.jpg" alt="Evasion Open Heart Collection"
                            class="w-full h-full object-cover transition-transform duration-700 hover:scale-110" width="600"
                            height="800" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-mathey-blue/80 via-transparent to-transparent">
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <span class="badge mb-3">NOUVEAUTÉ</span>
                        <h3 class="font-serif text-2xl font-bold mb-2">Evasion Open Heart</h3>
                        <p class="text-white/90 mb-4 text-sm">L'élégance du mouvement visible au cœur de votre montre.</p>
                        <a href="{{ route('collections.show', ['locale' => app()->getLocale(), 'slug' => 'evasion-open-heart']) }}"
                            class="btn-primary py-2 px-6 text-sm inline-flex items-center gap-2">
                            Découvrir
                        </a>
                    </div>
                </div>

                <!-- Zeus Chrono Auto -->
                <div class="card-luxury relative">
                    <div class="aspect-w-4 aspect-h-3 relative h-96">
                        <img src="/images/collections/zeus-chrono-auto.jpg" alt="Zeus Chrono Auto Collection"
                            class="w-full h-full object-cover transition-transform duration-700 hover:scale-110" width="600"
                            height="800" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-mathey-blue/80 via-transparent to-transparent">
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <span class="badge mb-3 bg-mathey-red">BEST-SELLER</span>
                        <h3 class="font-serif text-2xl font-bold mb-2">Zeus Chrono Auto</h3>
                        <p class="text-white/90 mb-4 text-sm">La précision chronométrique suisse en mouvement automatique.
                        </p>
                        <a href="{{ route('collections.show', ['locale' => app()->getLocale(), 'slug' => 'zeus-chrono-auto']) }}"
                            class="btn-primary py-2 px-6 text-sm inline-flex items-center gap-2">
                            Découvrir
                        </a>
                    </div>
                </div>

                <!-- Zeus Lady Diamond -->
                <div class="card-luxury relative">
                    <div class="aspect-w-4 aspect-h-3 relative h-96">
                        <img src="/images/collections/zeus-lady-diamond.jpg" alt="Zeus Lady Diamond Collection"
                            class="w-full h-full object-cover transition-transform duration-700 hover:scale-110" width="600"
                            height="800" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-mathey-blue/80 via-transparent to-transparent">
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <span class="badge mb-3">EXCLUSIF</span>
                        <h3 class="font-serif text-2xl font-bold mb-2">Zeus Lady Diamond</h3>
                        <p class="text-white/90 mb-4 text-sm">L'éclat des diamants au service de l'horlogerie féminine.</p>
                        <a href="{{ route('collections.show', ['locale' => app()->getLocale(), 'slug' => 'zeus-lady-diamond']) }}"
                            class="btn-primary py-2 px-6 text-sm inline-flex items-center gap-2">
                            Découvrir
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Selection du Mois Section -->
    <section class="py-20 bg-mathey-cream overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-12">
                <div>
                    <h2 class="font-serif text-3xl md:text-4xl lg:text-title font-bold text-mathey-blue mb-2">
                        Sélection du Mois
                    </h2>
                    <div class="h-1 w-20 bg-mathey-gold"></div>
                </div>
                <div class="flex gap-4">
                    <button
                        class="p-3 rounded-full border border-mathey-gold text-mathey-gold hover:bg-mathey-gold hover:text-white transition-all duration-300"
                        onclick="scrollSlider('left')">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </button>
                    <button
                        class="p-3 rounded-full border border-mathey-gold text-mathey-gold hover:bg-mathey-gold hover:text-white transition-all duration-300"
                        onclick="scrollSlider('right')">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div id="selectionSlider" class="flex gap-8 overflow-x-auto snap-x scroll-smooth pb-10 no-scrollbar">
                @foreach($featuredProducts ?? [] as $product)
                    <div class="min-w-[300px] sm:min-w-[350px] snap-start">
                        <div class="card-luxury h-full group">
                            <div class="relative aspect-square overflow-hidden">
                                <img src="{{ data_get($product, 'images.0', '/images/watch-placeholder.jpg') }}"
                                    alt="{{ data_get($product, 'name', 'Montre') }}"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                    width="400" height="400" loading="lazy">
                                <div class="absolute top-4 right-4">
                                    <span class="badge">Swiss watches - Mauritania</span>
                                </div>
                            </div>
                            <div class="p-6 text-center">
                                <p class="text-xs text-mathey-gray tracking-widest uppercase mb-1">
                                    {{ data_get($product, 'reference', '---') }}</p>
                                <h3 class="font-serif text-xl font-bold text-mathey-blue mb-3">
                                    {{ data_get($product, 'name', 'Expertise Mathey-Tissot') }}</h3>
                                <p class="text-mathey-gold font-bold text-lg mb-4">
                                    {{ number_format(data_get($product, 'price', 0), 2) }} €</p>
                                <button onclick="addToCart({{ data_get($product, 'id', 0) }})"
                                    class="btn-primary w-full text-sm py-2">
                                    Ajouter au panier
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if(empty($featuredProducts))
                    <!-- Placeholder items for demonstration if no products yet -->
                    @for($i = 1; $i <= 5; $i++)
                        <div class="min-w-[300px] sm:min-w-[350px] snap-start">
                            <div class="card-luxury h-full group">
                                <div class="relative aspect-square overflow-hidden bg-gray-100">
                                    <div
                                        class="absolute inset-0 flex items-center justify-center text-mathey-gray/20 font-serif text-6xl">
                                        1886</div>
                                    <div class="absolute top-4 right-4">
                                        <span class="swiss-badge">Swiss watches - Mauritania</span>
                                    </div>
                                </div>
                                <div class="p-6 text-center">
                                    <p class="text-xs text-mathey-gray tracking-widest uppercase mb-1">MT-{{ 2024 + $i }}</p>
                                    <h3 class="font-serif text-xl font-bold text-mathey-blue mb-3">Modèle Prestige #{{ $i }}</h3>
                                    <p class="text-mathey-gold font-bold text-lg mb-4">{{ 1200 + ($i * 150) }} €</p>
                                    <button class="btn-primary w-full text-sm py-2">
                                        Ajouter au panier
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endfor
                @endif
            </div>
        </div>
    </section>

    <!-- Heritage Section -->
    <section class="py-20 bg-mathey-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="order-2 lg:order-1">
                    <img src="/images/heritage-workshop.jpg" alt="Atelier Mathey-Tissot" class="rounded-lg shadow-2xl">
                </div>
                <div class="order-1 lg:order-2">
                    <span
                        class="inline-block px-4 py-2 bg-mathey-gold/20 text-mathey-gold text-sm font-bold rounded-full mb-6">
                        137 ANS D'EXCELLENCE
                    </span>
                    <h2 class="font-serif text-3xl md:text-4xl lg:text-title font-bold text-mathey-blue mb-6">
                        L'Héritage d'une<br>
                        <span class="text-mathey-gold">Dynastie Horlogère</span>
                    </h2>
                    <p class="text-lg text-mathey-gray mb-6 leading-relaxed">
                        Depuis 1886, Mathey-Tissot incarne l'excellence de l'horlogerie suisse. Chaque montre est le fruit
                        d'un savoir-faire transmis de génération en génération, alliant tradition et innovation pour créer
                        des pièces d'exception qui traversent le temps.
                    </p>
                    <div class="grid grid-cols-3 gap-6 mb-8">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-mathey-gold mb-2">1886</div>
                            <div class="text-sm text-mathey-gray">Année de fondation</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-mathey-gold mb-2">137</div>
                            <div class="text-sm text-mathey-gray">Ans d'expertise</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-mathey-gold mb-2">100%</div>
                            <div class="text-sm text-mathey-gray">Swiss watches - Mauritania</div>
                        </div>
                    </div>
                    <a href="{{ route('history', ['locale' => app()->getLocale()]) }}"
                        class="inline-flex items-center gap-2 bg-mathey-gold text-white px-8 py-4 rounded-lg hover:bg-mathey-gold/90 transition-all duration-300 font-medium tracking-wide">
                        Découvrir notre histoire
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-20 bg-mathey-blue">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-serif text-3xl md:text-4xl font-bold text-white mb-4">
                Restez Connecté avec l'Excellence
            </h2>
            <p class="text-lg text-white/90 mb-8">
                Recevez nos actualités, collections exclusives et offres spéciales directement dans votre boîte mail.
            </p>
            <form class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto" onsubmit="subscribeNewsletter(event)">
                <input type="email" placeholder="Votre adresse email" required
                    class="flex-1 px-6 py-4 rounded-lg focus:outline-none focus:ring-4 focus:ring-mathey-gold/30">
                <button type="submit"
                    class="bg-mathey-gold text-white px-8 py-4 rounded-lg hover:bg-mathey-gold/90 transition-all duration-300 font-medium tracking-wide">
                    S'inscrire
                </button>
            </form>
            <p class="text-sm text-white/70 mt-4">
                En vous inscrivant, vous acceptez de recevoir notre newsletter. Vous pouvez vous désinscrire à tout moment.
            </p>
        </div>
    </section>

    <script>
        // Slider Scroll
        function scrollSlider(direction) {
            const slider = document.getElementById('selectionSlider');
            const scrollAmount = 350;
            if (direction === 'left') {
                slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            } else {
                slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            }
        }

        // Add to cart functionality
        function addToCart(productId) {
            alert('Article ajouté au panier !');
            const cartIcon = document.querySelector('a[href="/cart"] span');
            if (cartIcon) {
                const currentCount = parseInt(cartIcon.textContent) || 0;
                cartIcon.textContent = currentCount + 1;
            }
        }

        // Home Page GSAP Animations
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof gsap === 'undefined') return;

            gsap.registerPlugin(ScrollTrigger);

            // Hero Parallax
            gsap.to("#hero-bg", {
                scrollTrigger: {
                    trigger: "section",
                    start: "top top",
                    end: "bottom top",
                    scrub: true
                },
                y: 100,
                scale: 1.2
            });

            // Hero Content Reveal
            gsap.to("#hero-content", {
                opacity: 1,
                y: 0,
                duration: 1.5,
                delay: 0.8,
                ease: "power4.out"
            });

            // Section Titles Reveal
            const titles = document.querySelectorAll('h2');
            titles.forEach(title => {
                gsap.from(title, {
                    scrollTrigger: {
                        trigger: title,
                        start: "top 90%",
                        toggleActions: "play none none reverse"
                    },
                    y: 30,
                    opacity: 0,
                    duration: 1,
                    ease: "power3.out"
                });
            });

            // Collection Cards Stagger
            gsap.from(".card-luxury", {
                scrollTrigger: {
                    trigger: ".grid",
                    start: "top 80%"
                },
                y: 50,
                opacity: 0,
                duration: 1,
                stagger: 0.2,
                ease: "power3.out"
            });

            // Featured Products Stagger
            gsap.from("#selectionSlider > div", {
                scrollTrigger: {
                    trigger: "#selectionSlider",
                    start: "top 85%"
                },
                x: 50,
                opacity: 0,
                duration: 1,
                stagger: 0.1,
                ease: "power3.out"
            });
        });
    </script>
@endsection