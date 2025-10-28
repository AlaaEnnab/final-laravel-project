<x-app-layout>
@can('view', $order)
<div class="container py-5">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2 class="fw-bold text-purple">Order #{{ $order->id }}</h2>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary shadow-sm">
            <i class="bi bi-arrow-left"></i> Back to Orders
        </a>
    </div>

    <!-- Customer & Order Info -->
    <div class="card shadow-lg rounded-4 border-0 mb-4 p-4">
        <div class="row mb-3">
            <div class="col-md-6 mb-2"><strong>Customer Name:</strong> {{ $order->customer_name }}</div>
            <div class="col-md-6 mb-2"><strong>Phone:</strong> {{ $order->customer_phone }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6 mb-2"><strong>Address:</strong> {{ $order->customer_address }}</div>
            <div class="col-md-6 mb-2"><strong>Status:</strong> 
                <span class="badge 
                    @if($order->status == 'pending') bg-warning text-dark
                    @elseif($order->status == 'completed') bg-success
                    @else bg-secondary
                    @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>
        <div><strong>Total:</strong> ${{ number_format($order->total,2) }}</div>
    </div>

    <!-- Products Section -->
    <div class="row g-4">
        @foreach($order->items as $item)
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-lg rounded-4 border-0 product-card h-100">
                <img src="{{ asset($item->product->image ?? 'default.png') }}" 
                     alt="{{ $item->product->name }}" 
                     class="card-img-top" style="height:200px; object-fit:cover; border-top-left-radius:16px; border-top-right-radius:16px;">
                <div class="card-body d-flex flex-column justify-content-between">
                    <h5 class="card-title fw-bold text-purple">{{ $item->product->name }}</h5>
                    <p class="mb-1"><strong>Quantity:</strong> {{ $item->quantity }}</p>
                    <p class="mb-0"><strong>Price:</strong> ${{ number_format($item->product->price,2) }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@else
<div class="container py-5">
    <div class="alert alert-warning text-center">
        You do not have permission to view this order.
    </div>
</div>
@endcan
</x-app-layout>
