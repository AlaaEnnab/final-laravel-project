<x-app-layout>
@can('create', App\Models\Product::class)
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-5 overflow-hidden">
                <div class="card-header text-center text-white p-4" style="background: linear-gradient(135deg, #6610f2, #6f42c1);">
                    <h2 class="fw-bold mb-0">Add New Product</h2>
                </div>

                <div class="card-body p-5">
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-3">
                            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Price</label>
                            <input type="number" name="price" step="0.01" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="4"></textarea>
                        </div>

                        <button type="submit" class="btn btn-gradient w-100 btn-lg fw-bold">Add Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="container py-5">
    <div class="alert alert-warning">
        You do not have permission to create products.
    </div>
</div>
@endcan
</x-app-layout>
