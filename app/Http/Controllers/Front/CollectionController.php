<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\CollectionRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function __construct(
        private readonly CollectionRepositoryInterface $collections,
        private readonly ProductRepositoryInterface $products
    ) {
    }

    public function index(Request $request, string $locale = 'fr')
    {
        $filters = [
            'category' => $request->string('category')->toString(),
            'created_from' => $request->string('created_from')->toString(),
            'created_to' => $request->string('created_to')->toString(),
            'sort' => $request->string('sort')->toString(),
        ];
        $collections = $this->collections->paginateActive($filters, 24);

        return view('collection.index', compact('collections'));
    }

    public function show(Request $request, string $locale, string $slug)
    {
        $collection = $this->collections->findBySlug($slug);
        abort_unless($collection, 404);

        $filters = [
            'category' => $slug,
            'price_min' => $request->input('price_min'),
            'price_max' => $request->input('price_max'),
            'size' => $request->input('size'),
            'color' => $request->input('color'),
            'availability' => $request->input('availability'),
            'sort' => $request->string('sort')->toString(),
        ];
        $products = $this->products->search($filters, 24);
        $collections = \App\Models\Collection::where('is_active', true)->get();

        return view('catalog.index', compact('collection', 'products', 'collections'));
    }
}
