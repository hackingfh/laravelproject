<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductRepositoryInterface $products
    ) {
    }

    public function index(Request $request, string $locale = 'fr')
    {
        $filters = [
            'q' => $request->input('q', $request->input('search', '')),
            'price_min' => $request->input('price_min'),
            'price_max' => $request->input('price_max'),
            'brand' => $request->string('brand')->toString(),
            'category' => $request->string('category')->toString(),
            'collection' => $request->string('collection')->toString(),
            'rating' => $request->input('rating'),
            'availability' => $request->string('availability')->toString(),
            'sort' => $request->string('sort')->toString(),
            'view' => $request->string('view')->toString(),
        ];
        $products = $this->products->search($filters, 24);
        $collections = \App\Models\Collection::where('is_active', true)->get();

        return view('catalog.index', compact('products', 'collections'));
    }

    public function show(string $locale, string $slug)
    {
        $product = $this->products->findBySlug($slug);
        abort_if(!$product, 404);
        $recommended = $this->products->recommended($product, 6);
        $stock = max(0, (int) $product->stock);

        return view('product.show', compact('product', 'recommended', 'stock'));
    }
}
