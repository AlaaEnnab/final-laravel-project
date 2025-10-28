<x-app-layout>
@role('admin')
<div class="container py-5">
    <h2>Add New Vendor</h2>

    <a href="{{ route('vendors.index') }}" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Back</a>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('vendors.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Name *</label>
            <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" class="form-control" name="phone" value="{{ old('phone') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea class="form-control" name="address">{{ old('address') }}</textarea>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="active" value="1" checked>
            <label class="form-check-label">Active</label>
        </div>

        <button class="btn btn-primary"><i class="bi bi-save"></i> Save</button>
    </form>
</div>
@endrole

@unlessrole('admin')
<div class="container py-5">
    <div class="alert alert-warning">
        You do not have permission to create vendors.
    </div>
</div>
@endunlessrole
</x-app-layout>
