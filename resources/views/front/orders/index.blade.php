@extends('layouts.app')

@section('title', 'Mes Commandes - Swiss Heritage')

@section('content')
    <div class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-10">
                <h1 class="text-3xl font-serif text-gray-900 mb-2">Mes Commandes</h1>
                <p class="text-gray-600">Suivez et gérez vos commandes passées chez Swiss Heritage.</p>
            </div>

            @if($list->isEmpty())
                <div class="bg-white rounded-2xl p-12 text-center border shadow-sm">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">Vous n'avez pas encore de commande</h2>
                    <p class="text-gray-500 mb-8">Découvrez nos collections pour trouver votre pièce d'exception.</p>
                    <a href="{{ url('/fr/collections') }}"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-slate-900 hover:bg-slate-800 transition">
                        Explorer les collections
                    </a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($list as $order)
                        <div class="bg-white rounded-2xl border shadow-sm overflow-hidden hover:shadow-md transition">
                            <div class="p-6 sm:px-8 flex flex-wrap items-center justify-between gap-4 border-b bg-gray-50/50">
                                <div class="flex gap-8">
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Commande N°</p>
                                        <p class="text-sm font-semibold text-gray-900">#{{ $order->id }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Date</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $order->created_at->format('d M Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Total</p>
                                        <p class="text-sm font-bold text-gray-900">{{ number_format($order->total, 2) }} €</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                            @if($order->status === 'completed' || $order->status === 'delivered') bg-green-100 text-green-700
                                                            @elseif($order->status === 'pending') bg-yellow-100 text-yellow-700
                                                            @else bg-blue-100 text-blue-700 @endif capitalize">
                                        {{ $order->status }}
                                    </span>
                                    <a href="{{ route('orders.show', ['locale' => app()->getLocale(), 'id' => $order->id]) }}"
                                        class="text-sm font-semibold text-slate-900 hover:text-swiss-red transition">
                                        Détails <span aria-hidden="true">&rarr;</span>
                                    </a>
                                </div>
                            </div>
                            <div class="p-6 sm:px-8">
                                <div class="flex items-center gap-6">
                                    <div class="flex -space-x-4 overflow-hidden">
                                        @foreach($order->items->take(3) as $item)
                                            <div
                                                class="inline-block h-16 w-16 rounded-lg ring-2 ring-white overflow-hidden bg-gray-100 border">
                                                @php
                                                    $productData = $item->product_snapshot ?? ($item->product ? [
                                                        'name' => $item->product->name,
                                                        'reference' => $item->product->reference,
                                                        'image' => $item->product->media->first()?->url
                                                    ] : null);
                                                    $imageUrl = $productData['image'] ?? $item->product?->media->first()?->url ?? '/images/placeholder.jpg';
                                                @endphp
                                                <img src="{{ $imageUrl }}" alt="{{ $productData['name'] ?? 'Produit' }}"
                                                    class="h-full w-full object-cover">
                                            </div>
                                        @endforeach
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            @if($firstItem = $order->items->first())
                                                @php
                                                    $firstProduct = $firstItem->product_snapshot ?? ($firstItem->product ? ['name' => $firstItem->product->name] : ['name' => 'Produit inconnu']);
                                                @endphp
                                                {{ $firstProduct['name'] }}
                                                @if($order->items->count() > 1)
                                                    <span class="text-gray-500 font-normal"> et {{ $order->items->count() - 1 }} autres
                                                        articles</span>
                                                @endif
                                            @else
                                                Aucun article
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">Expédié via DHL Express</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $list->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection