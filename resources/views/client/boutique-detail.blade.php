@extends('layouts.app')

@section('title', $boutique->name . ' — TheTrove')

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
            <a href="{{ route('client.dashboard') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/mainmini1.png') }}" alt="dashboard">
                <span>Dashboard</span>
            </a>
            <a href="{{ route('client.browse') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/produitmini1.png') }}" alt="browse">
                <span>Browse</span>
            </a>
            <a href="{{ route('client.boutique') }}" class="sidebar-nav-item active">
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

        <!-- BREADCRUMB -->
        <div class="reveal" style="margin-bottom: 24px;">
            <nav style="display: flex; align-items: center; gap: 12px; font-size: 0.85rem; color: var(--text-muted);">
                <a href="{{ route('client.boutique') }}" style="color: var(--accent); text-decoration: none;">Vaults</a>
                <span style="color: var(--border-hover);">/</span>
                <span style="color: var(--text-primary); font-weight: 500;">{{ $boutique->name }}</span>
            </nav>
        </div>

        <!-- BOUTIQUE HEADER -->
        <div class="boutique-hero reveal">
            <div class="boutique-hero-image">
                <img src="{{ $boutique->image ? asset('storage/' . $boutique->image) : asset('images/boutique.png') }}" 
                     alt="{{ $boutique->name }}"
                     onerror="this.src='{{ asset('images/placeholder-product.png') }}'">
                <div class="boutique-hero-overlay">
                    <span class="section-label">{{ $boutique->category ?? 'Vault' }}</span>
                </div>
            </div>
            <div class="boutique-hero-info">
                <h1 class="boutique-hero-title">{{ $boutique->name }}</h1>
                <p class="boutique-hero-desc">{{ $boutique->description ?? 'A curated collection of unique treasures.' }}</p>
                
                <div class="boutique-hero-meta">
                    <div class="boutique-meta-item">
                        <img src="{{ asset('images/produitmini1.png') }}" alt="products">
                        <span>{{ $boutique->produits_count ?? $boutique->produits->count() }} Products</span>
                    </div>
                    <div class="boutique-meta-item">
                        <img src="{{ asset('images/approvedmini.png') }}" alt="status">
                        <span style="color: #10b981;">Active</span>
                    </div>
                </div>

                <div class="boutique-hero-contact">
                    @if($boutique->email)
                    <div class="contact-row">
                        <i class="fas fa-envelope"></i>
                        <span>{{ $boutique->email }}</span>
                    </div>
                    @endif
                    @if($boutique->address)
                    <div class="contact-row">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $boutique->address }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- PRODUCTS SECTION -->
        <div class="products-section reveal">
            <div class="section-header">
                <h2 class="vault-title" style="font-size: 1.8rem; margin-bottom: 0;">
                    <img src="{{ asset('images/produit.png') }}" alt="products">Curated <span style="color: var(--accent); font-weight: 600;">Pieces</span>
                </h2>
                <div class="section-line"></div>
            </div>

            @if(isset($produits) && $produits->count() > 0)
            <div class="products-grid">
                @foreach($produits as $produit)
                <div class="product-card reveal" onclick="openProductModal({{ $produit->id_product }})" data-status="{{ $produit->status }}">
                    <div class="product-image-wrapper">
                        @php
                            $productImages = $produit->images;
                            $primaryImage = $produit->imagePrincipale ?? null;
                            $displayImage = $primaryImage ?? $productImages->first();
                            $imageCount = $productImages->count();
                        @endphp

                        @if($imageCount > 0)
                            <div class="image-carousel" id="carousel-{{ $produit->id_product }}" data-current="0" data-total="{{ $imageCount }}">
                                @foreach($productImages as $index => $img)
                                    <img src="{{ asset('storage/' . $img->path) }}" 
                                         alt="{{ $produit->nom }}" 
                                         class="product-image carousel-slide {{ $index === 0 ? 'active' : '' }}"
                                         data-index="{{ $index }}"
                                         onerror="this.src='{{ asset('images/placeholder-product.png') }}'">
                                @endforeach

                                @if($imageCount > 1)
                                    <button type="button" class="carousel-arrow carousel-prev" onclick="event.stopPropagation(); carouselPrev({{ $produit->id_product }})">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button type="button" class="carousel-arrow carousel-next" onclick="event.stopPropagation(); carouselNext({{ $produit->id_product }})">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                    <div class="carousel-dots">
                                        @foreach($productImages as $index => $img)
                                            <span class="carousel-dot {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}"></span>
                                        @endforeach
                                    </div>
                                @endif

                                @if($imageCount > 1)
                                <div class="image-count-badge">
                                    <i class="fas fa-images"></i> {{ $imageCount }}
                                </div>
                                @endif
                            </div>
                        @else
                            <img src="{{ asset('images/placeholder-product.png') }}" 
                                 alt="{{ $produit->nom }}" class="product-image"
                                 style="opacity: 0.5;">
                        @endif

                        @if(in_array($produit->id_product, $favoris_products))
                            <form action="{{ route('client.removeFromFavoris', $produit->id_product) }}" method="POST" 
                                  class="wishlist-form" onclick="event.stopPropagation()">
                                @csrf
                                <button type="submit" class="wishlist-btn active" title="Remove from Wishlist">
                                    <img src="{{ asset('images/wishmini.png') }}" alt="wish">
                                </button>
                            </form>
                        @else
                            <form action="{{ route('client.addToFavoris', $produit->id_product) }}" method="POST" 
                                  class="wishlist-form" onclick="event.stopPropagation()">
                                @csrf
                                <button type="submit" class="wishlist-btn" title="Add to Wishlist">
                                    <img src="{{ asset('images/wishmini.png') }}" alt="wish">
                                </button>
                            </form>
                        @endif
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">{{ $produit->nom }}</h3>
                        <p class="product-desc">{{ Str::limit($produit->description ?? 'A unique piece from this vault.', 60) }}</p>
                        <div class="product-meta">
                            <p class="product-category">
                                <img src="{{ asset('images/categoriemini1.png') }}" alt="cat">
                                {{ $produit->categorie->nom ?? 'Uncategorized' }}
                            </p>
                        </div>
                        <div class="product-footer">
                            <span class="product-price">{{ number_format($produit->prix ?? 0, 2) }} <span>DT</span></span>
                        </div>
                    </div>

                    <div class="card-actions" onclick="event.stopPropagation()">
                        @if(in_array($produit->id_product, $favoris_products))
                            <form action="{{ route('client.removeFromFavoris', $produit->id_product) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-wishlist active">
                                    <img src="{{ asset('images/wishmini.png') }}" alt="wish">
                                    <span>Wishlist</span>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('client.addToFavoris', $produit->id_product) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-wishlist">
                                    <img src="{{ asset('images/wishmini.png') }}" alt="wish">
                                    <span>Wishlist</span>
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('client.addToPanier', $produit->id_product) }}" method="POST" class="add-to-cart-form">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn-basket" {{ $produit->stock <= 0 ? 'disabled' : '' }}>
                                <img src="{{ asset('images/paniermini1.png') }}" alt="basket">
                                <span>Basket</span>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state reveal">
                <h3>Empty Vault</h3>
                <p>This boutique has no products listed yet.</p>
            </div>
            @endif
        </div>

        <!-- BACK BUTTON -->
        <div class="reveal" style="text-align: center; margin-top: 40px;">
            <a href="{{ route('client.boutique') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Vaults</span>
            </a>
        </div>
    </div>
</div>

<!-- PRODUCT DETAIL MODAL -->
<div class="modal-overlay" id="productModal">
    <div class="modal-container modal-large">
        <button class="modal-close" onclick="closeProductModal()">
            <img src="{{ asset('images/cancel.png') }}" alt="cancel">
        </button>

        <div class="modal-product-grid">
            <div class="modal-product-image" id="modalImageContainer">
                <img id="modalImage" src="" alt="">
            </div>
            <div class="modal-product-info">
                <div class="modal-product-header">
                    <span class="modal-category" id="modalCategory"></span>
                    <h2 class="modal-title" id="modalTitle"></h2>
                    <div class="modal-boutique" id="modalBoutique">
                        <img src="{{ asset('images/boutiquemini.png') }}" alt="boutique">
                        <span></span>
                    </div>
                </div>

                <div class="modal-price-stock">
                    <span class="modal-price" id="modalPrice"></span>
                    <span class="modal-stock in-stock" id="modalStock">
                        <img src="{{ asset('images/produitmini1.png') }}" alt="stock">
                        <span id="modalStockText"></span>
                    </span>
                </div>

                <div class="modal-description">
                    <h4>Description</h4>
                    <p id="modalDescription"></p>
                </div>

                <div class="modal-actions">
                    <form id="modalCartForm" method="POST" style="flex: 1;">
                        @csrf
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="modal-add-cart" id="modalAddCart">
                            <img src="{{ asset('images/paniermini1.png') }}" alt="basket">
                            <span>Basket</span>
                        </button>
                    </form>

                    <form id="modalWishForm" method="POST" style="flex: 1;">
                        @csrf
                        <button type="submit" class="btn-wishlist-modal" id="modalWishBtn">
                            <img src="{{ asset('images/wishmini.png') }}" alt="wishlist" id="modalWishImg">
                            <span>WishList</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ===== SIDEBAR (same as vaults page) ===== */
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

.section-label {
    display: inline-block; padding: 8px 20px; border-radius: 20px;
    background: var(--accent-glow); border: 1px solid var(--border-hover);
    color: var(--accent); font-size: 0.8rem; font-weight: 600;
    letter-spacing: 2px; text-transform: uppercase; font-family: 'Cinzel', serif;
}

/* ===== BOUTIQUE HERO ===== */
/* ===== BOUTIQUE HERO ===== */
.boutique-hero {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    overflow: hidden;
    margin-bottom: 20px;          /* was 60px */
    transition: all 0.4s ease;
    max-height: 250px;            /* ADD: cap overall height */
}
.boutique-hero:hover {
    border-color: var(--border-hover);
    box-shadow: 0 20px 50px var(--shadow);
}

.boutique-hero-image {
    position: relative;
    height: 250px;                /* was 400px */
    overflow: hidden;
    background: var(--bg-tertiary);
}
.boutique-hero-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}
.boutique-hero:hover .boutique-hero-image img {
    transform: scale(1.05);
}

.boutique-hero-overlay {
    position: absolute;
    top: 10px;                    /* was 20px */
    left: 10px;                   /* was 20px */
}

.boutique-hero-info {
    padding: 25px;                /* was 40px */
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.boutique-hero-title {
    font-family: 'Cinzel', serif;
    font-size: 1.8rem;            /* was 2.2rem */
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 10px;          /* was 16px */
    letter-spacing: -0.5px;
}

.boutique-hero-desc {
    font-size: 0.9rem;            /* was 1rem */
    color: var(--text-secondary);
    line-height: 1.6;             /* was 1.8 */
    margin-bottom: 16px;          /* was 24px */
    font-weight: 300;
}

.boutique-hero-meta {
    display: flex;
    gap: 16px;                    /* was 20px */
    margin-bottom: 16px;          /* was 24px */
    padding-bottom: 16px;         /* was 24px */
    border-bottom: 1px solid var(--border);
}

.boutique-meta-item {
    display: flex;
    align-items: center;
    gap: 8px;                     /* was 10px */
    font-size: 0.85rem;           /* was 0.9rem */
    color: var(--text-secondary);
    font-weight: 500;
}
.boutique-meta-item img {
    width: 20px;                  /* was 22px */
    height: 20px;
    object-fit: contain;
    opacity: 0.8;
}

.boutique-hero-contact {
    display: flex;
    flex-direction: column;
    gap: 8px;                     /* was 12px */
}

.contact-row {
    display: flex;
    align-items: center;
    gap: 10px;                    /* was 12px */
    font-size: 0.85rem;           /* was 0.9rem */
    color: var(--text-muted);
}
.contact-row i {
    color: var(--accent);
    width: 10px;                  /* was 20px */
    text-align: center;
    font-size: 0.9rem;
}

/* ===== PRODUCTS SECTION ===== */
.products-section {
    margin-bottom: 40px;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 24px;
    margin-bottom: 40px;
}

.section-line {
    flex: 1;
    height: 1px;
    background: var(--border);
}

.vault-title {
    font-family: 'Cinzel', serif;
    font-size: 2.4rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 16px;
    letter-spacing: -0.5px;
    display: flex;
    align-items: center;
    gap: 12px;
}
.vault-title img {
    width: 40px;
    height: 40px;
    object-fit: contain;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

.product-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.4s ease;
    position: relative;
    display: flex;
    flex-direction: column;
    cursor: pointer;
}
.product-card:hover {
    border-color: var(--border-hover);
    transform: translateY(-6px);
    box-shadow: 0 20px 50px var(--shadow);
}

.product-image-wrapper {
    position: relative;
    height: 220px;
    overflow: hidden;
    background: var(--bg-tertiary);
}

/* ===== IMAGE CAROUSEL ===== */
.image-carousel {
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.carousel-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0;
    transition: opacity 0.4s ease, transform 0.5s ease;
    transform: scale(1.02);
    pointer-events: none;
}

.carousel-slide.active {
    opacity: 1;
    transform: scale(1);
    pointer-events: auto;
}

.carousel-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 1px solid rgba(255,255,255,0.2);
    background: rgba(0,0,0,0.5);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    backdrop-filter: blur(4px);
}

.carousel-arrow:hover {
    background: var(--accent);
    color: #000;
    border-color: var(--accent);
}

.carousel-prev { left: 8px; }
.carousel-next { right: 8px; }

.carousel-dots {
    position: absolute;
    bottom: 8px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 6px;
    z-index: 10;
}

.carousel-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: rgba(255,255,255,0.4);
    transition: all 0.3s ease;
}

.carousel-dot.active {
    background: var(--accent);
    width: 18px;
    border-radius: 3px;
}

.image-count-badge {
    position: absolute;
    bottom: 12px;
    right: 12px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
    background: rgba(0,0,0,0.6);
    color: white;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
    z-index: 10;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.wishlist-form {
    position: absolute;
    top: 12px;
    right: 12px;
    margin: 0;
    padding: 0;
    z-index: 15;
}

.wishlist-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 1px solid var(--border);
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 0;
}

.wishlist-btn:hover {
    transform: scale(1.1);
    background: rgba(239,68,68,0.3);
    border-color: rgba(239,68,68,0.5);
}

.wishlist-btn.active {
    background: rgba(239,68,68,0.2);
    border-color: rgba(239,68,68,0.4);
}

.wishlist-btn img {
    width: 20px;
    height: 20px;
    object-fit: contain;
}

.product-info {
    padding: 20px;
    flex: 1;
}

.product-name {
    font-family: 'Cinzel', serif;
    font-size: 1.1rem;
    color: var(--text-primary);
    margin-bottom: 4px;
    font-weight: 600;
}

.product-desc {
    font-size: 0.8rem;
    color: var(--text-muted);
    margin-bottom: 8px;
    line-height: 1.4;
}

.product-meta {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-bottom: 12px;
}

.product-boutique,
.product-category {
    font-size: 0.8rem;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 6px;
}

.product-boutique img,
.product-category img {
    width: 14px;
    height: 14px;
    object-fit: contain;
    opacity: 0.6;
}

.product-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.product-price {
    font-family: 'Cinzel', serif;
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--accent);
}

.product-price span {
    font-size: 0.8rem;
    color: var(--accent-dim);
    font-weight: 400;
}

.card-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    padding: 0 20px 20px;
}

.card-actions form {
    margin: 0;
}

.btn-wishlist,
.btn-basket {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: 'DM Sans', sans-serif;
    width: 100%;
    border: 1px solid var(--border);
    background: var(--bg-card);
    color: var(--text-secondary);
}

.btn-wishlist:hover {
    border-color: #ef4444;
    color: #ef4444;
    background: rgba(239,68,68,0.1);
}

.btn-wishlist.active {
    color: #ef4444;
    border-color: #ef4444;
    background: rgba(239,68,68,0.15);
}

.btn-basket {
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dim) 100%);
    color: #000;
    border: none;
    box-shadow: 0 4px 15px rgba(201,168,76,0.3);
}

.btn-basket:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(201,168,76,0.4);
}

.btn-basket:disabled {
    background: var(--bg-tertiary);
    color: var(--text-muted);
    cursor: not-allowed;
    box-shadow: none;
}

.btn-wishlist img,
.btn-basket img {
    width: 16px;
    height: 16px;
    object-fit: contain;
}

/* ===== BACK BUTTON ===== */
.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 28px;
    border-radius: 12px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}
.back-btn:hover {
    border-color: var(--border-hover);
    color: var(--accent);
    transform: translateX(-4px);
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
.empty-state h3 { font-family: 'Cinzel', serif; color: var(--text-primary); margin-bottom: 12px; }
.empty-state p { color: var(--text-secondary); margin-bottom: 24px; }

/* ===== MODAL ===== */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.8);
    backdrop-filter: blur(12px);
    z-index: 2000;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    padding: 20px;
}

.modal-overlay.active {
    opacity: 1;
    visibility: visible;
}

.modal-container {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 24px;
    width: 100%;
    max-width: 900px;
    max-height: 90vh;
    overflow: hidden;
    transform: scale(0.9) translateY(20px);
    transition: all 0.3s ease;
    box-shadow: 0 30px 60px rgba(0,0,0,0.5);
    position: relative;
}

.modal-overlay.active .modal-container {
    transform: scale(1) translateY(0);
}

.modal-close {
    position: absolute;
    top: 20px;
    right: 20px;
    z-index: 10;
    width: 40px;
    height: 40px;
    border-radius: 12px;
    border: 1px solid var(--border);
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(10px);
    color: var(--text-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 0;
}

.modal-close:hover {
    background: rgba(239,68,68,0.2);
    border-color: rgba(239,68,68,0.4);
    color: #ef4444;
}

.modal-close img {
    width: 18px;
    height: 18px;
    object-fit: contain;
    opacity: 0.8;
}

.modal-product-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    min-height: 500px;
}

.modal-product-image {
    position: relative;
    overflow: hidden;
    background: var(--bg-tertiary);
}

.modal-product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.modal-product-info {
    padding: 40px;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    max-height: 90vh;
}

.modal-product-header {
    margin-bottom: 24px;
}

.modal-category {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 20px;
    background: var(--accent-glow);
    border: 1px solid var(--border-hover);
    color: var(--accent);
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 12px;
}

.modal-title {
    font-family: 'Cinzel', serif;
    font-size: 1.8rem;
    color: var(--text-primary);
    margin-bottom: 12px;
    font-weight: 600;
}

.modal-boutique {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--text-muted);
    font-size: 0.9rem;
}

.modal-boutique img {
    width: 20px;
    height: 20px;
    object-fit: contain;
    opacity: 0.7;
}

.modal-price-stock {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 24px;
    padding-bottom: 24px;
    border-bottom: 1px solid var(--border);
}

.modal-price {
    font-family: 'Cinzel', serif;
    font-size: 2rem;
    font-weight: 700;
    color: var(--accent);
}

.modal-stock {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    padding: 6px 14px;
    border-radius: 8px;
}

.modal-stock.in-stock {
    background: rgba(16,185,129,0.15);
    color: #10b981;
}

.modal-stock.out-stock {
    background: rgba(239,68,68,0.15);
    color: #ef4444;
}

.modal-stock img {
    width: 18px;
    height: 18px;
    object-fit: contain;
}

.modal-description {
    margin-bottom: 32px;
    flex: 1;
}

.modal-description h4 {
    font-family: 'Cinzel', serif;
    font-size: 1rem;
    color: var(--text-primary);
    margin-bottom: 12px;
}

.modal-description p {
    color: var(--text-secondary);
    font-size: 0.95rem;
    line-height: 1.8;
}

.modal-actions {
    display: flex;
    gap: 12px;
    align-items: center;
    padding-top: 24px;
    border-top: 1px solid var(--border);
}

.modal-add-cart,
.btn-wishlist-modal {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 14px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: 'DM Sans', sans-serif;
}

.modal-add-cart {
    border: none;
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dim) 100%);
    color: #000;
    box-shadow: 0 4px 20px rgba(201,168,76,0.3);
}

.modal-add-cart:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(201,168,76,0.4);
}

.modal-add-cart:disabled {
    background: var(--bg-tertiary);
    color: var(--text-muted);
    cursor: not-allowed;
    box-shadow: none;
}

.btn-wishlist-modal {
    border: 1px solid var(--border);
    background: var(--bg-card);
    color: var(--text-secondary);
}

.btn-wishlist-modal:hover {
    border-color: #ef4444;
    color: #ef4444;
}

.btn-wishlist-modal.active {
    border-color: #ef4444;
    color: #ef4444;
    background: rgba(239,68,68,0.15);
}

.modal-add-cart img,
.btn-wishlist-modal img {
    width: 20px;
    height: 20px;
    object-fit: contain;
}

/* ===== MODAL IMAGE CAROUSEL ===== */
.modal-image-carousel {
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.modal-image-carousel .carousel-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0;
    transition: opacity 0.4s ease;
    pointer-events: none;
}

.modal-image-carousel .carousel-slide.active {
    opacity: 1;
    pointer-events: auto;
}

.modal-image-carousel .carousel-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 1px solid rgba(255,255,255,0.2);
    background: rgba(0,0,0,0.5);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: all 0.3s ease;
    font-size: 1rem;
    backdrop-filter: blur(4px);
}

.modal-image-carousel .carousel-arrow:hover {
    background: var(--accent);
    color: #000;
    border-color: var(--accent);
}

.modal-image-carousel .carousel-prev { left: 12px; }
.modal-image-carousel .carousel-next { right: 12px; }

.modal-image-carousel .carousel-dots {
    position: absolute;
    bottom: 12px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 6px;
    z-index: 10;
}

.modal-image-carousel .carousel-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: rgba(255,255,255,0.4);
    transition: all 0.3s ease;
    cursor: pointer;
}

.modal-image-carousel .carousel-dot.active {
    background: var(--accent);
    width: 18px;
    border-radius: 3px;
}

.modal-image-count {
    position: absolute;
    bottom: 12px;
    right: 12px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
    background: rgba(0,0,0,0.6);
    color: white;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
    z-index: 10;
}

/* ===== ANIMATIONS ===== */
.reveal { opacity: 0; transform: translateY(30px); transition: all 0.6s ease; }
.reveal.active { opacity: 1; transform: translateY(0); }

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .boutique-hero {
        grid-template-columns: 1fr;
    }
    .boutique-hero-image {
        height: 280px;
    }
    .boutique-hero-info {
        padding: 28px;
    }
    .boutique-hero-title {
        font-size: 1.6rem;
    }
    .vault-title {
        font-size: 1.6rem;
    }
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    }
    .slide-sidebar { width: 280px; }
    .modal-product-grid {
        grid-template-columns: 1fr;
    }
    .modal-product-image {
        height: 300px;
    }
    .card-actions {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 640px) {
    .products-grid {
        grid-template-columns: 1fr;
    }
    .boutique-hero-meta {
        flex-direction: column;
        gap: 10px;
    }
}
</style>

@php
$productsData = $produits->mapWithKeys(function($produit) {
    return [$produit->id_product => [
        'id_product' => $produit->id_product,
        'nom' => $produit->nom,
        'prix' => $produit->prix,
        'stock' => $produit->stock,
        'description' => $produit->description,
        'categorie' => $produit->categorie ? ['nom' => $produit->categorie->nom] : null,
        'boutique' => $produit->boutique ? ['name' => $produit->boutique->name] : null,
        'images' => $produit->images->map(function($img) {
            return ['path' => $img->path];
        })->values(),
    ]];
});
@endphp

<script>
// ===== PRODUCT DATA =====
const productsData = @json($productsData);
const storageUrl = '{{ asset('storage') }}';
const favorisProducts = @json($favoris_products);

// ===== IMAGE CAROUSEL =====
function carouselNext(productId) {
    const carousel = document.getElementById('carousel-' + productId);
    if (!carousel) return;
    const total = parseInt(carousel.dataset.total);
    let current = parseInt(carousel.dataset.current);
    current = (current + 1) % total;
    carousel.dataset.current = current;
    updateCarousel(carousel, current);
}

function carouselPrev(productId) {
    const carousel = document.getElementById('carousel-' + productId);
    if (!carousel) return;
    const total = parseInt(carousel.dataset.total);
    let current = parseInt(carousel.dataset.current);
    current = (current - 1 + total) % total;
    carousel.dataset.current = current;
    updateCarousel(carousel, current);
}

function updateCarousel(carousel, current) {
    const slides = carousel.querySelectorAll('.carousel-slide');
    const dots = carousel.querySelectorAll('.carousel-dot');
    slides.forEach((slide, index) => slide.classList.toggle('active', index === current));
    dots.forEach((dot, index) => dot.classList.toggle('active', index === current));
}

// ===== PRODUCT MODAL =====
function openProductModal(id) {
    const product = productsData[id];
    if (!product) return;

    const imageContainer = document.getElementById('modalImageContainer');
    const images = product.images || [];

    if (images.length > 1) {
        let carouselHtml = `<div class="modal-image-carousel" id="modalCarousel" data-current="0" data-total="${images.length}">`;
        images.forEach((img, index) => {
            carouselHtml += `<img src="${storageUrl}/${img.path}" alt="${product.nom}" class="carousel-slide ${index === 0 ? 'active' : ''}" data-index="${index}" onerror="this.src='{{ asset('images/placeholder-product.png') }}'">`;
        });
        carouselHtml += `
            <button type="button" class="carousel-arrow carousel-prev" onclick="modalCarouselPrev()"><i class="fas fa-chevron-left"></i></button>
            <button type="button" class="carousel-arrow carousel-next" onclick="modalCarouselNext()"><i class="fas fa-chevron-right"></i></button>
            <div class="carousel-dots">`;
        images.forEach((img, index) => {
            carouselHtml += `<span class="carousel-dot ${index === 0 ? 'active' : ''}" data-index="${index}" onclick="goToModalSlide(${index})"></span>`;
        });
        carouselHtml += `</div><div class="modal-image-count"><i class="fas fa-images"></i> ${images.length}</div></div>`;
        imageContainer.innerHTML = carouselHtml;
    } else if (images.length === 1) {
        imageContainer.innerHTML = `<img src="${storageUrl}/${images[0].path}" alt="${product.nom}" onerror="this.src='{{ asset('images/placeholder-product.png') }}'">`;
    } else {
        imageContainer.innerHTML = `<img src="{{ asset('images/placeholder-product.png') }}" alt="${product.nom}">`;
    }

    document.getElementById('modalCategory').textContent = product.categorie?.nom || 'Uncategorized';
    document.getElementById('modalTitle').textContent = product.nom;
    document.getElementById('modalBoutique').querySelector('span').textContent = product.boutique?.name || '{{ $boutique->name }}';
    document.getElementById('modalPrice').textContent = parseFloat(product.prix).toFixed(2) + ' DT';

    const stockText = document.getElementById('modalStockText');
    const stockEl = document.getElementById('modalStock');
    if (product.stock > 0) {
        stockText.textContent = product.stock + ' in stock';
        stockEl.className = 'modal-stock in-stock';
    } else {
        stockText.textContent = 'Out of stock';
        stockEl.className = 'modal-stock out-stock';
    }

    document.getElementById('modalDescription').textContent = product.description || 'No description available.';

    // Wishlist form
    const isFav = favorisProducts.includes(product.id_product);
    const wishForm = document.getElementById('modalWishForm');
    const wishBtn = document.getElementById('modalWishBtn');
    
    if (isFav) {
        wishForm.action = `{{ url('client/remove-from-favoris') }}/${product.id_product}`;
        wishBtn.classList.add('active');
        wishBtn.querySelector('span').textContent = 'Remove';
    } else {
        wishForm.action = `{{ url('client/add-to-favoris') }}/${product.id_product}`;
        wishBtn.classList.remove('active');
        wishBtn.querySelector('span').textContent = 'WishList';
    }

    // Cart form
    document.getElementById('modalCartForm').action = `{{ url('client/add-to-panier') }}/${product.id_product}`;
    const cartBtn = document.getElementById('modalAddCart');
    if (product.stock <= 0) {
        cartBtn.disabled = true;
        cartBtn.innerHTML = '<img src="{{ asset('images/paniermini1.png') }}" alt="basket"><span>Out of Stock</span>';
    } else {
        cartBtn.disabled = false;
        cartBtn.innerHTML = '<img src="{{ asset('images/paniermini1.png') }}" alt="basket"><span>Basket</span>';
    }

    document.getElementById('productModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeProductModal() {
    document.getElementById('productModal').classList.remove('active');
    document.body.style.overflow = '';
}

// ===== MODAL CAROUSEL =====
function modalCarouselNext() {
    const carousel = document.getElementById('modalCarousel');
    if (!carousel) return;
    const total = parseInt(carousel.dataset.total);
    let current = parseInt(carousel.dataset.current);
    current = (current + 1) % total;
    carousel.dataset.current = current;
    updateModalCarousel(carousel, current);
}

function modalCarouselPrev() {
    const carousel = document.getElementById('modalCarousel');
    if (!carousel) return;
    const total = parseInt(carousel.dataset.total);
    let current = parseInt(carousel.dataset.current);
    current = (current - 1 + total) % total;
    carousel.dataset.current = current;
    updateModalCarousel(carousel, current);
}

function goToModalSlide(index) {
    const carousel = document.getElementById('modalCarousel');
    if (!carousel) return;
    carousel.dataset.current = index;
    updateModalCarousel(carousel, index);
}

function updateModalCarousel(carousel, current) {
    const slides = carousel.querySelectorAll('.carousel-slide');
    const dots = carousel.querySelectorAll('.carousel-dot');
    slides.forEach((slide, index) => slide.classList.toggle('active', index === current));
    dots.forEach((dot, index) => dot.classList.toggle('active', index === current));
}

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
    if (e.key === 'Escape') {
        if (slideSidebar.classList.contains('active')) closeSidebar();
        if (document.getElementById('productModal').classList.contains('active')) closeProductModal();
    }
});

// ===== SCROLL REVEAL =====
document.addEventListener('DOMContentLoaded', function() {
    const revealElements = document.querySelectorAll('.reveal');
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('active'); });
    }, { threshold: 0.1 });
    revealElements.forEach(el => revealObserver.observe(el));
});
</script>
@endsection