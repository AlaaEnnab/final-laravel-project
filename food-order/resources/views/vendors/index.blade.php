<x-app-layout>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h2 class="fw-bold text-purple-gradient">Vendors <small class="text-muted">Manage your vendors</small></h2>

        @role('admin')
        <a href="{{ route('vendors.create') }}" class="btn btn-gradient btn-lg fw-bold shadow-lg mt-2">
            <i class="bi bi-plus-circle"></i> Add New Vendor
        </a>
        @endrole
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-4 shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        @foreach($vendors as $vendor)
        <div class="col-md-6 col-lg-4">
            <div class="card vendor-card shadow-lg rounded-4 p-3 hover-card">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ asset($vendor->avatar ?? 'https://via.placeholder.com/60') }}" 
                         alt="Vendor Avatar" class="rounded-circle me-3 vendor-avatar">
                    <div>
                        <h5 class="mb-1">{{ $vendor->name }}</h5>
                        <p class="mb-0 text-muted small">{{ $vendor->email ?? '-' }}</p>
                        <p class="mb-0 text-muted small">{{ $vendor->phone ?? '-' }}</p>
                    </div>
                </div>
                <div class="mb-3">
                    @if($vendor->active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                </div>
                <div class="d-flex justify-content-between gap-1">
                    <a href="{{ route('vendors.show', $vendor->id) }}" class="btn btn-sm btn-purple action-btn"><i class="bi bi-eye"></i> View</a>

                    @can('update', $vendor)
                        <a href="{{ route('vendors.edit', $vendor->id) }}" class="btn btn-sm btn-purple action-btn"><i class="bi bi-pencil"></i> Edit</a>
                    @endcan

                    @can('delete', $vendor)
                        <form action="{{ route('vendors.destroy', $vendor->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this vendor?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-purple action-btn"><i class="bi bi-trash"></i> Delete</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $vendors->links() }}
    </div>
</div>
</x-app-layout>
