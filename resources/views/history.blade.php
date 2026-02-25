@extends('layouts.mathey')

@section('content')
    <div class="pt-32 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 opacity-0 translate-y-10" id="history-header">
                <span class="text-mathey-gold font-bold tracking-[0.2em] uppercase text-sm mb-4 block">Notre Héritage</span>
                <h1 class="text-4xl md:text-6xl font-serif text-mathey-blue mb-6 italic">L'Excellence depuis 1886</h1>
                <div class="w-24 h-px bg-mathey-gold mx-auto"></div>
            </div>

            <div class="grid lg:grid-cols-2 gap-16 items-center mb-24">
                <div class="opacity-0 -translate-x-10" id="history-content">
                    <h2 class="text-2xl font-serif text-mathey-blue mb-6">Une Tradition Horlogère Suisse</h2>
                    <p class="text-mathey-gray leading-relaxed mb-6">
                        Fondée en 1886 par Edmond Mathey-Tissot dans le village de Les Ponts-de-Martel, la maison
                        Mathey-Tissot a su traverser les décennies en préservant l'essence même de l'horlogerie de
                        précision.
                    </p>
                    <p class="text-mathey-gray leading-relaxed mb-6">
                        Spécialisée à l'origine dans les chronographes à répétition, la marque a rapidement acquis une
                        renommée internationale pour la qualité exceptionnelle de ses mouvements et l'élégance de ses
                        garde-temps.
                    </p>
                    <div class="flex items-center gap-4 text-mathey-gold font-serif italic text-lg">
                        <span>138 ans d'expertise</span>
                        <div class="flex-1 h-px bg-mathey-border"></div>
                    </div>
                </div>
                <div class="relative group opacity-0 translate-x-10 shadow-2xl rounded-lg overflow-hidden"
                    id="history-image">
                    <img src="/images/history-heritage.jpg" alt="Mathey-Tissot Heritage"
                        class="w-full h-auto transition-transform duration-700 group-hover:scale-110">
                    <div
                        class="absolute inset-0 bg-mathey-blue/10 group-hover:bg-transparent transition-colors duration-500">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof gsap !== 'undefined') {
                gsap.to("#history-header", { opacity: 1, y: 0, duration: 1, ease: "power3.out" });
                gsap.to("#history-content", { opacity: 1, x: 0, duration: 1, delay: 0.3, ease: "power3.out" });
                gsap.to("#history-image", { opacity: 1, x: 0, duration: 1, delay: 0.5, ease: "power3.out" });
            }
        });
    </script>
@endsection