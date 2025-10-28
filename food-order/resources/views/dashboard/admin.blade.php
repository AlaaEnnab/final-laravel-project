@role('admin')
<div class="row g-4"> 
    <div class="col-md-4">
        <div class="card text-white bg-primary p-3 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5>Vendors</h5>
                    <p class="fs-3">{{ \App\Models\Vendor::count() }}</p>
                </div>
                <i class="bi bi-people fs-1"></i>
            </div>
            <a href="{{ route('vendors.index') }}" class="btn btn-light btn-sm mt-3 w-100">Manage Vendors</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-white bg-success p-3 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5>Products</h5>
                    <p class="fs-3">{{ \App\Models\Product::count() }}</p>
                </div>
                <i class="bi bi-box-seam fs-1"></i>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-light btn-sm mt-3 w-100">Manage Products</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-white bg-warning p-3 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5>Orders</h5>
                    <p class="fs-3">{{ \App\Models\Order::count() }}</p>
                </div>
                <i class="bi bi-basket fs-1"></i>
            </div>
            <a href="{{ route('orders.index') }}" class="btn btn-light btn-sm mt-3 w-100">Manage Orders</a>
        </div>
    </div>
</div>
@endrole
