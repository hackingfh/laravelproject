<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCollectionRequest;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CollectionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'admin']);
    }

    public function index(Request $request)
    {
        Log::info('Admin collections list', ['admin_id' => $request->user()->id]);
        $q = Collection::query()->withCount('products');
        if ($s = $request->string('q')->toString()) {
            $q->where('name', 'like', '%'.$s.'%');
        }
        $collections = $q->orderBy('name')->paginate(20);

        return response()->json($collections);
    }

    public function show(int $id)
    {
        $collection = Collection::with('products')->findOrFail($id);

        return response()->json($collection);
    }

    public function store(StoreCollectionRequest $request)
    {
        $data = $request->validated();
        $collection = Collection::create($data);

        return response()->json(['message' => 'Collection créée', 'collection' => $collection], 201);
    }

    public function update(int $id, StoreCollectionRequest $request)
    {
        $collection = Collection::findOrFail($id);
        $collection->update($request->validated());

        return response()->json(['message' => 'Collection mise à jour', 'collection' => $collection]);
    }

    public function destroy(int $id)
    {
        $collection = Collection::findOrFail($id);
        $collection->products()->delete();
        $collection->delete();

        return response()->json(['message' => 'Collection supprimée']);
    }

    public function reorder(int $id, Request $request)
    {
        $collection = Collection::findOrFail($id);
        $order = (int) $request->input('display_order', 0);
        $collection->update(['display_order' => $order]);

        return response()->json(['message' => 'Ordre mis à jour']);
    }

    public function uploadImage(int $id, Request $request)
    {
        $collection = Collection::findOrFail($id);
        $file = $request->file('image');
        if (! $file) {
            return response()->json(['message' => 'Aucun fichier'], 422);
        }
        $path = $file->store('collections', 'public');
        $collection->update(['image' => '/storage/'.$path]);

        return response()->json(['message' => 'Image mise à jour', 'image' => '/storage/'.$path]);
    }

    public function exportCsv(Request $request)
    {
        $rows = Collection::select(['id', 'name', 'slug', 'period_start', 'period_end', 'is_active'])->get();
        $csv = implode(',', ['id', 'name', 'slug', 'period_start', 'period_end', 'is_active'])."\n";
        foreach ($rows as $r) {
            $csv .= "{$r->id},\"{$r->name}\",{$r->slug},{$r->period_start},{$r->period_end},".($r->is_active ? 1 : 0)."\n";
        }

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, 'collections.csv', ['Content-Type' => 'text/csv']);
    }

    public function exportPdf(Request $request)
    {
        return response()->json(['message' => 'PDF export requires dompdf integration'], 501);
    }
}
