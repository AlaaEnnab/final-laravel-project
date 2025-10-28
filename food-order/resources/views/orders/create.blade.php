<x-app-layout>
@can('create', App\Models\Order::class)
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-purple">Create New Order</h2>
        <p class="text-muted">Add products and details to create a new order.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex justify-content-between align-items-center shadow-lg rounded-3">
            <span>{{ session('success') }}</span>
            @if(session('new_order_id'))
                <a href="{{ route('orders.show', session('new_order_id')) }}" class="btn btn-sm btn-primary ms-3">
                    View Order
                </a>
            @endif
        </div>
    @endif

    <div class="row g-4 mt-3">
        <!-- Form Section -->
        <div class="col-lg-7">
            <div class="card shadow-lg rounded-4 border-0 p-4">
                <form action="{{ route('orders.store') }}" method="POST" id="orderForm">
                    @csrf
                    <div class="mb-4">
                        <label for="customer_name" class="form-label fw-semibold">Customer Name</label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control form-control-lg shadow-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="customer_phone" class="form-label fw-semibold">Customer Phone</label>
                        <input type="text" name="customer_phone" id="customer_phone" class="form-control form-control-lg shadow-sm" required>
                    </div>

                    <!-- Products Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Products</label>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="productsTable">
                                <thead class="table-purple text-white">
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>${{ number_format($product->price,2) }}</td>
                                        <td>
                                            <input type="number" name="products[{{ $product->id }}]" class="form-control form-control-sm product-qty" value="0" min="0" data-price="{{ $product->price }}">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-gradient btn-lg shadow-lg"><i class="bi bi-plus-lg"></i> Create Order</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="col-lg-5">
            <div class="card shadow-lg rounded-4 border-0 p-4 bg-light">
                <h5 class="fw-bold text-purple mb-3">Order Summary</h5>
                <ul class="list-group mb-3" id="orderSummary"></ul>
                <div class="fw-bold fs-5 d-flex justify-content-between">
                    <span>Total:</span>
                    <span id="totalAmount">$0.00</span>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="container py-5">
    <div class="alert alert-warning">
        You do not have permission to create orders.
    </div>
</div>
@endcan

<script>
function updateSummary() {
    const rows = document.querySelectorAll('.product-qty');
    const summary = document.getElementById('orderSummary');
    const totalSpan = document.getElementById('totalAmount');
    summary.innerHTML = '';
    let total = 0;
    rows.forEach(input => {
        const qty = parseInt(input.value) || 0;
        if(qty>0){
            const price = parseFloat(input.dataset.price);
            const subtotal = qty*price;
            total+=subtotal;
            const li=document.createElement('li');
            li.className='list-group-item d-flex justify-content-between align-items-center';
            li.textContent=`${input.closest('tr').children[0].textContent} x ${qty}`;
            const span=document.createElement('span');
            span.textContent=`$${subtotal.toFixed(2)}`;
            li.appendChild(span);
            summary.appendChild(li);
        }
    });
    totalSpan.textContent=`$${total.toFixed(2)}`;
}
document.querySelectorAll('.product-qty').forEach(input=>input.addEventListener('input',updateSummary));
updateSummary();
</script>
</x-app-layout>
