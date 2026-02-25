<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(string $locale = 'fr')
    {
        $featuredProducts = \App\Models\Product::with('media')
            ->where('is_visible', true)
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact('featuredProducts'));
    }

    public function history()
    {
        return view('history');
    }

    public function contact()
    {
        return view('contact');
    }
}
