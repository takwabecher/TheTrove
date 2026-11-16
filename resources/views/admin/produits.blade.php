@extends('layouts.app')

@section('title', 'Valider les Produits — TheTrove')

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
            <a href="{{ route('admin.produits') }}" class="sidebar-nav-item active">
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
    <div class="container" style="max-width: 1400px; margin: 0 auto; padding: 0 5%;">

        <!-- WELCOME HEADER -->
        <div class="vault-header reveal">
            <div class="vault-header-top">
                <span class="section-label">Management</span>
            </div>
            <h2 class="vault-title">
                <img src="{{ asset('images/produitmini.png') }}" alt="produitmini">
                Treasures
            </h2>
            <p class="vault-subtitle">
                Review treasures shared by Keepers
            </p>
        </div>

        <!-- TABS -->
        <div class="clients-tabs reveal">
            <button class="tab-btn active" data-tab="pending" onclick="switchTab('pending')">
                <img src="{{ asset('images/waitmini.png') }}" alt="waitmini">
                <span>Pending</span>
                <span class="tab-count">{{ $produits->where('status', 'pending')->count() }}</span>
            </button>
            <button class="tab-btn" data-tab="approved" onclick="switchTab('approved')">
                <img src="{{ asset('images/approvedmini.png') }}" alt="approvedmini">
                <span>Approved</span>
                <span class="tab-count">{{ $produits->where('status', 'approved')->count() }}</span>
            </button>
        </div>

        <!-- PENDING TABLE -->
        <div class="tab-content active" id="pending">
            <div class="table-wrapper reveal">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Vault</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produits->where('status', 'pending') as $produit)
                        @php
                            $productImages = $produit->images;
                            $primaryImage = $produit->imagePrincipale;
                            $displayImage = $primaryImage ?? $productImages->first();
                            $imageCount = $productImages->count();
                        @endphp
                        <tr>
                            <td>
                                <div class="product-cell clickable" onclick="openProductModal({{ $produit->id_product }})">
                                    @if($displayImage)
                                        <img src="{{ asset('storage/' . $displayImage->path) }}" 
                                             alt="{{ $produit->nom }}" class="product-thumb"
                                             onerror="this.src='{{ asset('images/placeholder-product.png') }}'">
                                    @else
                                        <img src="{{ asset('images/placeholder-product.png') }}" 
                                             alt="{{ $produit->nom }}" class="product-thumb">
                                    @endif
                                    <div class="product-info-cell">
                                        <div class="product-name-cell">{{ $produit->nom }}</div>
                                        <div class="product-desc-cell">{{ Str::limit($produit->description, 40) }}</div>
                                        @if($imageCount > 1)
                                            <div class="product-image-count">
                                                <i class="fas fa-images"></i> {{ $imageCount }} images
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="vault-cell">
                                    <img src="{{ asset('images/boutiquemini2.png') }}" alt="boutiquemini">
                                    <span>{{ $produit->boutique->name ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td>{{ $produit->categorie->nom ?? 'Uncategorized' }}</td>
                            <td class="price-cell">{{ number_format($produit->prix, 2) }} DT</td>
                            <td>
                                <span class="stock-badge {{ $produit->stock > 0 ? 'in-stock' : 'out-stock' }}">
                                    {{ $produit->stock }}
                                </span>
                            </td>
                            <td>
                                <div class="action-cell">
                                    <form action="{{ route('admin.approveProduit', $produit->id_product) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="table-btn approve">
                                            <img src="{{ asset('images/accept.png') }}" alt="accept"> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.rejectProduit', $produit->id_product) }}" method="POST" style="display: inline;" onsubmit="return confirm('Decline this product?')">
                                        @csrf
                                        <button type="submit" class="table-btn decline">
                                            <img src="{{ asset('images/cancel.png') }}" alt="cancel"> Decline
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="empty-row">
                                <div class="empty-state">
                                    <p>No treasures awaiting approval.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- APPROVED TABLE -->
        <div class="tab-content" id="approved">
            <div class="table-wrapper reveal">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Vault</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produits->where('status', 'approved') as $produit)
                        @php
                            $productImages = $produit->images;
                            $primaryImage = $produit->imagePrincipale;
                            $displayImage = $primaryImage ?? $productImages->first();
                            $imageCount = $productImages->count();
                        @endphp
                        <tr>
                            <td>
                                <div class="product-cell clickable" onclick="openProductModal({{ $produit->id_product }})">
                                    @if($displayImage)
                                        <img src="{{ asset('storage/' . $displayImage->path) }}" 
                                             alt="{{ $produit->nom }}" class="product-thumb"
                                             onerror="this.src='{{ asset('images/placeholder-product.png') }}'">
                                    @else
                                        <img src="{{ asset('images/placeholder-product.png') }}" 
                                             alt="{{ $produit->nom }}" class="product-thumb">
                                    @endif
                                    <div class="product-info-cell">
                                        <div class="product-name-cell">{{ $produit->nom }}</div>
                                        <div class="product-desc-cell">{{ Str::limit($produit->description, 40) }}</div>
                                        @if($imageCount > 1)
                                            <div class="product-image-count">
                                                <i class="fas fa-images"></i> {{ $imageCount }} images
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="vault-cell">
                                    <img src="{{ asset('images/boutiquemini2.png') }}" alt="boutiquemini">
                                    <span>{{ $produit->boutique->name ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td>{{ $produit->categorie->nom ?? 'Uncategorized' }}</td>
                            <td class="price-cell">{{ number_format($produit->prix, 2) }} DT</td>
                            <td>
                                <span class="stock-badge {{ $produit->stock > 0 ? 'in-stock' : 'out-stock' }}">
                                    {{ $produit->stock }}
                                </span>
                            </td>
                            <td>
                                <div class="action-cell">
                                    <form action="{{ route('admin.deleteProduit', $produit->id_product) }}" method="POST" style="display: inline;" onsubmit="return confirm('Decline this approved product?')">
                                        @csrf
                                        <button type="submit" class="table-btn decline">
                                            <img src="{{ asset('images/delete.png') }}" alt="delete"> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="empty-row">
                                <div class="empty-state">
                                    <p>No approved treasures yet.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- ============================================================ -->
<!-- PRODUCT DETAIL MODAL — TWO COLUMN (like client)              -->
<!-- ============================================================ -->
<div class="modal-overlay" id="productModal">
    <div class="modal-container modal-large">
        <button class="modal-close" onclick="closeProductModal()">
            <img src="{{ asset('images/cancel.png') }}" alt="cancel">
        </button>

        <div class="modal-product-grid">
            <!-- LEFT: IMAGE SECTION -->
            <div class="modal-product-image" id="modalImageContainer">
                <img id="modalImage" src="" alt="">
            </div>

            <!-- RIGHT: DETAILS SECTION -->
            <div class="modal-product-info">
                <div class="modal-product-header">
                    <span class="modal-status" id="modalStatus"></span>
                    <h2 class="modal-title" id="modalTitle"></h2>
                    <div class="modal-boutique" id="modalBoutique">
                        <img src="{{ asset('images/boutiquemini2.png') }}" alt="boutique">
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

                <div class="modal-meta-row">
                    <div class="meta-item">
                        <img src="{{ asset('images/categoriemini1.png') }}" alt="c">
                        <span id="modalCategory"></span>
                    </div>
                    <div class="meta-item">
                        <img src="{{ asset('images/clock.png') }}" alt="t">
                        <span id="modalDate"></span>
                    </div>
                </div>

                <div class="modal-description">
                    <h4>Description</h4>
                    <p id="modalDescription"></p>
                </div>

                <div class="modal-actions" id="modalActions"></div>
            </div>
        </div>
    </div>
</div>

<style>
/* ===== SIDEBAR & LAYOUT ===== */
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
    z-index: 9998 !important;
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
    z-index: 9999 !important;
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

.vault-header { text-align: center; margin-bottom: 40px; }
.vault-header-top { margin-bottom: 16px; }
.vault-title { font-family: 'Cinzel', serif; font-size: 2.4rem; font-weight: 600; color: var(--text-primary); margin-bottom: 16px; letter-spacing: -0.5px; display: flex; align-items: center; justify-content: center; gap: 16px; }
.vault-title img { width: 48px; height: 48px; object-fit: contain; }
.vault-subtitle { font-size: 1.1rem; color: var(--text-secondary); font-weight: 300; line-height: 1.8; max-width: 520px; margin: 0 auto; }



/* ===== STATS ===== */
.stats-grid {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;
    max-width: 750px; margin: 0 auto 40px;
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

@media (max-width: 1024px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 768px) { .stats-grid { grid-template-columns: 1fr; } .vault-title { font-size: 1.8rem; } .slide-sidebar { width: 280px; } }

/* ===== TABS ===== */
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

/* ===== TAB CONTENT ===== */
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

/* ===== TABLE ===== */
.table-wrapper {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.4s ease;
}

.table-wrapper:hover {
    border-color: var(--border-hover);
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table thead {
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-hover) 100%);
}

.data-table th {
    padding: 16px 20px;
    text-align: left;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
    border-bottom: 1px solid var(--border);
}

.data-table td {
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
    color: var(--text-secondary);
    font-size: 0.9rem;
    vertical-align: middle;
}

.data-table tbody tr:hover {
    background: var(--bg-hover);
}

.data-table tbody tr:last-child td {
    border-bottom: none;
}

/* ===== PRODUCT CELL ===== */
.product-cell {
    display: flex;
    align-items: center;
    gap: 14px;
}

.product-cell.clickable {
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 8px;
    border-radius: 12px;
    margin: -8px;
}

.product-cell.clickable:hover {
    background: var(--accent-glow);
    transform: translateX(4px);
}

.product-cell.clickable:hover .product-name-cell {
    color: var(--accent);
}

.product-thumb {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    object-fit: cover;
    border: 1px solid var(--border);
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.product-cell.clickable:hover .product-thumb {
    border-color: var(--accent);
    box-shadow: 0 0 15px var(--accent-glow);
}

.product-info-cell {
    min-width: 0;
}

.product-name-cell {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.95rem;
    margin-bottom: 4px;
    transition: color 0.3s ease;
}

.product-desc-cell {
    font-size: 0.8rem;
    color: var(--text-muted);
}

.product-image-count {
    font-size: 0.75rem;
    color: var(--accent);
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* ===== VAULT CELL ===== */
.vault-cell {
    display: flex;
    align-items: center;
    gap: 8px;
}

.vault-cell img {
    width: 20px;
    height: 20px;
    object-fit: contain;
    opacity: 0.7;
}

/* ===== PRICE CELL ===== */
.price-cell {
    font-family: 'Cinzel', serif;
    font-weight: 700;
    color: var(--accent);
    font-size: 1rem;
}

/* ===== STOCK BADGE ===== */
.stock-badge {
    padding: 6px 12px;
    border-radius: 10px;
    font-size: 0.8rem;
    font-weight: 600;
}

.stock-badge.in-stock {
    background: rgba(16, 185, 129, 0.15);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.stock-badge.out-stock {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

/* ===== ACTION CELL ===== */
.action-cell {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.table-btn {
    display: inline-flex;
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

.table-btn img {
    width: 16px;
    height: 16px;
    object-fit: contain;
}

.table-btn.approve {
    background: rgba(16, 185, 129, 0.15);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.table-btn.approve:hover {
    background: #10b981;
    color: var(--bg-primary);
    transform: translateY(-2px);
}

.table-btn.decline {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.table-btn.decline:hover {
    background: #ef4444;
    color: var(--bg-primary);
    transform: translateY(-2px);
}

/* ===== EMPTY ROW ===== */
.empty-row {
    text-align: center;
    padding: 60px 20px;
}

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.empty-state p {
    margin: 0;
    font-size: 1rem;
    color: var(--text-muted);
}

/* ============================================================ */
/* MODAL — TWO COLUMN LAYOUT (mirrors client)                   */
/* ============================================================ */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.85);
    backdrop-filter: blur(16px);
    z-index: 2000;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.4s ease;
    padding: 24px;
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
    position: relative;
    transform: scale(0.92) translateY(20px);
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 40px 80px rgba(0, 0, 0, 0.6);
}

.modal-overlay.active .modal-container {
    transform: scale(1) translateY(0);
}

/* Close button */
.modal-close {
    position: absolute;
    top: 20px;
    right: 20px;
    z-index: 100;
    width: 40px;
    height: 40px;
    border-radius: 12px;
    border: 1px solid var(--border);
    background: rgba(0, 0, 0, 0.6);
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
    background: rgba(239, 68, 68, 0.2);
    border-color: rgba(239, 68, 68, 0.4);
    color: #ef4444;
}
.modal-close img {
    width: 18px;
    height: 18px;
    object-fit: contain;
    opacity: 0.8;
}

/* ===== TWO COLUMN GRID ===== */
.modal-product-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    min-height: 500px;
    max-height: 90vh;
}

/* ===== LEFT: IMAGE SECTION ===== */
.modal-product-image {
    position: relative;
    overflow: hidden;
    background: var(--bg-tertiary);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-product-image > img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* ===== RIGHT: INFO SECTION ===== */
.modal-product-info {
    padding: 40px;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    max-height: 90vh;
}

.modal-product-header {
    margin-bottom: 20px;
}

.modal-status {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-bottom: 12px;
    letter-spacing: 0.5px;
}
.modal-status.pending {
    background: rgba(245, 158, 11, 0.15);
    color: #f59e0b;
    border: 1px solid rgba(245, 158, 11, 0.3);
}
.modal-status.approved {
    background: rgba(16, 185, 129, 0.15);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}
.modal-status.declined {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.modal-title {
    font-family: 'Cinzel', serif;
    font-size: 1.8rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 12px;
    line-height: 1.2;
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

/* Price & Stock row */
.modal-price-stock {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--border);
}

.modal-price {
    font-family: 'Cinzel', serif;
    font-size: 2rem;
    font-weight: 700;
    color: var(--accent);
    text-shadow: 0 0 20px var(--accent-glow);
}

.modal-stock {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    padding: 6px 14px;
    border-radius: 10px;
    font-weight: 600;
}
.modal-stock.in-stock {
    background: rgba(16, 185, 129, 0.15);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}
.modal-stock.out-stock {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}
.modal-stock img {
    width: 18px;
    height: 18px;
    object-fit: contain;
}

/* Meta row (category + date) */
.modal-meta-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 24px;
}
.modal-meta-row .meta-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    background: var(--bg-tertiary);
    border: 1px solid var(--border);
    border-radius: 12px;
    font-size: 0.9rem;
    color: var(--text-secondary);
}
.modal-meta-row .meta-item img {
    width: 18px;
    height: 18px;
    object-fit: contain;
    opacity: 0.7;
}

/* Description */
.modal-description {
    margin-bottom: 24px;
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
    line-height: 1.7;
    font-size: 0.95rem;
}

/* Actions */
.modal-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    padding-top: 20px;
    border-top: 1px solid var(--border);
}
.modal-actions .table-btn {
    flex: 1;
    justify-content: center;
    padding: 14px 24px;
    font-size: 0.95rem;
    min-width: 140px;
}

/* ===== MODAL IMAGE CAROUSEL (inside left column) ===== */
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
    width: 44px;
    height: 44px;
    border-radius: 50%;
    border: 1px solid rgba(255, 255, 255, 0.2);
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(10px);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 20;
    transition: all 0.3s ease;
    font-size: 1rem;
}
.modal-image-carousel .carousel-arrow:hover {
    background: var(--accent);
    color: #000;
    border-color: var(--accent);
    transform: translateY(-50%) scale(1.1);
}
.modal-image-carousel .carousel-prev { left: 16px; }
.modal-image-carousel .carousel-next { right: 16px; }
.modal-image-carousel .carousel-dots {
    position: absolute;
    bottom: 16px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 8px;
    z-index: 20;
    padding: 8px 16px;
    background: rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(10px);
    border-radius: 20px;
}
.modal-image-carousel .carousel-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.4);
    transition: all 0.3s ease;
    cursor: pointer;
}
.modal-image-carousel .carousel-dot.active {
    background: var(--accent);
    width: 24px;
    border-radius: 4px;
}
.modal-image-count {
    position: absolute;
    top: 16px;
    right: 16px;
    padding: 8px 14px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
    background: rgba(0, 0, 0, 0.6);
    color: white;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    z-index: 20;
}

/* ===== ANIMATIONS ===== */
.reveal {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s ease;
}

.reveal.active {
    opacity: 1;
    transform: translateY(0);
}

/* ===== MOBILE ===== */
@media (max-width: 1024px) {
    .table-wrapper {
        overflow-x: auto;
    }
    .data-table {
        min-width: 900px;
    }
}

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
    .action-cell {
        flex-direction: column;
    }
    .table-btn {
        width: 100%;
        justify-content: center;
    }
    /* Modal responsive */
    .modal-product-grid {
        grid-template-columns: 1fr;
        min-height: auto;
    }
    .modal-product-image {
        height: 300px;
        min-height: 300px;
    }
    .modal-product-info {
        padding: 24px;
        max-height: none;
    }
    .modal-title {
        font-size: 1.4rem;
    }
    .modal-price {
        font-size: 1.6rem;
    }
    .modal-meta-row {
        grid-template-columns: 1fr;
    }
    .modal-actions .table-btn {
        width: 100%;
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

// ===== TABS =====
function switchTab(tabName) {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabBtns.forEach(btn => {
        btn.classList.toggle('active', btn.dataset.tab === tabName);
    });

    tabContents.forEach(content => {
        content.classList.toggle('active', content.id === tabName);
    });
}

// ===== BUILD PRODUCTS DATA =====
@php
$productsData = $produits->map(function($p) {
    return [
        'id_product' => $p->id_product,
        'nom' => $p->nom,
        'prix' => $p->prix,
        'stock' => $p->stock,
        'status' => $p->status,
        'description' => $p->description,
        'created_at' => $p->created_at,
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
const storageUrl = '{{ asset('storage') }}';

// ===== PRODUCT MODAL =====
function openProductModal(id) {
    const product = productsData[id];
    if (!product) return;

    const imageContainer = document.getElementById('modalImageContainer');
    const images = product.images || [];

    if (images.length > 1) {
        // Multi-image carousel
        let carouselHtml = `<div class="modal-image-carousel" id="modalCarousel" data-current="0" data-total="${images.length}">`;
        images.forEach((img, index) => {
            carouselHtml += `<img src="${storageUrl}/${img.path}" alt="${product.nom}" class="carousel-slide ${index === 0 ? 'active' : ''}" data-index="${index}" onerror="this.src='{{ asset('images/placeholder-product.png') }}'">`;
        });
        carouselHtml += `
            <button type="button" class="carousel-arrow carousel-prev" onclick="event.stopPropagation(); modalCarouselPrev()"><i class="fas fa-chevron-left"></i></button>
            <button type="button" class="carousel-arrow carousel-next" onclick="event.stopPropagation(); modalCarouselNext()"><i class="fas fa-chevron-right"></i></button>
            <div class="carousel-dots">`;
        images.forEach((img, index) => {
            carouselHtml += `<span class="carousel-dot ${index === 0 ? 'active' : ''}" data-index="${index}" onclick="event.stopPropagation(); goToModalSlide(${index})"></span>`;
        });
        carouselHtml += `</div><div class="modal-image-count"><i class="fas fa-images"></i> ${images.length}</div></div>`;
        imageContainer.innerHTML = carouselHtml;
    } else if (images.length === 1) {
        // Single image
        imageContainer.innerHTML = `<img src="${storageUrl}/${images[0].path}" alt="${product.nom}" style="width:100%;height:100%;object-fit:cover;" onerror="this.src='{{ asset('images/placeholder-product.png') }}'">`;
    } else {
        // No images - placeholder
        imageContainer.innerHTML = `<img src="{{ asset('images/placeholder-product.png') }}" alt="${product.nom}" style="width:100%;height:100%;object-fit:cover;opacity:0.5;">`;
    }

    // Set status badge
    const modalStatus = document.getElementById('modalStatus');
    modalStatus.className = 'modal-status ' + product.status;
    if (product.status === 'pending') {
        modalStatus.innerHTML = '<img src="{{ asset('images/waitmini.png') }}" alt="wait" style="width:14px;height:14px;"> Pending Approval';
    } else if (product.status === 'approved') {
        modalStatus.innerHTML = '<img src="{{ asset('images/approvedmini.png') }}" alt="approved" style="width:14px;height:14px;"> Approved';
    } else if (product.status === 'declined') {
        modalStatus.innerHTML = '<img src="{{ asset('images/cancel.png') }}" alt="declined" style="width:14px;height:14px;"> Declined';
    }

    // Set text fields
    document.getElementById('modalTitle').textContent = product.nom;
    document.getElementById('modalBoutique').querySelector('span').textContent = product.boutique?.name || 'Unknown Vault';
    document.getElementById('modalCategory').textContent = product.categorie?.nom || 'Uncategorized';
    document.getElementById('modalDate').textContent = product.created_at ? new Date(product.created_at).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) : 'N/A';
    document.getElementById('modalPrice').textContent = parseFloat(product.prix).toFixed(2) + ' DT';
    document.getElementById('modalDescription').textContent = product.description || 'No description available.';

    // Stock
    const stockText = document.getElementById('modalStockText');
    const stockEl = document.getElementById('modalStock');
    if (product.stock > 0) {
        stockText.textContent = product.stock + ' units in stock';
        stockEl.className = 'modal-stock in-stock';
    } else {
        stockText.textContent = 'Out of stock';
        stockEl.className = 'modal-stock out-stock';
    }

    // Set actions based on status
    const modalActions = document.getElementById('modalActions');
    modalActions.innerHTML = '';

    if (product.status === 'pending') {
        modalActions.innerHTML = `
            <form action="{{ url('admin/approve-produit') }}/${product.id_product}" method="POST" style="flex: 1;">
                @csrf
                <button type="submit" class="table-btn approve" style="width: 100%;">
                    <img src="{{ asset('images/accept.png') }}" alt="accept"> Approve Product
                </button>
            </form>
            <form action="{{ url('admin/reject-produit') }}/${product.id_product}" method="POST" style="flex: 1;" onsubmit="return confirm('Decline this product?')">
                @csrf
                <button type="submit" class="table-btn decline" style="width: 100%;">
                    <img src="{{ asset('images/cancel.png') }}" alt="cancel"> Decline Product
                </button>
            </form>
        `;
    } else if (product.status === 'approved') {
        modalActions.innerHTML = `
            <form action="{{ url('admin/reject-produit') }}/${product.id_product}" method="POST" style="flex: 1;" onsubmit="return confirm('Decline this approved product?')">
                @csrf
                <button type="submit" class="table-btn decline" style="width: 100%;">
                    <img src="{{ asset('images/delete.png') }}" alt="delete"> Delete Product
                </button>
            </form>
        `;
    } else if (product.status === 'declined') {
        modalActions.innerHTML = `
            <form action="{{ url('admin/approve-produit') }}/${product.id_product}" method="POST" style="flex: 1;">
                @csrf
                <button type="submit" class="table-btn approve" style="width: 100%;">
                    <img src="{{ asset('images/accept.png') }}" alt="accept"> Re-Approve Product
                </button>
            </form>
        `;
    }

    // Show modal
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
    slides.forEach((slide, index) => {
        slide.classList.toggle('active', index === current);
    });
    dots.forEach((dot, index) => {
        dot.classList.toggle('active', index === current);
    });
}

// Close modal on overlay click
document.getElementById('productModal').addEventListener('click', (e) => {
    if (e.target === document.getElementById('productModal')) {
        closeProductModal();
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