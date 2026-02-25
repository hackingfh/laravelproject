<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">Journal d'activité</h2>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-xl shadow-sm border flex flex-wrap gap-4">
        <div class="flex-grow">
            <input wire:model.live.debounce.300ms="search" type="text"
                placeholder="Rechercher un utilisateur ou un modèle..."
                class="w-full rounded-lg border-gray-300 text-sm">
        </div>
        <div class="w-48">
            <select wire:model.live="action" class="w-full rounded-lg border-gray-300 text-sm">
                <option value="">Toutes les actions</option>
                <option value="created">Création</option>
                <option value="updated">Modification</option>
                <option value="deleted">Suppression</option>
                <option value="bulk_deleted">Suppression en masse</option>
            </select>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-medium">
                <tr>
                    <th class="p-4">Date</th>
                    <th class="p-4">Administrateur</th>
                    <th class="p-4">Action</th>
                    <th class="p-4">Modèle</th>
                    <th class="p-4">ID</th>
                    <th class="p-4 w-1/3">Détails</th>
                </tr>
            </thead>
            <tbody class="divide-y text-sm">
                @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-gray-500 whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-4 font-medium">{{ $log->user->name }}</td>
                        <td class="p-4">
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase
                                        @if($log->action === 'created') bg-green-100 text-green-700
                                        @elseif($log->action === 'deleted' || $log->action === 'bulk_deleted') bg-red-100 text-red-700
                                        @else bg-blue-100 text-blue-700 @endif">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="p-4 text-gray-400 font-mono text-xs">{{ class_basename($log->model_type) }}</td>
                        <td class="p-4 text-gray-500 text-xs">#{{ $log->model_id ?: '-' }}</td>
                        <td class="p-4">
                            <div
                                class="max-h-32 overflow-y-auto text-xs bg-gray-50 p-3 rounded-lg border border-gray-100 space-y-1.5">
                                @if(is_array($log->payload) && count($log->payload) > 0)
                                    @php $hasVisiblePayload = false; @endphp
                                    @foreach($log->payload as $key => $value)
                                        @if($key !== 'description' && !empty($value) && !is_array($value))
                                            @php $hasVisiblePayload = true; @endphp
                                            <div class="flex items-start gap-2">
                                                <span class="font-bold text-gray-700 min-w-[80px] opacity-70 italic">{{ $key }}:</span>
                                                <span class="text-gray-600 break-all">{{ $value }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                    @if(!$hasVisiblePayload)
                                        <span class="text-gray-400 italic">Aucun détail pertinent</span>
                                    @endif
                                @else
                                    <span class="text-gray-400 italic">Aucune donnée</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-500 italic">Aucun log trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>