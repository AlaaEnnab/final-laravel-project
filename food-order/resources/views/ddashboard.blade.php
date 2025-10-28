@extends('layouts.app')

@section('content')
<div class="dashboard-wrapper d-flex" style="min-height: calc(100vh - 120px);">

    {{-- SIDEBAR --}}
    <aside class="sidebar p-3 bg-white shadow-sm" style="width:260px; border-radius:12px;">
        <div class="brand d-flex align-items-center mb-4">
            <div class="me-2 bg-gradient d-flex align-items-center justify-content-center" 
                 style="width:44px;height:44px;border-radius:10px;color:#fff;">
                <i class="bi bi-basket2"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold">Food Admin</h6>
                <small class="text-muted">Control Panel</small>
            </div>
        </div>

        <nav class="nav flex-column">
            <a class="nav-link mb-2 d-flex align-items-center" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2 me-2"></i> Overview
            </a>
            <a class="nav-link mb-2 d-flex align-items-center" href="{{ route('orders.index') }}">
                <i class="bi bi-receipt me-2"></i> Orders
            </a>
               <a class="nav-link mb-2 d-flex align-items-center" href="{{ route('vendors.index') }}">
                <i class="bi bi-people me-2"></i> Vendors
            </a>
            <a class="nav-link mb-2 d-flex align-items-center" href="{{ route('products.index') }}">
                <i class="bi bi-box-seam me-2"></i> Products
            </a>
            <a class="nav-link mb-2 d-flex align-items-center" href="#">
                <i class="bi bi-people me-2"></i> Customers
            </a>
            <a class="nav-link mb-2 d-flex align-items-center" href="#">
                <i class="bi bi-gear me-2"></i> Settings
            </a>
        </nav>

        <hr>

        <div class="mt-3">
            <small class="text-muted">Quick actions</small>
            <div class="d-grid gap-2 mt-2">
                <a href="{{ route('products.create') }}" class="btn btn-outline-primary btn-sm">+ Add Product</a>
                <a href="{{ route('orders.create') }}" class="btn btn-outline-success btn-sm">+ Create Order</a>
            </div>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="flex-grow-1 ms-4">
        {{-- TOPBAR --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0">Dashboard</h3>
                <small class="text-muted">Welcome back — overview of recent activity</small>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input id="globalSearch" type="text" class="form-control" placeholder="Search orders, products...">
                </div>

                <div>
                    <button class="btn btn-gradient">Export</button>
                </div>
            </div>
        </div>

        {{-- STATS + CHART --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 shadow-sm p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-muted text-uppercase">Total Orders</small>
                            <h4 class="fw-bold mb-0">{{ $totalOrders }}</h4>
                        </div>
                        <div class="bg-light p-2 rounded">
                            <i class="bi bi-receipt" style="font-size:1.3rem;"></i>
                        </div>
                    </div>
                    <div class="text-muted mt-2 small">Since start</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card h-100 shadow-sm p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-muted text-uppercase">Completed</small>
                            <h4 class="fw-bold mb-0">{{ $completedOrders }}</h4>
                        </div>
                        <div class="bg-light p-2 rounded">
                            <i class="bi bi-check2-circle" style="font-size:1.3rem;color:green;"></i>
                        </div>
                    </div>
                    <div class="text-muted mt-2 small">Completed orders</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card h-100 shadow-sm p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-muted text-uppercase">Pending</small>
                            <h4 class="fw-bold mb-0">{{ $pendingOrders }}</h4>
                        </div>
                        <div class="bg-light p-2 rounded">
                            <i class="bi bi-clock-history" style="font-size:1.3rem;color:#f59e0b;"></i>
                        </div>
                    </div>
                    <div class="text-muted mt-2 small">Awaiting fulfillment</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card h-100 shadow-sm p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-muted text-uppercase">Revenue (monthly)</small>
                            <h4 class="fw-bold mb-0">${{ number_format($monthlyRevenue ?? 0,2) }}</h4>
                        </div>
                        <div class="bg-light p-2 rounded">
                            <i class="bi bi-currency-dollar" style="font-size:1.3rem;color:#0ea5e9;"></i>
                        </div>
                    </div>
                    <div class="text-muted mt-2 small">This month</div>
                </div>
            </div>
        </div>

        {{-- CHART + RECENT ORDERS --}}
        <div class="row g-3">
            <div class="col-xl-8">
                <div class="card shadow-sm p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Sales (last 6 months)</h5>
                        <div>
                            <select id="chartRange" class="form-select form-select-sm">
                                <option value="6">6 months</option>
                                <option value="12">12 months</option>
                            </select>
                        </div>
                    </div>
                    <canvas id="salesChart" height="140"></canvas>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card shadow-sm p-3">
                    <h6 class="mb-3">Top Products</h6>
                    <ul class="list-group list-group-flush">
                        @foreach($topProducts as $prod)
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset($prod->image ?? 'default.png') }}" alt="" class="me-3" style="width:50px;height:50px;object-fit:cover;border-radius:8px;">
                                <div>
                                    <div class="fw-semibold">{{ $prod->name }}</div>
                                    <small class="text-muted">${{ number_format($prod->price,2) }}</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold">{{ $prod->sold_count ?? 0 }}</div>
                                <small class="text-muted">sold</small>
                            </div>
                        </li>
                        @endforeach

                        @if($topProducts->isEmpty())
                        <li class="list-group-item text-center text-muted">No products yet</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        {{-- RECENT ORDERS TABLE --}}
        <div class="card shadow-sm mt-4 p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Recent Orders</h5>
                <div class="d-flex gap-2">
                    <select id="filterStatus" class="form-select form-select-sm">
                        <option value="">All status</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                    </select>
                    <button id="clearFilters" class="btn btn-outline-secondary btn-sm">Clear</button>
                </div>
            </div>

            <div class="table-responsive">
                <table id="ordersTable" class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Products</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Created</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr data-status="{{ $order->status }}">
                            <td>{{ $order->id }}</td>
                            <td>
                                <div class="fw-semibold">{{ $order->customer_name }}</div>
                                <small class="text-muted">{{ $order->customer_phone }}</small>
                            </td>
                            <td>
                                @foreach($order->items as $item)
                                    <span class="badge bg-light text-dark me-1">{{ $item->product->name }} x{{ $item->quantity }}</span>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge {{ $order->status == 'pending' ? 'bg-warning text-dark' : 'bg-success' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>${{ number_format($order->total,2) }}</td>
                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                            </td>
                        </tr>
                        @endforeach
                        @if($recentOrders->isEmpty())
                        <tr><td colspan="7" class="text-center text-muted py-3">No recent orders</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- Styles: keep them local to this page --}}
@push('styles')
<style>
    /* layout tweaks */
    .dashboard-wrapper { gap: 20px; padding: 30px; }
    .sidebar { flex-shrink: 0; }
    @media (max-width: 992px) {
        .dashboard-wrapper { flex-direction: column; }
        .sidebar { width: 100%; border-radius: 10px; }
    }

    .bg-gradient {
        background: linear-gradient(45deg, var(--accent-start), var(--accent-end));
        color: #fff;
    }

    .btn-gradient {
        background: linear-gradient(45deg, var(--accent-start), var(--accent-end));
        color: var(--btn-text);
        border: none;
    }
</style>
@endpush

{{-- Scripts: Chart.js + simple filters + search --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- SALES CHART ---
    const ctx = document.getElementById('salesChart').getContext('2d');
    const labels = {!! json_encode($chartLabels ?? []) !!};      // e.g. ['May','Jun',...']
    const dataPoints = {!! json_encode($chartData ?? []) !!};    // e.g. [120, 230, ...]
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Sales',
                data: dataPoints,
                fill: true,
                tension: 0.35,
                borderWidth: 2,
                pointRadius: 3,
                backgroundColor: 'rgba(102,16,242,0.12)',
                borderColor: 'rgba(102,16,242,1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    // switch range (example only: no new data fetch — server should provide more data if needed)
    document.getElementById('chartRange').addEventListener('change', function(){
        // to implement: fetch new ranges via AJAX and update the chart
        // For now we just show a toast or leave as-is
        alert('To change range dynamically implement an AJAX endpoint that returns chart data.');
    });

    // --- FILTERS & SEARCH ---
    const ordersTable = document.getElementById('ordersTable');
    const filterStatus = document.getElementById('filterStatus');
    const globalSearch = document.getElementById('globalSearch');
    const clearFilters = document.getElementById('clearFilters');

    function applyFilters() {
        const q = globalSearch.value.trim().toLowerCase();
        const status = filterStatus.value;
        const rows = ordersTable.querySelectorAll('tbody tr');
        rows.forEach(row => {
            if(row.dataset.status === undefined) return;
            const text = row.innerText.toLowerCase();
            const matchesQuery = q === '' ? true : text.includes(q);
            const matchesStatus = status === '' ? true : row.dataset.status === status;
            row.style.display = (matchesQuery && matchesStatus) ? '' : 'none';
        });
    }

    globalSearch.addEventListener('input', applyFilters);
    filterStatus.addEventListener('change', applyFilters);
    clearFilters.addEventListener('click', function(){
        globalSearch.value = '';
        filterStatus.value = '';
        applyFilters();
    });

});
</script>
@endpush

@endsection
