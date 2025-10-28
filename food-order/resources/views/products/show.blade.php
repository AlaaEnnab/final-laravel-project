<x-app-layout>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-5 overflow-hidden">
                <!-- Card Header -->
                <div class="card-header text-center text-white p-4" style="background: linear-gradient(135deg, #6610f2, #6f42c1);">
                    <h2 class="fw-bold mb-0">{{ $product->name }}</h2>
                </div>

                <!-- Card Body -->
                <div class="card-body p-5 text-center">
                    @if(isset($product->image))
                        <img src="{{ asset($product->image) }}" alt="Product Image" class="img-fluid rounded-4 shadow-lg mb-4" style="max-height:250px; object-fit:cover;">
                    @endif

                    <p class="h5 fw-semibold"><span class="text-muted">Price:</span> ${{ number_format($product->price,2) }}</p>
                    
                    <p class="mt-3 text-start"><strong>Description:</strong><br>
                    {{ $product->description ?? 'No description provided.' }}</p>

                    <a href="{{ route('products.index') }}" class="btn btn-gradient btn-lg mt-4">Back to Products</a>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
