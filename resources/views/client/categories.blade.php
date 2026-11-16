@extends('layouts.app')

@section('title', 'Categories — TheTrove')

@section('content')
<div class="vault-dashboard">

    <!-- MOBILE MENU BUTTON -->
    <button class="menu-toggle" id="menuToggle" aria-label="Open menu">
        <i class="fas fa-bars"></i>
    </button>

    <!-- SIDEBAR OVERLAY -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- SLIDING SIDEBAR -->
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
            <a href="{{ route('client.browse') }}" class="sidebar-nav-item active">
                <img src="{{ asset('images/produitmini1.png') }}" alt="browse">
                <span>Browse</span>
            </a>
            <a href="{{ route('client.boutique') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/boutiquemini1.png') }}" alt="boutique">
                <span>Vaults</span>
            </a>
            <a href="{{ route('client.categories') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/categoriemini1.png') }}" alt="categorie">
                <span>Categories</span>
            </a>
            <a href="{{ route('client.panier') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/paniermini.png') }}" alt="panier">
                <span>Basket</span>
            </a>
            <a href="{{ route('client.favoris') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/wishmini.png') }}" alt="favoris">
                <span>WishList</span>
            </a>
            <a href="{{ route('client.commandes') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/ordermini1.png') }}" alt="orders">
                <span>Orders</span>
            </a>
            <a href="{{ route('client.settings') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/settingmini1.png') }}" alt="orders">
                <span>Settings</span>
            </a>
        </div>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <img src="{{ asset('images/profilmini.png') }}" alt="client">
                <div>
                    <div style="font-weight: 600; color: var(--text-primary);">{{ auth()->user()->name }}</div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">Client</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 5%;">

        <!-- HEADER -->
        <div class="vault-header reveal">
            <div class="vault-header-top">
                <span class="section-label">Collection</span>
            </div>
            <h2 class="vault-title">
                <img src="{{ asset('images/categoriemini.png') }}" alt="categories">Browse <span style="color: var(--accent); font-weight: 600;">Categories</span>
            </h2>
            <p class="vault-subtitle">
                Explore our curated collections organized by theme and style.
            </p>
        </div>

        <!-- CATEGORIES GRID -->
        <div class="categories-grid" id="categoriesGrid">
            @forelse($categories as $category)
            <a href="{{ route('client.category.products', ['id' => $category->id_cat]) }}" class="category-card reveal">
                <div class="category-image-wrapper">
                    <img src="{{ $category->image ? asset('storage/' . $category->image) : asset('images/placeholder-product.png') }}" 
                         alt="{{ $category->name }}"
                         class="category-image"
                         onerror="this.src='{{ asset('images/placeholder-product.png') }}'">
                    <div class="category-overlay">
                        <div class="category-products-count">
                            <img src="{{ asset('images/produitmini1.png') }}" alt="products">
                            <span>{{ $category->produits_count ?? 0 }} pieces</span>
                        </div>
                    </div>
                </div>
                <div class="category-info">
                    <h3 class="category-name">{{ $category->name }}</h3>
                    <p class="category-desc">{{ Str::limit($category->description ?? 'A curated collection of unique treasures.', 80) }}</p>
                    <div class="category-footer">
                        <span class="category-explore">
                            <span>Explore</span>
                            <i class="fas fa-arrow-right"></i>
                        </span>
                    </div>
                </div>
            </a>
            @empty
            <div class="empty-state reveal">
                <div class="empty-icon">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3>No Collections Yet</h3>
                <p>Check back later for new curated categories.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<style>
/* ===== SIDEBAR ===== */
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
.vault-title { font-family: 'Cinzel', serif; font-size: 2.4rem; font-weight: 600; color: var(--text-primary); margin-bottom: 16px; letter-spacing: -0.5px; display: flex; align-items: center; justify-content: center; gap: 12px; }
.vault-title img { width: 40px; height: 40px; object-fit: contain; }
.vault-subtitle { font-size: 1.1rem; color: var(--text-secondary); font-weight: 300; line-height: 1.8; max-width: 520px; margin: 0 auto; }

.section-label {
    display: inline-block; padding: 8px 20px; border-radius: 20px;
    background: var(--accent-glow); border: 1px solid var(--border-hover);
    color: var(--accent); font-size: 0.8rem; font-weight: 600;
    letter-spacing: 2px; text-transform: uppercase; font-family: 'Cinzel', serif;
}

/* ===== STATS ===== */
.stats-grid {
    display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;
    max-width: 500px; margin: 0 auto 60px;
}
.stat-box {
    background: var(--bg-card); border: 1px solid var(--border); border-radius: 20px;
    padding: 28px 20px; text-align: center; transition: all 0.4s ease;
}
.stat-box:hover { border-color: var(--border-hover); transform: translateY(-4px); box-shadow: 0 20px 50px var(--shadow); }
.stat-icon-img { width: 60px; height: 60px; margin: 0 auto 12px; display: flex; align-items: center; justify-content: center; }
.stat-icon-img img { width: 100%; height: 100%; object-fit: contain; opacity: 0.8; }
.stat-number-real { font-family: 'Cinzel', serif; font-size: 2.6rem; font-weight: 700; color: var(--accent); line-height: 1; margin: 8px 0 4px; text-align: center; letter-spacing: 1px; text-shadow: 0 0 20px var(--accent-glow); }
.stat-label { font-size: 0.75rem; color: var(--text-muted); letter-spacing: 2px; text-transform: uppercase; text-align: center; margin-top: 8px; }

/* ===== CATEGORIES GRID ===== */
.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

.category-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.4s ease;
    text-decoration: none;
    display: flex;
    flex-direction: column;
}
.category-card:hover {
    border-color: var(--border-hover);
    transform: translateY(-6px);
    box-shadow: 0 20px 50px var(--shadow);
}

.category-image-wrapper {
    position: relative;
    height: 240px;
    overflow: hidden;
    background: var(--bg-tertiary);
}
.category-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}
.category-card:hover .category-image {
    transform: scale(1.05);
}

.category-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 50%);
    display: flex;
    align-items: flex-end;
    padding: 16px;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.category-card:hover .category-overlay {
    opacity: 1;
}

.category-products-count {
    display: flex;
    align-items: center;
    gap: 8px;
    color: white;
    font-size: 0.9rem;
    font-weight: 600;
    background: rgba(0,0,0,0.5);
    padding: 6px 14px;
    border-radius: 20px;
    backdrop-filter: blur(10px);
}
.category-products-count img { width: 18px; height: 18px; object-fit: contain; }

.category-info {
    padding: 24px;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.category-name {
    font-family: 'Cinzel', serif;
    font-size: 1.2rem;
    color: var(--text-primary);
    margin-bottom: 4px;
    font-weight: 600;
}
.category-desc {
    font-size: 0.85rem;
    color: var(--text-muted);
    line-height: 1.5;
    margin-bottom: 16px;
    flex: 1;
}
.category-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 16px;
    border-top: 1px solid var(--border);
}
.category-explore {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--accent);
    transition: all 0.3s ease;
}
.category-card:hover .category-explore {
    gap: 12px;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 80px 20px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
}
.empty-icon { font-size: 4rem; color: var(--accent); margin-bottom: 20px; opacity: 0.5; }
.empty-state h3 { font-family: 'Cinzel', serif; color: var(--text-primary); margin-bottom: 12px; }
.empty-state p { color: var(--text-secondary); margin-bottom: 24px; }

/* ===== ANIMATIONS ===== */
.reveal { opacity: 0; transform: translateY(30px); transition: all 0.6s ease; }
.reveal.active { opacity: 1; transform: translateY(0); }

@media (max-width: 768px) {
    .stats-grid { grid-template-columns: 1fr; }
    .vault-title { font-size: 1.8rem; }
    .slide-sidebar { width: 280px; }
    .categories-grid { grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); }
}
@media (max-width: 640px) {
    .categories-grid { grid-template-columns: 1fr; }
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