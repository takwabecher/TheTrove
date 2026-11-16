@extends('layouts.app')

@section('title', 'Delivery Companies')

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
                <span class="section-label">Management</span>
            </div>
            <h2 class="vault-title">
                <img src="{{ asset('images/delivery.png') }}" alt="deliverymini">
                Delivery Companies
            </h2>
            <p class="vault-subtitle">
                Manage delivery partners for your marketplace.
            </p>
        </div>

        <!-- ADD COMPANY BUTTON -->
        <div class="add-company-section reveal">
            <a href="{{ route('admin.delivery_companies.create') }}" class="add-company-btn">
                <img src="{{ asset('images/add.png') }}" alt="add">
                Add Company
            </a>
        </div>

        <!-- COMPANIES SECTION -->
        <div class="companies-section reveal">
            <div class="section-header">
                <h3 class="section-title">
                    My Companies
                </h3>
                <span class="company-count">{{ $companies->count() }} companies</span>
            </div>

            <!-- TABLE -->
            <div class="table-card">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Company</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Orders</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companies as $company)
                        <tr>
                            <td>
    <div class="company-cell">
        @if($company->logo && \Storage::disk('public')->exists($company->logo))
            <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" class="company-logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="company-avatar" style="display:none;">{{ strtoupper(substr($company->name, 0, 1)) }}</div>
        @else
            <div class="company-avatar">{{ strtoupper(substr($company->name, 0, 1)) }}</div>
        @endif
        <span class="company-name">{{ $company->name }}</span>
    </div>
</td>
                            <td>{{ $company->email }}</td>
                            <td>{{ $company->phone ?? 'N/A' }}</td>
                            <td>
                                <span class="status-dot {{ $company->is_active ? 'active' : 'inactive' }}">
                                    <i class="fas fa-circle"></i> {{ $company->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $company->commandes->count() }}</td>
                            <td>{{ $company->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="action-btns">
                                    <a href="{{ route('admin.delivery_companies.edit', $company->id) }}" class="btn-icon edit" title="Edit">
                                        <img src="{{ asset('images/edit.png') }}" alt="edit">
                                    </a>
                                    <form action="{{ route('admin.delivery_companies.destroy', $company->id) }}" method="POST" class="inline-form" onsubmit="return confirm('Delete this company?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-icon delete" title="Delete">
                                            <img src="{{ asset('images/delete.png') }}" alt="delete">
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="empty-cell">
                                <p>No delivery companies yet.</p>
                                <p class="empty-hint">Click "Add Company" to create your first delivery partner.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="pagination-wrapper">
                {{ $companies->links() }}
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
    vertical-align: middle;
    margin-right: 12px;
}

.vault-subtitle {
    font-size: 1.1rem;
    color: var(--text-secondary);
    font-weight: 300;
    max-width: 520px;
    margin: 0 auto;
}

/* ADD COMPANY BUTTON */
.add-company-section {
    display: flex;
    justify-content: center;
    margin-bottom: 40px;
}

.add-company-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 32px;
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dim) 100%);
    color: var(--bg-primary);
    border: none;
    border-radius: 12px;
    font-family: 'DM Sans', sans-serif;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.add-company-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 40px var(--accent-glow);
}

.add-company-btn img {
    width: 20px;
    height: 20px;
}

/* COMPANIES SECTION */
.companies-section {
    margin-top: 60px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.section-title {
    font-family: 'Cinzel', serif;
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.company-count {
    background: var(--accent-glow);
    color: var(--accent);
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    border: 1px solid var(--border-hover);
}

/* TABLE */
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

/* COMPANY CELL */
.company-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.company-logo {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    object-fit: cover;
    border: 1px solid var(--border);
    flex-shrink: 0;
}

.company-avatar {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: var(--accent-glow);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent);
    font-weight: 700;
    font-size: 0.9rem;
    font-family: 'Cinzel', serif;
}

.company-name {
    font-weight: 600;
    color: var(--text-primary);
}

/* STATUS */
.status-dot {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.85rem;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 20px;
    border: 1px solid var(--border);
}

.status-dot.active {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border-color: rgba(16, 185, 129, 0.3);
}

.status-dot.inactive {
    background: rgba(107, 114, 128, 0.1);
    color: var(--text-muted);
    border-color: var(--border);
}

.status-dot i {
    font-size: 0.5rem;
}

/* ACTION BUTTONS */
.action-btns {
    display: flex;
    gap: 8px;
}

.btn-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    border: 1px solid var(--border);
    background: var(--bg-tertiary);
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-icon img {
    width: 16px;
    height: 16px;
    opacity: 0.7;
}

.btn-icon.edit:hover {
    background: rgba(59, 130, 246, 0.15);
    border-color: rgba(59, 130, 246, 0.3);
}

.btn-icon.edit:hover img {
    opacity: 1;
    filter: brightness(0) saturate(100%) invert(47%) sepia(85%) saturate(2348%) hue-rotate(200deg) brightness(97%) contrast(96%);
}

.btn-icon.delete:hover {
    background: rgba(239, 68, 68, 0.15);
    border-color: rgba(239, 68, 68, 0.3);
}

.btn-icon.delete:hover img {
    opacity: 1;
    filter: brightness(0) saturate(100%) invert(39%) sepia(95%) saturate(3298%) hue-rotate(343deg) brightness(97%) contrast(96%);
}

.inline-form {
    display: inline;
}

/* EMPTY STATE */
.empty-cell {
    text-align: center;
    padding: 80px 40px !important;
    color: var(--text-muted);
}

.empty-cell i {
    font-size: 3rem;
    margin-bottom: 16px;
    display: block;
    color: var(--accent);
}

.empty-cell p {
    margin: 0 0 8px;
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
}

.empty-hint {
    font-size: 0.85rem;
    opacity: 0.7;
    font-weight: 400;
}

/* PAGINATION */
.pagination-wrapper {
    margin-top: 40px;
    display: flex;
    justify-content: center;
}

/* MOBILE */
@media (max-width: 768px) {
    .slide-sidebar {
        width: 280px;
    }
    .vault-title {
        font-size: 1.8rem;
    }
    .section-header {
        flex-direction: column;
        gap: 12px;
        text-align: center;
    }
    .table-card {
        overflow-x: auto;
    }
    .data-table {
        min-width: 800px;
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