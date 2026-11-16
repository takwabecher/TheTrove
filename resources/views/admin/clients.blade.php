@extends('layouts.app')

@section('title', 'Manage Clients — TheTrove')

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
            <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-item">
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
            <a href="{{ route('admin.clients') }}" class="sidebar-nav-item active">
                <img src="{{ asset('images/profilmini1.png') }}" alt="profil">
                <span>Clients</span>
            </a>
            <a href="{{ route('admin.produits') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/produitmini1.png') }}" alt="item">
                <span>Items</span>
            </a>
            <a href="{{ route('admin.delivery_companies') }}" class="sidebar-nav-item">
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
                <span class="section-label">Management</span>
            </div>
            <h2 class="vault-title">
                <img src="{{ asset('images/profilmini.png') }}" alt="profilmini">
                Clients
            </h2>
            <p class="vault-subtitle">
                Manage all treasure hunters on your marketplace.
            </p>
        </div>

        <!-- TABS -->
        <div class="clients-tabs reveal">
            <button class="tab-btn active" data-tab="pending">
                <img src="{{ asset('images/waitmini.png') }}" alt="waitmini">
                <span>Pending</span>
                <span class="tab-count">{{ $pendingClients->count() }}</span>
            </button>
            <button class="tab-btn" data-tab="approved">
                <img src="{{ asset('images/approvedmini.png') }}" alt="approvedmini">
                <span>Approved</span>
                <span class="tab-count">{{ $approvedClients->count() }}</span>
            </button>
            <button class="tab-btn" data-tab="suspended">
                <img src="{{ asset('images/suspendmini.png') }}" alt="suspendmini">
                <span>Suspended</span>
                <span class="tab-count">{{ $suspendedClients->count() }}</span>
            </button>
        </div>

        <!-- PENDING CLIENTS -->
        <div class="tab-content active" id="pending">
            <div class="clients-grid">
                @forelse($pendingClients as $client)
                    <div class="client-card pending">
                        <div class="client-info">
                            <div class="client-name">{{ $client->name }}</div>
                            <div class="client-email">{{ $client->email }}</div>
                            <div class="client-date">Joined {{ $client->created_at->format('M d, Y') }}</div>
                        </div>
                        <div class="client-actions">
                            <form action="{{ route('admin.clients.approve', $client->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="action-btn approve">
                                    <img src="{{ asset('images/accept.png') }}" alt="accept"> Approve
                                </button>
                            </form>
                            <form action="{{ route('admin.clients.decline', $client->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                <button type="submit" class="action-btn decline">
                                    <img src="{{ asset('images/cancel.png') }}" alt="cancel"> Decline
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <p>No pending clients.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- APPROVED CLIENTS -->
        <div class="tab-content" id="approved">
            <div class="clients-grid">
                @forelse($approvedClients as $client)
                    <div class="client-card approved">
                        <div class="client-info">
                            <div class="client-name">{{ $client->name }}</div>
                            <div class="client-email">{{ $client->email }}</div>
                            <div class="client-date">Joined {{ $client->created_at->format('M d, Y') }}</div>
                        </div>
                        <div class="client-actions">
                            <form action="{{ route('admin.clients.suspend', $client->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="action-btn suspend">
                                    <img src="{{ asset('images/suspended.png') }}" alt="suspended"> Suspend
                                </button>
                            </form>
                            <form action="{{ route('admin.clients.delete', $client->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure? This cannot be undone.')">
                                @csrf
                                <button type="submit" class="action-btn delete">
                                    <img src="{{ asset('images/delete.png') }}" alt="delete"> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <p>No approved clients.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- SUSPENDED CLIENTS -->
        <div class="tab-content" id="suspended">
            <div class="clients-grid">
                @forelse($suspendedClients as $client)
                    <div class="client-card suspended">
                        <div class="client-info">
                            <div class="client-name">{{ $client->name }}</div>
                            <div class="client-email">{{ $client->email }}</div>
                            <div class="client-date">Joined {{ $client->created_at->format('M d, Y') }}</div>
                        </div>
                        <div class="client-actions">
                            <form action="{{ route('admin.clients.reactivate', $client->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="action-btn reactivate">
                                    <img src="{{ asset('images/refresh.png') }}" alt="refresh"> Reactivate
                                </button>
                            </form>
                            <form action="{{ route('admin.clients.delete', $client->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure? This cannot be undone.')">
                                @csrf
                                <button type="submit" class="action-btn delete">
                                    <img src="{{ asset('images/delete.png') }}" alt="delete"> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <p>No suspended clients.</p>
                    </div>
                @endforelse
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

/* TABS */
.clients-tabs {
    display: flex;
    gap: 12px;
    justify-content: center;
    margin-bottom: 40px;
    flex-wrap: wrap;
}

.tab-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 28px;
    border-radius: 12px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    color: var(--text-secondary);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.tab-btn:hover {
    border-color: var(--border-hover);
    color: var(--text-primary);
}

.tab-btn.active {
    background: var(--accent-glow);
    border-color: var(--accent);
    color: var(--accent);
}

.tab-count {
    background: var(--bg-tertiary);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 700;
    border: 1px solid var(--border);
}

.tab-btn.active .tab-count {
    background: var(--accent);
    color: var(--bg-primary);
    border-color: var(--accent);
}

/* TAB CONTENT */
.tab-content {
    display: none;
    animation: fadeIn 0.4s ease;
}

.tab-content.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* CLIENTS GRID */
.clients-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
    max-width: 1000px;
    margin: 0 auto;
}

/* CLIENT CARD */
.client-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 24px;
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
}

.client-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, transparent, var(--accent), transparent);
    opacity: 0;
    transition: opacity 0.3s;
}

.client-card:hover {
    transform: translateY(-4px);
    border-color: var(--border-hover);
    box-shadow: 0 20px 50px var(--shadow);
}

.client-card:hover::before {
    opacity: 1;
}

.client-card.pending {
    border-left: 3px solid #f59e0b;
}

.client-card.approved {
    border-left: 3px solid #10b981;
}

.client-card.suspended {
    border-left: 3px solid #ef4444;
}

.client-avatar {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    background: var(--accent-glow);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: var(--accent);
    margin-bottom: 16px;
}

.client-name {
    font-family: 'Cinzel', serif;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 6px;
}

.client-email {
    font-size: 0.9rem;
    color: var(--text-secondary);
    margin-bottom: 4px;
}

.client-date {
    font-size: 0.8rem;
    color: var(--text-muted);
    margin-bottom: 20px;
}

/* CLIENT ACTIONS */
.client-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    border-radius: 10px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    text-decoration: none;
}

.action-btn.approve {
    background: rgba(16, 185, 129, 0.15);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.action-btn.approve:hover {
    background: #10b981;
    color: var(--bg-primary);
    transform: translateY(-2px);
}

.action-btn.decline {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.action-btn.decline:hover {
    background: #ef4444;
    color: var(--bg-primary);
    transform: translateY(-2px);
}

.action-btn.suspend {
    background: rgba(245, 158, 11, 0.15);
    color: #f59e0b;
    border: 1px solid rgba(245, 158, 11, 0.3);
}

.action-btn.suspend:hover {
    background: #f59e0b;
    color: var(--bg-primary);
    transform: translateY(-2px);
}

.action-btn.reactivate {
    background: rgba(59, 130, 246, 0.15);
    color: #3b82f6;
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.action-btn.reactivate:hover {
    background: #3b82f6;
    color: var(--bg-primary);
    transform: translateY(-2px);
}

.action-btn.delete {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.action-btn.delete:hover {
    background: #ef4444;
    color: var(--bg-primary);
    transform: translateY(-2px);
}

/* EMPTY STATE */
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 40px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    color: var(--text-muted);
}

.empty-state i {
    font-size: 3rem;
    color: var(--accent);
    margin-bottom: 16px;
}

.empty-state p {
    margin: 0;
    font-size: 1rem;
}

/* MOBILE */
@media (max-width: 768px) {
    .slide-sidebar {
        width: 280px;
    }
    .vault-title {
        font-size: 1.8rem;
    }
    .clients-tabs {
        flex-direction: column;
        align-items: stretch;
    }
    .tab-btn {
        justify-content: center;
    }
    .clients-grid {
        grid-template-columns: 1fr;
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

// TABS
const tabBtns = document.querySelectorAll('.tab-btn');
const tabContents = document.querySelectorAll('.tab-content');

tabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        // Remove active from all
        tabBtns.forEach(b => b.classList.remove('active'));
        tabContents.forEach(c => c.classList.remove('active'));
        
        // Add active to clicked
        btn.classList.add('active');
        const tabId = btn.getAttribute('data-tab');
        document.getElementById(tabId).classList.add('active');
    });
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