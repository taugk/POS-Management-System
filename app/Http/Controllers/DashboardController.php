<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\Supplier;
use App\Models\Sales;
use App\Models\Expense;
use App\Models\Expenses;
use App\Models\Promo;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Statistik Dasar
        $productCount = products::count();
        $supplierCount = Supplier::count();
        $promoCount = Promo::where('is_active', true)->count();

        // Statistik Keuangan (Bulan Ini)
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $monthlyRevenue = Sales::whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('total_price');
        $monthlyExpense = Expenses::whereBetween('expense_date', [$startOfMonth, $endOfMonth])->sum('amount');
        $monthlyNetProfit = $monthlyRevenue - $monthlyExpense;

        // Data Grafik Penjualan (7 Hari Terakhir)
        $salesData = Sales::selectRaw('DATE(created_at) as date, SUM(total_price) as total')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // Produk Stok Menipis
        $lowStockProducts = products::where('stock', '<=', 5)->limit(5)->get();

        return view('dashboard.index', compact(
            'productCount', 
            'supplierCount', 
            'promoCount',
            'monthlyRevenue', 
            'monthlyExpense', 
            'monthlyNetProfit',
            'salesData',
            'lowStockProducts'
        ));
    }
}