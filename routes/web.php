<?php

use App\Http\Controllers\Front\CartController as FrontCart;
use App\Http\Controllers\Front\CheckoutController as FrontCheckout;
use App\Http\Controllers\Front\CollectionController as FrontCollections;
use App\Http\Controllers\Front\HomeController as FrontHome;
use App\Http\Controllers\Front\OrderController as FrontOrders;
use App\Http\Controllers\Front\ProductController as FrontProducts;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['locale']], function () {
    Route::get('/{locale?}', [FrontHome::class, 'index'])->whereIn('locale', ['fr', 'en'])->name('home');

    Route::get('/{locale}/collections', [FrontCollections::class, 'index'])->whereIn('locale', ['fr', 'en'])->name('collections.index');
    Route::get('/{locale}/collections/{slug}', [FrontCollections::class, 'show'])->whereIn('locale', ['fr', 'en'])->name('collections.show');

    Route::get('/{locale}/products', [FrontProducts::class, 'index'])->whereIn('locale', ['fr', 'en'])->name('products.index');
    Route::get('/{locale}/products/{slug}', [FrontProducts::class, 'show'])->whereIn('locale', ['fr', 'en'])->name('products.show');

    Route::get('/{locale}/cart', [FrontCart::class, 'index'])->whereIn('locale', ['fr', 'en'])->name('cart.index');
    Route::post('/{locale}/cart/add/{product}', [FrontCart::class, 'add'])->whereIn('locale', ['fr', 'en'])->name('cart.add');
    Route::post('/{locale}/cart/update', [FrontCart::class, 'update'])->whereIn('locale', ['fr', 'en'])->name('cart.update');
    Route::post('/{locale}/cart/remove', [FrontCart::class, 'remove'])->whereIn('locale', ['fr', 'en'])->name('cart.remove');
    Route::post('/{locale}/cart/promo', [FrontCart::class, 'applyPromo'])->whereIn('locale', ['fr', 'en'])->name('cart.promo');

    Route::get('/{locale}/checkout', [FrontCheckout::class, 'index'])->whereIn('locale', ['fr', 'en'])->name('checkout.index');
    Route::post('/{locale}/checkout', [FrontCheckout::class, 'store'])->whereIn('locale', ['fr', 'en'])->name('checkout.store');

    Route::get('/{locale}/orders', [FrontOrders::class, 'index'])->whereIn('locale', ['fr', 'en'])->name('orders.index');
    Route::get('/{locale}/orders/{id}', [FrontOrders::class, 'show'])->whereIn('locale', ['fr', 'en'])->name('orders.show');

    Route::get('/{locale}/history', [FrontHome::class, 'history'])->whereIn('locale', ['fr', 'en'])->name('history');
    Route::get('/{locale}/contact', [FrontHome::class, 'contact'])->whereIn('locale', ['fr', 'en'])->name('contact');
});

Route::get('/debug/php', function () {
    return response()->json([
        'PHP_BINARY' => PHP_BINARY,
        'PHP_VERSION' => PHP_VERSION,
        'LOADED_INI' => php_ini_loaded_file(),
        'EXT_DIR' => ini_get('extension_dir'),
        'MODULES' => get_loaded_extensions(),
    ]);
});

// Redirect root to default locale
Route::get('/', function () {
    return redirect('/fr');
});

require __DIR__ . '/auth.php';
