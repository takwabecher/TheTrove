@extends('layouts.app')

@section('title', ($category->nom ?? $category->name) . ' — TheTrove')

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

        <!-- BREADCRUMB -->
        <div class="reveal" style="margin-bottom: 24px;">
            <nav style="display: flex; align-items: center; gap: 12px; font-size: 0.85rem; color: var(--text-muted);">
                <a href="{{ route('client.categories') }}" style="color: var(--accent); text-decoration: none;">Categories</a>
                <span style="color: var(--border-hover);">/</span>
                <span style="color: var(--text-primary); font-weight: 500;">{{ $category->nom ?? $category->name }}</span>
            </nav>
        </div>

        <!-- COMPACT CATEGORY HEADER (top-right corner style) -->
    
        <div class="category-header-compact reveal">
            <div class="category-header-left">
                
                <h1 class="vault-title" style="font-size: 1.8rem; margin-bottom: 0;">
                    <img src="{{ asset('images/produit.png') }}" alt="products">
                    Available <span style="color: var(--accent); font-weight: 600;">Pieces</span>
                </h1>
            </div>

            <div class="category-badge-corner">
                <div class="category-badge-img">
                    <img src="{{ $category->image ? asset('storage/' . $category->image) : asset('images/categoriemini1.png') }}" 
                         alt="{{ $category->nom ?? $category->name }}"
                         onerror="this.src='{{ asset('images/categoriemini1.png') }}'">
                </div>
                <div class="category-badge-info">
                    <span class="category-badge-label">Collection</span>
                    <span class="category-badge-name">{{ $category->nom ?? $category->name }}</span>
                </div>
            </div>
        </div>

        <!-- PRODUCTS SECTION -->
        <div class="products-section reveal">
            @if($products->count() > 0)
            <div class="products-grid">
                @foreach($products as $product)
                @php
                    $productImages = $product->images;
                    $primaryImage = $product->imagePrincipale;
                    $imageCount = $productImages->count();
                    $isFav = in_array($product->id_product, $favoris_products ?? []);
                @endphp
                <div class="product-card reveal" onclick="openProductModal({{ $product->id_product }})">
                    <div class="product-image-wrapper">
                        @if($imageCount > 0)
                            <div class="image-carousel" id="carousel-{{ $product->id_product }}" data-current="0" data-total="{{ $imageCount }}">
                                @foreach($productImages as $index => $img)
                                    <img src="{{ asset('storage/' . $img->path) }}" 
                                         alt="{{ $product->nom }}" 
                                         class="carousel-slide {{ $index === 0 ? 'active' : '' }}"
                                         data-index="{{ $index }}"
                                         onerror="this.src='{{ asset('images/placeholder-product.png') }}'">
                                @endforeach

                                @if($imageCount > 1)
                                    <button type="button" class="carousel-arrow carousel-prev" onclick="event.stopPropagation(); carouselPrev({{ $product->id_product }})">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button type="button" class="carousel-arrow carousel-next" onclick="event.stopPropagation(); carouselNext({{ $product->id_product }})">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                    <div class="carousel-dots">
                                        @foreach($productImages as $index => $img)
                                            <span class="carousel-dot {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}"></span>
                                        @endforeach
                                    </div>
                                    <div class="image-count-badge">
                                        <i class="fas fa-images"></i> {{ $imageCount }}
                                    </div>
                                @endif
                            </div>
                        @else
                            <img src="{{ asset('images/placeholder-product.png') }}" 
                                 alt="{{ $product->nom }}" 
                                 style="width: 100%; height: 100%; object-fit: cover; opacity: 0.5;">
                        @endif

                        <!-- Wishlist button (top-right) -->
                        @if($isFav)
                            <form action="{{ route('client.removeFromFavoris', $product->id_product) }}" method="POST" 
                                  class="wishlist-form" onclick="event.stopPropagation()">
                                @csrf
                                <button type="submit" class="wishlist-btn active" title="Remove from Wishlist">
                                    <img src="{{ asset('images/wishmini.png') }}" alt="wish">
                                </button>
                            </form>
                        @else
                            <form action="{{ route('client.addToFavoris', $product->id_product) }}" method="POST" 
                                  class="wishlist-form" onclick="event.stopPropagation()">
                                @csrf
                                <button type="submit" class="wishlist-btn" title="Add to Wishlist">
                                    <img src="{{ asset('images/wishmini.png') }}" alt="wish">
                                </button>
                            </form>
                        @endif
                    </div>
                    <div class="product-info">
                        <div class="product-vault">
                            <img src="{{ asset('images/boutiquemini1.png') }}" alt="vault">
                            <span>{{ $product->boutique->name ?? 'Unknown Vault' }}</span>
                        </div>
                        <h3 class="product-name">{{ $product->nom }}</h3>
                        <p class="product-desc">{{ Str::limit($product->description ?? 'A unique piece from this collection.', 60) }}</p>
                        <div class="product-footer">
                            <span class="product-price">{{ number_format($product->prix, 2) }} <span>DT</span></span>

                            <div class="product-actions-row" onclick="event.stopPropagation()">
                                @if($isFav)
                                    <form action="{{ route('client.removeFromFavoris', $product->id_product) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="action-icon-btn active" title="Remove from Wishlist">
                                            <img src="{{ asset('images/wishmini.png') }}" alt="wish">
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('client.addToFavoris', $product->id_product) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="action-icon-btn" title="Add to Wishlist">
                                            <img src="{{ asset('images/wishmini.png') }}" alt="wish">
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('client.addToPanier', $product->id_product) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="action-icon-btn cart" {{ $product->stock <= 0 ? 'disabled' : '' }} title="Add to Basket">
                                        <img src="{{ asset('images/paniermini1.png') }}" alt="basket">
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="pagination-wrap">
                {{ $products->links() }}
            </div>
            @else
            <div class="empty-state reveal">
                <h3>No Pieces Available</h3>
                <p>This collection has no approved products yet.</p>
            </div>
            @endif
        </div>

        <!-- BACK BUTTON -->
        <div class="reveal" style="text-align: center; margin-top: 40px;">
            <a href="{{ route('client.categories') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Categories</span>
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
                    <span class="modal-stock" id="modalStock">
                        <img src="{{ asset('images/stock.png') }}" alt="stock" id="modalStockImg">
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
.vault-title img { width: 40px; height: 40px; object-fit: contain; }

/* ===== COMPACT CATEGORY HEADER (corner badge style) ===== */
.category-header-compact {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 40px;
    gap: 24px;
    flex-wrap: wrap;
}

.category-header-left {
    flex: 1;
    min-width: 250px;
}

.category-header-subtitle {
    color: var(--text-muted);
    font-size: 0.95rem;
    margin-top: 4px;
    font-weight: 300;
}

/* Corner badge */
.category-badge-corner {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    transition: all 0.3s ease;
    max-width: 360px;
    flex-shrink: 0;
}
.category-badge-corner:hover {
    border-color: var(--border-hover);
    box-shadow: 0 8px 24px var(--shadow);
    transform: translateY(-2px);
}

.category-badge-img {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    overflow: hidden;
    flex-shrink: 0;
    border: 1px solid var(--border);
    background: var(--bg-tertiary);
}
.category-badge-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.category-badge-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
    min-width: 0;
}

.category-badge-label {
    font-size: 0.65rem;
    font-weight: 600;
    color: var(--accent);
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-family: 'Cinzel', serif;
}

.category-badge-name {
    font-family: 'Cinzel', serif;
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--text-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.category-badge-count {
    font-size: 0.75rem;
    color: var(--text-muted);
    font-weight: 400;
}

/* ===== PRODUCTS SECTION ===== */
.products-section {
    margin-bottom: 40px;
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
    z-index: 5;
}

/* Wishlist form/button */
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
}

.product-vault {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    font-size: 0.8rem;
    color: var(--text-muted);
}
.product-vault img {
    width: 16px;
    height: 16px;
    object-fit: contain;
    opacity: 0.6;
}

.product-name {
    font-family: 'Cinzel', serif;
    font-size: 1.1rem;
    color: var(--text-primary);
    margin-bottom: 6px;
    font-weight: 600;
}

.product-desc {
    font-size: 0.85rem;
    color: var(--text-muted);
    line-height: 1.5;
    margin-bottom: 16px;
}

.product-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 16px;
    border-top: 1px solid var(--border);
}

.product-price {
    font-family: 'Cinzel', serif;
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--accent);
}
.product-price span {
    font-size: 0.8rem;
    color: var(--accent-dim);
    font-weight: 400;
}

.product-actions-row {
    display: flex;
    gap: 8px;
    align-items: center;
}

.action-icon-btn {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    border: 1px solid var(--border);
    background: var(--bg-card);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 0;
}
.action-icon-btn img {
    width: 16px;
    height: 16px;
    object-fit: contain;
    opacity: 0.7;
}
.action-icon-btn:hover {
    border-color: var(--accent);
    background: var(--accent-glow);
}
.action-icon-btn:hover img {
    opacity: 1;
}
.action-icon-btn.active {
    background: rgba(239,68,68,0.15);
    border-color: #ef4444;
}
.action-icon-btn.active img {
    opacity: 1;
    filter: brightness(0) saturate(100%) invert(31%) sepia(91%) saturate(5058%) hue-rotate(346deg) brightness(97%) contrast(92%);
}
.action-icon-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}
.action-icon-btn.cart {
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dim) 100%);
    border: none;
}
.action-icon-btn.cart img {
    opacity: 1;
    filter: brightness(0);
}
.action-icon-btn.cart:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(201,168,76,0.3);
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

/* ===== PAGINATION ===== */
.pagination-wrap {
    display: flex;
    justify-content: center;
    margin-top: 40px;
}
.pagination-wrap nav {
    display: flex;
    gap: 8px;
}
.pagination-wrap a,
.pagination-wrap span {
    padding: 10px 16px;
    border-radius: 10px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    color: var(--text-secondary);
    font-size: 0.9rem;
    text-decoration: none;
    transition: all 0.3s ease;
}
.pagination-wrap a:hover {
    border-color: var(--border-hover);
    color: var(--accent);
}
.pagination-wrap span[aria-current="page"] {
    background: var(--accent);
    color: #000;
    border-color: var(--accent);
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
    background: rgba(239,68,68,0.1);
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

/* Modal image carousel */
.modal-image-carousel {
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
}
.modal-image-carousel .carousel-slide {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
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
    width: 40px; height: 40px;
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
    width: 6px; height: 6px;
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
    .category-header-compact {
        flex-direction: column;
        align-items: stretch;
    }
    .category-badge-corner {
        max-width: 100%;
        align-self: flex-end;
    }
    .vault-title {
        font-size: 1.6rem;
    }
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    }
    .slide-sidebar { width: 280px; }
    .modal-product-grid { grid-template-columns: 1fr; }
    .modal-product-image { height: 300px; }
}

@media (max-width: 640px) {
    .products-grid {
        grid-template-columns: 1fr;
    }
    .category-badge-corner {
        align-self: center;
    }
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
    if (e.key === 'Escape' && document.getElementById('productModal').classList.contains('active')) {
        closeProductModal();
    }
});

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
    slides.forEach((slide, index) => {
        slide.classList.toggle('active', index === current);
    });
    dots.forEach((dot, index) => {
        dot.classList.toggle('active', index === current);
    });
}

// ===== BUILD PRODUCTS DATA =====
@php
$productsData = $products->map(function($p) {
    return [
        'id_product' => $p->id_product,
        'nom' => $p->nom,
        'prix' => $p->prix,
        'stock' => $p->stock,
        'description' => $p->description,
        'categorie' => $p->categorie ? ['nom' => $p->categorie->nom] : null,
        'boutique' => $p->boutique ? ['name' => $p->boutique->name] : null,
        'images' => $p->images->map(function($img) {
            return [
                'id_image' => $img->id_image,
                'path' => $img->path,
                'is_primary' => $img->is_primary,
            ];
        })->values(),
    ];
})->keyBy('id_product');
@endphp

const productsData = @json($productsData);
const favorisProducts = @json($favoris_products ?? []);
const storageUrl = '{{ asset('storage') }}';

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
    document.getElementById('modalBoutique').querySelector('span').textContent = product.boutique?.name || 'Unknown Vault';
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

    const isFav = favorisProducts.includes(product.id_product);
    const wishForm = document.getElementById('modalWishForm');
    const wishBtn = document.getElementById('modalWishBtn');

    if (isFav) {
        wishForm.action = `{{ url('client/remove-from-favoris') }}/${product.id_product}`;
        wishBtn.classList.add('active');
        wishBtn.querySelector('span').textContent = 'Remove from WishList';
    } else {
        wishForm.action = `{{ url('client/add-to-favoris') }}/${product.id_product}`;
        wishBtn.classList.remove('active');
        wishBtn.querySelector('span').textContent = 'Add to WishList';
    }

    document.getElementById('modalCartForm').action = `{{ url('client/add-to-panier') }}/${product.id_product}`;

    const cartBtn = document.getElementById('modalAddCart');
    if (product.stock <= 0) {
        cartBtn.disabled = true;
        cartBtn.innerHTML = '<img src="{{ asset('images/paniermini1.png') }}" alt="basket"><span>Out of Stock</span>';
    } else {
        cartBtn.disabled = false;
        cartBtn.innerHTML = '<img src="{{ asset('images/paniermini1.png') }}" alt="basket"><span>Add to Basket</span>';
    }

    document.getElementById('productModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeProductModal() {
    document.getElementById('productModal').classList.remove('active');
    document.body.style.overflow = '';
}

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
    slides.forEach((slide, index) => {
        slide.classList.toggle('active', index === current);
    });
    dots.forEach((dot, index) => {
        dot.classList.toggle('active', index === current);
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