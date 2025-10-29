<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct()
    {

        $this->middleware(['auth', 'role:customer'])->only(['index', 'store', 'thankyou', 'show']);
    }

    // GET /checkout
    public function index()
    {
        $products = Product::all(); 
        return view('checkout.index', compact('products'));
    }

    // POST /checkout
    public function store(Request $request)
    {
        $request->validate([
            'customer_phone' => 'required|string|max:50',
            'customer_address' => 'required|string|max:255',
            'products' => 'required|array',
            'quantities' => 'required|array',
            'payment_method' => 'required|string|in:cod,card,bank_transfer',
        ]);

        $productIds = $request->input('products', []);
        $quantities = $request->input('quantities', []);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $items = [];
        $itemsTotal = 0;

        foreach ($productIds as $i => $id) {
            $qty = max(0, (int) ($quantities[$i] ?? 0));
            if ($qty <= 0 || !isset($products[$id])) continue;

            $p = $products[$id];
            $subtotal = $p->price * $qty;

            $items[] = [
                'product_id' => $p->id,
                'name' => $p->name,
                'price' => (float) $p->price,
                'quantity' => $qty,
                'subtotal' => (float) $subtotal,
            ];

            $itemsTotal += $subtotal;
        }

        if (empty($items)) {
            return back()->withInput()->with('error', 'Please select at least one product.');
        }

        $delivery = $itemsTotal >= 50 ? 0 : 5;
        $grand = round($itemsTotal + $delivery, 2);

        $order = Order::create([
            'user_id' => Auth::id(),
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'items' => $items,
            'items_total' => $itemsTotal,
            'delivery_fee' => $delivery,
            'grand_total' => $grand,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_method === 'cod' ? 'pending' : 'pending_payment',
        ]);

        return redirect()->route('checkout.thankyou', $order->id)
                         ->with('success', 'Order placed successfully.');
    }

    // GET /checkout/thankyou/{order}
    public function thankyou(Order $order)
    {
        $this->authorizeOrderOwner($order);
        return view('checkout.thankyou', compact('order'));
    }

    // GET /checkout/order/{order}
    public function show(Order $order)
    {
        $this->authorizeOrderOwner($order);
        return view('orders.show', compact('order'));
    }

    
    protected function authorizeOrderOwner(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
