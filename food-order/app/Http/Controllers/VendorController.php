<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function __construct()
    {
       
        $this->middleware('auth');
        $this->middleware('role:admin'); 
    }

    // ==============================
    // Show all vendors
    // ==============================
    public function index()
    {
        $vendors = Vendor::latest()->paginate(12);
        return view('vendors.index', compact('vendors'));
    }

    // ==============================
    // Create Vendor
    // ==============================
    public function create()
    {
        $this->authorize('create', Vendor::class);
        return view('vendors.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Vendor::class);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'active' => 'sometimes|boolean',
        ]);

        Vendor::create($data);

        return redirect()->route('vendors.index')->with('success', 'Vendor created successfully.');
    }

    // ==============================
    // Show single vendor with products
    // ==============================
    public function show(Vendor $vendor)
    {
        $vendor->load('products'); 
        return view('vendors.show', compact('vendor'));
    }

    // ==============================
    // Edit Vendor
    // ==============================
    public function edit(Vendor $vendor)
    {
        $this->authorize('update', $vendor);
        return view('vendors.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $this->authorize('update', $vendor);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'active' => 'sometimes|boolean',
        ]);

        $vendor->update($data);

        return redirect()->route('vendors.index')->with('success', 'Vendor updated successfully.');
    }

    // ==============================
    // Delete Vendor
    // ==============================
    public function destroy(Vendor $vendor)
    {
        $this->authorize('delete', $vendor);

        
        $vendor->products()->update(['vendor_id' => null]);

        $vendor->delete();

        return redirect()->route('vendors.index')->with('success', 'Vendor deleted successfully.');
    }

    // ==============================
    // Attach product to vendor
    // ==============================
    public function attachProduct(Request $request, Vendor $vendor)
    {
        $this->authorize('update', $vendor);

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::findOrFail($request->product_id);
        $product->update(['vendor_id' => $vendor->id]);

        return back()->with('success', 'Product attached to vendor.');
    }

    // ==============================
    // Detach product from vendor
    // ==============================
    public function detachProduct(Request $request, Vendor $vendor)
    {
        $this->authorize('update', $vendor);

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->vendor_id == $vendor->id) {
            $product->update(['vendor_id' => null]);
        }

        return back()->with('success', 'Product detached from vendor.');
    }
}
