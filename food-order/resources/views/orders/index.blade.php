<x-app-layout>
@can('viewAny', App\Models\Order::class)
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Orders</h2>
        @can('create', App\Models\Order::class)
        <a href="{{ route('orders.create') }}" class="btn btn-gradient btn-lg">+ New Order</a>
        @endcan
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        @forelse($orders as $order)
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-lg rounded-4 overflow-hidden h-100">
                <div class="card-header bg-gradient text-white text-center">
                    <h5 class="mb-0">Order #{{ $order->id }}</h5>
                </div>
                <div class="card-body">
                    <p><strong>Customer:</strong> {{ $order->customer_name }}<br>{{ $order->customer_phone }}</p>
                    <p><strong>Address:</strong> {{ $order->customer_address ?? 'N/A' }}</p>
                    <p><strong>Products:</strong></p>
                    <ul class="mb-2">
                        @foreach($order->items as $item)
                            <li>{{ $item->product->name }} x {{ $item->quantity }} (${{ number_format($item->subtotal,2) }})</li>
                        @endforeach
                    </ul>
                    <p class="fw-bold"><strong>Total:</strong> ${{ number_format($order->total,2) }}</p>
                    <span class="badge bg-warning text-dark">{{ ucfirst($order->status) }}</span>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5 text-muted">No orders found.</div>
        </div>
        @endforelse
    </div>
</div>
@else
<div class="container py-5">
    <div class="alert alert-warning">
        You do not have permission to view orders.
    </div>
</div>
@endcan

</x-app-layout>
