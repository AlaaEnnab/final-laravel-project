@role('vendor')
@php $vendor = auth()->user(); @endphp
<div class="row g-4">
    <div class="col-md-6">
        <div class="card text-white bg-success p-3 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5>My Products</h5>
                    <p class="fs-3">{{ $vendor->products()->count() }}</p>
                </div>
                <i class="bi bi-box-seam fs-1"></i>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-light btn-sm mt-3 w-100">Manage My Products</a>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card text-white bg-warning p-3 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5>Orders with My Products</h5>
                    <p class="fs-3">{{ \App\Models\OrderItem::whereHas('product', fn($q)=> $q->where('vendor_id', $vendor->id))->count() }}</p>
                </div>
                <i class="bi bi-basket fs-1"></i>
            </div>
            <a href="{{ route('orders.index') }}" class="btn btn-light btn-sm mt-3 w-100">View Orders</a>
        </div>
    </div>
</div>
@endrole
