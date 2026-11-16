@extends('delivery.layout')

@section('title', 'Call History — TheTrove Delivery')

@section('delivery_content')
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

    <!-- HEADER -->
    <div class="vault-header reveal">
        <h2 class="vault-title">
            <img src="{{ asset('images/phone.png') }}" alt="calls"> Call History
        </h2>
        <p class="vault-subtitle">
            All client calls logged in the system.
        </p>
    </div>

    <!-- TABLE -->
    <div class="table-section reveal">
        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Order </th>
                        <th>Client</th>
                        <th>Phone</th>
                        <th>Type</th>
                        <th>Duration</th>
                        <th>Date</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($calls as $call)
                    <tr>
                        <td>
                            <a href="{{ route('delivery.order_detail', $call->commande_id) }}" class="order-link">
                                {{ $call->commande?->formatted_id ?? 'ORD-?????' }}
                            </a>
                        </td>
                        <td>
                            <div class="company-cell">
                                <div class="company-avatar">{{ strtoupper(substr($call->client_name ?? '', 0, 1)) }}</div>
                                <span class="company-name">{{ $call->client_name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td>{{ $call->client_phone ?? '-' }}</td>
                        <td>
                            <span class="status-dot {{ $call->call_type }}">
                                <i class="fas fa-circle"></i> {{ ucfirst($call->call_type ?? 'unknown') }}
                            </span>
                        </td>
                        <td><span class="duration">{{ $call->duration_formatted ?? '00:00' }}</span></td>
                        <td>{{ $call->called_at?->format('M d, Y H:i') ?? '-' }}</td>
                        <td><span class="notes-text">{{ $call->notes ?? '—' }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="empty-cell">
                            <i class="fas fa-phone-slash"></i>
                            <p>No calls logged yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-wrapper">
            {{ $calls->links() }}
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

/* VAULT DASHBOARD */
.vault-dashboard {
    padding: 140px 40px 80px;
    max-width: 1200px;
    margin: 0 auto;
    min-height: 100vh;
}

/* VAULT HEADER */
.vault-header {
    text-align: center;
    margin-bottom: 48px;
}

.vault-header-top {
    margin-bottom: 16px;
}

.section-label {
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
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
}

.vault-title img {
    width: 44px;
    height: 44px;
    object-fit: contain;
}

.vault-subtitle {
    font-size: 1.1rem;
    color: var(--text-secondary);
    font-weight: 300;
    max-width: 520px;
    margin: 0 auto;
}

/* TABLE SECTION */
.table-section {
    margin-bottom: 40px;
}

/* TABLE CARD */
.table-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    overflow: hidden;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    text-align: left;
    padding: 18px 24px;
    color: var(--text-muted);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-bottom: 1px solid var(--border);
    background: var(--bg-tertiary);
}

.data-table td {
    padding: 18px 24px;
    border-bottom: 1px solid var(--border);
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.data-table tr:last-child td {
    border-bottom: none;
}

.data-table tr:hover td {
    background: rgba(255,255,255,0.02);
}

/* ORDER LINK */
.order-link {
    font-family: 'Cinzel', serif;
    color: var(--accent);
    text-decoration: none;
    font-weight: 600;
}

.order-link:hover {
    text-decoration: underline;
}

/* COMPANY CELL */
.company-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.company-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: var(--accent-glow);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent);
    font-weight: 700;
    font-size: 0.85rem;
    font-family: 'Cinzel', serif;
}

.company-name {
    font-weight: 600;
    color: var(--text-primary);
}

/* STATUS DOT */
.status-dot {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 20px;
    border: 1px solid var(--border);
}

.status-dot.outgoing {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
    border-color: rgba(59, 130, 246, 0.3);
}

.status-dot.incoming {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border-color: rgba(16, 185, 129, 0.3);
}

.status-dot i {
    font-size: 0.5rem;
}

/* DURATION */
.duration {
    font-family: monospace;
    color: var(--text-primary);
    font-weight: 600;
}

/* NOTES */
.notes-text {
    color: var(--text-muted);
    font-size: 0.85rem;
    font-style: italic;
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    display: block;
}

/* EMPTY CELL */
.empty-cell {
    text-align: center;
    padding: 60px 24px !important;
    color: var(--text-muted);
}

.empty-cell i {
    font-size: 2.5rem;
    margin-bottom: 12px;
    display: block;
    color: var(--accent);
}

.empty-cell p {
    margin: 0;
    font-weight: 600;
    color: var(--text-primary);
}

/* PAGINATION */
.pagination-wrapper {
    margin-top: 40px;
    display: flex;
    justify-content: center;
}

/* REVEAL */
.reveal {
    opacity: 0;
    transform: translateY(40px);
    transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}

.reveal.active {
    opacity: 1;
    transform: translateY(0);
}

.order-number {
    font-family: 'Cinzel', serif;
    font-weight: 600;
    color: var(--accent);
    letter-spacing: 1px;
}
/* MOBILE */
@media (max-width: 768px) {
    .vault-dashboard {
        padding: 120px 20px 60px;
    }
    .vault-title {
        font-size: 1.8rem;
        flex-direction: column;
        gap: 12px;
    }
    .slide-sidebar {
        width: 280px;
    }
    .table-card {
        overflow-x: auto;
    }
    .data-table {
        min-width: 900px;
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