@extends('delivery.layout')

@section('title', 'Orders — Delivery')
@section('page_title', 'Orders')

@section('content')
@php
    $statusLabels = [
        'all'       => 'All Orders',
        'pending'   => 'Pending',
        'picked_up' => 'Picked Up',
        'in_transit'=> 'In Transit',
        'delivered' => 'Delivered',
    ];
    $currentLabel = $statusLabels[$status] ?? 'All Orders';
@endphp

<div class="orders-page">
        <button class="menu-toggle" id="menuToggle" aria-label="Open menu">
        <i class="fas fa-bars"></i>
    </button>

    <!-- SIDEBAR OVERLAY -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- SLIDING SIDEBAR -->
    <aside class="slide-sidebar" id="slideSidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="{{ asset('images/TheTroveLogo.png') }}" alt="TheTrove">
                <span>The<span style="color: var(--accent);">Trove</span></span>
            </div>
            <button class="sidebar-close" id="sidebarClose" aria-label="Close menu">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="sidebar-nav">
            <a href="{{ route('delivery.dashboard') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/mainmini1.png') }}" alt="main">
                <span>Dashboard</span>
            </a>
            <a href="{{ route('delivery.orders') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/boxmini1.png') }}" alt="orders">
                <span>Orders</span>
            </a>
            <a href="{{ route('delivery.order_history') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/historymini1.png') }}" alt="history">
                <span>History</span>
            </a>
            <a href="{{ route('delivery.call_history') }}" class="sidebar-nav-item active">
                <img src="{{ asset('images/phonemini1.png') }}" alt="calls">
                <span>Calls</span>
            </a>
        </div>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <img src="{{ asset('images/deliverymini.png') }}" alt="delivery">
                <div>
                    <div style="font-weight: 600; color: var(--text-primary);">{{ auth('delivery')->user()->name }}</div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">Delivery Partner</div>
                </div>
            </div>
            <form action="{{ route('delivery.logout') }}" method="POST" style="margin-top: 12px;">
                @csrf
                <button type="submit" class="sidebar-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="orders-header">
        <h1 class="orders-title"><img src="{{ asset('images/ordermini.png') }}" alt="delivery"> {{ $currentLabel }}</h1>
        <p class="orders-subtitle">Manage and track your assigned orders</p>
    </div>

    <!-- Status Filter Tabs -->
    <div class="filter-tabs">
        @foreach($statusLabels as $key => $label)
        <a href="{{ route('delivery.orders', ['status' => $key]) }}"
           class="filter-tab {{ $status === $key ? 'active' : '' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Client</th>
                    <th>Boutique(s)</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($commandes as $commande)
                @php
                    $orderKey = $commande->id_cmd;
                    $boutiqueNames = $commande->items
                        ->map(fn($item) => $item->produit?->boutique?->name ?? $item->produit?->boutique?->nom)
                        ->filter()
                        ->unique()
                        ->values();
                @endphp
                <tr>
                    <td>
                        <div class="company-cell">
                            <span class="company-name">ORD-{{ $commande->created_at?->format('Y') ?? now()->format('Y') }}-{{ str_pad($orderKey, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="client-cell">
                            <span class="client-name">{{ $commande->client?->name ?? 'N/A' }}</span>
                            <span class="client-phone">{{ $commande->client?->phone ?? '-' }}</span>
                        </div>
                    </td>
                    <td>
                        @if($boutiqueNames->isEmpty())
                            <span class="text-muted">N/A</span>
                        @else
                            <span class="boutique-tags">
                                @foreach($boutiqueNames as $name)
                                <span class="tag">{{ $name }}</span>
                                @endforeach
                            </span>
                        @endif
                    </td>
                    <td>
                        <span class="status-pill {{ $commande->delivery_status }}">
                            <i class="fas fa-circle"></i> {{ ucfirst(str_replace('_', ' ', $commande->delivery_status)) }}
                        </span>
                    </td>
                    <td>{{ $commande->created_at?->format('M d, Y H:i') ?? '-' }}</td>
                    <td>
                        <a href="{{ route('delivery.order_detail', $orderKey) }}" class="btn-icon edit" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="empty-cell">
                        <i class="fas fa-inbox"></i>
                        <p>No orders found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{ $commandes->appends(['status' => $status])->links() }}
    </div>
</div>

<style>
.menu-toggle {
    position: fixed;
    top: 100px;
    left: 24px;
    width: 48px;
    height: 48px;
    border-radius: 12px;
    border: 1px solid var(--border);
    background: var(--bg-card);
    color: var(--accent);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 200;
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.menu-toggle:hover {
    background: var(--accent-glow);
    transform: scale(1.05);
}

/* SIDEBAR OVERLAY */
.sidebar-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    z-index: 998;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.sidebar-overlay.active {
    opacity: 1;
    visibility: visible;
}

/* SLIDING SIDEBAR */
.slide-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 320px;
    height: 100vh;
    background: var(--bg-secondary);
    border-right: 1px solid var(--border);
    z-index: 999;
    transform: translateX(-100%);
    transition: transform 0.4s ease;
    display: flex;
    flex-direction: column;
    padding: 0;
}

.slide-sidebar.active {
    transform: translateX(0);
}

/* SIDEBAR HEADER */
.sidebar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 24px;
    border-bottom: 1px solid var(--border);
}

.sidebar-logo {
    display: flex;
    align-items: center;
    gap: 12px;
}

.sidebar-logo img {
    width: 36px;
    height: 36px;
    object-fit: contain;
}

.sidebar-logo span {
    font-family: 'Cinzel', serif;
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--text-primary);
    letter-spacing: 1px;
}

.sidebar-close {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    border: 1px solid var(--border);
    background: var(--bg-card);
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.sidebar-close:hover {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

/* SIDEBAR NAV */
.sidebar-nav {
    flex: 1;
    padding: 24px 16px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.sidebar-nav-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 16px;
    border-radius: 12px;
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 0.95rem;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.sidebar-nav-item:hover, .sidebar-nav-item.active {
    background: var(--accent-glow);
    color: var(--accent);
    border-color: var(--border-hover);
}

.sidebar-nav-item img {
    width: 20px;
    height: 20px;
    object-fit: contain;
}

/* SIDEBAR FOOTER */
.sidebar-footer {
    padding: 20px 24px;
    border-top: 1px solid var(--border);
}

.sidebar-user {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}

.sidebar-user img {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
}

.sidebar-logout {
    width: 100%;
    padding: 12px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--text-secondary);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.sidebar-logout:hover {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border-color: rgba(239, 68, 68, 0.3);
}
.orders-page { max-width: 1400px; margin: 0 auto; padding-top: 120px; }
.orders-header { text-align: center; margin-bottom: 32px; }
.orders-title { font-family: 'Cinzel', serif; font-size: 1.8rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px; }
.orders-subtitle { color: var(--text-secondary); font-size: 0.95rem; }
.filter-tabs { display: flex; flex-wrap: wrap; gap: 8px; justify-content: center; margin-bottom: 24px; }
.filter-tab { padding: 8px 20px; border-radius: 10px; background: var(--bg-card); border: 1px solid var(--border); color: var(--text-secondary); text-decoration: none; font-size: 0.85rem; font-weight: 500; transition: all 0.3s; }
.filter-tab:hover { border-color: var(--border-hover); color: var(--text-primary); }
.filter-tab.active { background: var(--accent-glow); border-color: var(--accent); color: var(--accent); font-weight: 600; }
.table-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; margin-bottom: 24px; }
.data-table { width: 100%; border-collapse: collapse; }
.data-table th { text-align: left; padding: 16px 20px; color: var(--text-muted); font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border); background: var(--bg-tertiary); }
.data-table td { padding: 16px 20px; border-bottom: 1px solid var(--border); color: var(--text-secondary); font-size: 0.85rem; }
.data-table tr:last-child td { border-bottom: none; }
.data-table tr:hover td { background: rgba(255,255,255,0.02); }
[data-theme="light"] .data-table tr:hover td { background: rgba(0,0,0,0.01); }
.company-cell { display: flex; align-items: center; gap: 12px; }
.company-avatar { width: 36px; height: 36px; border-radius: 10px; background: var(--accent-glow); display: flex; align-items: center; justify-content: center; color: var(--accent); font-weight: 700; font-size: 0.7rem; font-family: 'Cinzel', serif; }
.company-name { font-weight: 600; color: var(--text-primary); font-family: 'Cinzel', serif; font-size: 0.85rem; }
.client-cell { display: flex; flex-direction: column; gap: 2px; }
.client-name { color: var(--text-primary); font-weight: 600; font-size: 0.85rem; }
.client-phone { color: var(--text-muted); font-size: 0.75rem; }
.boutique-tags { display: flex; flex-wrap: wrap; gap: 4px; }
.tag { background: var(--bg-tertiary); color: var(--text-secondary); font-size: 0.75rem; padding: 3px 10px; border-radius: 6px; border: 1px solid var(--border); }
.status-pill { display: inline-flex; align-items: center; gap: 6px; font-size: 0.75rem; font-weight: 600; padding: 5px 12px; border-radius: 20px; border: 1px solid transparent; }
.status-pill.pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; border-color: rgba(245, 158, 11, 0.2); }
.status-pill.picked_up { background: var(--accent-glow); color: var(--accent); border-color: var(--border-hover); }
.status-pill.in_transit { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; border-color: rgba(139, 92, 246, 0.2); }
.status-pill.delivered { background: rgba(16, 185, 129, 0.1); color: #10b981; border-color: rgba(16, 185, 129, 0.2); }
.status-pill.cancelled { background: rgba(239, 68, 68, 0.1); color: #ef4444; border-color: rgba(239, 68, 68, 0.2); }
.status-pill i { font-size: 0.4rem; }
.btn-icon { width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-tertiary); color: var(--text-secondary); display: flex; align-items: center; justify-content: center; cursor: pointer; text-decoration: none; transition: all 0.3s; }
.btn-icon.edit:hover { background: var(--accent-glow); color: var(--accent); border-color: var(--accent); }
.empty-cell { text-align: center; padding: 48px !important; color: var(--text-muted); }
.empty-cell i { font-size: 2rem; margin-bottom: 12px; display: block; color: var(--accent); }
.empty-cell p { margin: 0; font-weight: 600; color: var(--text-primary); }
.pagination-wrap { display: flex; justify-content: center; }
.pagination-wrap nav { display: flex; gap: 6px; }
.pagination-wrap a, .pagination-wrap span { padding: 8px 14px; border-radius: 8px; background: var(--bg-card); border: 1px solid var(--border); color: var(--text-secondary); text-decoration: none; font-size: 0.85rem; transition: all 0.3s; }
.pagination-wrap a:hover { border-color: var(--accent); color: var(--accent); }
.pagination-wrap span[aria-current="page"] { background: var(--accent-glow); border-color: var(--accent); color: var(--accent); font-weight: 600; }
@media (max-width: 768px) { .table-card { overflow-x: auto; } .data-table { min-width: 800px; } }
</style>
@endsection