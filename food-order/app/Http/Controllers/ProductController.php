<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // عرض كل المنتجات
    public function index()
    {
        $products = Product::latest()->get();
        return view('products.index', compact('products'));
    }

    // عرض نموذج إنشاء منتج جديد
    public function create()
    {
        return view('products.create');
    }

    // حفظ المنتج الجديد
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
             'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        
    $data = $request->only(['name', 'price', 'description']);

    if($request->hasFile('image')){
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('uploads/products'), $imageName);
        $data['image'] = 'uploads/products/'.$imageName;
    }
// 
//   if ($request->hasFile('image')) {
//         // حفظ الصورة في public/storage/products
//         $imagePath = $request->file('image')->store('products', 'public');
//         $product->image = $imagePath;
//     }
//         $product->save();

        Product::create($request->only(['name', 'price', 'description']));

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    // عرض تفاصيل منتج محدد
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }


   public function edit(Product $product)
{
    $this->authorize('update', $product);
    return view('products.edit', compact('product'));
}
   public function update(Request $request, Product $product)
{
    $request->validate([
        'name'  => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    ]);

    $data = $request->only(['name', 'price', 'description']);

    if($request->hasFile('image')){
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('uploads/products'), $imageName);
        $data['image'] = 'uploads/products/'.$imageName;
    }

    $product->update($data);

    return redirect()->route('products.index')->with('success', 'Product updated successfully.');
}

    
    public function destroy(Product $product)
    {
         $this->authorize('delete', $product);
        $product->delete();
        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
