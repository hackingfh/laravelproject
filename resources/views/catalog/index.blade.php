@extends('layouts.mathey')

@section('content')
    <div class="bg-mathey-cream py-12 border-b border-mathey-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="font-serif text-4xl font-bold text-mathey-blue mb-2 text-center">Collection Complète</h1>
            <p class="text-mathey-gray text-center max-w-2xl mx-auto">Explorez notre héritage horloger suisse à travers nos
                modèles exclusifs.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
            <!-- Sidebar Filters -->
            <aside class="lg:col-span-1 space-y-8">
                <form method="get" action="{{ route('products.index', ['locale' => app()->getLocale()]) }}"
                    class="space-y-6 bg-white p-6 rounded-2xl border border-mathey-border shadow-sm">
                    <div>
                        <h3 class="font-serif font-bold text-mathey-blue mb-4">Filtrer par</h3>
                        <label class="block text-sm font-medium text-mathey-gray mb-2" for="collection">Collection</label>
                        <select id="collection" name="collection"
                            class="w-full border-mathey-border rounded-xl px-4 py-3 focus:ring-mathey-gold focus:border-mathey-gold">
                            <option value="">Toutes les collections</option>
                            @foreach(\App\Models\Collection::where('is_active', true)->get() as $c)
                                <option value="{{ $c->slug }}" {{ request('collection') == $c->slug ? 'selected' : '' }}>
                                    {{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-mathey-gray">Gamme de prix</label>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="number" step="0.01" name="price_min" value="{{ request('price_min') }}"
                                placeholder="Min"
                                class="w-full border-mathey-border rounded-xl px-4 py-2 text-sm focus:ring-mathey-gold">
                            <input type="number" step="0.01" name="price_max" value="{{ request('price_max') }}"
                                placeholder="Max"
                                class="w-full border-mathey-border rounded-xl px-4 py-2 text-sm focus:ring-mathey-gold">
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-mathey-blue text-white py-3 rounded-xl hover:bg-mathey-blue/90 transition font-medium">
                        Appliquer les filtres
                    </button>
                    @if(request()->anyFilled(['collection', 'price_min', 'price_max']))
                        <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}"
                            class="block text-center text-sm text-mathey-gray hover:text-mathey-gold underline mt-2">Réinitialiser</a>
                    @endif
                </form>
            </aside>

            <!-- Product Grid -->
            <section class="lg:col-span-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($products as $p)
                        <div class="card-luxury flex flex-col h-full group">
                            <div class="relative aspect-square overflow-hidden bg-mathey-cream">
                                <img src="{{ $p->images[0] ?? '/images/placeholder.jpg' }}" alt="{{ $p->name }}"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                    loading="lazy">
                                <div class="absolute top-4 left-4">
                                    <span class="badge">Swiss watches - Mauritania</span>
                                </div>
                            </div>
                            <div class="p-6 flex flex-col flex-grow text-center">
                                <p class="text-xs text-mathey-gray tracking-widest uppercase mb-1">
                                    {{ $p->reference ?? $p->sku }}</p>
                                <h3 class="font-serif text-lg font-bold text-mathey-blue mb-3">{{ $p->name }}</h3>
                                <div class="mt-auto">
                                    <div class="text-mathey-gold font-bold text-xl mb-6">
                                        {{ number_format($p->price, 2, ',', ' ') }} €</div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'slug' => $p->slug]) }}"
                                            class="flex-1 bg-mathey-blue text-white py-2 rounded-lg hover:bg-mathey-blue/90 transition text-sm font-medium">Voir
                                            détails</a>

                                        <form
                                            action="{{ route('cart.add', ['locale' => app()->getLocale(), 'product' => $p->id]) }}"
                                            method="POST" class="flex-grow">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit"
                                                class="w-full py-2 bg-mathey-gold text-white rounded-lg hover:bg-mathey-gold/90 transition text-sm font-medium">
                                                Acheter
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-12">
                    {{ $products->withQueryString()->links() }}
                </div>
            </section>
        </div>
    </div>
@endsection