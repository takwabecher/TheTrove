@extends('layouts.app')

@section('title', 'Dashboard — TheTrove')

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
            <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-item active">
                <img src="{{ asset('images/mainmini1.png') }}" alt="main">
                <span>Home</span>
            </a>
            <a href="{{ route('admin.categories') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/categoriemini1.png') }}" alt="categorie">
                <span>Categories</span>
            </a>
            <a href="{{ route('admin.boutiques') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/boutiquemini1.png') }}" alt="boutique">
                <span>Vaults</span>
            </a>
            <a href="{{ route('admin.clients') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/profilmini1.png') }}" alt="profil">
                <span>Clients</span>
            </a>
            <a href="{{ route('admin.produits') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/produitmini1.png') }}" alt="item">
                <span>Items</span>
            </a>
            <a href="{{ route('admin.delivery_companies') }}" class="sidebar-nav-item {{ request()->routeIs('admin.delivery_companies*') ? 'active' : '' }}">
                <img src="{{ asset('images/deliverymini.png') }}" alt="delivery">
                <span>Delivery</span>
            </a>
        </div>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <img src="{{ asset('images/adminmini.png') }}" alt="adminmini">
                <div>
                    <div style="font-weight: 600; color: var(--text-primary);">{{ auth()->user()->name }}</div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">Administrator</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 5%;">
        <!-- WELCOME HEADER -->
        <div class="vault-header reveal">
            <div class="vault-header-top">
                <span class="section-label">Administration</span>
            </div>
            <h2 class="vault-title">
                <img src="{{ asset('images/admin.png') }}" alt="admin">
                Welcome, <span style="color: var(--accent); font-weight: 600;">{{ auth()->user()->name }}</span> !
            </h2>
            <p class="vault-subtitle">
                Keep TheTrove organized, secure, and thriving.
            </p>
            <img src="{{ asset('images/staticsmini.png') }}" alt="admin">
        <!-- QUICK STATS ROW -->
        <div class="stats-grid reveal">
            <div class="stats-card">
                <div class="stats-icon orders">
                    <img src="{{ asset('images/boutiquemini1.png') }}" alt="boutiques">
                </div>
                <div class="stats-info">
                    <h3 class="stats-number">{{ $total_boutiques }}</h3>
                    <p class="stats-label">Active Vaults</p>
                </div>
            </div>
            <div class="stats-card">
                <div class="stats-icon orders">
                    <img src="{{ asset('images/profilmini1.png') }}" alt="clients">
                </div>
                <div class="stats-info">
                    <h3 class="stats-number">{{ $total_clients }}</h3>
                    <p class="stats-label">Active Clients</p>
                </div>
            </div>
            <div class="stats-card">
                <div class="stats-icon orders">
                    <img src="{{ asset('images/delivery.png') }}" alt="delivery">
                </div>
                <div class="stats-info">
                    <h3 class="stats-number">{{$total_delivery}}</h3>
                    <p class="stats-label">Active Delivery Companies </p>
                </div>
            </div>
            <div class="stats-card">
                <div class="stats-icon orders">
                    <img src="{{ asset('images/produitmini1.png') }}" alt="items">
                </div>
                <div class="stats-info">
                    <h3 class="stats-number">{{ $approved_items }}</h3>
                    <p class="stats-label">Approved Items</p>
                </div>
            </div>
            <div class="stats-card">
                <div class="stats-icon orders">
                    <img src="{{ asset('images/categoriemini1.png') }}" alt="categories">
                </div>
                <div class="stats-info">
                    <h3 class="stats-number">{{ $total_categories }}</h3>
                    <p class="stats-label">Categories</p>
                </div>
            </div>
            <div class="stats-card">
                <div class="stats-icon orders">
                    <img src="{{ asset('images/ordermini.png') }}" alt="orders">
                </div>
                <div class="stats-info">
                    <h3 class="stats-number">{{ $confirmed_orders }}</h3>
                    <p class="stats-label">Confirmed Orders</p>
                </div>
            </div>
            <div class="stats-card">
                <div class="stats-icon orders">
                    <img src="{{ asset('images/incomemini.png') }}" alt="revenue">
                </div>
                <div class="stats-info">
                    <h3 class="stats-number">{{ number_format($total_revenue, 2) }} DT</h3>
                    <p class="stats-label">Site Revenue ({{ $site_fee_percent }}%)</p>
                </div>
            </div>

            <div class="stats-card">
                <div class="stats-icon orders">
                    <img src="{{ asset('images/incomemini.png') }}" alt="revenue">
                </div>
                <div class="stats-info">
                    <h3 class="stats-number">{{ number_format($total_sales, 2) }} DT</h3>
                    <p class="stats-label">Total Sales</p>
                </div>
            </div>
            <div class="stats-card">
    <div class="stats-icon orders">
        <img src="{{ asset('images/rate.png') }}" alt="rating">
    </div>
    <div class="stats-info">
        @php
            $fullStars = floor($avg_product_rating);
            $halfStar = ($avg_product_rating - $fullStars) >= 0.5;
            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
        @endphp
        <h3 class="stats-number rating-stars">
            @for($i = 0; $i < $fullStars; $i++)
                <i class="fas fa-star"></i>
            @endfor
            @if($halfStar)
                <i class="fas fa-star-half-alt"></i>
            @endif
            @for($i = 0; $i < $emptyStars; $i++)
                <i class="far fa-star"></i>
            @endfor
        </h3>
        <p class="stats-label">Site Rating</p>
    </div>
</div>

        </div>
        <!-- REVENUE BREAKDOWN -->
        <div class="detail-section reveal">
            <div class="detail-header">
                <img src="{{ asset('images/staticsmini.png') }}" alt="revenue">
                <h3>Revenue Breakdown</h3>
            </div>
            <div class="detail-table">
                <div class="detail-row">
                    <span class="detail-label">Total Sales (All Boutiques)</span>
                    <span class="detail-value">{{ number_format($total_sales, 2) }} DT</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Site Fee Percentage</span>
                    <span class="detail-value accent">{{ $site_fee_percent }}%</span>
                </div>
                <div class="detail-row highlight">
                    <span class="detail-label">Site Revenue</span>
                    <span class="detail-value revenue">{{ number_format($total_revenue, 2) }} DT</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Boutiques Keep</span>
                    <span class="detail-value">{{ number_format($total_sales - $total_revenue, 2) }} DT</span>
                </div>
            </div>
        </div>

        <!-- RECENT ORDERS -->
        <div class="detail-section reveal">
            <div class="detail-header">
                <img src="{{ asset('images/ordermini.png') }}" alt="orders">
                <h3>Recent Orders</h3>
            </div>
            <div class="orders-list">
                @forelse($recent_orders as $order)
                    <div class="order-row">
                        <div class="order-info">
                            <span class="order-id">{{ $order->client->name }}</span>
                        </div>
                        <div class="order-details">
                            <span class="order-date">{{ $order->created_at->format('M d, Y') }}</span>
                            <span class="order-amount">{{ number_format($order->total_price, 2) }} DT</span>
                        </div>
                        <span class="order-status confirmed">Confirmed</span>
                    </div>
                @empty
                    <div class="empty-state-small">
                        <p>No recent orders.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- SITE FEE SETTINGS -->
        <div class="detail-section reveal">
            <div class="detail-header">
                <img src="{{ asset('images/settingmini1.png') }}" alt="settings">
                <h3>Site Fee Settings</h3>
            </div>
            <div class="settings-body">
                <div class="current-fee">
                    <span class="fee-label">Current Activity Percentage:</span>
                    <span class="fee-value">{{ $stats->activity_percentage }}%</span>
                </div>
                <form action="{{ route('admin.updateActivity') }}" method="POST" class="settings-form">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Activity Percentage</label>
                        <div class="input-group">
                            <input type="number" class="vault-input" name="activity_percentage" 
                                   value="{{ $stats->activity_percentage }}" min="0" max="100" step="0.1" required>
                            <span class="input-suffix">%</span>
                        </div>
                    </div>
                    <button type="submit" class="vault-submit-btn">
                        <img src="{{ asset('images/save.png') }}" alt="save"> Update Fee
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<style>
/* MENU TOGGLE */
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

.navbar, #navbar, nav.navbar {
    z-index: 90 !important;
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

/* SIDEBAR FOOTER */
.sidebar-footer {
    padding: 20px 24px;
    border-top: 1px solid var(--border);
}

.sidebar-user {
    display: flex;
    align-items: center;
    gap: 12px;
}

.sidebar-user img {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
}

/* VAULT DASHBOARD */
.vault-dashboard {
    padding: 140px 0 80px;
    background: var(--bg-primary);
    min-height: 100vh;
}

/* WELCOME HEADER */
.vault-header {
    text-align: center;
    margin-bottom: 40px;
}

.vault-header-top {
    margin-bottom: 16px;
}

.section-label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--accent);
    font-size: 0.75rem;
    font-weight: 500;
    letter-spacing: 4px;
    text-transform: uppercase;
}

.vault-title {
    font-family: 'Cinzel', serif;
    font-size: 2.4rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 16px;
}

.vault-title img {
    width: 40px;
    height: 40px;
    margin-right: 12px;
    vertical-align: middle;
}

.vault-subtitle {
    font-size: 1.1rem;
    color: var(--text-secondary);
    font-weight: 300;
    max-width: 520px;
    margin: 0 auto;
}

/* STATS GRID */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
    max-width: 1000px;
    margin: 0 auto 40px;
}

.stats-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 28px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.4s ease;
}

.stats-card:hover {
    transform: translateY(-8px);
    border-color: var(--border-hover);
    box-shadow: 0 20px 50px var(--shadow);
}

.stats-card.highlight {
    border-color: var(--accent);
    background: linear-gradient(135deg, var(--bg-card) 0%, var(--accent-glow) 100%);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stats-icon img {
    width: 32px;
    height: 32px;
    object-fit: contain;
}

.stats-icon.orders {
    background: rgba(245, 158, 11, 0.15);
}

.stats-number {
    font-family: 'Cinzel', serif;
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 4px;
}

.stats-label {
    font-size: 0.9rem;
    color: var(--text-muted);
    margin: 0;
}

/* ALERTS GRID */
.alerts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    max-width: 1000px;
    margin: 0 auto 40px;
}

.alert-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.alert-card:hover {
    transform: translateY(-4px);
    border-color: var(--border-hover);
    box-shadow: 0 20px 50px var(--shadow);
}

.alert-card.warning {
    border-left: 3px solid #f59e0b;
}

.alert-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(245, 158, 11, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #f59e0b;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.alert-content h4 {
    font-family: 'Cinzel', serif;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 4px;
}

.alert-content p {
    font-size: 0.85rem;
    color: var(--text-muted);
    margin: 0;
}

.alert-arrow {
    margin-left: auto;
    color: var(--text-muted);
    transition: all 0.3s ease;
}

.alert-card:hover .alert-arrow {
    color: var(--accent);
    transform: translateX(4px);
}

/* DETAIL SECTION */
.detail-section {
    max-width: 800px;
    margin: 0 auto 40px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.4s ease;
}

.detail-section:hover {
    border-color: var(--border-hover);
    box-shadow: 0 20px 50px var(--shadow);
}

.detail-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 24px 28px;
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-hover) 100%);
    border-bottom: 1px solid var(--border);
}

.detail-header img {
    width: 24px;
    height: 24px;
}

.detail-header h3 {
    font-family: 'Cinzel', serif;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.detail-table {
    padding: 16px 28px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 0;
    border-bottom: 1px solid var(--border);
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-row.highlight {
    background: var(--accent-glow);
    margin: 0 -28px;
    padding: 18px 28px;
    border-bottom: none;
}

.detail-row .detail-label {
    font-size: 0.95rem;
    color: var(--text-secondary);
    font-weight: 500;
}

.detail-row .detail-value {
    font-size: 0.95rem;
    color: var(--text-primary);
    font-weight: 600;
}

.detail-row .detail-value.accent {
    color: var(--accent);
}

.detail-row .detail-value.revenue {
    color: var(--accent);
    font-size: 1.1rem;
}

/* ORDERS LIST */
.orders-list {
    padding: 16px 28px;
}

.order-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 0;
    border-bottom: 1px solid var(--border);
}

.order-row:last-child {
    border-bottom: none;
}

.order-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.order-id {
    font-family: 'Cinzel', serif;
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--text-primary);
}

.order-client {
    font-size: 0.85rem;
    color: var(--text-muted);
}

.order-details {
    display: flex;
    flex-direction: column;
    gap: 4px;
    text-align: right;
}

.order-date {
    font-size: 0.8rem;
    color: var(--text-muted);
}

.order-amount {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--accent);
}

.order-status {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.order-status.confirmed {
    background: rgba(16, 185, 129, 0.15);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.empty-state-small {
    text-align: center;
    padding: 40px;
    color: var(--text-muted);
}

/* SETTINGS BODY */
.settings-body {
    padding: 24px 28px;
}

.current-fee {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
    padding: 16px 20px;
    background: var(--accent-glow);
    border-radius: 12px;
    border: 1px solid var(--border-hover);
}

.fee-label {
    font-size: 0.9rem;
    color: var(--text-secondary);
}

.fee-value {
    font-family: 'Cinzel', serif;
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--accent);
}

.settings-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    margin-bottom: 0;
}

.form-label {
    display: block;
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 500;
    margin-bottom: 8px;
}

.input-group {
    display: flex;
    align-items: center;
    gap: 0;
}

.vault-input {
    width: 100%;
    padding: 14px 16px;
    background: var(--bg-tertiary);
    border: 1px solid var(--border);
    border-radius: 12px 0 0 12px;
    color: var(--text-primary);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    outline: none;
    flex: 1;
}

.vault-input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-glow);
}

.input-suffix {
    padding: 14px 20px;
    background: var(--bg-tertiary);
    border: 1px solid var(--border);
    border-left: none;
    border-radius: 0 12px 12px 0;
    color: var(--text-muted);
    font-weight: 600;
    font-size: 0.95rem;
}

.vault-submit-btn {
    width: 100%;
    padding: 14px 24px;
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dim) 100%);
    color: var(--bg-primary);
    border: none;
    border-radius: 12px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.vault-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px var(--accent-glow);
}

.vault-submit-btn img {
    width: 20px;
    height: 20px;
}

/* MOBILE */
@media (max-width: 768px) {
    .slide-sidebar {
        width: 280px;
    }
    .vault-title {
        font-size: 1.8rem;
    }
    .stats-grid {
        grid-template-columns: 1fr;
    }
    .alerts-grid {
        grid-template-columns: 1fr;
    }
    .order-row {
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
    }
    .order-details {
        text-align: left;
    }
    .detail-row {
        flex-direction: column;
        gap: 8px;
        align-items: flex-start;
    }
    .detail-row.highlight {
        margin: 0 -20px;
        padding: 18px 20px;
    }
    .detail-table, .orders-list, .settings-body {
        padding: 16px 20px;
    }
}
.rating-stars {
    color: var(--accent);
    font-size: 1.1rem;
    letter-spacing: 2px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.rating-stars .rating-value {
    font-family: 'Cinzel', serif;
    font-size: 1.3rem;
    color: var(--text-primary);
    margin-left: 4px;
}
</style>

<script>
// SIDEBAR TOGGLE
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

// Scroll reveal
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