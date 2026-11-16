@extends('layouts.app')

@section('title', 'Boutique Dashboard — TheTrove')

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
            <a href="{{ route('boutique.dashboard') }}" class="sidebar-nav-item active">
                <img src="{{ asset('images/mainmini1.png') }}" alt="mainmini">
                <span>Home</span>
            </a>
            <a href="{{ route('boutique.categories') }}" class="sidebar-nav-item ">
                <img src="{{ asset('images/categoriemini1.png') }}" alt="categoriemini">
                <span>Categories</span>
            </a>
            <a href="{{ route('boutique.produits') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/produitmini1.png') }}" alt="produitmini">
                <span>Items</span>
            </a>
            <a href="{{ route('boutique.commandes') }}" class="sidebar-nav-item">
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

        <!-- WELCOME HEADER -->
        <div class="vault-header reveal">
            <div class="vault-header-top">
                <span class="section-label">Your Vault</span>
            </div>
            <h2 class="vault-title">
                <img src="{{ asset('images/boutiquemini.png') }}" alt="boutiquemini">Welcome, <span style="color: var(--accent); font-weight: 600;">{{ auth()->user()->name }}</span> !
            </h2>
            <p class="vault-subtitle">
                Manage your treasure and track your business.
            </p>
        </div>

        <!-- STATS ROW — ADMIN STYLE -->
        <div class="stats-grid reveal" style="margin-bottom: 40px;">
            <div class="stats-card">
                <div class="stats-icon">
                    <img src="{{ asset('images/produit.png') }}" alt="products">
                </div>
                <div class="stats-info">
                    <h3 class="stats-number">{{ $produits_count }}</h3>
                    <p class="stats-label">All Items</p>
                </div>
            </div>
            <div class="stats-card">
                <div class="stats-icon">
                    <img src="{{ asset('images/order.png') }}" alt="orders">
                </div>
                <div class="stats-info">
                    <h3 class="stats-number">{{ $commandes_count }}</h3>
                    <p class="stats-label">Completed Orders</p>
                </div>
            </div>
            <div class="stats-card">
                <div class="stats-icon">
                    <img src="{{ asset('images/income.png') }}" alt="revenue">
                </div>
                <div class="stats-info">
                    <h3 class="stats-number">{{ number_format($revenue, 2) }} DT</h3>
                    <p class="stats-label">Your Earnings</p>
                </div>
            </div>
            <div class="stats-card">
                <div class="stats-icon">
                    <img src="{{ asset('images/wait.png') }}" alt="pending">
                </div>
                <div class="stats-info">
                    <h3 class="stats-number">{{ $pending_orders }}</h3>
                    <p class="stats-label">Pending Orders</p>
                </div>
            </div>
        </div>

        <!-- RECENT CONFIRMED ORDERS -->
        <div class="detail-section reveal">
            <div class="detail-header">
                <img src="{{ asset('images/ordermini.png') }}" alt="orders">
                <h3>Recent Confirmed Orders</h3>
            </div>
            <div class="orders-list">
                @forelse($recent_orders as $order)
                    <div class="order-row">
                        <div class="order-info">
                            <span class="order-client">{{ $order->client->name ?? 'Unknown Client' }}</span>
                        </div>
                        <div class="order-details">
                            <span class="order-date">{{ $order->created_at->format('M d, Y') }}</span>
                            <span class="order-amount">
    {{ number_format(
        $order->items
            ->where('produit.id_boutique', auth()->id())
            ->sum(fn($item) => $item->price * $item->quantity),
        2
    ) }} DT
</span>
                        </div>
                        <span class="order-status confirmed">Confirmed</span>
                    </div>
                @empty
                    <div class="empty-state-small">
                        <p>No confirmed orders yet.</p>
                    </div>
                @endforelse
            </div> 
        </div>

        <!-- LOGO STAGE WITH ORBIT RINGS -->
        <div class="row" style="display: flex; justify-content: center; margin-top: 60px; margin-bottom: 100px;">
            <div class="hero-logo-stage">
                <div class="hero-glow"></div>
                <div class="orbit-ring orbit-ring-3">
                    <div class="orbit-dot"></div>
                </div>
                <div class="orbit-ring orbit-ring-2">
                    <div class="orbit-dot"></div>
                </div>
                <div class="orbit-ring orbit-ring-1">
                    <div class="orbit-dot"></div>
                </div>
                <div class="hero-logo-main">
                    <img src="{{ asset('images/TheTroveLogo.png') }}" alt="TheTrove Treasure Chest">
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* MENU TOGGLE BUTTON */
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

.menu-toggle:hover {
    background: var(--accent-glow);
    border-color: var(--border-hover);
    transform: scale(1.05);
}

/* SIDEBAR OVERLAY */
.sidebar-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
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
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
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
    font-size: 1rem;
}

.sidebar-close:hover {
    background: rgba(239, 68, 68, 0.1);
    border-color: rgba(239, 68, 68, 0.3);
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

.sidebar-nav-item:hover {
    background: var(--accent-glow);
    color: var(--accent);
    border-color: var(--border-hover);
}

.sidebar-nav-item.active {
    background: var(--accent-glow);
    color: var(--accent);
    border-color: var(--border-hover);
}

.sidebar-nav-item img {
    width: 24px;
    height: 24px;
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
}

/* VAULT DASHBOARD */
.vault-dashboard {
    padding: 140px 0 80px;
    background: var(--bg-primary);
    min-height: 100vh;
}

.vault-header {
    text-align: center;
    margin-bottom: 40px;
}

.vault-header-top {
    margin-bottom: 16px;
}

.vault-title {
    font-family: 'Cinzel', serif;
    font-size: 2.4rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 16px;
    letter-spacing: -0.5px;
}

.vault-subtitle {
    font-size: 1.1rem;
    color: var(--text-secondary);
    font-weight: 300;
    line-height: 1.8;
    max-width: 520px;
    margin: 0 auto;
}

/* ===== ADMIN-STYLE STATS ===== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 24px;
    max-width: 750px;
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
    background: rgba(245, 158, 11, 0.15);
}

.stats-icon img {
    width: 32px;
    height: 32px;
    object-fit: contain;
}

.stats-info {
    flex: 1;
    min-width: 0;
}

.stats-number {
    font-family: 'Cinzel', serif;
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.stats-label {
    font-size: 0.9rem;
    color: var(--text-muted);
    margin: 0;
}

/* ===== DETAIL SECTION (Recent Orders) ===== */
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
    object-fit: contain;
}

.detail-header h3 {
    font-family: 'Cinzel', serif;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.orders-list {
    padding: 16px 28px;
}

.order-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 0;
    border-bottom: 1px solid var(--border);
    gap: 16px;
}

.order-row:last-child {
    border-bottom: none;
}

.order-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
    flex: 1;
    min-width: 0;
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
    flex-shrink: 0;
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
    flex-shrink: 0;
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

/* ===== ORBIT ANIMATIONS ===== */
@keyframes orbitSpin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes logoFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-15px); }
}

@keyframes glowPulse {
    0%, 100% { opacity: 0.6; transform: scale(1); }
    50% { opacity: 1; transform: scale(1.15); }
}

.hero-logo-stage {
    position: relative;
    width: 350px;
    height: 350px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.hero-glow {
    position: absolute;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    background: radial-gradient(circle, var(--accent-glow) 0%, transparent 70%);
    pointer-events: none;
    animation: glowPulse 4s ease-in-out infinite;
}

.orbit-ring {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
}

.orbit-ring-1 {
    width: 280px;
    height: 280px;
    border: 1px solid var(--border);
    animation: orbitSpin 20s linear infinite;
}

.orbit-ring-2 {
    width: 340px;
    height: 340px;
    border: 1px dashed var(--border);
    animation: orbitSpin 30s linear infinite reverse;
}

.orbit-ring-3 {
    width: 400px;
    height: 400px;
    border: 1px solid var(--border);
    animation: orbitSpin 40s linear infinite;
    opacity: 0.5;
}

.orbit-dot {
    position: absolute;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--accent);
    box-shadow: 0 0 10px var(--accent-glow);
}

.orbit-ring-1 .orbit-dot {
    top: -4px;
    left: 50%;
    transform: translateX(-50%);
}

.orbit-ring-2 .orbit-dot {
    bottom: -4px;
    left: 30%;
}

.orbit-ring-3 .orbit-dot {
    top: 50%;
    right: -4px;
    transform: translateY(-50%);
}

.hero-logo-main {
    position: relative;
    z-index: 2;
    width: 200px;
    animation: logoFloat 6s ease-in-out infinite;
}

.hero-logo-main img {
    width: 100%;
    filter: drop-shadow(0 20px 60px var(--accent-glow));
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    .vault-title {
        font-size: 1.8rem;
    }
    .slide-sidebar {
        width: 280px;
    }
    .order-row {
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
    }
    .order-details {
        text-align: left;
    }
    .detail-header, .orders-list {
        padding: 16px 20px;
    }
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