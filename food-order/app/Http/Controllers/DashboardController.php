<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{


   public function index()
{
    



    $totalOrders = Order::count();
    $completedOrders = Order::where('status','completed')->count();
    $pendingOrders = Order::where('status','pending')->count();
    $monthlyRevenue = Order::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('total');
    $recentOrders = Order::with('items.product')->latest()->take(12)->get();

    // chart data: labels and data for last 6 months
    $months = collect();
    $data = collect();
    for ($i=5; $i>=0; $i--) {
        $m = now()->subMonths($i);
        $label = $m->format('M Y');
        $months->push($label);
        $sum = Order::whereYear('created_at', $m->year)
                    ->whereMonth('created_at', $m->month)
                    ->sum('total');
        $data->push((float) $sum);
    }

  $topProducts = Product::select(
        'products.id',
        'products.name',
        'products.price',
        'products.image',
        \DB::raw('COALESCE(SUM(order_items.quantity),0) as sold_count')
    )
    ->leftJoin('order_items','order_items.product_id','=','products.id')
    ->groupBy('products.id','products.name','products.price','products.image')
    ->orderByDesc('sold_count')
    ->take(5)
    ->get();

    return view('dashboard', [
        'totalOrders' => $totalOrders,
        'completedOrders' => $completedOrders,
        'pendingOrders' => $pendingOrders,
        'monthlyRevenue' => $monthlyRevenue,
        'recentOrders' => $recentOrders,
        'chartLabels' => $months->toArray(),
        'chartData' => $data->toArray(),
        'topProducts' => $topProducts,
    ]);
}

}
