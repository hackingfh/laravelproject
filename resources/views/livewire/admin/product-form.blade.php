<div class="max-w-4xl mx-auto space-y-6" x-data="{ showLightbox: false, lightboxSrc: '' }">
    <!-- Lightbox Modal -->
    <div x-show="showLightbox" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm"
        style="display: none;" @keydown.escape.window="showLightbox = false">
        <div class="relative max-w-5xl w-full h-full flex items-center justify-center"
            @click.away="showLightbox = false">
            <img :src="lightboxSrc" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
            <button type="button" @click="showLightbox = false"
                class="absolute top-0 right-0 m-4 p-2 bg-white/10 hover:bg-white/20 text-white rounded-full transition">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
    </div>
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.products') }}" class="p-2 text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
        </a>
        <h2 class="text-2xl font-bold">{{ $product_id ? 'Modifier le produit' : 'Nouveau produit' }}</h2>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Info -->
            <div class="bg-white p-6 rounded-xl shadow-sm border space-y-4">
                <h3 class="font-semibold border-b pb-2 text-gray-700">Informations de base</h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nom du produit</label>
                    <input wire:model="name" type="text"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Prix (€)</label>
                        <input wire:model="price" type="number" step="0.01"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Stock</label>
                        <input wire:model="stock" type="number"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('stock') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Collection</label>
                    <select wire:model="collection_id"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Aucune</option>
                        @foreach($collections as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Identifiers -->
            <div class="bg-white p-6 rounded-xl shadow-sm border space-y-4">
                <h3 class="font-semibold border-b pb-2 text-gray-700">Détails techniques</h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Référence</label>
                    <input wire:model="reference" type="text"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">SKU</label>
                    <input wire:model="sku" type="text"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="pt-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model="is_visible"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Afficher sur le site</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="bg-white p-6 rounded-xl shadow-sm border space-y-4">
            <h3 class="font-semibold border-b pb-2 text-gray-700">Description</h3>
            <textarea wire:model="description" rows="5"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
        </div>

        <!-- Images -->
        <div class="bg-white p-6 rounded-xl shadow-sm border space-y-4">
            <h3 class="font-semibold border-b pb-2 text-gray-700">Images</h3>

            @if($existing_media && count($existing_media) > 0)
                <div class="grid grid-cols-4 gap-4 mb-4">
                    @foreach($existing_media as $media)
                        <div class="relative group cursor-zoom-in"
                            @click="lightboxSrc = '{{ $media->url }}'; showLightbox = true">
                            <img src="{{ $media->url }}"
                                class="w-full h-32 object-cover rounded-lg border transition group-hover:brightness-90">
                            <button type="button" wire:click.stop="deleteMedia({{ $media->id }})"
                                class="absolute top-1 right-1 p-1 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($new_images && count($new_images) > 0)
                <div class="space-y-2 mb-4">
                    <h4 class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Nouvelles images (Aperçu)</h4>
                    <div class="grid grid-cols-4 gap-4">
                        @foreach($new_images as $index => $image)
                            <div class="relative group cursor-zoom-in" @click="lightboxSrc = '{{ $image->temporaryUrl() }}'; showLightbox = true">
                                <img src="{{ $image->temporaryUrl() }}"
                                    class="w-full h-32 object-cover rounded-lg border-2 border-blue-200 transition group-hover:brightness-95">
                                <button type="button" wire:click.stop="removeNewImage({{ $index }})"
                                    class="absolute top-1 right-1 p-1 bg-gray-800/80 text-white rounded-full opacity-0 group-hover:opacity-100 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                                <div
                                    class="absolute bottom-0 left-0 right-0 bg-blue-600 text-white text-[10px] px-2 py-1 rounded-b-lg text-center font-bold">
                                    À ENREGISTRER</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div
                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg @error('new_images.*') border-red-300 @enderror">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path
                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600">
                        <label
                            class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                            <span>Télécharger des images</span>
                            <input wire:model="new_images" type="file" multiple class="sr-only">
                        </label>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG up to 15MB</p>
                </div>
            </div>

            @error('new_images') <div class="bg-red-50 border-l-4 border-red-500 p-2 mt-2"><span
            class="text-red-700 text-xs font-medium block">{{ $message }}</span></div> @enderror
            @error('new_images.*') <div class="bg-red-50 border-l-4 border-red-500 p-2 mt-2"><span
            class="text-red-700 text-xs font-medium block">{{ $message }}</span></div> @enderror

            <div wire:loading wire:target="new_images"
                class="text-blue-600 text-xs mt-2 italic flex items-center gap-2">
                <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"
                        fill="none"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                Téléchargement en cours...
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.products') }}"
                class="px-6 py-2 border rounded-lg hover:bg-gray-50 transition">Annuler</a>
            <button type="submit"
                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-lg transition">
                Enregistrer le produit
            </button>
        </div>
    </form>
</div>