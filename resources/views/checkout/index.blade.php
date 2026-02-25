@extends('layouts.mathey')

@section('title', 'Paiement - Mathey-Tissot - Swiss watches - Mauritania')

@section('content')
    <div class="bg-mathey-cream py-12 border-b border-mathey-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="font-serif text-4xl font-bold text-mathey-blue mb-2 text-center">Estimation du Luxe</h1>
            <p class="text-mathey-gray text-center max-w-2xl mx-auto">Finalisez votre acquisition en toute sécurité. Chaque transaction est protégée par notre héritage de confiance.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Checkout Form -->
            <form action="{{ route('checkout.store', ['locale' => app()->getLocale()]) }}" method="POST" class="space-y-8">
                @csrf

                <!-- Shipping -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-mathey-border">
                    <h2 class="text-xl font-serif font-bold text-mathey-blue mb-6 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-mathey-gold text-white flex items-center justify-center text-sm">1</span>
                        Détails de Livraison
                    </h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-mathey-gray mb-2">Adresse de livraison complète</label>
                            <textarea name="shipping_address" rows="3" required
                                class="w-full rounded-xl border-mathey-border shadow-sm focus:ring-mathey-gold focus:border-mathey-gold"
                                placeholder="Numéro, Rue, Code Postal, Ville, Pays...">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-mathey-gray mb-2">Numéro de téléphone (WhatsApp)</label>
                            <div class="flex gap-2">
                                <div class="w-1/3">
                                    <select id="country-select" onchange="updateCountryCode(this.value)"
                                        class="w-full rounded-xl border-mathey-border shadow-sm focus:ring-mathey-gold focus:border-mathey-gold text-sm">
                                        <option value="222" selected>Mauritanie (+222)</option>
                                        <option value="216">Tunisie (+216)</option>
                                        <option value="33">France (+33)</option>
                                        <option value="212">Maroc (+212)</option>
                                        <option value="213">Algérie (+213)</option>
                                        <option value="41">Suisse (+41)</option>
                                        <option value="custom">Autre...</option>
                                    </select>
                                </div>
                                <div class="relative w-24">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm">+</span>
                                    </div>
                                    <input type="text" name="country_code" id="country-code" value="222" required
                                        class="w-full pl-6 rounded-xl border-mathey-border shadow-sm focus:ring-mathey-gold focus:border-mathey-gold text-sm"
                                        placeholder="222">
                                </div>
                                <div class="flex-grow">
                                    <input type="tel" name="phone_number" value="{{ old('phone_number') }}" required
                                        class="w-full rounded-xl border-mathey-border shadow-sm focus:ring-mathey-gold focus:border-mathey-gold text-sm"
                                        placeholder="00 00 00 00">
                                </div>
                            </div>
                            <p class="text-[10px] text-mathey-gray mt-1">Saisissez l'indicatif et votre numéro pour la validation WhatsApp.</p>
                            @if($errors->any())
                                <div class="bg-red-50 p-3 rounded-lg mt-2 font-mono text-[10px] text-red-600">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>• {{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-mathey-gray mb-2">Instructions Particulières (Optionnel)</label>
                            <input type="text" name="notes" value="{{ old('notes') }}"
                                class="w-full rounded-xl border-mathey-border shadow-sm focus:ring-mathey-gold focus:border-mathey-gold"
                                placeholder="Ex: Code porte, demande spécifique...">
                        </div>
                    </div>
                </div>

                <!-- Payment -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-mathey-border">
                    <h2 class="text-xl font-serif font-bold text-mathey-blue mb-6 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-mathey-gold text-white flex items-center justify-center text-sm">2</span>
                        Méthode de Règlement
                    </h2>

                    <div class="space-y-4">
                        <label class="relative flex items-center p-4 border border-mathey-border rounded-xl cursor-pointer hover:bg-mathey-cream/30 transition group">
                            <input type="radio" name="payment_method" value="card" checked onchange="toggleCardDetails(true)"
                                class="h-4 w-4 text-mathey-gold focus:ring-mathey-gold border-mathey-border">
                            <span class="ml-3 block">
                                <span class="text-sm font-bold text-mathey-blue">Carte Bancaire</span>
                                <span class="text-xs text-mathey-gray">Visa, Mastercard, American Express</span>
                            </span>
                            <div class="ml-auto flex gap-2">
                                <img src="/images/icons/visa.svg" class="h-6 opacity-60 group-hover:opacity-100 transition" alt="Visa">
                                <img src="/images/icons/mastercard.svg" class="h-6 opacity-60 group-hover:opacity-100 transition" alt="Mastercard">
                            </div>
                        </label>

                        <!-- Card Details Section -->
                        <div id="card-details-section" class="mt-4 p-6 bg-mathey-cream/20 rounded-xl border border-dashed border-mathey-border space-y-4">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-mathey-gray uppercase tracking-wider mb-2">Titulaire de la carte</label>
                                    <input type="text" name="card_name" value="{{ old('card_name') }}"
                                        class="w-full rounded-xl border-mathey-border shadow-sm focus:ring-mathey-gold focus:border-mathey-gold"
                                        placeholder="NOM PRÉNOM">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-mathey-gray uppercase tracking-wider mb-2">Numéro de Carte</label>
                                    <input type="text" name="card_number" value="{{ old('card_number') }}"
                                        class="w-full rounded-xl border-mathey-border shadow-sm focus:ring-mathey-gold focus:border-mathey-gold"
                                        placeholder="0000 0000 0000 0000">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-mathey-gray uppercase tracking-wider mb-2">Expiration</label>
                                        <input type="text" name="card_expiry" value="{{ old('card_expiry') }}"
                                            class="w-full rounded-xl border-mathey-border shadow-sm focus:ring-mathey-gold focus:border-mathey-gold"
                                            placeholder="MM / YY">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-mathey-gray uppercase tracking-wider mb-2">CVV</label>
                                        <input type="text" name="card_cvv" value="{{ old('card_cvv') }}"
                                            class="w-full rounded-xl border-mathey-border shadow-sm focus:ring-mathey-gold focus:border-mathey-gold"
                                            placeholder="123">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <label class="relative flex items-center p-4 border border-mathey-border rounded-xl cursor-pointer hover:bg-mathey-cream/30 transition">
                            <input type="radio" name="payment_method" value="paypal" onchange="toggleCardDetails(false)"
                                class="h-4 w-4 text-mathey-gold focus:ring-mathey-gold border-mathey-border">
                            <span class="ml-3 block">
                                <span class="text-sm font-bold text-mathey-blue">PayPal</span>
                                <span class="text-xs text-mathey-gray">Règlement via votre compte PayPal</span>
                            </span>
                            <div class="ml-auto">
                                <img src="/images/icons/paypal.svg" class="h-5" alt="PayPal">
                            </div>
                        </label>
                    </div>
                </div>

                <button type="submit"
                    class="w-full py-4 bg-mathey-blue text-white rounded-2xl font-bold text-lg hover:bg-mathey-blue/90 transition shadow-xl transform active:scale-95 group">
                    Confirmer l'Acquisition
                    <svg class="w-5 h-5 inline-block ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </button>
            </form>

            <!-- Order Summary -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-mathey-border sticky top-32">
                    <h2 class="text-xl font-serif font-bold text-mathey-blue mb-6">Votre Sélection</h2>

                    <div class="max-h-96 overflow-y-auto pr-2 space-y-4 mb-8">
                        @foreach($cart->items as $item)
                            <div class="flex gap-4 group">
                                <div class="h-20 w-20 flex-shrink-0 bg-mathey-cream rounded-xl overflow-hidden border border-mathey-border transition-transform group-hover:scale-105">
                                    @if($item->product->media->first())
                                        <img src="{{ $item->product->media->first()->url }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-mathey-gray/30 font-serif">M-T</div>
                                    @endif
                                </div>
                                <div class="flex-grow">
                                    <p class="text-sm font-bold text-mathey-blue">{{ $item->product->name }}</p>
                                    <p class="text-xs text-mathey-gray">Réf: {{ $item->product->reference }}</p>
                                    <p class="text-xs text-mathey-gray">Quantité: {{ $item->quantity }}</p>
                                </div>
                                <p class="text-sm font-bold text-mathey-gold">
                                    {{ number_format($item->price_at_addition * $item->quantity, 2, ',', ' ') }} €</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-3 border-t border-mathey-border pt-6">
                        <div class="flex justify-between text-sm text-mathey-gray">
                            <span>Sous-total</span>
                            <span>{{ number_format($cart->total, 2, ',', ' ') }} €</span>
                        </div>
                        <div class="flex justify-between text-sm text-mathey-gray">
                            <span>Livraison</span>
                            <span class="text-green-600 font-bold">Inclus</span>
                        </div>
                        <div class="flex justify-between text-xl font-serif font-bold text-mathey-blue pt-3 border-t border-mathey-border">
                            <span>Total</span>
                            <span class="text-mathey-gold">{{ number_format($cart->total, 2, ',', ' ') }} €</span>
                        </div>
                    </div>

                    <div class="mt-8 p-4 bg-mathey-cream/50 rounded-xl flex items-start gap-4">
                        <svg class="w-6 h-6 text-mathey-gold mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                        <p class="text-xs text-mathey-gray leading-relaxed">Vos transactions sont hautement sécurisées. Nous utilisons un protocole de chiffrement SSL de grade militaire. Vos données bancaires ne sont jamais stockées sur nos serveurs.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleCardDetails(show) {
            const section = document.getElementById('card-details-section');
            if (show) {
                gsap.to(section, { height: 'auto', opacity: 1, duration: 0.5, ease: "power2.out", display: 'block' });
            } else {
                gsap.to(section, { height: 0, opacity: 0, duration: 0.5, ease: "power2.in", onComplete: () => { section.style.display = 'none'; } });
            }
        }

        function updateCountryCode(value) {
            const codeInput = document.getElementById('country-code');
            if (value !== 'custom') {
                codeInput.value = value;
            }
        }

        document.querySelector('form').addEventListener('submit', function(e) {
            // Logic handled by backend now
        });

        document.addEventListener('DOMContentLoaded', () => {
            // Check initial state
            const isCard = document.querySelector('input[name="payment_method"]:checked').value === 'card';
            const section = document.getElementById('card-details-section');
            if (!isCard) {
                section.style.display = 'none';
                section.style.height = 0;
                section.style.opacity = 0;
            }
        });
    </script>
@endsection