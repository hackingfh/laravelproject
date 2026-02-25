<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->with(['media', 'collection']);

        if ($request->filled('q')) {
            $q = $request->string('q')->toString();
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                    ->orWhere('reference', 'like', "%{$q}%")
                    ->orWhere('movement', 'like', "%{$q}%");
            });
        }

        if ($request->filled('collection')) {
            $slug = $request->string('collection')->toString();
            $query->whereHas('collection', function ($c) use ($slug) {
                $c->where('slug', $slug);
            });
        }

        if ($request->filled('material')) {
            $query->where('material', $request->string('material')->toString());
        }

        if ($request->filled('complication')) {
            $comp = $request->string('complication')->toString();
            $query->whereJsonContains('complications', $comp);
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', (float) $request->input('price_min'));
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', (float) $request->input('price_max'));
        }

        $query->orderBy('price');

        $perPage = min(max((int) $request->input('per_page', 12), 1), 60);

        return response()->json($query->paginate($perPage));
    }
}
