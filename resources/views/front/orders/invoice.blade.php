<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Facture #{{ $order->order_number }} - Mathey-Tissot</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .print-shadow-none {
                shadow: none !important;
                border: 1px solid #eee !important;
            }
        }
    </style>
</head>

<body class="bg-gray-100 font-sans antialiased p-8">
    <div class="max-w-4xl mx-auto bg-white p-12 shadow-xl border border-gray-100 rounded-lg print-shadow-none">
        <!-- Breadcrumb / Back button (no print) -->
        <div class="no-print mb-8">
            <button onclick="window.print()"
                class="bg-mathey-blue text-white px-6 py-2 rounded-lg hover:bg-opacity-90 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                Imprimer la facture (PDF)
            </button>
        </div>

        <div class="flex justify-between items-start mb-12">
            <div>
                <img src="/images/logo-mathey.png" alt="Mathey-Tissot" class="h-12 w-auto mb-4" width="160" height="40">
                <h1 class="text-2xl font-serif font-bold text-mathey-blue">Mathey-Tissot Mauritanie</h1>
                <p class="text-sm text-mathey-gray">Swiss watches since 1886</p>
            </div>
            <div class="text-right">
                <h2 class="text-4xl font-serif font-bold text-mathey-gold mb-2">FACTURE</h2>
                <p class="text-gray-600">N° {{ $order->order_number }}</p>
                <p class="text-gray-600">Date: {{ $order->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-12 mb-12">
            <div>
                <h3 class="text-xs font-bold text-mathey-gray uppercase tracking-widest mb-4">Émetteur</h3>
                <p class="font-bold text-mathey-blue leading-relaxed">Mathey-Tissot Official Store</p>
                <p class="text-sm text-mathey-gray leading-relaxed">Nouakchott, Mauritanie<br>Tél: +222 00 00 00
                    00<br>Email: contact@mathey-tissot.mr</p>
            </div>
            <div>
                <h3 class="text-xs font-bold text-mathey-gray uppercase tracking-widest mb-4">Client</h3>
                <p class="font-bold text-mathey-blue leading-relaxed">{{ $order->user->name }}</p>
                <div class="text-sm text-mathey-gray leading-relaxed">
                    @if(is_array($order->shipping_address))
                        <p>{{ $order->shipping_address['street'] ?? ($order->shipping_address['address'] ?? '') }}</p>
                        <p>{{ $order->shipping_address['city'] ?? '' }}{{ isset($order->shipping_address['zip']) ? ', ' . $order->shipping_address['zip'] : '' }}</p>
                        <p>{{ $order->shipping_address['country'] ?? '' }}</p>
                    @else
                        <p>{{ $order->shipping_address }}</p>
                    @endif
                    <p>Email: {{ $order->user->email }}</p>
                    <p>Tél: {{ $order->user->phone }}</p>
                </div>
            </div>
        </div>

        <table class="w-full mb-12 text-left">
            <thead>
                <tr class="border-b-2 border-mathey-blue">
                    <th class="py-4 font-bold text-mathey-blue">Description</th>
                    <th class="py-4 font-bold text-mathey-blue text-center">Qté</th>
                    <th class="py-4 font-bold text-mathey-blue text-right">Prix Unitaire</th>
                    <th class="py-4 font-bold text-mathey-blue text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($order->items as $item)
                    <tr>
                        <td class="py-4">
                            <p class="font-bold text-mathey-blue">
                                {{ $item->product_snapshot['name'] ?? $item->product?->name ?? 'Produit Supprimé' }}</p>
                            <p class="text-xs text-mathey-gray italic">Réf:
                                {{ $item->product_snapshot['reference'] ?? $item->product?->reference ?? 'N/A' }}</p>
                        </td>
                        <td class="py-4 text-center">{{ $item->quantity }}</td>
                        <td class="py-4 text-right">{{ number_format($item->price_at_purchase, 2, ',', ' ') }} €</td>
                        <td class="py-4 text-right font-bold">
                            {{ number_format($item->price_at_purchase * $item->quantity, 2, ',', ' ') }} €
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex justify-end">
            <div class="w-64 space-y-3">
                <div class="flex justify-between text-gray-600">
                    <span>Sous-total</span>
                    <span>{{ number_format($order->total, 2, ',', ' ') }} €</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Livraison</span>
                    <span>Gratuit</span>
                </div>
                <div
                    class="flex justify-between text-xl font-serif font-bold text-mathey-blue pt-3 border-t-2 border-mathey-gold">
                    <span>Total</span>
                    <span>{{ number_format($order->total, 2, ',', ' ') }} €</span>
                </div>
            </div>
        </div>

        <div class="mt-20 pt-12 border-t border-gray-100 text-center text-xs text-mathey-gray">
            <p class="mb-2 italic">Merci pour votre confiance en Mathey-Tissot.</p>
            <p>Cette facture est générée numériquement. En cas de réclamation, veuillez nous contacter sous 30 jours.
            </p>
        </div>
    </div>

    <script>
        // Trigger print automatically if the user wants it "automatically"
        window.onload = function () {
            setTimeout(function () {
                window.print();
            }, 500);
        }
    </script>
</body>

</html>