@extends('layouts.app')

@section('title', 'Mes Produits — TheTrove')

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
            <a href="{{ route('boutique.dashboard') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/mainmini1.png') }}" alt="mainmini">
                <span>Home</span>
            </a>
            <a href="{{ route('boutique.categories') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/categoriemini1.png') }}" alt="categoriemini">
                <span>Categories</span>
            </a>
            <a href="{{ route('boutique.produits') }}" class="sidebar-nav-item active">
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

        <!-- HEADER -->
        <div class="vault-header reveal">
            <h2 class="vault-title">
                <img src="{{ asset('images/produitmini.png') }}" alt="prod">My <span style="color: var(--accent); font-weight: 600;">Products</span>
            </h2>
            <p class="vault-subtitle">
                Manage your items, track approvals, and grow your treasure.
            </p>
        </div>


        <!-- ADD PRODUCT BUTTON -->
        <div class="section-header reveal" style="margin-bottom: 30px;">
            <button class="btn-add-product" onclick="openAddModal()">
                <img src="{{ asset('images/add.png') }}" alt="add">
                <span>Add New Product</span>
            </button>
        </div>

        <!-- FILTER TABS -->
        <div class="filter-tabs reveal" style="margin-bottom: 30px;">
            <button class="filter-tab active" data-filter="all" onclick="filterProducts('all')">
                <img src="{{ asset('images/produitmini1.png') }}" alt="produit"> All
            </button>
            <button class="filter-tab" data-filter="approved" onclick="filterProducts('approved')">
                <img src="{{ asset('images/approvedmini.png') }}" alt="approved"> Approved
            </button>
            <button class="filter-tab" data-filter="pending" onclick="filterProducts('pending')">
                <img src="{{ asset('images/waitmini.png') }}" alt="wait"> Pending
            </button>
        </div>

        <!-- PRODUCTS GRID -->
        <div class="products-grid" id="productsGrid">
            @forelse($produits as $produit)
            <div class="product-card reveal" data-status="{{ $produit->status }}">
                <div class="product-image-wrapper">
                
    @php
        $productImages = $produit->images;
        $primaryImage = $produit->imagePrincipale;
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
                <button type="button" class="carousel-arrow carousel-prev" onclick="carouselPrev({{ $produit->id_product }})">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button type="button" class="carousel-arrow carousel-next" onclick="carouselNext({{ $produit->id_product }})">
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
                    
                    <div class="product-status-badge {{ $produit->status }}">
                        @if($produit->status == 'approved')
                            <img src="{{ asset('images/approvedmini.png') }}" alt="approvedmini"> Approved
                        @elseif($produit->status == 'pending')
                            <img src="{{ asset('images/waitmini.png') }}" alt="waitmini"> Pending
                        @endif
                    </div>
                    <div class="product-actions-overlay">
                        <button class="action-btn edit" onclick="openEditModal({{ $produit->id_product }})" title="Edit">
                            <img src="{{ asset('images/edit.png') }}" alt="edit">
                        </button>
                        <button class="action-btn delete" onclick="confirmDelete({{ $produit->id_product }}, '{{ addslashes($produit->nom) }}')" title="Delete">
                            <img src="{{ asset('images/delete.png') }}" alt="delete">
                        </button>
                    </div>
                </div>
                <div class="product-info">
                    <h3 class="product-name">{{ $produit->nom }}</h3>
                    <p class="product-desc">{{ Str::limit($produit->description, 60) }}</p>
                    <p class="product-category">{{ $produit->categorie->nom ?? 'Uncategorized' }}</p>
                    <div class="product-footer">
                        <span class="product-price">{{ number_format($produit->prix, 2) }} <span>DT</span></span>
                        <span class="product-stock {{ $produit->stock > 0 ? 'in-stock' : 'out-stock' }}">
                            <img src="{{ asset('images/stock.png') }}" alt="stock"> {{ $produit->stock }}
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state reveal">
                <h3>No Products Yet</h3>
                <p>Start building your treasure by adding your first product.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- ADD PRODUCT MODAL -->
<!-- ADD PRODUCT MODAL -->
<div class="modal-overlay" id="addModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3><img src="{{ asset('images/add.png') }}" alt="add"> Add New Product</h3>
            <button class="modal-close" onclick="closeAddModal()">
                <img src="{{ asset('images/cancel.png') }}" alt="cancel">
            </button>
        </div>

        <form action="{{ route('boutique.addProduct') }}" method="POST" enctype="multipart/form-data" class="modal-form" id="addProductForm">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="nom">Product Name</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required placeholder="Enter product name">
                    @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="id_cat">Category</label>
                    <select id="id_cat" name="id_cat" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $categorie)
                        <option value="{{ $categorie->id_cat }}" {{ old('id_cat') == $categorie->id_cat ? 'selected' : '' }}>{{ $categorie->nom }}</option>
                        @endforeach
                    </select>
                    @error('id_cat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <!-- PRICE WITH FEE CALCULATOR -->
                <div class="form-group">
                    <label for="prix">Your Price (DT) <small style="color: var(--text-muted); font-weight: 400;">— What you earn</small></label>
                    <input type="number" id="prix" name="prix" step="0.01" min="0" required placeholder="0.00" value="{{ old('prix') }}">
                    @error('prix')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" id="stock" name="stock" min="0" required placeholder="0" value="{{ old('stock') }}">
                    @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <!-- PRICE BREAKDOWN -->
                <div class="form-group full-width">
                    <div style="background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 12px; padding: 16px;">
                        <p style="margin: 0 0 10px 0; font-weight: 600; color: var(--text-secondary); font-size: 0.85rem;">Price Breakdown</p>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px; padding-bottom: 6px; border-bottom: 1px dashed var(--border);">
                            <span style="color: var(--text-muted); font-size: 0.8rem;">You Earn</span>
                            <span id="boutiqueEarning" style="font-weight: 600; color: var(--text-primary);">0.00 DT</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px; padding-bottom: 6px; border-bottom: 1px dashed var(--border);">
                            <span style="color: var(--text-muted); font-size: 0.8rem;">Site Fee (<span class="feePercent">{{ $siteFeePercent ?? 10 }}</span>%)</span>
                            <span id="siteFee" style="font-weight: 600; color: var(--accent);">0.00 DT</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-secondary); font-size: 0.85rem; font-weight: 600;">Customer Pays</span>
                            <span id="customerPays" style="font-weight: 700; color: var(--accent); font-size: 1.1rem;">0.00 DT</span>
                        </div>
                        <input type="hidden" id="prix_final" name="prix_final" value="{{ old('prix_final') }}">
                        <input type="hidden" id="site_fee_amount" name="site_fee_amount" value="{{ old('site_fee_amount') }}">
                    </div>
                </div>
                
                <div class="form-group full-width">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="3" placeholder="Describe your product...">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group full-width">
                    <label>Product Images <small style="color: var(--text-muted); font-weight: 400;">(First = Primary)</small></label>
                    <div class="file-upload-wrapper" id="addDropZone">
                        <input type="file" id="addImages" name="images[]" accept="image/*" multiple onchange="handleMultiImagePreview(this, 'addPreviewContainer', 'addFileLabel','add')">
                        <div class="file-upload-label" id="addFileLabel">
                            <img src="{{ asset('images/upload.png') }}" alt="upload">
                            <span>Click or drag images here</span>
                            <small style="color: var(--text-muted); margin-top: 4px;">You can select multiple images</small>
                        </div>
                        <div id="addPreviewContainer" class="multi-preview-container"></div>
                    </div>
                    @error('images')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    @error('images.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeAddModal()"> 
                    <img src="{{ asset('images/cancel.png') }}" alt="cancel">Cancel
                </button>
                <button type="submit" class="btn-submit">
                    <img src="{{ asset('images/add.png') }}" alt="add"> Add Product
                </button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT PRODUCT MODAL -->
<div class="modal-overlay" id="editModal">
    <div class="modal-container" style="max-width: 700px;">
        <div class="modal-header">
            <h3><img src="{{ asset('images/edit.png') }}" alt="edit"> Edit Product</h3>
            <button class="modal-close" onclick="closeEditModal()">
                <img src="{{ asset('images/cancel.png') }}" alt="cancel">
            </button>
        </div>

        <form method="POST" enctype="multipart/form-data" class="modal-form" id="editProductForm">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="edit_nom">Product Name</label>
                    <input type="text" id="edit_nom" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="edit_id_cat">Category</label>
                    <select id="edit_id_cat" name="id_cat" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $categorie)
                        <option value="{{ $categorie->id_cat }}">{{ $categorie->nom }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- PRICE WITH FEE CALCULATOR -->
                <div class="form-group">
                    <label for="edit_prix">Your Price (DT) <small style="color: var(--text-muted); font-weight: 400;">— What you earn</small></label>
                    <input type="number" id="edit_prix" name="prix" step="0.01" min="0" required
                           oninput="updateEditPriceBreakdown()">
                </div>
                
                <div class="form-group">
                    <label for="edit_stock">Stock</label>
                    <input type="number" id="edit_stock" name="stock" min="0" required>
                </div>
                
                <!-- PRICE BREAKDOWN -->
                <div class="form-group full-width">
                    <div style="background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 12px; padding: 16px;">
                        <p style="margin: 0 0 10px 0; font-weight: 600; color: var(--text-secondary); font-size: 0.85rem;"> Price Breakdown</p>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px; padding-bottom: 6px; border-bottom: 1px dashed var(--border);">
                            <span style="color: var(--text-muted); font-size: 0.8rem;">You Earn</span>
                            <span id="editBoutiqueEarning" style="font-weight: 600; color: var(--text-primary);">0.00 DT</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px; padding-bottom: 6px; border-bottom: 1px dashed var(--border);">
                            <span style="color: var(--text-muted); font-size: 0.8rem;">Site Fee (<span class="feePercent">{{ $siteFeePercent ?? 10 }}</span>%)</span>
                            <span id="editSiteFee" style="font-weight: 600; color: var(--accent);">0.00 DT</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-secondary); font-size: 0.85rem; font-weight: 600;">Customer Pays</span>
                            <span id="editCustomerPays" style="font-weight: 700; color: var(--accent); font-size: 1.1rem;">0.00 DT</span>
                        </div>
                        <input type="hidden" id="edit_prix_final" name="prix_final">
                        <input type="hidden" id="edit_site_fee_amount" name="site_fee_amount">
                    </div>
                </div>
                
                <div class="form-group full-width">
                    <label for="edit_description">Description</label>
                    <textarea id="edit_description" name="description" rows="3"></textarea>
                </div>
                
                <!-- Existing Images -->
                <div class="form-group full-width" id="editExistingImagesSection" style="display: none;">
                    <label>Current Images</label>
                    <div id="editExistingImages" class="existing-images-grid"></div>
                </div>
                
                <!-- Add New Images -->
                <div class="form-group full-width">
                    <label>Add New Images <small style="color: var(--text-muted); font-weight: 400;">(Optional)</small></label>
                    <div class="file-upload-wrapper" id="editDropZone">
                        <input type="file" id="editImages" name="images[]" accept="image/*" multiple onchange="handleMultiImagePreview(this, 'editPreviewContainer', 'editFileLabel','edit')">
                        <div class="file-upload-label" id="editFileLabel">
                            <img src="{{ asset('images/upload.png') }}" alt="upload">
                            <span>Click or drag new images here</span>
                            <small style="color: var(--text-muted); margin-top: 4px;">These will be added to existing images</small>
                        </div>
                        <div id="editPreviewContainer" class="multi-preview-container"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEditModal()">
                    <img src="{{ asset('images/cancel.png') }}" alt="cancel">Cancel
                </button>
                <button type="submit" class="btn-submit">
                    <img src="{{ asset('images/save.png') }}" alt="save"> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- DELETE CONFIRMATION MODAL -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-container modal-small">
        <div class="modal-header" style="border-bottom-color: rgba(239,68,68,0.3);">
            <h3 style="color: #ef4444;"><i class="fas fa-exclamation-triangle"></i> Delete Product</h3>
            <button class="modal-close" onclick="closeDeleteModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" style="padding: 30px; text-align: center;">
            <div class="delete-icon">
                <i class="fas fa-trash-alt"></i>
            </div>
            <p style="color: var(--text-secondary); margin-bottom: 8px;">Are you sure you want to delete</p>
            <h4 id="deleteProductName" style="color: var(--text-primary); margin-bottom: 24px; font-family: 'Cinzel', serif;"></h4>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 24px;">
                <i class="fas fa-info-circle"></i> This will also remove all associated images.
            </p>
            <form id="deleteForm" method="POST">
                @csrf
                <div class="modal-footer" style="justify-content: center; gap: 16px;">
                    <button type="button" class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
                    <button type="submit" class="btn-delete-confirm">
                        <i class="fas fa-trash"></i> Yes, Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* ===== SIDEBAR & LAYOUT ===== */
.menu-toggle {
    position: fixed; top: 100px; left: 24px;
    width: 48px; height: 48px; border-radius: 12px; border: 1px solid var(--border);
    background: var(--bg-card); color: var(--accent); display: flex;
    align-items: center; justify-content: center; cursor: pointer; z-index: 200;
    font-size: 1.2rem; transition: all 0.3s ease; backdrop-filter: blur(10px);
}
.menu-toggle:hover { background: var(--accent-glow); border-color: var(--border-hover); transform: scale(1.05); }

.sidebar-overlay {
    position: fixed; inset: 0; background: rgba(0,0,0,0.6);
    backdrop-filter: blur(4px); z-index: 9998 !important;
    opacity: 0; visibility: hidden; transition: all 0.3s ease;
}
.sidebar-overlay.active { opacity: 1; visibility: visible; }

.slide-sidebar {
    position: fixed; top: 0; left: 0; width: 320px; height: 100vh;
    background: var(--bg-secondary); border-right: 1px solid var(--border);
    z-index: 9999 !important; transform: translateX(-100%);
    transition: transform 0.4s cubic-bezier(0.16,1,0.3,1);
    display: flex; flex-direction: column; padding: 0;
}
.slide-sidebar.active { transform: translateX(0); }
.navbar, #navbar, nav.navbar { z-index: 90 !important; }

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
.vault-title { font-family: 'Cinzel', serif; font-size: 2.4rem; font-weight: 600; color: var(--text-primary); margin-bottom: 16px; letter-spacing: -0.5px; }
.vault-subtitle { font-size: 1.1rem; color: var(--text-secondary); font-weight: 300; line-height: 1.8; max-width: 520px; margin: 0 auto; }

.section-label {
    display: inline-block; padding: 8px 20px; border-radius: 20px;
    background: var(--accent-glow); border: 1px solid var(--border-hover);
    color: var(--accent); font-size: 0.8rem; font-weight: 600;
    letter-spacing: 2px; text-transform: uppercase; font-family: 'Cinzel', serif;
}

/* ===== STATS ===== */
.stats-grid {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;
    max-width: 750px; margin: 0 auto 60px;
}
.stat-box {
    background: var(--bg-card); border: 1px solid var(--border); border-radius: 20px;
    padding: 28px 20px; text-align: center; transition: all 0.4s ease;
}
.stat-box:hover { border-color: var(--border-hover); transform: translateY(-4px); box-shadow: 0 20px 50px var(--shadow); }
.stat-icon-img { width: 60px; height: 60px; margin: 0 auto 12px; display: flex; align-items: center; justify-content: center; }
.stat-icon-img img { width: 100%; height: 100%; object-fit: contain; opacity: 0.8; }
.stat-number-real { font-family: 'Cinzel', serif; font-size: 2.6rem; font-weight: 700; color: var(--accent); line-height: 1; margin: 8px 0 4px; text-align: center; letter-spacing: 1px; text-shadow: 0 0 20px var(--accent-glow); }
.stat-number-real span { font-family: 'DM Sans', sans-serif; font-size: 1rem; color: var(--accent-dim); font-weight: 400; }
.stat-label { font-size: 0.75rem; color: var(--text-muted); letter-spacing: 2px; text-transform: uppercase; text-align: center; margin-top: 8px; }

@media (max-width: 1024px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 768px) { .stats-grid { grid-template-columns: 1fr; } .vault-title { font-size: 1.8rem; } .slide-sidebar { width: 280px; } }

/* ===== ADD BUTTON ===== */
.section-header { display: flex; justify-content: flex-end; }
.btn-add-product {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 14px 28px; border-radius: 12px; border: none;
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dim) 100%);
    color: #000; font-weight: 600; font-size: 0.95rem; cursor: pointer;
    transition: all 0.3s ease; font-family: 'DM Sans', sans-serif;
    box-shadow: 0 4px 20px rgba(201,168,76,0.3);
    text-decoration: none;
}
.btn-add-product:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(201,168,76,0.4); color: #000; }
.btn-add-product i { font-size: 0.9rem; }

/* ===== FILTER TABS ===== */
.filter-tabs { display: flex; gap: 12px; flex-wrap: wrap; }
.filter-tab {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 20px; border-radius: 10px; border: 1px solid var(--border);
    background: var(--bg-card); color: var(--text-secondary); font-size: 0.9rem;
    font-weight: 500; cursor: pointer; transition: all 0.3s ease; font-family: 'DM Sans', sans-serif;
}
.filter-tab:hover { border-color: var(--border-hover); color: var(--text-primary); }
.filter-tab.active {
    background: var(--accent-glow); border-color: var(--accent);
    color: var(--accent);
}
.filter-tab i { font-size: 0.8rem; }

/* ===== PRODUCTS GRID ===== */
.products-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px; margin-bottom: 40px;
}

.product-card {
    background: var(--bg-card); border: 1px solid var(--border); border-radius: 20px;
    overflow: hidden; transition: all 0.4s ease; position: relative;
}
.product-card:hover { border-color: var(--border-hover); transform: translateY(-6px); box-shadow: 0 20px 50px var(--shadow); }
.product-card.hidden { display: none; }

.product-image-wrapper { position: relative; height: 220px; overflow: hidden; background: var(--bg-tertiary); }

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
.product-image { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
.product-card:hover .product-image { transform: scale(1.05); }

.image-count-badge {
    position: absolute; bottom: 12px; right: 12px;
    padding: 6px 12px; border-radius: 20px; font-size: 0.8rem;
    font-weight: 600; display: flex; align-items: center; gap: 6px;
    background: rgba(0,0,0,0.6); color: white; backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
}

.product-status-badge {
    position: absolute; top: 12px; left: 12px;
    padding: 6px 14px; border-radius: 20px; font-size: 0.75rem;
    font-weight: 600; display: flex; align-items: center; gap: 6px;
    backdrop-filter: blur(10px);
}
.product-status-badge.approved { background: rgba(16,185,129,0.2); color: #10b981; border: 1px solid rgba(16,185,129,0.3); }
.product-status-badge.pending { background: rgba(245,158,11,0.2); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); }
.product-status-badge.declined { background: rgba(239,68,68,0.2); color: #ef4444; border: 1px solid rgba(239,68,68,0.3); }

.product-actions-overlay {
    position: absolute; top: 12px; right: 12px;
    display: flex; gap: 8px; opacity: 0; transform: translateY(-10px);
    transition: all 0.3s ease;
}
.product-card:hover .product-actions-overlay { opacity: 1; transform: translateY(0); }

.action-btn {
    width: 36px; height: 36px; border-radius: 10px; border: 1px solid var(--border);
    background: rgba(0,0,0,0.6); backdrop-filter: blur(10px);
    color: var(--text-primary); display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all 0.3s ease; font-size: 0.9rem;
    text-decoration: none;
}
.action-btn:hover { transform: scale(1.1); }
.action-btn.edit:hover { background: rgba(59,130,246,0.3); border-color: rgba(59,130,246,0.5); color: #3b82f6; }
.action-btn.delete:hover { background: rgba(239,68,68,0.3); border-color: rgba(239,68,68,0.5); color: #ef4444; }

.product-info { padding: 20px; }
.product-name { font-family: 'Cinzel', serif; font-size: 1.1rem; color: var(--text-primary); margin-bottom: 4px; font-weight: 600; }
.product-desc { font-size: 0.8rem; color: var(--text-muted); margin-bottom: 8px; line-height: 1.4; }
.product-category { font-size: 0.85rem; color: var(--text-muted); margin-bottom: 14px; }
.product-footer { display: flex; justify-content: space-between; align-items: center; }
.product-price { font-family: 'Cinzel', serif; font-size: 1.2rem; font-weight: 700; color: var(--accent); }
.product-price span { font-size: 0.8rem; color: var(--accent-dim); font-weight: 400; }
.product-stock { font-size: 0.8rem; padding: 4px 10px; border-radius: 8px; }
.product-stock.in-stock { background: rgba(16,185,129,0.15); color: #10b981; }
.product-stock.out-stock { background: rgba(239,68,68,0.15); color: #ef4444; }

/* ===== EMPTY STATE ===== */
.empty-state {
    grid-column: 1 / -1; text-align: center; padding: 80px 20px;
    background: var(--bg-card); border: 1px solid var(--border); border-radius: 20px;
}
.empty-icon { font-size: 4rem; color: var(--accent); margin-bottom: 20px; opacity: 0.5; }
.empty-state h3 { font-family: 'Cinzel', serif; color: var(--text-primary); margin-bottom: 12px; }
.empty-state p { color: var(--text-secondary); margin-bottom: 24px; }

/* ===== MODALS ===== */
.modal-overlay {
    position: fixed; inset: 0; background: rgba(0,0,0,0.7);
    backdrop-filter: blur(8px); z-index: 1000;
    display: flex; align-items: center; justify-content: center;
    opacity: 0; visibility: hidden; transition: all 0.3s ease;
    padding: 20px;
}
.modal-overlay.active { opacity: 1; visibility: visible; }

.modal-container {
    background: var(--bg-secondary); border: 1px solid var(--border);
    border-radius: 24px; width: 100%; max-width: 600px;
    max-height: 90vh; overflow-y: auto;
    transform: scale(0.9) translateY(20px); transition: all 0.3s ease;
    box-shadow: 0 30px 60px rgba(0,0,0,0.4);
}
.modal-overlay.active .modal-container { transform: scale(1) translateY(0); }
.modal-small { max-width: 420px; }

.modal-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 24px 28px; border-bottom: 1px solid var(--border);
}
.modal-header h3 { font-family: 'Cinzel', serif; color: var(--text-primary); font-size: 1.2rem; display: flex; align-items: center; gap: 10px; }
.modal-header h3 i { color: var(--accent); }
.modal-close {
    width: 36px; height: 36px; border-radius: 10px; border: 1px solid var(--border);
    background: var(--bg-card); color: var(--text-secondary); display: flex;
    align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease;
}
.modal-close:hover { background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.3); color: #ef4444; }

.modal-form { padding: 28px; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.form-group { display: flex; flex-direction: column; gap: 8px; }
.form-group.full-width { grid-column: 1 / -1; }
.form-group label { font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); }
.form-group input, .form-group select, .form-group textarea {
    padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border);
    background: var(--bg-card); color: var(--text-primary); font-size: 0.95rem;
    font-family: 'DM Sans', sans-serif; transition: all 0.3s ease;
}
.form-group input:focus, .form-group select:focus, .form-group textarea:focus {
    outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow);
}
.form-group input::placeholder, .form-group textarea::placeholder { color: var(--text-muted); }
.form-group select { cursor: pointer; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%236b7280' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 16px center; padding-right: 40px; }

.file-upload-wrapper {
    position: relative; border: 2px dashed var(--border); border-radius: 16px;
    padding: 30px; text-align: center; cursor: pointer; transition: all 0.3s ease;
    background: var(--bg-card); overflow: hidden;
}
.file-upload-wrapper:hover { border-color: var(--accent); background: var(--accent-glow); }
.file-upload-wrapper input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; z-index: 2; }
.file-upload-label { display: flex; flex-direction: column; align-items: center; gap: 10px; color: var(--text-muted); pointer-events: none; }
.file-upload-label i { font-size: 2rem; color: var(--accent); }

/* Multi-image preview */
.multi-preview-container {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
    gap: 10px; margin-top: 16px;
}
.multi-preview-item {
    position: relative; border-radius: 10px; overflow: hidden;
    border: 2px solid var(--border); aspect-ratio: 1;
}
.multi-preview-item img {
    width: 100%; height: 100%; object-fit: cover;
}
.multi-preview-item.primary {
    border-color: var(--accent); box-shadow: 0 0 0 2px var(--accent-glow);
}
.multi-preview-item .preview-badge {
    position: absolute; top: 4px; left: 4px;
    padding: 2px 6px; border-radius: 4px; font-size: 0.65rem;
    font-weight: 600; background: var(--accent); color: #000;
}
.multi-preview-item .preview-remove {
    position: absolute; top: 4px; right: 4px;
    width: 20px; height: 20px; border-radius: 50%;
    background: rgba(239,68,68,0.9); color: white;
    border: none; cursor: pointer; font-size: 0.7rem;
    display: flex; align-items: center; justify-content: center;
}

/* Existing images in edit modal */
.existing-images-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 12px;
}
.existing-image-item {
    position: relative; border-radius: 12px; overflow: hidden;
    border: 2px solid var(--border); aspect-ratio: 1;
}
.existing-image-item img {
    width: 100%; height: 100%; object-fit: cover;
}
.existing-image-item.primary {
    border-color: var(--accent); box-shadow: 0 0 0 2px var(--accent-glow);
}
.existing-image-item .img-primary-badge {
    position: absolute; top: 4px; left: 4px;
    padding: 2px 8px; border-radius: 4px; font-size: 0.65rem;
    font-weight: 600; background: var(--accent); color: #000;
}
.existing-image-item .img-actions {
    position: absolute; bottom: 4px; right: 4px; left: 4px;
    display: flex; gap: 4px; justify-content: center;
}
.existing-image-item .img-action-btn {
    padding: 4px 8px; border-radius: 6px; border: none;
    font-size: 0.7rem; font-weight: 600; cursor: pointer;
    transition: all 0.2s ease;
}
.img-action-btn.set-primary { background: var(--accent); color: #000; }
.img-action-btn.set-primary:hover { background: var(--accent-dim); }
.img-action-btn.delete-img { background: rgba(239,68,68,0.9); color: white; }
.img-action-btn.delete-img:hover { background: #ef4444; }

.modal-footer {
    display: flex; justify-content: flex-end; gap: 12px;
    padding: 20px 28px; border-top: 1px solid var(--border);
}
.btn-cancel {
    padding: 12px 24px; border-radius: 12px; border: 1px solid var(--border);
    background: transparent; color: var(--text-secondary); font-weight: 600;
    cursor: pointer; transition: all 0.3s ease; font-family: 'DM Sans', sans-serif;
}
.btn-cancel:hover { border-color: var(--text-primary); color: var(--text-primary); }
.btn-submit {
    padding: 12px 24px; border-radius: 12px; border: none;
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dim) 100%);
    color: #000; font-weight: 600; cursor: pointer; transition: all 0.3s ease;
    font-family: 'DM Sans', sans-serif; display: inline-flex; align-items: center; gap: 8px;
    box-shadow: 0 4px 15px rgba(201,168,76,0.3);
}
.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(201,168,76,0.4); }

.delete-icon { font-size: 3rem; color: #ef4444; margin-bottom: 20px; opacity: 0.8; }
.btn-delete-confirm {
    padding: 12px 24px; border-radius: 12px; border: none;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white; font-weight: 600; cursor: pointer; transition: all 0.3s ease;
    font-family: 'DM Sans', sans-serif; display: inline-flex; align-items: center; gap: 8px;
    box-shadow: 0 4px 15px rgba(239,68,68,0.3);
}
.btn-delete-confirm:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(239,68,68,0.4); }

.invalid-feedback {
    color: #ef4444; font-size: 0.85rem; margin-top: 6px;
}

/* ===== ANIMATIONS ===== */
.reveal { opacity: 0; transform: translateY(30px); transition: all 0.6s ease; }
.reveal.active { opacity: 1; transform: translateY(0); }

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 640px) {
    .form-grid { grid-template-columns: 1fr; }
    .products-grid { grid-template-columns: 1fr; }
    .modal-footer { flex-direction: column; }
    .btn-cancel, .btn-submit, .btn-delete-confirm { width: 100%; justify-content: center; }
    .carousel-arrow { width: 28px; height: 28px; font-size: 0.75rem; }
}
</style>

<script>
// ===== SITE FEE CONFIGURATION =====
const SITE_FEE_PERCENT = {{ $siteFeePercent ?? 10 }};

// ===== PRICE CALCULATOR =====
function updatePriceBreakdown() {
    const prixInput = document.getElementById('prix');
    const boutiqueEarningEl = document.getElementById('boutiqueEarning');
    const siteFeeEl = document.getElementById('siteFee');
    const customerPaysEl = document.getElementById('customerPays');
    const prixFinalInput = document.getElementById('prix_final');
    const siteFeeAmountInput = document.getElementById('site_fee_amount');

    if (!prixInput) return;

    const boutiquePrice = parseFloat(prixInput.value) || 0;
    const customerPays = boutiquePrice > 0 ? boutiquePrice / (1 - SITE_FEE_PERCENT / 100) : 0;
    const siteFee = customerPays - boutiquePrice;

    if (boutiqueEarningEl) boutiqueEarningEl.textContent = boutiquePrice.toFixed(2) + ' DT';
    if (siteFeeEl) siteFeeEl.textContent = siteFee.toFixed(2) + ' DT';
    if (customerPaysEl) customerPaysEl.textContent = customerPays.toFixed(2) + ' DT';
    if (prixFinalInput) prixFinalInput.value = customerPays.toFixed(2);
    if (siteFeeAmountInput) siteFeeAmountInput.value = siteFee.toFixed(2);
}
// ===== EDIT PRICE CALCULATOR =====
function updateEditPriceBreakdown() {
    const prixInput = document.getElementById('edit_prix');
    const boutiqueEarningEl = document.getElementById('editBoutiqueEarning');
    const siteFeeEl = document.getElementById('editSiteFee');
    const customerPaysEl = document.getElementById('editCustomerPays');
    const prixFinalInput = document.getElementById('edit_prix_final');
    const siteFeeAmountInput = document.getElementById('edit_site_fee_amount');

    if (!prixInput) return;

    const boutiquePrice = parseFloat(prixInput.value) || 0;
    const customerPays = boutiquePrice > 0 ? boutiquePrice / (1 - SITE_FEE_PERCENT / 100) : 0;
    const siteFee = customerPays - boutiquePrice;

    if (boutiqueEarningEl) boutiqueEarningEl.textContent = boutiquePrice.toFixed(2) + ' DT';
    if (siteFeeEl) siteFeeEl.textContent = siteFee.toFixed(2) + ' DT';
    if (customerPaysEl) customerPaysEl.textContent = customerPays.toFixed(2) + ' DT';
    if (prixFinalInput) prixFinalInput.value = customerPays.toFixed(2);
    if (siteFeeAmountInput) siteFeeAmountInput.value = siteFee.toFixed(2);
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
        if (document.getElementById('addModal').classList.contains('active')) closeAddModal();
        if (document.getElementById('editModal').classList.contains('active')) closeEditModal();
        if (document.getElementById('deleteModal').classList.contains('active')) closeDeleteModal();
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

// ===== FILTER PRODUCTS =====
function filterProducts(filter) {
    const cards = document.querySelectorAll('.product-card');
    const tabs = document.querySelectorAll('.filter-tab');
    tabs.forEach(tab => {
        tab.classList.toggle('active', tab.dataset.filter === filter);
    });
    cards.forEach(card => {
        if (filter === 'all' || card.dataset.status === filter) {
            card.classList.remove('hidden');
            card.style.animation = 'fadeIn 0.4s ease forwards';
        } else {
            card.classList.add('hidden');
        }
    });
}

// ===== ADD MODAL =====
function openAddModal() {
    document.getElementById('addModal').classList.add('active');
    document.body.style.overflow = 'hidden';
    
    // Attach price calculator when modal opens
    const prixInput = document.getElementById('prix');
    if (prixInput) {
        // Remove old listener to prevent duplicates
        prixInput.removeEventListener('input', updatePriceBreakdown);
        prixInput.addEventListener('input', updatePriceBreakdown);
        // Run once immediately
        updatePriceBreakdown();
    }
}

function closeAddModal() {
    document.getElementById('addModal').classList.remove('active');
    document.body.style.overflow = '';
    document.getElementById('addProductForm').reset();
    document.getElementById('addPreviewContainer').innerHTML = '';
    document.getElementById('addFileLabel').style.display = 'flex';
    accumulatedFiles.add = [];
    
    // Reset price breakdown display
    const boutiqueEarningEl = document.getElementById('boutiqueEarning');
    const siteFeeEl = document.getElementById('siteFee');
    const customerPaysEl = document.getElementById('customerPays');
    if (boutiqueEarningEl) boutiqueEarningEl.textContent = '0.00 DT';
    if (siteFeeEl) siteFeeEl.textContent = '0.00 DT';
    if (customerPaysEl) customerPaysEl.textContent = '0.00 DT';
}

// ===== MULTI-IMAGE PREVIEW =====
let accumulatedFiles = { add: [], edit: [] };

function handleMultiImagePreview(input, containerId, labelId, context) {
    const container = document.getElementById(containerId);
    const label = document.getElementById(labelId);
    const newFiles = Array.from(input.files);
    
    if (newFiles.length === 0 && accumulatedFiles[context].length === 0) return;
    
    accumulatedFiles[context] = accumulatedFiles[context].concat(newFiles);
    
    const dt = new DataTransfer();
    accumulatedFiles[context].forEach(file => dt.items.add(file));
    input.files = dt.files;
    
    if (accumulatedFiles[context].length > 0) {
        label.style.display = 'none';
    }
    
    container.innerHTML = '';
    
    accumulatedFiles[context].forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'multi-preview-item' + (index === 0 ? ' primary' : '');
            div.innerHTML = `
                <img src="${e.target.result}" alt="Preview ${index + 1}">
                ${index === 0 ? '<span class="preview-badge">PRIMARY</span>' : ''}
                <button type="button" class="preview-remove" onclick="removePreviewImage(${index}, '${context}', '${containerId}', '${labelId}', '${input.id}')" title="Remove">×</button>
            `;
            container.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

function removePreviewImage(index, context, containerId, labelId, inputId) {
    accumulatedFiles[context].splice(index, 1);
    
    const input = document.getElementById(inputId);
    const dt = new DataTransfer();
    accumulatedFiles[context].forEach(file => dt.items.add(file));
    input.files = dt.files;
    
    if (accumulatedFiles[context].length === 0) {
        document.getElementById(labelId).style.display = 'flex';
        document.getElementById(containerId).innerHTML = '';
    } else {
        handleMultiImagePreview(input, containerId, labelId, context);
    }
}

// ===== DRAG & DROP =====
['addDropZone', 'editDropZone'].forEach(id => {
    const zone = document.getElementById(id);
    if (!zone) return;
    
    zone.addEventListener('dragover', (e) => {
        e.preventDefault();
        zone.style.borderColor = 'var(--accent)';
        zone.style.background = 'var(--accent-glow)';
    });
    zone.addEventListener('dragleave', () => {
        zone.style.borderColor = 'var(--border)';
        zone.style.background = 'var(--bg-card)';
    });
    zone.addEventListener('drop', (e) => {
        e.preventDefault();
        zone.style.borderColor = 'var(--border)';
        zone.style.background = 'var(--bg-card)';
        const input = zone.querySelector('input[type="file"]');
        const context = input.id === 'addImages' ? 'add' : 'edit';
        
        const droppedFiles = Array.from(e.dataTransfer.files);
        accumulatedFiles[context] = accumulatedFiles[context].concat(droppedFiles);
        
        const dt = new DataTransfer();
        accumulatedFiles[context].forEach(file => dt.items.add(file));
        input.files = dt.files;
        
        const containerId = context === 'add' ? 'addPreviewContainer' : 'editPreviewContainer';
        const labelId = context === 'add' ? 'addFileLabel' : 'editFileLabel';
        handleMultiImagePreview(input, containerId, labelId, context);
    });
});

// ===== BUILD PRODUCTS DATA =====
@php
$productsData = $produits->map(function($p) {
    return [
        'id_product' => $p->id_product,
        'nom' => $p->nom,
        'id_cat' => $p->id_cat,
        'prix' => $p->prix,
        'stock' => $p->stock,
        'description' => $p->description,
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
const csrfToken = '{{ csrf_token() }}';
const storageUrl = '{{ asset('storage') }}';

// ===== EDIT MODAL =====
function openEditModal(id) {
    const product = productsData[id];
    if (!product) return;

    document.getElementById('edit_nom').value = product.nom;
    document.getElementById('edit_id_cat').value = product.id_cat;
    document.getElementById('edit_prix').value = product.prix_boutique || product.prix;
    document.getElementById('edit_stock').value = product.stock;
    document.getElementById('edit_description').value = product.description || '';
    updateEditPriceBreakdown();

    document.getElementById('editProductForm').action = `{{ url('boutique/update-product') }}/${product.id_product}`;

    const existingSection = document.getElementById('editExistingImagesSection');
    const existingContainer = document.getElementById('editExistingImages');
    
    if (product.images && product.images.length > 0) {
        existingSection.style.display = 'block';
        let html = '';
        
        product.images.forEach(img => {
            const isPrimary = img.is_primary;
            const primaryUrl = `{{ url('boutique/produit') }}/${product.id_product}/image/${img.id_image}/primary`;
            const deleteUrl = `{{ url('boutique/produit') }}/${product.id_product}/image/${img.id_image}`;
            
            html += `
                <div class="existing-image-item ${isPrimary ? 'primary' : ''}">
                    <img src="${storageUrl}/${img.path}" alt="Product image">
                    ${isPrimary ? '<span class="img-primary-badge">PRIMARY</span>' : ''}
                    <div class="img-actions">
                        ${!isPrimary ? `
                            <button type="button" class="img-action-btn set-primary" 
                                    onclick="submitImageAction('${primaryUrl}', 'POST')" 
                                    title="Set as primary">★</button>
                        ` : ''}
                        <button type="button" class="img-action-btn delete-img" 
                                onclick="if(confirm('Delete this image?')) submitImageAction('${deleteUrl}', 'DELETE')" 
                                title="Delete">×</button>
                    </div>
                </div>
            `;
        });
        
        existingContainer.innerHTML = html;
    } else {
        existingSection.style.display = 'none';
    }

    document.getElementById('editPreviewContainer').innerHTML = '';
    document.getElementById('editFileLabel').style.display = 'flex';
    document.getElementById('editImages').value = '';

    document.getElementById('editModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function submitImageAction(url, method) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;
    form.style.display = 'none';
    
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;
    form.appendChild(csrfInput);
    
    if (method === 'DELETE') {
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
    }
    
    document.body.appendChild(form);
    form.submit();
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
    document.body.style.overflow = '';
    accumulatedFiles.edit = [];
}

// ===== DELETE MODAL =====
function confirmDelete(id, name) {
    document.getElementById('deleteProductName').textContent = name;
    document.getElementById('deleteForm').action = `{{ url('boutique/delete-product') }}/${id}`;
    document.getElementById('deleteModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
    document.body.style.overflow = '';
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