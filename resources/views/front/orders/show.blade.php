@extends('layouts.app')

@section('title', 'Détails de la Commande #' . $order->id . ' - Swiss Heritage')

@section('content')
    <div class="bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4 mb-8">
                <a href="{{ route('orders.index', ['locale' => app()->getLocale()]) }}"
                    class="p-2 bg-white rounded-full border shadow-sm text-gray-400 hover:text-slate-900 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-serif text-gray-900">Commande #{{ $order->id }}</h1>
                    <p class="text-sm text-gray-500">Passée le {{ $order->created_at->format('d F Y à H:i') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Main Details -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Status & Tracking -->
                    <div class="bg-white rounded-2xl border shadow-sm p-6 overflow-hidden">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="font-semibold text-gray-900">État de la commande</h2>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 capitalize">
                                {{ $order->status }}
                            </span>
                        </div>

                        <div class="relative pb-4">
                            <div class="flex items-center gap-4">
                                <div class="p-3 bg-slate-900 text-white rounded-xl">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4a2 2 0 012-2m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">En cours de livraison via
                                        {{ $tracking['carrier'] }}
                                    </p>
                                    <p class="text-xs text-gray-500">Livraison estimée :
                                        {{ \Carbon\Carbon::parse($tracking['eta'])->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items -->
                    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
                        <div class="p-6 border-b">
                            <h2 class="font-semibold text-gray-900">Articles commandés</h2>
                        </div>
                        <div class="divide-y">
                            @foreach($order->items as $item)
                                <div class="p-6 flex gap-6">
                                    <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-xl border bg-gray-50">
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
                                    <div class="flex-grow flex flex-col justify-between">
                                        <div>
                                            <h4 class="font-serif text-gray-900">
                                                {{ $productData['name'] ?? $item->product?->name ?? 'Produit Supprimé' }}
                                            </h4>
                                            <p class="text-xs text-gray-500 uppercase tracking-widest mt-1">Réf:
                                                {{ $productData['reference'] ?? $item->product?->reference ?? 'N/A' }}
                                            </p>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <p class="text-gray-500">Quantité: {{ $item->quantity }}</p>
                                            <p class="font-bold text-gray-900">
                                                {{ number_format($item->price * $item->quantity, 2) }} €
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Sidebar Info -->
                <div class="space-y-6">
                    <!-- Summary -->
                    <div class="bg-slate-900 text-white rounded-2xl p-6 shadow-xl">
                        <h2 class="font-semibold mb-6 border-b border-white/10 pb-4">Récapitulatif</h2>
                        <div class="space-y-4 text-sm">
                            <div class="flex justify-between text-slate-400">
                                <span>Sous-total</span>
                                <span>{{ number_format($order->total, 2) }} €</span>
                            </div>
                            <div class="flex justify-between text-slate-400">
                                <span>Livraison</span>
                                <span>Gratuit</span>
                            </div>
                            <div class="pt-4 border-t border-white/10 flex justify-between items-end">
                                <span class="font-medium">Total TTC</span>
                                <span class="text-2xl font-bold italic">€{{ number_format($order->total, 2) }}</span>
                            </div>
                        </div>

                        <a href="{{ $invoiceUrl }}"
                            class="mt-8 w-full inline-flex items-center justify-center px-4 py-3 border border-white/20 rounded-xl text-sm font-medium hover:bg-white/10 transition gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                            Télécharger la facture
                        </a>
                    </div>

                    <!-- Shipping Address -->
                    <div class="bg-white rounded-2xl border shadow-sm p-6">
                        <h2 class="font-semibold text-gray-900 mb-4">Adresse de livraison</h2>
                        <div class="text-sm text-gray-600 space-y-1">
                            <p class="font-bold text-gray-900">{{ Auth::user()->name }}</p>
                            @if(is_array($order->shipping_address))
                                <p>{{ $order->shipping_address['street'] ?? ($order->shipping_address['address'] ?? '') }}</p>
                                <p>{{ $order->shipping_address['city'] ?? '' }}{{ isset($order->shipping_address['zip']) ? ', ' . $order->shipping_address['zip'] : '' }}
                                </p>
                                <p>{{ $order->shipping_address['country'] ?? '' }}</p>
                            @else
                                <p>{{ $order->shipping_address ?? 'Contactez le support pour l\'adresse' }}</p>
                            @endif
                            <p class="pt-2 border-t mt-2 text-xs text-gray-400 font-medium italic">Tél:
                                {{ Auth::user()->phone }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection