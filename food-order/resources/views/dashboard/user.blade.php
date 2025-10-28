@role('user')
@php $user = auth()->user(); @endphp
<div class="row g-4">
    <div class="col-md-6">
        <div class="card text-white bg-info p-3 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5>My Orders</h5>
                    <p class="fs-3">{{ $user->orders()->count() }}</p>
                </div>
                <i class="bi bi-basket fs-1"></i>
            </div>
            <a href="{{ route('orders.index') }}" class="btn btn-light btn-sm mt-3 w-100">View Orders</a>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card text-white bg-success p-3 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5>New Order</h5>
                    <p>Create a new food order</p>
                </div>
                <i class="bi bi-plus-circle fs-1"></i>
            </div>
            <a href="{{ route('orders.create') }}" class="btn btn-light btn-sm mt-3 w-100">Create Order</a>
        </div>
    </div>
</div>
@endrole
