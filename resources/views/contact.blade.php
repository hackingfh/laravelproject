@extends('layouts.mathey')

@section('content')
    <div class="pt-32 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 opacity-0 translate-y-10" id="contact-header">
                <span class="text-mathey-gold font-bold tracking-[0.2em] uppercase text-sm mb-4 block">Nous Contacter</span>
                <h1 class="text-4xl md:text-6xl font-serif text-mathey-blue mb-6 italic">À Votre Service</h1>
                <div class="w-24 h-px bg-mathey-gold mx-auto"></div>
            </div>

            <div class="grid lg:grid-cols-2 gap-16">
                <!-- Contact Form -->
                <div class="bg-mathey-cream p-8 md:p-12 rounded-lg shadow-sm opacity-0 -translate-x-10" id="contact-form">
                    <form action="#" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-mathey-blue mb-2">Prénom</label>
                                <input type="text" name="first_name" required
                                    class="w-full bg-white border border-mathey-border px-4 py-3 rounded focus:outline-none focus:border-mathey-gold transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-mathey-blue mb-2">Nom</label>
                                <input type="text" name="last_name" required
                                    class="w-full bg-white border border-mathey-border px-4 py-3 rounded focus:outline-none focus:border-mathey-gold transition-colors">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-mathey-blue mb-2">Email</label>
                            <input type="email" name="email" required
                                class="w-full bg-white border border-mathey-border px-4 py-3 rounded focus:outline-none focus:border-mathey-gold transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-mathey-blue mb-2">Objet</label>
                            <select name="subject"
                                class="w-full bg-white border border-mathey-border px-4 py-3 rounded focus:outline-none focus:border-mathey-gold transition-colors">
                                <option>Service Après-Vente</option>
                                <option>Informations Produits</option>
                                <option>Partenariats</option>
                                <option>Autre</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-mathey-blue mb-2">Message</label>
                            <textarea name="message" rows="5" required
                                class="w-full bg-white border border-mathey-border px-4 py-3 rounded focus:outline-none focus:border-mathey-gold transition-colors"></textarea>
                        </div>
                        <button type="submit" class="btn-primary w-full py-4 text-lg">Envoyer le Message</button>
                    </form>
                </div>

                <!-- Contact Info -->
                <div class="space-y-12 opacity-0 translate-x-10" id="contact-info">
                    <div>
                        <h3 class="text-2xl font-serif text-mathey-blue mb-6">Nos Coordonnées</h3>
                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <div class="p-3 bg-mathey-gold/10 rounded-full text-mathey-gold">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-mathey-blue">Showroom Mauritanie</h4>
                                    <p class="text-mathey-gray">Avenue Charles de Gaulle, Nouakchott</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="p-3 bg-mathey-gold/10 rounded-full text-mathey-gold">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-mathey-blue">Service Client</h4>
                                    <p class="text-mathey-gray">+222 12 34 56 78</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 border border-mathey-gold/20 rounded-lg">
                        <h3 class="text-xl font-serif text-mathey-blue mb-4 italic">Heures d'Ouverture</h3>
                        <ul class="space-y-2 text-mathey-gray">
                            <li class="flex justify-between"><span>Lundi - Vendredi :</span><span>09:00 - 18:00</span></li>
                            <li class="flex justify-between"><span>Samedi :</span><span>10:00 - 16:00</span></li>
                            <li class="flex justify-between"><span>Dimanche :</span><span>Fermé</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof gsap !== 'undefined') {
                gsap.to("#contact-header", { opacity: 1, y: 0, duration: 1, ease: "power3.out" });
                gsap.to("#contact-form", { opacity: 1, x: 0, duration: 1, delay: 0.3, ease: "power3.out" });
                gsap.to("#contact-info", { opacity: 1, x: 0, duration: 1, delay: 0.5, ease: "power3.out" });
            }
        });
    </script>
@endsection