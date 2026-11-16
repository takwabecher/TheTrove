@extends('layouts.app')

@section('content')
<div class="delivery-wrapper">

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
            <a href="{{ route('delivery.dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('delivery.dashboard') ? 'active' : '' }}">
                <img src="{{ asset('images/mainmini1.png') }}" alt="main">
                <span>Dashboard</span>
            </a>
            <a href="{{ route('delivery.orders') }}" class="sidebar-nav-item {{ request()->routeIs('delivery.orders*') ? 'active' : '' }}">
                <img src="{{ asset('images/ordermini1.png') }}" alt="orders">
                <span>Orders</span>
            </a>
            <a href="{{ route('delivery.order_history') }}" class="sidebar-nav-item {{ request()->routeIs('delivery.order_history') ? 'active' : '' }}">
                <img src="{{ asset('images/timemini1.png') }}" alt="history">
                <span>History</span>
            </a>
            <a href="{{ route('delivery.call_history') }}" class="sidebar-nav-item {{ request()->routeIs('delivery.call_history') ? 'active' : '' }}">
                <img src="{{ asset('images/phonem.png') }}" alt="calls">
                <span>Calls</span>
            </a>
            <a href="{{ route('delivery.settings') }}" class="sidebar-nav-item {{ request()->routeIs('delivery.settings') ? 'active' : '' }}">
                <img src="{{ asset('images/settingmini1.png') }}" alt="calls">
                <span>Settings</span>
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
        </div>
    </aside>

    <!-- MAIN DELIVERY CONTENT -->
    <div class="delivery-content">
        @yield('delivery_content')
    </div>

</div>

<style>
/* DELIVERY WRAPPER */
.delivery-wrapper {
    padding: 140px 40px 80px;
    max-width: 1400px;
    margin: 0 auto;
    min-height: 100vh;
    position: relative;
}

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
    z-index: 1100 !important;
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
    z-index: 998 !important;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 1100 !important;
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
    z-index: 1102 !important;
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

/* DELIVERY CONTENT */
.delivery-content {
    max-width: 1200px;
    margin: 0 auto;
}

/* MOBILE */
@media (max-width: 768px) {
    .delivery-wrapper {
        padding: 120px 20px 60px;
    }
    .slide-sidebar {
        width: 280px;
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
</script>
@endsection