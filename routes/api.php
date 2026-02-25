<?php

use App\Http\Controllers\Admin\CollectionController as AdminCollections;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\OrderController as AdminOrders;
use App\Http\Controllers\Admin\ProductController as AdminProducts;
use App\Http\Controllers\Api\CollectionController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/products', [ProductController::class, 'index']);
Route::get('/collections', [CollectionController::class, 'index']);

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index']);

    Route::get('/collections', [AdminCollections::class, 'index']);
    Route::post('/collections', [AdminCollections::class, 'store']);
    Route::get('/collections/{id}', [AdminCollections::class, 'show']);
    Route::put('/collections/{id}', [AdminCollections::class, 'update']);
    Route::delete('/collections/{id}', [AdminCollections::class, 'destroy']);
    Route::post('/collections/{id}/reorder', [AdminCollections::class, 'reorder']);
    Route::post('/collections/{id}/image', [AdminCollections::class, 'uploadImage']);
    Route::get('/collections/export/csv', [AdminCollections::class, 'exportCsv']);
    Route::get('/collections/export/pdf', [AdminCollections::class, 'exportPdf']);

    Route::get('/products', [AdminProducts::class, 'index']);
    Route::post('/products', [AdminProducts::class, 'store']);
    Route::get('/products/{id}', [AdminProducts::class, 'show']);
    Route::put('/products/{id}', [AdminProducts::class, 'update']);
    Route::delete('/products/{id}', [AdminProducts::class, 'destroy']);
    Route::post('/products/{id}/duplicate', [AdminProducts::class, 'duplicate']);
    Route::post('/products/{id}/images', [AdminProducts::class, 'uploadImages']);
    Route::post('/products/import/csv', [AdminProducts::class, 'importCsv']);
    Route::get('/products/export/csv', [AdminProducts::class, 'exportCsv']);
    Route::post('/products/{id}/variations', [AdminProducts::class, 'createVariation']);
    Route::put('/products/{id}/variations/{variationId}', [AdminProducts::class, 'updateVariation']);
    Route::delete('/products/{id}/variations/{variationId}', [AdminProducts::class, 'deleteVariation']);

    Route::get('/orders', [AdminOrders::class, 'index']);
    Route::get('/orders/{id}', [AdminOrders::class, 'show']);
    Route::post('/orders/{id}/status', [AdminOrders::class, 'changeStatus']);
    Route::post('/orders/{id}/refund', [AdminOrders::class, 'refund']);
    Route::get('/orders/export/csv', [AdminOrders::class, 'exportCsv']);
    Route::get('/orders/{id}/invoice', [AdminOrders::class, 'invoicePdf']);
});
