<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        // يمكن إضافة middleware حسب الدور إذا أردت حماية جميع الأكشن
        // $this->middleware('role:admin|vendor');
    }

    // ==============================
    // Show all products
    // ==============================
    public function index()
    {
        $user = Auth::user();

        // إذا كان Admin، عرض كل المنتجات، إذا Vendor، عرض منتجاته فقط
        if ($user->hasRole('vendor')) {
            $products = Product::where('vendor_id', $user->id)->latest()->get();
        } else {
            $products = Product::latest()->get();
        }

        return view('products.index', compact('products'));
    }

    // ==============================
    // Create Product
    // ==============================
    public function create()
    {
        $this->authorize('create', Product::class);
        return view('products.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Product::class);

        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->only(['name', 'price', 'description']);

        // إذا المستخدم Vendor، ضع vendor_id تلقائياً
        if (Auth::user()->hasRole('vendor')) {
            $data['vendor_id'] = Auth::id();
        }

        // معالجة الصورة
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/products'), $imageName);
            $data['image'] = 'uploads/products/' . $imageName;
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    // ==============================
    // Show single product
    // ==============================
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    // ==============================
    // Edit product
    // ==============================
    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->only(['name', 'price', 'description']);

        // معالجة الصورة
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/products'), $imageName);
            $data['image'] = 'uploads/products/' . $imageName;
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    // ==============================
    // Delete product
    // ==============================
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
