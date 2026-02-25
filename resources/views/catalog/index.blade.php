@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-2xl font-semibold mb-6">Catalogue</h1>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <aside class="md:col-span-1 space-y-4">
                <form method="get" action="">
                    <div>
                        <label class="block text-sm font-medium mb-1" for="collection">Collection</label>
                        <select id="collection" name="collection" class="w-full border rounded px-3 py-2">
                            <option value="">Toutes</option>
                            @foreach(\App\Models\Collection::where('is_active', true)->get() as $c)
                                <option value="{{ $c->slug }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-sm font-medium mb-1" for="price_min">Prix min</label>
                            <input id="price_min" type="number" step="0.01" name="price_min"
                                class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" for="price_max">Prix max</label>
                            <input id="price_max" type="number" step="0.01" name="price_max"
                                class="w-full border rounded px-3 py-2">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" for="material">Matériau du bracelet</label>
                        <select id="material" name="material" class="w-full border rounded px-3 py-2">
                            <option value="">Tous</option>
                            <option value="acier">Acier</option>
                            <option value="cuir">Cuir</option>
                            <option value="titane">Titane</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" for="movement">Mouvement</label>
                        <select id="movement" name="movement" class="w-full border rounded px-3 py-2">
                            <option value="">Tous</option>
                            <option value="automatique">Automatique</option>
                            <option value="manuel">Manuel</option>
                            <option value="quartz">Quartz</option>
                        </select>
                    </div>
                    <button class="mt-3 inline-flex items-center px-3 py-2 rounded bg-swiss-red text-white">Filtrer</button>
                </form>
            </aside>
            <section class="md:col-span-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($products as $p)
                        <div class="card">
                            <img src="{{ $p->images[0] ?? '/images/placeholder.jpg' }}" alt="{{ $p->name }}"
                                class="w-full h-48 object-cover" loading="lazy">
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm badge">Swiss watches - Mauritania</span>
                                    <span class="text-xs">2 ans de garantie</span>
                                </div>
                                <div class="text-lg font-medium">{{ $p->name }}</div>
                                <div class="mt-1">{{ number_format($p->price, 2, '.', ' ') }} CHF</div>
                                <div class="mt-3 flex items-center gap-2">
                                    <a href="{{ url('/fr/products/' . $p->slug) }}"
                                        class="inline-flex items-center px-3 py-2 rounded bg-swiss-red text-white">Voir</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">{{ $products->withQueryString()->links() }}</div>
            </section>
        </div>
    </div>
@endsection