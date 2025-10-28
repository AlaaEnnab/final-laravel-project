<x-app-layout>
<div class="container py-5 d-flex justify-content-center">
  <div class="card shadow-lg rounded-4 p-4" style="max-width: 800px; width: 100%;">

    <h3 class="mb-3 text-center">Checkout</h3>
    <p class="text-muted text-center mb-4">راجع معلوماتك وأكمل الطلب.</p>

    <form action="{{ route('checkout.store') }}" method="POST">
      @csrf

      <!-- Customer Info -->
      <div class="mb-4">
        <label class="form-label fw-semibold">Phone</label>
        <input type="text" name="customer_phone" class="form-control" 
               value="{{ old('customer_phone', auth()->user()->phone ?? '') }}" required>
      </div>

      <div class="mb-4">
        <label class="form-label fw-semibold">Address</label>
        <input type="text" name="customer_address" class="form-control" 
               value="{{ old('customer_address', auth()->user()->address ?? '') }}" required>
      </div>

      <!-- Products -->
      <h5 class="mb-3">Products</h5>
      <div class="mb-4">
        @foreach($products as $i => $product)
        <div class="d-flex align-items-center justify-content-between p-3 border rounded mb-2 hover-shadow">
          <div class="d-flex align-items-center">
            <img src="{{ $product->image ? asset($product->image) : 'https://via.placeholder.com/56' }}" 
                 alt="" class="rounded" style="width:56px;height:56px;object-fit:cover;margin-right:12px;">
            <div>
              <div class="fw-semibold">{{ $product->name }}</div>
              <div class="text-muted small">${{ number_format($product->price,2) }}</div>
            </div>
          </div>
          <div>
            <input type="hidden" name="products[]" value="{{ $product->id }}">
            <input type="number" name="quantities[]" class="form-control text-center product-quantity" 
                   min="0" value="{{ old('quantities.'.$i, 0) }}" style="width:72px;">
          </div>
        </div>
        @endforeach
      </div>

      <!-- Payment Method -->
      <div class="mb-4">
        <label class="form-label fw-semibold">Payment method</label>
        <select name="payment_method" class="form-select">
          <option value="cod">Cash on Delivery (COD)</option>
          <option value="card">Card / Online</option>
          <option value="bank_transfer">Bank Transfer</option>
        </select>
      </div>

      <!-- Order Summary -->
      <h5 class="mb-3">Order Summary</h5>
      <div id="order-summary" class="p-3 border rounded mb-4 bg-light">
        <!-- JavaScript سيملأ هذا القسم تلقائيًا -->
      </div>

      <!-- Buttons -->
      <div class="d-flex justify-content-between">
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">← Continue shopping</a>
        <button type="submit" class="btn btn-primary">Place Order</button>
      </div>
    </form>

  </div>
</div>

<style>
.card { border: none; }
.btn-primary { background: #6f42c1; border-color: #6f42c1; }
.btn-outline-secondary { border-radius: 6px; }
.hover-shadow:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); transition: 0.3s; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantities = document.querySelectorAll('.product-quantity');
    const products = @json($products);
    const summaryDiv = document.querySelector('#order-summary');

    function updateSummary() {
        let itemsTotal = 0;
        summaryDiv.innerHTML = '';

        quantities.forEach((input, i) => {
            const qty = parseInt(input.value) || 0;
            if(qty > 0){
                const lineTotal = products[i].price * qty;
                itemsTotal += lineTotal;

                const div = document.createElement('div');
                div.className = 'd-flex justify-content-between small mb-1';
                div.innerHTML = `<div>${products[i].name} x ${qty}</div><div>$${lineTotal.toFixed(2)}</div>`;
                summaryDiv.appendChild(div);
            }
        });

        if(itemsTotal === 0){
            summaryDiv.innerHTML = '<p class="text-muted small mb-0">لم يتم اختيار أي منتج بعد.</p>';
        } else {
            const hr = document.createElement('hr');
            summaryDiv.appendChild(hr);

            const delivery = (itemsTotal >= 50 ? 0 : 5);

            const itemsTotalDiv = document.createElement('div');
            itemsTotalDiv.className = 'd-flex justify-content-between';
            itemsTotalDiv.innerHTML = `<div class="text-muted">Items total</div><div class="fw-semibold">$${itemsTotal.toFixed(2)}</div>`;
            summaryDiv.appendChild(itemsTotalDiv);

            const deliveryDiv = document.createElement('div');
            deliveryDiv.className = 'd-flex justify-content-between';
            deliveryDiv.innerHTML = `<div class="text-muted">Delivery</div><div class="fw-semibold">$${delivery.toFixed(2)}</div>`;
            summaryDiv.appendChild(deliveryDiv);

            const hr2 = document.createElement('hr');
            summaryDiv.appendChild(hr2);

            const grandDiv = document.createElement('div');
            grandDiv.className = 'd-flex justify-content-between';
            grandDiv.innerHTML = `<div class="h5">Grand Total</div><div class="h5">$${(itemsTotal + delivery).toFixed(2)}</div>`;
            summaryDiv.appendChild(grandDiv);
        }
    }

    quantities.forEach(input => input.addEventListener('input', updateSummary));
    updateSummary();
});
</script>

</x-app-layout>
