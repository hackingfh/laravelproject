<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'admin']);
    }

    public function index(Request $request)
    {
        Log::info('Admin products list', ['admin_id' => $request->user()->id]);
        $q = Product::query()->with(['collection'])->withCount('media');
        if ($s = $request->string('q')->toString()) {
            $q->where('name', 'like', '%'.$s.'%');
        }
        $products = $q->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($products);
    }

    public function show(int $id)
    {
        $product = Product::with(['media', 'options', 'collection'])->findOrFail($id);
        $product->load('media', 'options', 'collection');

        return response()->json($product);
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']).'-'.Str::random(4);
        $product = Product::create($data);

        return response()->json(['message' => 'Produit créé', 'product' => $product], 201);
    }

    public function update(int $id, StoreProductRequest $request)
    {
        $product = Product::findOrFail($id);
        $product->update($request->validated());

        return response()->json(['message' => 'Produit mis à jour', 'product' => $product]);
    }

    public function destroy(int $id)
    {
        $product = Product::findOrFail($id);
        $product->media()->delete();
        $product->options()->detach();
        $product->delete();

        return response()->json(['message' => 'Produit supprimé']);
    }

    public function duplicate(int $id)
    {
        $product = Product::findOrFail($id);
        $copy = $product->replicate();
        $copy->name = $product->name.' (copie)';
        $copy->slug = Str::slug($copy->name).'-'.Str::random(4);
        $copy->reference = Str::upper(Str::random(7));
        $copy->sku = 'SKU-'.Str::upper(Str::random(8));
        $copy->save();

        return response()->json(['message' => 'Produit dupliqué', 'product' => $copy]);
    }

    public function uploadImages(int $id, Request $request)
    {
        $product = Product::findOrFail($id);
        $files = $request->file('images', []);
        $stored = [];
        foreach ($files as $file) {
            $path = $file->store('products', 'public');
            $product->media()->create([
                'path' => '/storage/'.$path,
                'type' => 'image',
            ]);
            $stored[] = '/storage/'.$path;
        }

        return response()->json(['message' => 'Images ajoutées', 'images' => $stored]);
    }

    public function importCsv(Request $request)
    {
        $file = $request->file('csv');
        if (! $file) {
            return response()->json(['message' => 'CSV requis'], 422);
        }
        $rows = array_map('str_getcsv', file($file->getRealPath()));
        $headers = array_map('trim', array_shift($rows));
        foreach ($rows as $row) {
            $data = array_combine($headers, $row);
            Product::create([
                'name' => $data['name'] ?? 'Produit',
                'price' => (float) ($data['price'] ?? 0),
                'stock' => (int) ($data['stock'] ?? 0),
                'description' => $data['description'] ?? null,
                'is_visible' => (bool) ($data['is_visible'] ?? true),
            ]);
        }

        return response()->json(['message' => 'Import terminé']);
    }

    public function exportCsv()
    {
        $rows = Product::select(['id', 'name', 'slug', 'price', 'stock', 'reference', 'sku'])->get();
        $csv = implode(',', ['id', 'name', 'slug', 'price', 'stock', 'reference', 'sku'])."\n";
        foreach ($rows as $r) {
            $csv .= "{$r->id},\"{$r->name}\",{$r->slug},{$r->price},{$r->stock},{$r->reference},{$r->sku}\n";
        }

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, 'products.csv', ['Content-Type' => 'text/csv']);
    }

    public function createVariation(int $id, Request $request)
    {
        $product = Product::findOrFail($id);
        $variation = ProductVariation::create([
            'product_id' => $product->id,
            'sku' => $request->input('sku', 'VAR-'.Str::upper(Str::random(8))),
            'options' => $request->input('options', []),
            'stock' => (int) $request->input('stock', 0),
            'price_modifier' => (float) $request->input('price_modifier', 0),
        ]);

        return response()->json(['message' => 'Variation créée', 'variation' => $variation], 201);
    }

    public function updateVariation(int $id, int $variationId, Request $request)
    {
        $variation = ProductVariation::where('product_id', $id)->findOrFail($variationId);
        $variation->update($request->only(['sku', 'options', 'stock', 'price_modifier']));

        return response()->json(['message' => 'Variation mise à jour', 'variation' => $variation]);
    }

    public function deleteVariation(int $id, int $variationId)
    {
        $variation = ProductVariation::where('product_id', $id)->findOrFail($variationId);
        $variation->delete();

        return response()->json(['message' => 'Variation supprimée']);
    }
}
