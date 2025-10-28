<x-app-layout>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <!-- زر إضافة منتج يظهر فقط للمسموح لهم -->
        @can('create', App\Models\Product::class)
        <a href="{{ route('products.create') }}" class="btn btn-gradient btn-lg fw-bold shadow-lg">+ Add Product</a>
        @endcan
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-4 shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card shadow-lg border-0 rounded-5 overflow-hidden hover-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="table-dark text-uppercase">
                        <tr>
                            <th>#ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr class="table-row-hover">
                            <td>{{ $product->id }}</td>
                            <td>
                                @if($product->image)
                                    <img src="{{ asset($product->image) }}" alt="Product Image" class="rounded-circle shadow-sm product-img">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>${{ number_format($product->price,2) }}</td>
                            <td>{{ Str::limit($product->description,50) }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-purple action-btn">View</a>

                                    @can('update', $product)
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-purple action-btn">Edit</a>
                                    @endcan

                                    @can('delete', $product)
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-purple action-btn">Delete</button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-3">No products found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
