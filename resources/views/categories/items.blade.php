@extends('layouts.app')

@section('title', $category->nom . ' — TheTrove')

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
            <a href="{{ route('admin.categories') }}" class="sidebar-nav-item active">
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

        <!-- CATEGORY HEADER -->
        <div class="vault-header reveal">
            <div class="vault-header-top">
                <span class="section-label">Category</span>
            </div>
            <h2 class="vault-title">
                {{ $category->nom }}
            </h2>
            <p class="vault-subtitle">
                {{ $category->description }}
            </p>
        </div>

        <!-- CATEGORY IMAGE -->
        <div class="category-hero reveal">
            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->nom }}"
                 onerror="this.src='{{ asset('images/categoriemini1.png') }}'">
        </div>

        <!-- ITEMS GRID -->
        <div class="items-section reveal">
            <div class="section-header">
                <h3 class="section-title">
                    <img src="{{ asset('images/produitmini.png') }}" alt="itemsmini">
                    Treasures in this Category
                </h3>
                <span class="items-count">{{ $produits->count() }} items</span>
            </div>

            <div class="items-grid">
                @forelse($produits as $produit)
                    @php
                        $productImages = $produit->images;
                        $primaryImage = $produit->imagePrincipale;
                        $displayImage = $primaryImage ?? $productImages->first();
                        $imageUrl = $displayImage ? asset('storage/' . $displayImage->path) : asset('images/placeholder-product.png');
                    @endphp
                    <div class="item-card">
                        <div class="item-image">
                            <img src="{{ $imageUrl }}" alt="{{ $produit->nom }}"
                                 onerror="this.src='{{ asset('images/placeholder-product.png') }}'">
                        </div>
                        <div class="item-content">
                            <h4 class="item-name">{{ $produit->nom }}</h4>
                            <p class="item-description">{{ Str::limit($produit->description, 100) }}</p>
                            <div class="item-footer">
                                <span class="item-price">{{ number_format($produit->prix, 2) }} DT</span>
                                <span class="item-status {{ $produit->status }}"><img src="{{ asset('images/' . $produit->status . '.png') }}" alt="{{ $produit->status }}">{{ ucfirst($produit->status) }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state-items">
                        <p>No treasures found in this category yet.</p>
                        <p class="empty-hint">Check back later for new arrivals!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- BACK BUTTON -->
        <div class="back-section reveal">
            <a href="{{ route('admin.categories') }}" class="back-btn">
                <img src="{{ asset('images/categoriemini.png') }}" alt="back">
                Back to Categories
            </a>
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
.sidebar-user img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

/* MAIN CONTENT */
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
.vault-subtitle {
    font-size: 1.1rem;
    color: var(--text-secondary);
    font-weight: 300;
    max-width: 520px;
    margin: 0 auto;
}

/* CATEGORY HERO */
.category-hero {
    max-width: 600px;
    margin: 0 auto 60px;
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid var(--border);
    box-shadow: 0 20px 50px var(--shadow);
}
.category-hero img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    display: block;
}

/* ITEMS SECTION */
.items-section {
    margin-top: 40px;
}
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    max-width: 1000px;
    margin-left: auto;
    margin-right: auto;
}
.section-title {
    font-family: 'Cinzel', serif;
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}
.section-title img {
    width: 28px;
    height: 28px;
}
.items-count {
    background: var(--accent-glow);
    color: var(--accent);
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    border: 1px solid var(--border-hover);
}

/* ITEMS GRID */
.items-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 24px;
    max-width: 1000px;
    margin: 0 auto;
}
.item-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.4s ease;
}
.item-card:hover {
    transform: translateY(-8px);
    border-color: var(--border-hover);
    box-shadow: 0 20px 50px var(--shadow);
}
.item-image {
    width: 100%;
    height: 200px;
    background: var(--bg-tertiary);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.item-content {
    padding: 20px;
}
.item-name {
    font-family: 'Cinzel', serif;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 8px;
}
.item-description {
    font-size: 0.85rem;
    color: var(--text-muted);
    line-height: 1.6;
    margin: 0 0 16px;
}
.item-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.item-price {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--accent);
}
.item-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: capitalize;
}

.item-status img {
    width: 14px;
    height: 14px;
    object-fit: contain;
}

.item-status.pending {
    background: rgba(245, 158, 11, 0.15);
    color: #f59e0b;
    border: 1px solid rgba(245, 158, 11, 0.3);
}

.item-status.approved {
    background: rgba(16, 185, 129, 0.15);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

/* EMPTY STATE */
.empty-state-items {
    grid-column: 1 / -1;
    text-align: center;
    padding: 80px 40px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    color: var(--text-muted);
}
.empty-state-items i {
    font-size: 3rem;
    color: var(--accent);
    margin-bottom: 16px;
}
.empty-state-items p {
    margin: 0 0 8px;
    font-size: 1rem;
}
.empty-hint {
    font-size: 0.85rem;
    opacity: 0.7;
}

/* BACK BUTTON */
.back-section {
    text-align: center;
    margin-top: 60px;
}
.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 32px;
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dim) 100%);
    color: var(--bg-primary);
    border-radius: 12px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.95rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}
.back-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 40px var(--accent-glow);
    color: var(--bg-primary);
}
.back-btn img {
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
    .items-grid {
        grid-template-columns: 1fr;
    }
    .section-header {
        flex-direction: column;
        gap: 12px;
        text-align: center;
    }
    .category-hero img {
        height: 200px;
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