<!-- resources/views/checkout/thankyou.blade.php -->
<x-app-layout>
<div class="container py-5">
    <div class="card p-4 rounded-3 shadow-sm">
        <h3>Thank you — Order received</h3>
        <p class="mb-2">Order #{{ $order->id }} has been placed.</p>
        <p class="text-muted">Total: ${{ number_format($order->grand_total,2) }}</p>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Continue shopping</a>
    </div>
</div>
</x-app-layout>
