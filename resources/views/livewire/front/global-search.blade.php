<div class="max-w-3xl w-full relative" x-data="{ open: false }" @click.away="open = false">
    <form action="{{ route('products.index', ['locale' => app()->getLocale()]) }}" method="GET">
        <div class="relative">
            <input type="text" name="q" wire:model.live.debounce.300ms="search" @focus="open = true"
                placeholder="Rechercher une collection, un modèle..."
                class="w-full bg-transparent border-b-2 border-mathey-gold py-4 text-2xl md:text-4xl text-white placeholder-white/30 focus:outline-none focus:border-white transition-colors duration-300">

            <div wire:loading class="absolute right-0 top-1/2 -translate-y-1/2">
                <svg class="animate-spin h-6 w-6 text-mathey-gold" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
            </div>
        </div>
    </form>

    @if($showResults && $search != '')
        <div x-show="open"
            class="absolute left-0 right-0 mt-4 bg-white rounded-xl shadow-2xl overflow-hidden z-[70] max-h-[70vh] overflow-y-auto">
            @if(count($results['products']) > 0 || count($results['collections']) > 0)
                <div class="p-2">
                    @if(count($results['collections']) > 0)
                        <div class="px-4 py-2 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">
                            Collections</div>
                        <div class="mb-2">
                            @foreach($results['collections'] as $coll)
                                <a href="{{ route('collections.show', ['locale' => app()->getLocale(), 'slug' => $coll->slug]) }}"
                                    class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg transition group">
                                    <div class="w-10 h-10 bg-gray-100 rounded flex items-center justify-center overflow-hidden border">
                                        @if($coll->image)
                                            <img src="{{ $coll->image }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        @endif
                                    </div>
                                    <span
                                        class="text-gray-800 font-medium group-hover:text-mathey-gold transition">{{ $coll->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if(count($results['products']) > 0)
                        <div class="px-4 py-2 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">
                            Produits</div>
                        <div class="space-y-1">
                            @foreach($results['products'] as $prod)
                                <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'slug' => $prod->slug]) }}"
                                    class="flex items-center gap-4 p-3 hover:bg-gray-50 rounded-lg transition group">
                                    <div class="w-16 h-16 bg-white rounded border overflow-hidden flex-shrink-0">
                                        @php $media = $prod->media->first(); @endphp
                                        @if($media)
                                            <img src="{{ $media->url }}" class="w-full h-full object-cover">
                                        @else
                                            <img src="/storage/products/placeholder.jpg" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div class="flex-grow">
                                        <div class="text-gray-900 font-semibold group-hover:text-mathey-gold transition">
                                            {{ $prod->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $prod->reference }}</div>
                                        <div class="text-mathey-gold font-bold mt-1 text-sm">
                                            {{ number_format($prod->price, 0, ',', ' ') }} MRU</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <div class="p-3 border-t bg-gray-50 mt-2">
                        <a href="{{ route('products.index', ['locale' => app()->getLocale(), 'q' => $search]) }}"
                            class="block text-center text-sm font-semibold text-mathey-blue hover:text-mathey-gold transition">
                            Voir tous les résultats pour "{{ $search }}"
                        </a>
                    </div>
                </div>
            @else
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <p class="text-gray-500">Aucun résultat trouvé pour "{{ $search }}"</p>
                </div>
            @endif
        </div>
    @endif
</div>