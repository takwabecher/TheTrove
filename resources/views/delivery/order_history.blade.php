@extends('delivery.layout')

@section('title', 'Order History — Delivery')
@section('page_title', 'Order History')

@section('content')
<div class="history-page">

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
    <div class="history-header">
        <div class="history-icon">
            
        </div>
        <h1 class="history-title"><img src="{{ asset('images/approved.png') }}" alt="delivery"> Completed Orders</h1>
        <p class="history-subtitle">All delivered orders</p>
    </div>

    <div class="history-table-wrapper">
        <table class="history-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Client</th>
                    <th>Phone</th>
                    <th>Boutique(s)</th>
                    <th>Total</th>
                    <th>Delivered At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($completedCommandes as $commande)
                @php
                    $orderKey = $commande->id_cmd;
                    $boutiqueNames = $commande->items
                        ->map(fn($item) => $item->produit?->boutique?->name ?? $item->produit?->boutique?->nom)
                        ->filter()
                        ->unique()
                        ->values();
                @endphp
                <tr>
                    <td><span class="order-id">{{ $commande->formatted_id ?? '—' }}</span>
                    <td><span class="client-name">{{ $commande->client?->name ?? 'N/A' }}</span></td>
                    <td>{{ $commande->client?->phone ?? '-' }}</td>
                    <td>
                        @if($boutiqueNames->isEmpty())
                            <span class="text-muted">N/A</span>
                        @else
                            {{ $boutiqueNames->join(', ') }}
                        @endif
                    </td>
                    <td>
                        <span class="price">{{ number_format(($commande->total_price ?? 0) + (auth('delivery')->user()->shipping_rate ?? 0), 2) }} DT</span>
                    </td>
                    <td><span class="date">{{ $commande->delivered_at?->format('M d, Y H:i') ?? 'N/A' }}</span></td>
                    <td>
                        <a href="{{ route('delivery.order_detail', $orderKey) }}" class="btn-view">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="empty-cell">
                        <i class="fas fa-archive"></i>
                        <p>No completed orders yet.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $completedCommandes->links() }}
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
.history-page { max-width: 1400px; margin: 0 auto; padding-top: 120px; }
.history-header { text-align: center; margin-bottom: 40px; }
.history-icon { margin-bottom: 16px; }
.history-icon img { width: 48px; height: 48px; object-fit: contain; opacity: 0.9; }
.history-title { font-family: 'Cinzel', serif; font-size: 1.8rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px; }
.history-subtitle { color: var(--text-secondary); font-size: 0.95rem; }
.history-table-wrapper { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; }
.history-table { width: 100%; border-collapse: collapse; }
.history-table th { text-align: left; padding: 16px 20px; color: var(--text-muted); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border); background: var(--bg-tertiary); }
.history-table td { padding: 16px 20px; border-bottom: 1px solid var(--border); color: var(--text-secondary); font-size: 0.9rem; }
.history-table tr:last-child td { border-bottom: none; }
.history-table tr:hover td { background: rgba(255,255,255,0.02); }
[data-theme="light"] .history-table tr:hover td { background: rgba(0,0,0,0.01); }
.order-id { font-family: 'Cinzel', serif; font-weight: 600; color: var(--accent); }
.client-name { font-weight: 600; color: var(--text-primary); }
.price { color: var(--accent); font-weight: 700; }
.date { color: var(--text-muted); font-size: 0.85rem; }
.btn-view { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: var(--accent-glow); color: var(--accent); border: 1px solid var(--border-hover); border-radius: 10px; text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: all 0.3s ease; }
.btn-view:hover { background: var(--accent); color: var(--bg-primary); }
.empty-cell { text-align: center; padding: 60px 20px !important; color: var(--text-muted); }
.empty-cell i { font-size: 2.5rem; margin-bottom: 12px; display: block; color: var(--accent); }
.empty-cell p { margin: 0; font-weight: 600; color: var(--text-primary); }

@media (max-width: 768px) { .history-table-wrapper { overflow-x: auto; } .history-table { min-width: 800px; } }
</style>
@endsection