<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'admin']);
    }

    public function index(Request $request)
    {
        Log::info('Admin dashboard viewed', ['admin_id' => $request->user()->id]);
        $dateFrom = $request->input('date_from', now()->subDays(30)->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());

        $revenueTotal = Order::whereBetween('created_at', [$dateFrom, $dateTo])->sum('total');
        $salesByDay = Order::selectRaw('DATE(created_at) as day, COUNT(*) as count, SUM(total) as revenue')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('day')
            ->orderBy('day')
            ->get();
        $lowStock = Product::where('stock', '<', 5)->orderBy('stock')->limit(10)->get();

        $trend = $salesByDay->map(fn ($r) => ['x' => $r->day, 'y' => (float) $r->revenue]);

        return response()->json([
            'filters' => ['date_from' => $dateFrom, 'date_to' => $dateTo],
            'widgets' => [
                'revenue_total' => (float) $revenueTotal,
                'sales_by_day' => $salesByDay,
                'low_stock' => $lowStock,
                'trend_30_days' => $trend,
            ],
            'exports' => [
                'csv_url' => '/api/admin/orders/export/csv?date_from='.$dateFrom.'&date_to='.$dateTo,
                'pdf_url' => '/api/admin/orders/export/pdf?date_from='.$dateFrom.'&date_to='.$dateTo,
            ],
        ]);
    }
}
