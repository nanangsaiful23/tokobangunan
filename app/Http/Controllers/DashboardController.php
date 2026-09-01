<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $todaySales = Sale::whereDate('created_at', $today);

        $summary = [
            'today_transaction_count' => (clone $todaySales)->count(),
            'today_revenue' => (clone $todaySales)->sum('total'),
            'today_outstanding' => (clone $todaySales)->where('status', '!=', 'paid')->sum(
                DB::raw('total - paid_amount')
            ),
            'low_stock_count' => Product::active()->lowStock()->count(),
        ];

        $lowStockProducts = Product::active()
            ->lowStock()
            ->orderBy('current_stock')
            ->limit(8)
            ->get();

        $recentSales = Sale::with('cashier')
            ->latest()
            ->limit(8)
            ->get();

        return view('dashboard.index', compact('summary', 'lowStockProducts', 'recentSales'));
    }
}
