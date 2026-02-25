<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">Gestion des Produits</h2>
        <a href="{{ route('admin.products.create') }}"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                </path>
            </svg>
            Ajouter un produit
        </a>
    </div>

    @if (session()->has('success'))
        <div class="p-4 bg-green-100 text-green-700 rounded-lg border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white p-4 rounded-xl shadow-sm border grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Recherche</label>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Nom, réf..."
                class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Collection</label>
            <select wire:model.live="collection_id"
                class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Toutes</option>
                @foreach($collections as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Stock</label>
            <select wire:model.live="stock_status"
                class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Tous les niveaux</option>
                <option value="low">Stock faible (<5)< /option>
                <option value="out">Rupture</option>
            </select>
        </div>
        <div class="flex items-end">
            @if(count($selected) > 0)
                <button wire:click="deleteSelected" wire:confirm="Supprimer {{ count($selected) }} produits ?"
                    class="w-full px-4 py-2 bg-red-50 text-red-600 border border-red-200 rounded-lg hover:bg-red-100 transition text-sm font-medium">
                    Supprimer ({{ count($selected) }})
                </button>
            @endif
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-medium">
                <tr>
                    <th class="p-4 w-10"><input type="checkbox" class="rounded border-gray-300"></th>
                    <th class="p-4">Image</th>
                    <th class="p-4 cursor-pointer" wire:click="sortBy('name')">
                        Produit @if($sort_field === 'name') {{ $sort_direction === 'asc' ? '↑' : '↓' }} @endif
                    </th>
                    <th class="p-4">Réf</th>
                    <th class="p-4 cursor-pointer" wire:click="sortBy('price')">
                        Prix @if($sort_field === 'price') {{ $sort_direction === 'asc' ? '↑' : '↓' }} @endif
                    </th>
                    <th class="p-4 cursor-pointer" wire:click="sortBy('stock')">
                        Stock @if($sort_field === 'stock') {{ $sort_direction === 'asc' ? '↑' : '↓' }} @endif
                    </th>
                    <th class="p-4 text-center">Visible</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y text-sm">
                @forelse($products as $p)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4"><input type="checkbox" wire:model.live="selected" value="{{ $p->id }}"
                                class="rounded border-gray-300"></td>
                        <td class="p-4">
                            @php $firstImage = data_get($p->images, 0); @endphp
                            <div
                                class="w-12 h-12 rounded-lg border overflow-hidden bg-gray-50 flex items-center justify-center">
                                @if($firstImage)
                                    <img src="{{ $firstImage }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                @endif
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="font-medium text-gray-900">{{ $p->name }}</div>
                            <div class="text-xs text-gray-400">{{ $p->collection->name ?? 'Sans collection' }}</div>
                        </td>
                        <td class="p-4 text-gray-500">{{ $p->reference ?: '-' }}</td>
                        <td class="p-4 font-semibold">{{ number_format($p->price, 2) }} €</td>
                        <td class="p-4">
                            <span
                                class="px-2 py-1 rounded-full text-xs font-medium {{ $p->stock < 5 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                {{ $p->stock }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <button wire:click="toggleVisibility({{ $p->id }})" class="focus:outline-none">
                                <div
                                    class="w-10 h-5 rounded-full p-1 transition {{ $p->is_visible ? 'bg-blue-500' : 'bg-gray-300' }}">
                                    <div
                                        class="w-3 h-3 bg-white rounded-full transition transform {{ $p->is_visible ? 'translate-x-5' : '' }}">
                                    </div>
                                </div>
                            </button>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.products.edit', $p->id) }}"
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </a>
                                {{-- duplication etc --}}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-gray-500 italic">Aucun produit trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>