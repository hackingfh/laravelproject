<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Product;
use Livewire\Component;

class Dashboard extends Component
{
    public $stats = [];
    public $recentActivity = [];

    public function mount()
    {
        $this->loadStats();
        $this->loadActivity();
    }

    public function loadStats()
    {
        $this->stats = [
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'revenue' => Order::sum('total'),
            'low_stock' => Product::where('stock', '<', 5)->count(),
        ];
    }

    public function loadActivity()
    {
        $this->recentActivity = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('layouts.admin');
    }
}
