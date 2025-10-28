<x-app-layout>
@can('update', $product)
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-5 overflow-hidden">
                <div class="card-header text-center text-white p-4" style="background: linear-gradient(135deg, #6610f2, #6f42c1);">
                    <h2 class="fw-bold mb-0">Edit Product</h2>
                </div>

                <div class="card-body p-5">
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-3">
                            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Product Name</label>
                            <input type="text" name="name" class="form-control form-control-lg rounded-4 shadow-sm" value="{{ $product->name }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Image</label>
                            <input type="file" name="image" class="form-control form-control-lg rounded-4 shadow-sm">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Price</label>
                            <input type="number" name="price" step="0.01" class="form-control form-control-lg rounded-4 shadow-sm" value="{{ $product->price }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control form-control-lg rounded-4 shadow-sm" rows="5">{{ $product->description }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-gradient w-100 btn-lg fw-bold">Update Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="container py-5">
    <div class="alert alert-warning">
        You do not have permission to edit this product.
    </div>
</div>
@endcan
</x-app-layout>
