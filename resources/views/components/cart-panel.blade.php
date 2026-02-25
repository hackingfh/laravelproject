<div class="bg-white rounded-lg shadow-sm border border-mathey-border overflow-hidden">
    <div class="p-6">
        <div class="flex items-center justify-between mb-8 pb-4 border-b border-mathey-border">
            <h2 class="text-2xl font-serif font-semibold text-mathey-blue">Votre Sélection</h2>
            <span class="text-mathey-gray text-sm">{{ $cart->items->count() }} article(s)</span>
        </div>

        @if($cart->items->isEmpty())
            <div class="text-center py-12">
                <div class="text-mathey-gold mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <p class="text-mathey-gray mb-8">Votre panier est actuellement vide.</p>
                <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}" class="btn-primary px-8 py-3">
                    Découvrir nos montres
                </a>
            </div>
        @else
            <div class="space-y-6 mb-8">
                @foreach($cart->items as $item)
                    <div class="flex gap-6 py-4 border-b border-mathey-border last:border-0">
                        <div class="w-24 h-24 bg-mathey-cream rounded flex-shrink-0 overflow-hidden">
                            <img src="{{ $item->product->images[0] ?? '/images/placeholder.jpg' }}"
                                alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between mb-1">
                                <h3 class="font-serif font-bold text-mathey-blue">{{ $item->product->name }}</h3>
                                <span
                                    class="font-bold text-mathey-gold">{{ number_format($item->price_at_addition * $item->quantity, 2, ',', ' ') }}
                                    €</span>
                            </div>
                            <p class="text-xs text-mathey-gray mb-3 italic">Réf: {{ $item->product->reference }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-mathey-gray">Quantité: {{ $item->quantity }}</span>
                                <button onclick="removeFromCart({{ $item->id }})"
                                    class="text-xs text-red-500 hover:underline">Supprimer</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-mathey-cream/50 p-6 rounded-lg space-y-4">
                <div class="flex justify-between text-mathey-gray">
                    <span>Sous-total</span>
                    <span>{{ number_format($totals['subtotal'], 2, ',', ' ') }} €</span>
                </div>
                <div class="flex justify-between text-mathey-gray">
                    <span>Livraison</span>
                    <span>{{ $totals['shipping'] > 0 ? number_format($totals['shipping'], 2, ',', ' ') . ' €' : 'Gratuite' }}</span>
                </div>
                @if(isset($totals['tax']))
                    <div class="flex justify-between text-mathey-gray">
                        <span>TVA (estimée)</span>
                        <span>{{ number_format($totals['tax'], 2, ',', ' ') }} €</span>
                    </div>
                @endif
                <div class="pt-4 border-t border-mathey-border flex justify-between items-center">
                    <span class="text-lg font-serif font-bold text-mathey-blue">Total</span>
                    <span class="text-2xl font-bold text-mathey-gold">{{ number_format($totals['total'], 2, ',', ' ') }}
                        €</span>
                </div>
            </div>

            <div class="mt-8">
                <a href="{{ route('checkout.index', ['locale' => app()->getLocale()]) }}"
                    class="btn-primary w-full py-4 text-center block">
                    Passer à la commande
                </a>
            </div>
        @endif
    </div>
</div>

<script>
    function removeFromCart(itemId) {
        if (!confirm('Supprimer cet article ?')) return;

        fetch('{{ route('cart.remove', ['locale' => app()->getLocale()]) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ item_id: itemId })
        }).then(res => res.json())
            .then(data => window.location.reload());
    }
</script>