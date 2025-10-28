<x-app-layout>
    <div class="container py-5">
        <h1 class="mb-4 text-white">Dashboard</h1>

        @php
            $role = auth()->user()->role;
        @endphp

        @if($role === 'admin')
            @include('dashboard.admin')
        @elseif($role === 'vendor')
            @include('dashboard.vendor')
        @elseif($role === 'user')
            @include('dashboard.user')
        @endif
    </div>
</x-app-layout>
