@extends('layouts.mathey')

@section('content')

    <!-- Breadcrumb -->
    <nav class="bg-mathey-cream border-b border-mathey-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <ol class="flex items-center space-x-2 text-sm text-mathey-gray">
                <li><a href="{{ url('/') }}" class="hover:text-mathey-gold">Accueil</a></li>
                <li><svg class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg></li>
                <li><a href="{{ url('/collections') }}" class="hover:text-mathey-gold">Collections</a></li>
                <li><a href="{{ url('/collections/' . $product->collection->slug) }}"
                        class="hover:text-mathey-gold">{{ $product->collection->name }}</a></li>
                <li class="text-mathey-text font-medium">{{ $product->name }}</li>
            </ol>
        </div>
    </nav>

    <!-- Product Detail -->
    <section class="py-12 bg-mathey-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Product Images -->
                <div class="space-y-4">
                    <div class="relative overflow-hidden rounded-lg bg-mathey-cream">
                        <img id="mainImage" src="{{ $product->images[0] ?? '/images/placeholder.jpg' }}"
                            alt="{{ $product->name }}" class="w-full h-[600px] object-cover cursor-zoom-in"
                            onclick="openZoom()">
                        <div class="absolute top-4 left-4 space-y-2">
                            <span
                                class="inline-block px-3 py-1 bg-mathey-gold text-white text-xs font-bold rounded-full">Swiss
                                watches - Mauritania</span>
                            <span class="inline-block px-3 py-1 bg-mathey-blue text-white text-xs font-bold rounded-full">2
                                ANS DE GARANTIE</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach(($product->images ?? []) as $index => $image)
                            <button onclick="changeMainImage('{{ $image }}', {{ $index }})"
                                class="relative overflow-hidden rounded-lg border-2 {{ $loop->first ? 'border-mathey-gold' : 'border-mathey-border' }}">
                                <img src="{{ $image }}" alt="{{ $product->name }}" class="w-full h-24 object-cover">
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Product Information -->
                <div class="space-y-6">
                    <div>
                        <h1 class="font-serif text-3xl lg:text-4xl font-bold text-mathey-blue mb-2">{{ $product->name }}
                        </h1>
                        <p class="text-mathey-gray">Référence: {{ $product->reference ?? $product->sku }}</p>
                    </div>

                    <div class="flex items-baseline gap-3">
                        <span class="text-3xl font-bold text-mathey-gold">{{ number_format($product->price, 2, ',', ' ') }}
                            €</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= 4 ? 'text-yellow-400 fill-current' : 'text-gray-300' }}"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                </svg>
                            @endfor
                        </div>
                        <span class="text-mathey-gray">4.8 (127 avis)</span>
                    </div>

                    <p class="text-mathey-gray leading-relaxed">{{ Str::limit($product->description, 200) }}</p>

                    <form method="post" action="{{ url('/cart/add/' . $product->id) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-mathey-blue mb-2">Bracelet</label>
                            <select name="selected_options[bracelet]"
                                class="w-full px-4 py-3 border border-mathey-border rounded-lg focus:border-mathey-gold">
                                <option value="acier">Acier</option>
                                <option value="cuir">Cuir</option>
                                <option value="titane">Titane</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-mathey-blue mb-2">Couleur du cadran</label>
                            <select name="selected_options[cadran]"
                                class="w-full px-4 py-3 border border-mathey-border rounded-lg focus:border-mathey-gold">
                                <option value="noir">Noir</option>
                                <option value="bleu">Bleu</option>
                                <option value="argent">Argent</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="text-sm font-medium text-mathey-blue">Quantité:</label>
                            <div class="flex items-center border border-mathey-border rounded-lg">
                                <button type="button" onclick="decreaseQuantity()" class="p-2 hover:bg-mathey-gray/10">
                                    <svg class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4">
                                        </path>
                                    </svg>
                                </button>
                                <input type="number" name="quantity" id="quantity" value="1" min="1"
                                    class="w-16 text-center border-0">
                                <button type="button" onclick="increaseQuantity()" class="p-2 hover:bg-mathey-gray/10">
                                    <svg class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <button type="submit"
                            class="w-full bg-mathey-gold text-white py-4 rounded-lg hover:bg-mathey-gold/90 font-medium">
                            Ajouter au panier
                        </button>
                    </form>

                    <div class="grid grid-cols-2 gap-4 pt-6 border-t border-mathey-border">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-mathey-gold">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm text-mathey-gray">Garantie 2 ans</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-mathey-gold">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span class="text-sm text-mathey-gray">Livraison express</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Image Zoom Modal -->
    <div id="zoomModal" class="fixed inset-0 bg-black/90 z-50 hidden flex items-center justify-center p-4"
        onclick="closeZoom()">
        <button class="absolute top-4 right-4 text-white">
            <svg class="w-8 h-8">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <img id="zoomImage" src="" alt="Zoom" class="max-w-full max-h-full object-contain">
    </div>

    <script>
        function changeMainImage(imageSrc, index) {
            document.getElementById('mainImage').src = imageSrc;
            document.querySelectorAll('.grid button').forEach((btn, i) => {
                if (i === index) {
                    btn.classList.add('border-mathey-gold');
                    btn.classList.remove('border-mathey-border');
                } else {
                    btn.classList.remove('border-mathey-gold');
                    btn.classList.add('border-mathey-border');
                }
            });
        }

        function openZoom() {
            document.getElementById('zoomModal').classList.remove('hidden');
            document.getElementById('zoomImage').src = document.getElementById('mainImage').src;
        }

        function closeZoom() {
            document.getElementById('zoomModal').classList.add('hidden');
        }

        function increaseQuantity() {
            const input = document.getElementById('quantity');
            input.value = parseInt(input.value) + 1;
        }

        function decreaseQuantity() {
            const input = document.getElementById('quantity');
            if (input.value > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }
    </script>
@endsection