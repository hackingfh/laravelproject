<div class="space-y-6" x-data="{ showLightbox: false, lightboxSrc: '' }">
    <!-- Lightbox Modal -->
    <div x-show="showLightbox" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
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

    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">Gestion du Catalogue</h2>
        <button wire:click="$set('showForm', true)"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                </path>
            </svg>
            Nouvelle catégorie
        </button>
    </div>

    @if (session()->has('success'))
        <div class="p-4 bg-green-100 text-green-700 rounded-lg border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Hierarchy View -->
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <div class="p-4 bg-gray-50 border-b font-semibold text-gray-700 underline">Structure des catégories
                </div>
                <div class="p-4">
                    @forelse($collections as $category)
                        <div class="mb-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border group">
                                <div class="flex items-center gap-3">
                                    <div class="relative cursor-zoom-in"
                                        @click="lightboxSrc = '{{ $category->image ?: '/storage/products/placeholder.jpg' }}'; showLightbox = true">
                                        @if($category->image)
                                            <img src="{{ $category->image }}" class="w-10 h-10 object-cover rounded border">
                                        @else
                                            <div class="w-10 h-10 bg-gray-200 flex items-center justify-center rounded border">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <span
                                        class="font-medium @if(!$category->is_published) text-gray-400 italic @endif">{{ $category->name }}</span>
                                </div>
                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                                    <button wire:click="edit({{ $category->id }})"
                                        class="p-1 text-blue-600 hover:bg-blue-50 rounded"><svg class="w-4 h-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                            </path>
                                        </svg></button>
                                    <button wire:click="togglePublish({{ $category->id }})"
                                        class="p-1 {{ $category->is_published ? 'text-green-600' : 'text-gray-400' }} hover:bg-gray-100 rounded"><svg
                                            class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg></button>
                                    <button wire:click="delete({{ $category->id }})"
                                        wire:confirm="Supprimer cette catégorie et ses sous-catégories ?"
                                        class="p-1 text-red-600 hover:bg-red-50 rounded"><svg class="w-4 h-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg></button>
                                </div>
                            </div>

                            <!-- Children -->
                            <div class="ml-8 mt-2 space-y-2 border-l-2 pl-4">
                                @foreach($category->children as $child)
                                    <div class="flex items-center justify-between p-2 bg-white rounded border group">
                                        <div class="flex items-center gap-2">
                                            @if($child->image)
                                                <img src="{{ $child->image }}"
                                                    class="w-8 h-8 object-cover rounded border cursor-zoom-in"
                                                    @click="lightboxSrc = '{{ $child->image }}'; showLightbox = true">
                                            @endif
                                            <span
                                                class="text-sm @if(!$child->is_published) text-gray-400 italic @endif">{{ $child->name }}</span>
                                        </div>
                                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                                            <button wire:click="edit({{ $child->id }})"
                                                class="p-1 text-blue-600 hover:bg-blue-50 rounded"><svg class="w-3 h-3"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                    </path>
                                                </svg></button>
                                            <button wire:click="delete({{ $child->id }})"
                                                wire:confirm="Supprimer cette sous-catégorie ?"
                                                class="p-1 text-red-600 hover:bg-red-50 rounded"><svg class="w-3 h-3"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg></button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-8 italic">Aucune catégorie définie.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Form Sidebar -->
        <div class="space-y-4">
            @if($showForm)
                <div class="bg-white p-6 rounded-xl shadow-sm border space-y-4 sticky top-24">
                    <div class="flex items-center justify-between border-b pb-2">
                        <h3 class="font-semibold text-gray-700">{{ $editing_id ? 'Éditer' : 'Créer' }}</h3>
                        <button wire:click="resetForm" class="text-gray-400 hover:text-gray-600 text-sm">Annuler</button>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom</label>
                        <input wire:model="name" type="text"
                            class="mt-1 block w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Parent (Sous-catégorie de...)</label>
                        <select wire:model="parent_id"
                            class="mt-1 block w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Aucun (Catégorie racine)</option>
                            @foreach($allCollections as $ac)
                                <option value="{{ $ac->id }}">{{ $ac->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea wire:model="description" rows="3"
                            class="mt-1 block w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <!-- Image Section -->
                    <div class="space-y-3 pt-2 border-t">
                        <label class="block text-sm font-medium text-gray-700">Image de la collection</label>

                        @if($new_image)
                            <div class="relative group cursor-zoom-in"
                                @click="lightboxSrc = '{{ $new_image->temporaryUrl() }}'; showLightbox = true">
                                <img src="{{ $new_image->temporaryUrl() }}"
                                    class="w-full h-32 object-cover rounded-lg border-2 border-blue-200">
                                <button type="button" wire:click.stop="$set('new_image', null)"
                                    class="absolute top-1 right-1 p-1 bg-red-500 text-white rounded-full">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                                <div
                                    class="absolute bottom-0 left-0 right-0 bg-blue-600 text-white text-[10px] py-1 text-center font-bold rounded-b-lg">
                                    À ENREGISTRER</div>
                            </div>
                        @elseif($existing_image)
                            <div class="relative group cursor-zoom-in"
                                @click="lightboxSrc = '{{ $existing_image }}'; showLightbox = true">
                                <img src="{{ $existing_image }}" class="w-full h-32 object-cover rounded-lg border">
                            </div>
                        @endif

                        <div class="flex items-center justify-center w-full">
                            <label
                                class="flex flex-col items-center justify-center w-full h-24 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 @error('new_image') border-red-300 @enderror">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                        </path>
                                    </svg>
                                    <p class="text-xs text-gray-500">PNG, JPG (Max 10MB)</p>
                                </div>
                                <input type="file" wire:model="new_image" class="hidden" />
                            </label>
                        </div>
                        @error('new_image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                        <div wire:loading wire:target="new_image"
                            class="text-blue-600 text-[10px] italic flex items-center gap-1">
                            <svg class="animate-spin h-3 w-3" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"
                                    fill="none"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Chargement...
                        </div>
                    </div>

                    <button wire:click="save"
                        class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-md">
                        {{ $editing_id ? 'Mettre à jour' : 'Enregistrer' }}
                    </button>
                </div>
            @else
                <div class="bg-blue-50 p-6 rounded-xl border border-blue-100 text-center space-y-3">
                    <p class="text-sm text-blue-700">Sélectionnez une catégorie pour la modifier ou cliquez sur le bouton
                        "Nouvelle catégorie".</p>
                    <svg class="w-12 h-12 text-blue-200 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            @endif
        </div>
    </div>
</div>