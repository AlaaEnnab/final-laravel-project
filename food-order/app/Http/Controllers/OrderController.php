<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ==============================
    // Show all orders
    // ==============================
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $orders = Order::with('items.product')->latest()->get();
        } elseif ($user->hasRole('vendor')) {
            // جميع الطلبات التي تحتوي منتجات هذا البائع
            $orders = Order::whereHas('items.product', function($q) use ($user) {
                $q->where('vendor_id', $user->id);
            })->with('items.product')->latest()->get();
        } else {
            // Customer يرى طلباته فقط
            $orders = Order::where('user_id', $user->id)->with('items.product')->latest()->get();
        }

        return view('orders.index', compact('orders'));
    }

    // ==============================
    // Show form to create order
    // ==============================
    public function create()
    {
        $products = Product::all();
        return view('orders.create', compact('products'));
    }

    // ==============================
    // Store new order
    // ==============================
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'nullable|string',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'status' => 'pending',
                'total' => 0,
            ]);

            $total = 0;

            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['id']);
                $subtotal = $product->price * $item['quantity'];

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            }

            $order->update(['total' => $total]);

            DB::commit();

            return redirect()->route('orders.index')->with([
                'success' => 'تم إنشاء الطلب بنجاح!',
                'new_order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // ==============================
    // Show single order
    // ==============================
    public function show(Order $order)
    {
        $this->authorize('view', $order); // تحقق الصلاحية

        $order->load('items.product');
        return view('orders.show', compact('order'));
    }
}
