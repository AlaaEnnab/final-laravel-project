<x-app-layout>
<div class="container py-5">
    <h2>Vendor Details</h2>

    <a href="{{ route('vendors.index') }}" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Back</a>

    <table class="table table-borderless mb-4">
        <tr>
            <th>Name:</th>
            <td>{{ $vendor->name }}</td>
            <th>Email:</th>
            <td>{{ $vendor->email ?? '-' }}</td>
        </tr>
        <tr>
            <th>Phone:</th>
            <td>{{ $vendor->phone ?? '-' }}</td>
            <th>Active:</th>
            <td>{{ $vendor->active ? 'Yes' : 'No' }}</td>
        </tr>
        <tr>
            <th>Address:</th>
            <td colspan="3">{{ $vendor->address ?? '-' }}</td>
        </tr>
    </table>

    <h4>Products</h4>
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vendor->products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>${{ number_format($product->price,2) }}</td>
                <td>
                    @can('detachProduct', $vendor)
                    <form action="{{ route('vendors.detachProduct', $vendor->id) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Detach this product?')">
                            <i class="bi bi-x-circle"></i> Detach
                        </button>
                    </form>
                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @can('attachProduct', $vendor)
    <h5 class="mt-4">Attach Product</h5>
    <form action="{{ route('vendors.attachProduct', $vendor->id) }}" method="POST" class="d-flex gap-2">
        @csrf
        <select name="product_id" class="form-select" required>
            @foreach(App\Models\Product::whereNull('vendor_id')->get() as $product)
                <option value="{{ $product->id }}">{{ $product->name }} - ${{ number_format($product->price,2) }}</option>
            @endforeach
        </select>
        <button class="btn btn-success"><i class="bi bi-plus-circle"></i> Attach</button>
    </form>
    @endcan
</div>
</x-app-layout>
