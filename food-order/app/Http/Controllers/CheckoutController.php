<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    // إذا أردت السماح للزوار (guests) بالشراء، قم بإزالة middleware auth أو اضبطه
    public function __construct()
    {
        // يمكنك تعطيل auth إذا أردت السماح للزوار:
        $this->middleware('auth')->only(['index', 'store', 'thankyou']);
    }

    // GET /checkout
    public function index()
    {
        // هنا نفترض أنك تعرض كافة المنتجات للتحديد — إذا تستخدم Cart، استبدل ذلك بمحتويات السلة.
        $products = Product::latest()->get();
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

        // احصل على المنتجات الحقيقية من DB
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $items = [];
        $itemsTotal = 0;

        foreach ($productIds as $i => $id) {
            $qty = max(0, (int) ($quantities[$i] ?? 0));
            if ($qty <= 0) continue;

            if (!isset($products[$id])) continue;

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

        // مثال قاعدة رسوم التوصيل
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

        // إذا كانت طريقة الدفع بطاقة — توجه للبوابة هنا
        if ($request->payment_method === 'card') {
            // مثال تبسيطي: لو كنت تستخدم Stripe ستنشئ Checkout Session هنا
            // اعد توجيه المستخدم إلى صفحة دفع خارجية أو صفحة انتظار
            // return redirect()->route('payment.redirect', $order->id);
        }

        return redirect()->route('checkout.thankyou', $order->id)->with('success', 'Order placed successfully.');
    }

    // GET /checkout/thankyou/{order}
    public function thankyou(Order $order)
    {
        // تأكد من أن المستخدم له الحق برؤية هذا الطلب
        if ($order->user_id && $order->user_id !== Auth::id()) {
            abort(403);
        }
        return view('checkout.thankyou', compact('order'));
    }

    // (اختياري) عرض تفاصيل الطلب — admin أو owner
    public function show(Order $order)
    {
        if ($order->user_id && $order->user_id !== Auth::id()) {
            abort(403);
        }
        return view('orders.show', compact('order'));
    }
}
