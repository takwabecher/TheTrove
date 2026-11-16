@extends('layouts.app')

@section('title', 'Commandes — TheTrove')

@section('content')
<div class="vault-dashboard">

    <!-- MOBILE MENU BUTTON -->
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
            <a href="{{ route('boutique.dashboard') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/mainmini1.png') }}" alt="mainmini">
                <span>Home</span>
            </a>
            <a href="{{ route('boutique.categories') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/categoriemini1.png') }}" alt="categoriemini">
                <span>Categories</span>
            </a>
            <a href="{{ route('boutique.produits') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/produitmini1.png') }}" alt="produitmini">
                <span>Items</span>
            </a>
            <a href="{{ route('boutique.commandes') }}" class="sidebar-nav-item active">
                <img src="{{ asset('images/ordermini1.png') }}" alt="ordermini">
                <span>Orders</span>
            </a>
            <a href="{{ route('boutique.settings') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/settingmini1.png') }}" alt="settingmini">
                <span>Settings</span>
            </a>
        </div>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <img src="{{ asset('images/boutiquemini.png') }}" alt="boutiquemini">
                <div>
                    <div style="font-weight: 600; color: var(--text-primary);">{{ auth()->user()->name }}</div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">Boutique Owner</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 5%;">

        <!-- HEADER -->
        <div class="vault-header reveal">
            <h2 class="vault-title">
                <img src="{{ asset('images/ordermini.png') }}" alt="orders"> My <span style="color: var(--accent); font-weight: 600;">Orders</span>
            </h2>
            <p class="vault-subtitle">
                Manage incoming orders and track your sales.
            </p>
        </div>

        @php
            $myBoutiqueId = auth()->id();

            foreach ($commandes as $cmd) {
                $cmd->invoice_number = 'INV-' . date('Y') . '-' . strtoupper(str_pad($cmd->id_cmd, 5, '0', STR_PAD_LEFT));
                $cmd->my_items = $cmd->items->filter(function($ligne) use ($myBoutiqueId) {
                    return $ligne->produit && $ligne->produit->id_boutique == $myBoutiqueId;
                });
                $cmd->my_total = $cmd->my_items->sum(function($ligne) {
                    return $ligne->price * $ligne->quantity;
                });
            }
        @endphp

        <!-- FILTER TABS -->
        <div class="filter-tabs reveal" style="margin-bottom: 30px;">
            <button class="filter-tab active" data-filter="all" onclick="filterOrders('all')">
                <img src="{{ asset('images/ordermini1.png') }}" alt="orders"> All
            </button>
            <button class="filter-tab" data-filter="pending" onclick="filterOrders('pending')">
                <img src="{{ asset('images/waitmini.png') }}" alt="wait"> Pending
            </button>
            <button class="filter-tab" data-filter="confirmed" onclick="filterOrders('confirmed')">
                <img src="{{ asset('images/approvedmini.png') }}" alt="approved">  Confirmed
            </button>
        </div>

        <!-- ORDERS LIST -->
        <div class="orders-list" id="ordersList">
            @forelse($commandes as $cmd)
            @if($cmd->my_items->isEmpty()) @continue @endif
            <div class="order-card reveal" data-status="{{ $cmd->status }}">
                <div class="order-header">
                    <div class="order-header-left">
                        <div class="order-number">{{ $cmd->invoice_number }}</div>
                        <div class="order-status-badge {{ $cmd->status }}">
                            @if($cmd->status == 'confirmed')
                                Confirmed
                            @elseif($cmd->status == 'pending')
                                Pending
                            @endif
                        </div>
                    </div>
                    <div class="order-header-right">
                        <div class="order-date">
                            {{ \Carbon\Carbon::parse($cmd->date_cmd)->format('d M Y, H:i') ?? 'N/A' }}
                        </div>
                        <div class="order-total">{{ number_format($cmd->my_total, 2) }} <span>DT</span></div>
                    </div>
                </div>

                <div class="order-body">
                    <div class="order-client">
                        <div class="client-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="client-info">
                            <div class="client-name">{{ $cmd->client->name ?? 'Unknown Client' }}</div>
                            <div class="client-email">{{ $cmd->client->email ?? '' }}</div>
                        </div>
                    </div>

                    <div class="order-items">
                        @foreach($cmd->my_items as $ligne)
                        <div class="order-item">
                            <div class="item-product">
                                <img src="{{ $ligne->produit->images->first() ? asset('storage/' . $ligne->produit->images->first()->path) : asset('images/placeholder-product.png') }}" 
                                     alt="{{ $ligne->produit->nom }}" class="item-image"
                                     onerror="this.src='{{ asset('images/placeholder-product.png') }}'">
                                <div class="item-details">
                                    <div class="item-name">{{ $ligne->produit->nom ?? 'Produit supprimé' }}</div>
                                    <div class="item-price">{{ number_format($ligne->price, 2) }} DT x {{ $ligne->quantity }}</div>
                                </div>
                            </div>
                            <div class="item-total">{{ number_format($ligne->price * $ligne->quantity, 2) }} DT</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                @if($cmd->status === 'pending')
                <div class="order-actions">
                    <form action="{{ route('boutique.confirmCommande', $cmd->id_cmd) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-confirm">
                            <img src="{{ asset('images/accept.png') }}" alt="accept"> Confirm
                        </button>
                    </form>
                    <form action="{{ route('boutique.declineCommande', $cmd->id_cmd) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-decline">
                            <img src="{{ asset('images/cancel.png') }}" alt="cancel"> Refuse
                        </button>
                    </form>
                </div>
                @else
                <div class="order-actions completed">
                    <span class="action-status {{ $cmd->status }}">
                        @if($cmd->status == 'confirmed')
                            Order confirmed on {{ \Carbon\Carbon::parse($cmd->updated_at)->format('d M Y') }}
                        @endif
                    </span>
                </div>
                @endif
            </div>
            @empty
            <div class="empty-state reveal">
                <div class="empty-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <h3>No Orders Yet</h3>
                <p>Orders will appear here when customers start buying your products.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<style>
/* ===== SIDEBAR & LAYOUT ===== */
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
    backdrop-filter: blur(10px);
}
.menu-toggle:hover { background: var(--accent-glow); border-color: var(--border-hover); transform: scale(1.05); }

.sidebar-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(4px);
    z-index: 998;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}
.sidebar-overlay.active { opacity: 1; visibility: visible; }

.slide-sidebar {
    position: fixed;
    top: 0; left: 0;
    width: 320px; height: 100vh;
    background: var(--bg-secondary);
    border-right: 1px solid var(--border);
    z-index: 999;
    transform: translateX(-100%);
    transition: transform 0.4s cubic-bezier(0.16,1,0.3,1);
    display: flex; flex-direction: column; padding: 0;
}
.slide-sidebar.active { transform: translateX(0); }
.navbar, #navbar, nav.navbar, .navbar-expand, .navbar-expand-md, .navbar-expand-lg, .navbar-expand-xl, .navbar-expand-xxl, .navbar-expand-sm, .navbar-expand-xs {
    z-index: 90 !important;
}

.sidebar-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 24px; border-bottom: 1px solid var(--border);
}
.sidebar-logo { display: flex; align-items: center; gap: 12px; }
.sidebar-logo img { width: 36px; height: 36px; object-fit: contain; }
.sidebar-logo span { font-family: 'Cinzel', serif; font-size: 1.2rem; font-weight: 700; color: var(--text-primary); letter-spacing: 1px; }
.sidebar-close {
    width: 40px; height: 40px; border-radius: 10px; border: 1px solid var(--border);
    background: var(--bg-card); color: var(--text-secondary); display: flex;
    align-items: center; justify-content: center; cursor: pointer;
    transition: all 0.3s ease; font-size: 1rem;
}
.sidebar-close:hover { background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.3); color: #ef4444; }

.sidebar-nav { flex: 1; padding: 24px 16px; display: flex; flex-direction: column; gap: 8px; }
.sidebar-nav-item {
    display: flex; align-items: center; gap: 14px; padding: 14px 16px;
    border-radius: 12px; color: var(--text-secondary); text-decoration: none;
    font-size: 0.95rem; font-weight: 500; transition: all 0.3s ease;
    border: 1px solid transparent;
}
.sidebar-nav-item:hover, .sidebar-nav-item.active {
    background: var(--accent-glow); color: var(--accent); border-color: var(--border-hover);
}
.sidebar-nav-item img { width: 24px; height: 24px; object-fit: contain; }

.sidebar-footer { padding: 20px 24px; border-top: 1px solid var(--border); }
.sidebar-user { display: flex; align-items: center; gap: 12px; }
.sidebar-user img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }

.vault-dashboard { padding: 140px 0 80px; background: var(--bg-primary); min-height: 100vh; }

.vault-header { text-align: center; margin-bottom: 60px; }
.vault-header-top { margin-bottom: 16px; }
.vault-title { font-family: 'Cinzel', serif; font-size: 2.4rem; font-weight: 600; color: var(--text-primary); margin-bottom: 16px; letter-spacing: -0.5px; }
.vault-subtitle { font-size: 1.1rem; color: var(--text-secondary); font-weight: 300; line-height: 1.8; max-width: 520px; margin: 0 auto; }

.section-label {
    display: inline-block; padding: 8px 20px; border-radius: 20px;
    background: var(--accent-glow); border: 1px solid var(--border-hover);
    color: var(--accent); font-size: 0.8rem; font-weight: 600;
    letter-spacing: 2px; text-transform: uppercase; font-family: 'Cinzel', serif;
}

/* ===== FILTER TABS ===== */
.filter-tabs { display: flex; gap: 12px; flex-wrap: wrap; }
.filter-tab {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 20px; border-radius: 10px; border: 1px solid var(--border);
    background: var(--bg-card); color: var(--text-secondary); font-size: 0.9rem;
    font-weight: 500; cursor: pointer; transition: all 0.3s ease; font-family: 'DM Sans', sans-serif;
}
.filter-tab:hover { border-color: var(--border-hover); color: var(--text-primary); }
.filter-tab.active {
    background: var(--accent-glow); border-color: var(--accent);
    color: var(--accent);
}
.filter-tab i { font-size: 0.8rem; }

/* ===== ORDERS LIST ===== */
.orders-list { display: flex; flex-direction: column; gap: 24px; margin-bottom: 40px; }

.order-card {
    background: var(--bg-card); border: 1px solid var(--border); border-radius: 20px;
    overflow: hidden; transition: all 0.4s ease;
}
.order-card:hover { border-color: var(--border-hover); box-shadow: 0 20px 50px var(--shadow); }
.order-card.hidden { display: none; }

.order-header {
    display: flex; justify-content: space-between; align-items: center;
    padding: 20px 24px; border-bottom: 1px solid var(--border);
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-hover) 100%);
}
.order-header-left { display: flex; align-items: center; gap: 16px; }
.order-number {
    font-family: 'Cinzel', serif; font-size: 1.1rem; font-weight: 700; color: var(--accent);
}
.order-status-badge {
    padding: 6px 14px; border-radius: 20px; font-size: 0.75rem;
    font-weight: 600; display: flex; align-items: center; gap: 6px;
}
.order-status-badge.confirmed { background: rgba(16,185,129,0.15); color: #10b981; border: 1px solid rgba(16,185,129,0.3); }
.order-status-badge.pending { background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); }
.order-status-badge.declined { background: rgba(239,68,68,0.15); color: #ef4444; border: 1px solid rgba(239,68,68,0.3); }

.order-header-right { text-align: right; }
.order-date { font-size: 0.85rem; color: var(--text-muted); margin-bottom: 4px; }
.order-date i { margin-right: 6px; }
.order-total { font-family: 'Cinzel', serif; font-size: 1.4rem; font-weight: 700; color: var(--accent); }
.order-total span { font-size: 0.9rem; color: var(--accent-dim); font-weight: 400; }

.order-body { padding: 24px; }

.order-client {
    display: flex; align-items: center; gap: 14px;
    padding: 16px; border-radius: 14px; background: var(--bg-tertiary);
    margin-bottom: 20px;
}
.client-avatar {
    width: 48px; height: 48px; border-radius: 50%;
    background: var(--accent-glow); border: 1px solid var(--border-hover);
    display: flex; align-items: center; justify-content: center;
    color: var(--accent); font-size: 1.2rem;
}
.client-name { font-weight: 600; color: var(--text-primary); font-size: 1rem; }
.client-email { font-size: 0.85rem; color: var(--text-muted); }

.order-items { display: flex; flex-direction: column; gap: 12px; }
.order-item {
    display: flex; justify-content: space-between; align-items: center;
    padding: 14px; border-radius: 12px; border: 1px solid var(--border);
    background: var(--bg-tertiary); transition: all 0.3s ease;
}
.order-item:hover { border-color: var(--border-hover); }
.item-product { display: flex; align-items: center; gap: 14px; }
.item-image {
    width: 56px; height: 56px; border-radius: 10px; object-fit: cover;
    border: 1px solid var(--border);
    flex-shrink: 0;
}
.item-name { font-weight: 600; color: var(--text-primary); font-size: 0.95rem; }
.item-price { font-size: 0.85rem; color: var(--text-muted); }
.item-total { font-family: 'Cinzel', serif; font-weight: 700; color: var(--accent); font-size: 1rem; }

.order-actions {
    display: flex; gap: 12px;
    padding: 20px 24px; border-top: 1px solid var(--border);
    background: var(--bg-tertiary);
}
.order-actions.completed { justify-content: flex-end; }

.btn-confirm {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 12px 24px; border-radius: 12px; border: none;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white; font-weight: 600; font-size: 0.9rem; cursor: pointer;
    transition: all 0.3s ease; font-family: 'DM Sans', sans-serif;
    box-shadow: 0 4px 15px rgba(16,185,129,0.3);
}
.btn-confirm:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(16,185,129,0.4); }

.btn-decline {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 12px 24px; border-radius: 12px; border: 1px solid var(--border);
    background: transparent; color: var(--text-secondary); font-weight: 600;
    font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease;
    font-family: 'DM Sans', sans-serif;
}
.btn-decline:hover { background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.3); color: #ef4444; }

.action-status { font-size: 0.9rem; display: flex; align-items: center; gap: 8px; }
.action-status.confirmed { color: #10b981; }
.action-status.declined { color: #ef4444; }
.action-status i { font-size: 1.1rem; }

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center; padding: 80px 20px;
    background: var(--bg-card); border: 1px solid var(--border); border-radius: 20px;
}
.empty-icon { font-size: 4rem; color: var(--accent); margin-bottom: 20px; opacity: 0.5; }
.empty-state h3 { font-family: 'Cinzel', serif; color: var(--text-primary); margin-bottom: 12px; }
.empty-state p { color: var(--text-secondary); }

/* ===== ANIMATIONS ===== */
.reveal { opacity: 0; transform: translateY(30px); transition: all 0.6s ease; }
.reveal.active { opacity: 1; transform: translateY(0); }

@media (max-width: 768px) {
    .order-header { flex-direction: column; align-items: flex-start; gap: 12px; }
    .order-header-right { text-align: left; width: 100%; }
    .order-actions { flex-direction: column; }
    .btn-confirm, .btn-decline { width: 100%; justify-content: center; }
    .order-item { flex-direction: column; align-items: flex-start; gap: 8px; }
    .item-total { align-self: flex-end; }
}
</style>

<script>
// ===== SIDEBAR TOGGLE =====
const menuToggle = document.getElementById('menuToggle');
const sidebarClose = document.getElementById('sidebarClose');
const slideSidebar = document.getElementById('slideSidebar');
const sidebarOverlay = document.getElementById('sidebarOverlay');

function openSidebar() {
    slideSidebar.classList.add('active');
    sidebarOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeSidebar() {
    slideSidebar.classList.remove('active');
    sidebarOverlay.classList.remove('active');
    document.body.style.overflow = '';
}
menuToggle.addEventListener('click', openSidebar);
sidebarClose.addEventListener('click', closeSidebar);
sidebarOverlay.addEventListener('click', closeSidebar);
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && slideSidebar.classList.contains('active')) {
        closeSidebar();
    }
});

// ===== FILTER ORDERS =====
function filterOrders(filter) {
    const cards = document.querySelectorAll('.order-card');
    const tabs = document.querySelectorAll('.filter-tab');

    tabs.forEach(tab => {
        tab.classList.toggle('active', tab.dataset.filter === filter);
    });

    cards.forEach(card => {
        if (filter === 'all' || card.dataset.status === filter) {
            card.classList.remove('hidden');
        } else {
            card.classList.add('hidden');
        }
    });
}

// ===== SCROLL REVEAL =====
document.addEventListener('DOMContentLoaded', function() {
    const revealElements = document.querySelectorAll('.reveal');
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('active');
        });
    }, { threshold: 0.1 });
    revealElements.forEach(el => revealObserver.observe(el));
});
</script>
@endsection