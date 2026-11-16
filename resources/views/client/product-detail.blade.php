@extends('layouts.app')

@section('title', $produit->nom . ' — TheTrove')

@section('content')

<style>
/* ===== PRODUCT DETAIL PAGE ===== */
.product-detail-page {
    padding-top: 100px;
    background: var(--bg-primary);
    min-height: 100vh;
}

/* Breadcrumb */
.pd-breadcrumb {
    max-width: 1280px;
    margin: 0 auto;
    padding: 20px 32px;
    font-size: 0.85rem;
    color: var(--text-muted);
}

.pd-breadcrumb a {
    color: var(--accent);
    text-decoration: none;
    font-weight: 500;
    transition: opacity 0.3s;
}

.pd-breadcrumb a:hover { opacity: 0.8; }

/* Main Grid */
.pd-main {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 32px 60px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 48px;
}

@media (max-width: 1024px) {
    .pd-main { grid-template-columns: 1fr; gap: 32px; }
}

/* ===== IMAGE GALLERY ===== */
.pd-gallery {
    position: sticky;
    top: 100px;
    height: fit-content;
}

.pd-main-image {
    width: 100%;
    aspect-ratio: 1;
    background: var(--bg-secondary);
    border-radius: 24px;
    overflow: hidden;
    border: 1px solid var(--border);
    position: relative;
    cursor: zoom-in;
}

.pd-main-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.pd-main-image:hover img {
    transform: scale(1.05);
}

.pd-thumbnails {
    display: flex;
    gap: 12px;
    margin-top: 16px;
    overflow-x: auto;
    padding-bottom: 8px;
}

.pd-thumb {
    min-width: 80px;
    width: 80px;
    height: 80px;
    border-radius: 12px;
    overflow: hidden;
    border: 2px solid transparent;
    cursor: pointer;
    transition: all 0.3s;
    background: var(--bg-secondary);
}

.pd-thumb:hover,
.pd-thumb.active {
    border-color: var(--accent);
    transform: translateY(-2px);
}

.pd-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* ===== PRODUCT INFO ===== */
.pd-info {
    padding-top: 8px;
}

.pd-store-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--accent);
    font-size: 0.85rem;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    text-decoration: none;
    margin-bottom: 12px;
    transition: opacity 0.3s;
}

.pd-store-link:hover { opacity: 0.8; }

.pd-store-link img {
    width: 24px;
    height: 24px;
    border-radius: 6px;
    object-fit: cover;
}

.pd-title {
    font-family: 'Cinzel', serif;
    font-size: 2.2rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.2;
    margin-bottom: 16px;
}

.pd-meta-row {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.pd-rating {
    display: flex;
    align-items: center;
    gap: 8px;
}

.pd-rating .stars {
    color: #f59e0b;
    font-size: 1rem;
    letter-spacing: 2px;
}

.pd-rating .score {
    font-family: 'Cinzel', serif;
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--accent);
}

.pd-rating .count {
    font-size: 0.85rem;
    color: var(--text-muted);
}

.pd-divider {
    width: 1px;
    height: 20px;
    background: var(--border);
}

.pd-category-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    background: var(--accent-glow);
    border: 1px solid var(--border-hover);
    border-radius: 20px;
    font-size: 0.8rem;
    color: var(--accent);
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
}

/* Price */
.pd-price-block {
    background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-tertiary) 100%);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 28px;
    margin-bottom: 24px;
}

.pd-price {
    font-family: 'Cinzel', serif;
    font-size: 2.8rem;
    font-weight: 700;
    color: var(--accent);
    line-height: 1;
    margin-bottom: 8px;
}

.pd-price .currency {
    font-size: 1.2rem;
    color: var(--accent-dim);
    font-weight: 400;
}

.pd-stock-info {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    color: var(--text-muted);
}

.pd-stock-info.in-stock { color: #10b981; }
.pd-stock-info.low-stock { color: #f59e0b; }
.pd-stock-info.out-stock { color: #ef4444; }

/* Actions */
.pd-actions {
    display: flex;
    gap: 12px;
    margin-bottom: 28px;
}

.pd-btn-primary {
    flex: 1;
    padding: 16px 28px;
    border-radius: 14px;
    border: none;
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dim) 100%);
    color: #000;
    font-family: 'DM Sans', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    letter-spacing: 0.5px;
}

.pd-btn-primary:hover:not(:disabled) {
    transform: translateY(-3px);
    box-shadow: 0 12px 40px rgba(201,168,76,0.35);
}

.pd-btn-primary:disabled {
    background: var(--bg-tertiary);
    color: var(--text-muted);
    cursor: not-allowed;
}

.pd-btn-secondary {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    border: 1px solid var(--border);
    background: var(--bg-card);
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.pd-btn-secondary:hover {
    border-color: #ef4444;
    color: #ef4444;
    background: rgba(239,68,68,0.05);
}

.pd-btn-secondary.active {
    background: rgba(239,68,68,0.1);
    border-color: #ef4444;
    color: #ef4444;
}

/* Description */
.pd-description {
    margin-bottom: 32px;
}

.pd-description h4 {
    font-family: 'Cinzel', serif;
    font-size: 1.1rem;
    color: var(--text-primary);
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.pd-description h4 i {
    color: var(--accent);
    font-size: 0.9rem;
}

.pd-description p {
    color: var(--text-secondary);
    font-size: 0.95rem;
    line-height: 1.8;
    font-weight: 300;
}

/* Specs Grid */
.pd-specs {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-bottom: 32px;
}

.pd-spec {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 12px;
}

.pd-spec i {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: var(--accent-glow);
    color: var(--accent);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
}

.pd-spec div {
    display: flex;
    flex-direction: column;
}

.pd-spec .label {
    font-size: 0.75rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.pd-spec .value {
    font-size: 0.9rem;
    color: var(--text-primary);
    font-weight: 600;
}

/* ===== REVIEWS SECTION ===== */
.pd-reviews-section {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 32px 60px;
}

.reviews-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 32px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--border);
    flex-wrap: wrap;
    gap: 16px;
}

.reviews-header h2 {
    font-family: 'Cinzel', serif;
    font-size: 1.6rem;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 12px;
}

.reviews-header h2 i {
    color: var(--accent);
}

.reviews-header .count-badge {
    background: var(--accent-glow);
    color: var(--accent);
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 700;
    border: 1px solid var(--border-hover);
}

/* Rating Summary */
.rating-summary {
    display: grid;
    grid-template-columns: 200px 1fr;
    gap: 40px;
    margin-bottom: 40px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 32px;
}

@media (max-width: 768px) {
    .rating-summary { grid-template-columns: 1fr; gap: 24px; }
}

.rating-big {
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border-right: 1px solid var(--border);
    padding-right: 40px;
}

@media (max-width: 768px) {
    .rating-big { border-right: none; border-bottom: 1px solid var(--border); padding-right: 0; padding-bottom: 24px; }
}

.rating-big .score {
    font-family: 'Cinzel', serif;
    font-size: 4rem;
    font-weight: 700;
    color: var(--accent);
    line-height: 1;
}

.rating-big .stars {
    color: #f59e0b;
    font-size: 1.2rem;
    letter-spacing: 3px;
    margin: 8px 0;
}

.rating-big .total {
    font-size: 0.85rem;
    color: var(--text-muted);
}

/* Rating Bars */
.rating-bars {
    display: flex;
    flex-direction: column;
    gap: 10px;
    justify-content: center;
}

.rating-bar {
    display: flex;
    align-items: center;
    gap: 12px;
}

.rating-bar .star-label {
    font-size: 0.8rem;
    color: var(--text-muted);
    min-width: 40px;
    text-align: right;
}

.rating-bar .bar-track {
    flex: 1;
    height: 8px;
    background: var(--bg-tertiary);
    border-radius: 4px;
    overflow: hidden;
}

.rating-bar .bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #f59e0b, #fbbf24);
    border-radius: 4px;
    transition: width 0.8s ease;
}

.rating-bar .bar-count {
    font-size: 0.8rem;
    color: var(--text-muted);
    min-width: 30px;
}

/* Write Review Button */
.write-review-btn {
    padding: 12px 24px;
    border-radius: 12px;
    border: none;
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dim) 100%);
    color: #000;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.write-review-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(201,168,76,0.3);
}

.write-review-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

/* Review Form */
.review-form-container {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 32px;
    margin-bottom: 40px;
    display: none;
}

.review-form-container.active {
    display: block;
    animation: slideDown 0.4s ease;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

.review-form-container h3 {
    font-family: 'Cinzel', serif;
    font-size: 1.2rem;
    color: var(--text-primary);
    margin-bottom: 20px;
}

.star-rating-input {
    display: flex;
    gap: 8px;
    margin-bottom: 20px;
}

.star-rating-input i {
    font-size: 1.8rem;
    color: var(--border);
    cursor: pointer;
    transition: all 0.2s;
}

.star-rating-input i:hover,
.star-rating-input i.active {
    color: #f59e0b;
    transform: scale(1.2);
}

.review-form-container textarea {
    width: 100%;
    padding: 16px;
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 14px;
    color: var(--text-primary);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.95rem;
    resize: vertical;
    min-height: 120px;
    outline: none;
    transition: all 0.3s;
    margin-bottom: 16px;
}

.review-form-container textarea:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-glow);
}

.review-form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

.review-form-actions button {
    padding: 12px 24px;
    border-radius: 12px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    border: none;
}

.review-form-actions .btn-cancel {
    background: transparent;
    color: var(--text-muted);
    border: 1px solid var(--border);
}

.review-form-actions .btn-cancel:hover {
    border-color: var(--text-secondary);
    color: var(--text-secondary);
}

.review-form-actions .btn-submit {
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dim) 100%);
    color: #000;
}

.review-form-actions .btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(201,168,76,0.3);
}

/* Reviews List */
.reviews-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.review-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 24px;
    transition: all 0.3s ease;
}

.review-card:hover {
    border-color: var(--border-hover);
    transform: translateX(4px);
}

.review-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
    flex-wrap: wrap;
    gap: 12px;
}

.review-user {
    display: flex;
    align-items: center;
    gap: 12px;
}

.review-user img {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--border);
}

.review-user-info h5 {
    font-size: 0.95rem;
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 2px;
}

.review-user-info .verified-badge {
    font-size: 0.7rem;
    color: #10b981;
    display: flex;
    align-items: center;
    gap: 4px;
}

.review-meta {
    text-align: right;
}

.review-meta .stars {
    color: #f59e0b;
    font-size: 0.85rem;
    letter-spacing: 1px;
    margin-bottom: 4px;
}

.review-meta .date {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.review-body {
    color: var(--text-secondary);
    font-size: 0.9rem;
    line-height: 1.7;
    padding-left: 56px;
}

.review-actions {
    display: flex;
    gap: 16px;
    margin-top: 12px;
    padding-left: 56px;
}

.review-actions button {
    background: none;
    border: none;
    color: var(--text-muted);
    font-size: 0.8rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: color 0.3s;
    font-family: 'DM Sans', sans-serif;
}

.review-actions button:hover {
    color: var(--accent);
}

/* Empty Reviews */
.empty-reviews {
    text-align: center;
    padding: 60px 20px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 24px;
}

.empty-reviews i {
    font-size: 3rem;
    color: var(--accent);
    opacity: 0.4;
    margin-bottom: 16px;
}

.empty-reviews h4 {
    font-family: 'Cinzel', serif;
    font-size: 1.2rem;
    color: var(--text-primary);
    margin-bottom: 8px;
}

.empty-reviews p {
    color: var(--text-muted);
    font-size: 0.9rem;
}

/* ===== RELATED PRODUCTS ===== */
.related-section {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 32px 80px;
}

.related-section h2 {
    font-family: 'Cinzel', serif;
    font-size: 1.6rem;
    color: var(--text-primary);
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.related-section h2 i {
    color: var(--accent);
}

.related-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

@media (max-width: 1024px) { .related-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 640px) { .related-grid { grid-template-columns: 1fr; } }

/* ===== QUANTITY SELECTOR ===== */
.quantity-selector {
    display: flex;
    align-items: center;
    gap: 0;
    margin-bottom: 16px;
}

.quantity-selector button {
    width: 44px;
    height: 44px;
    border: 1px solid var(--border);
    background: var(--bg-tertiary);
    color: var(--text-primary);
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.quantity-selector button:first-child {
    border-radius: 12px 0 0 12px;
}

.quantity-selector button:last-child {
    border-radius: 0 12px 12px 0;
}

.quantity-selector button:hover {
    background: var(--accent);
    color: #000;
    border-color: var(--accent);
}

.quantity-selector input {
    width: 60px;
    height: 44px;
    border: 1px solid var(--border);
    border-left: none;
    border-right: none;
    background: var(--bg-card);
    color: var(--text-primary);
    text-align: center;
    font-family: 'DM Sans', sans-serif;
    font-size: 1rem;
    font-weight: 600;
    outline: none;
}

/* ===== TOAST NOTIFICATION ===== */
.toast-notification {
    position: fixed;
    bottom: 30px;
    right: 30px;
    padding: 16px 24px;
    background: var(--bg-card);
    border: 1px solid var(--border-hover);
    border-radius: 16px;
    color: var(--text-primary);
    font-size: 0.9rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 12px;
    z-index: 5000;
    box-shadow: 0 12px 40px var(--shadow);
    transform: translateY(100px);
    opacity: 0;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.toast-notification.show {
    transform: translateY(0);
    opacity: 1;
}

.toast-notification i {
    font-size: 1.2rem;
}

.toast-notification.success i { color: #10b981; }
.toast-notification.error i { color: #ef4444; }
</style>

<!-- BREADCRUMB -->
<div class="pd-breadcrumb">
    <a href="{{ route('client.dashboard') }}">Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size:0.6rem; margin:0 8px;"></i>
    <a href="{{ route('client.browse') }}">Browse</a>
    <i class="fas fa-chevron-right" style="font-size:0.6rem; margin:0 8px;"></i>
    <span style="color:var(--text-secondary)">{{ Str::limit($produit->nom, 40) }}</span>
</div>

<!-- MAIN PRODUCT -->
<div class="product-detail-page">
    <div class="pd-main">
        <!-- Image Gallery -->
        <div class="pd-gallery">
            <div class="pd-main-image" id="mainImageContainer">
                @php
                    $allImages = $produit->images;
                    $primaryImg = $allImages->firstWhere('is_primary', true) ?? $allImages->first();
                @endphp
                <img id="mainImage" src="{{ $primaryImg ? asset('storage/' . $primaryImg->path) : asset('images/placeholder-product.png') }}" 
                     alt="{{ $produit->nom }}"
                     onerror="this.src='{{ asset('images/placeholder-product.png') }}'">
            </div>

            @if($allImages->count() > 1)
            <div class="pd-thumbnails">
                @foreach($allImages as $img)
                <div class="pd-thumb {{ $loop->first ? 'active' : '' }}" onclick="changeMainImage('{{ asset('storage/' . $img->path) }}', this)">
                    <img src="{{ asset('storage/' . $img->path) }}" alt="" onerror="this.style.display='none'">
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="pd-info">
            <a href="{{ route('client.boutiqueDetail', $produit->boutique->id ?? 0) }}" class="pd-store-link">
                <img src="{{ asset('images/boutiquemini.png') }}" alt="">
                {{ $produit->boutique->name ?? 'Unknown Vault' }}
            </a>

            <h1 class="pd-title">{{ $produit->nom }}</h1>

            <div class="pd-meta-row">
                <div class="pd-rating">
                    <span class="stars">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($avgRating ?? 0))
                                <i class="fas fa-star"></i>
                            @elseif($i - 0.5 <= ($avgRating ?? 0))
                                <i class="fas fa-star-half-alt"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </span>
                    <span class="score">{{ number_format($avgRating ?? 0, 1) }}</span>
                    <span class="count">({{ $reviewCount ?? 0 }} reviews)</span>
                </div>
                <div class="pd-divider"></div>
                <span class="pd-category-tag">
                    <i class="fas fa-tag"></i>
                    {{ $produit->categorie->nom ?? 'Uncategorized' }}
                </span>
            </div>

            <div class="pd-price-block">
                <div class="pd-price">{{ number_format($produit->prix, 2) }} <span class="currency">DT</span></div>
                <div class="pd-stock-info {{ $produit->stock > 5 ? 'in-stock' : ($produit->stock > 0 ? 'low-stock' : 'out-stock') }}">
                    <i class="fas fa-{{ $produit->stock > 0 ? 'check-circle' : 'times-circle' }}"></i>
                    @if($produit->stock > 5)
                        In Stock — {{ $produit->stock }} units available
                    @elseif($produit->stock > 0)
                        Only {{ $produit->stock }} left — Order soon!
                    @else
                        Out of Stock
                    @endif
                </div>
            </div>

            <!-- Quantity -->
            <div class="quantity-selector">
                <button type="button" onclick="adjustQty(-1)"><i class="fas fa-minus"></i></button>
                <input type="number" id="qtyInput" value="1" min="1" max="{{ $produit->stock }}" readonly>
                <button type="button" onclick="adjustQty(1)"><i class="fas fa-plus"></i></button>
            </div>

            <!-- Actions -->
            <div class="pd-actions">
                <form action="{{ route('client.addToPanier', $produit->id_product) }}" method="POST" style="flex:1;" id="addToCartForm">
                    @csrf
                    <input type="hidden" name="quantity" id="cartQty" value="1">
                    <button type="submit" class="pd-btn-primary" {{ $produit->stock <= 0 ? 'disabled' : '' }}>
                        <i class="fas fa-shopping-bag"></i>
                        {{ $produit->stock > 0 ? 'Add to Basket' : 'Out of Stock' }}
                    </button>
                </form>

                @if(in_array($produit->id_product, $favoris_products ?? []))
                <form action="{{ route('client.removeFromFavoris', $produit->id_product) }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="pd-btn-secondary active" title="Remove from Wishlist">
                        <i class="fas fa-heart"></i>
                    </button>
                </form>
                @else
                <form action="{{ route('client.addToFavoris', $produit->id_product) }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="pd-btn-secondary" title="Add to Wishlist">
                        <i class="far fa-heart"></i>
                    </button>
                </form>
                @endif
            </div>

            <!-- Description -->
            <div class="pd-description">
                <h4><i class="fas fa-align-left"></i> Description</h4>
                <p>{{ $produit->description ?? 'No description available for this treasure.' }}</p>
            </div>

            <!-- Specs -->
            <div class="pd-specs">
                <div class="pd-spec">
                    <i class="fas fa-box"></i>
                    <div>
                        <span class="label">Stock</span>
                        <span class="value">{{ $produit->stock }} units</span>
                    </div>
                </div>
                <div class="pd-spec">
                    <i class="fas fa-store"></i>
                    <div>
                        <span class="label">Vault</span>
                        <span class="value">{{ $produit->boutique->name ?? 'Unknown' }}</span>
                    </div>
                </div>
                <div class="pd-spec">
                    <i class="fas fa-folder"></i>
                    <div>
                        <span class="label">Category</span>
                        <span class="value">{{ $produit->categorie->nom ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="pd-spec">
                    <i class="fas fa-calendar"></i>
                    <div>
                        <span class="label">Added</span>
                        <span class="value">{{ $produit->created_at ? $produit->created_at->format('M Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- REVIEWS SECTION -->
    <div class="pd-reviews-section">
        <div class="reviews-header">
            <h2>
                <i class="fas fa-comments"></i>
                Reviews & Ratings
                <span class="count-badge">{{ $reviewCount ?? 0 }}</span>
            </h2>

            @if($canReview)
            <button class="write-review-btn" id="writeReviewBtn" onclick="toggleReviewForm()">
                <i class="fas fa-pen"></i> Write a Review
            </button>
            @else
            <button class="write-review-btn" disabled title="You can only review products you've purchased">
                <i class="fas fa-lock"></i> Purchase to Review
            </button>
            @endif
        </div>

        @if($reviewCount > 0)
        <!-- Rating Summary -->
        <div class="rating-summary">
            <div class="rating-big">
                <div class="score">{{ number_format($avgRating ?? 0, 1) }}</div>
                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= round($avgRating ?? 0))
                            <i class="fas fa-star"></i>
                        @else
                            <i class="far fa-star"></i>
                        @endif
                    @endfor
                </div>
                <div class="total">{{ $reviewCount }} review{{ $reviewCount != 1 ? 's' : '' }}</div>
            </div>
            <div class="rating-bars">
                @for($star = 5; $star >= 1; $star--)
                    @php
                        $count = $ratingDistribution[$star] ?? 0;
                        $pct = $reviewCount > 0 ? ($count / $reviewCount) * 100 : 0;
                    @endphp
                <div class="rating-bar">
                    <span class="star-label">{{ $star }} <i class="fas fa-star" style="font-size:0.7rem;"></i></span>
                    <div class="bar-track">
                        <div class="bar-fill" style="width: {{ $pct }}%"></div>
                    </div>
                    <span class="bar-count">{{ $count }}</span>
                </div>
                @endfor
            </div>
        </div>
        @endif

        <!-- Review Form -->
        @if($canReview)
        <div class="review-form-container" id="reviewForm">
            <h3>Share Your Experience</h3>
            <form action="{{ route('client.addReview', $produit->id_product) }}" method="POST" onsubmit="return validateReview()">
                @csrf
                <div class="star-rating-input" id="starInput">
                    <i class="far fa-star" data-value="1" onclick="setRating(1)" onmouseenter="hoverRating(1)" onmouseleave="resetHover()"></i>
                    <i class="far fa-star" data-value="2" onclick="setRating(2)" onmouseenter="hoverRating(2)" onmouseleave="resetHover()"></i>
                    <i class="far fa-star" data-value="3" onclick="setRating(3)" onmouseenter="hoverRating(3)" onmouseleave="resetHover()"></i>
                    <i class="far fa-star" data-value="4" onclick="setRating(4)" onmouseenter="hoverRating(4)" onmouseleave="resetHover()"></i>
                    <i class="far fa-star" data-value="5" onclick="setRating(5)" onmouseenter="hoverRating(5)" onmouseleave="resetHover()"></i>
                </div>
                <input type="hidden" name="rating" id="ratingValue" value="0">

                <textarea name="comment" placeholder="Tell others what you think about this product..." required></textarea>

                <div class="review-form-actions">
                    <button type="button" class="btn-cancel" onclick="toggleReviewForm()">Cancel</button>
                    <button type="submit" class="btn-submit"><i class="fas fa-paper-plane"></i> Submit Review</button>
                </div>
            </form>
        </div>
        @endif

        <!-- Reviews List -->
        <div class="reviews-list">
            @forelse($reviews ?? [] as $review)
            <div class="review-card">
                <div class="review-header">
                    <div class="review-user">
                        <img src="{{ asset('images/profilmini1.png') }}" alt="">
                        <div class="review-user-info">
                            <h5>{{ $review->user->name ?? 'Anonymous' }}</h5>
                            @if($review->is_verified_purchase)
                            <span class="verified-badge"><i class="fas fa-check-circle"></i> Verified Purchase</span>
                            @endif
                        </div>
                    </div>
                    <div class="review-meta">
                        <div class="stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <div class="date">{{ $review->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                <div class="review-body">
                    {{ $review->comment }}
                </div>
                <div class="review-actions">
                    <button onclick="likeReview({{ $review->id }})">
                        <i class="far fa-thumbs-up"></i> Helpful ({{ $review->helpful_count ?? 0 }})
                    </button>
                </div>
            </div>
            @empty
            <div class="empty-reviews">
                <i class="fas fa-comment-slash"></i>
                <h4>No Reviews Yet</h4>
                <p>Be the first to share your thoughts on this treasure!</p>
            </div>
            @endforelse
        </div>

        @if(($reviews ?? collect())->hasPages())
        <div style="margin-top:24px;">
            {{ $reviews->links() }}
        </div>
        @endif
    </div>

    <!-- RELATED PRODUCTS -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
    <div class="related-section">
        <h2><i class="fas fa-gem"></i> You May Also Like</h2>
        <div class="related-grid">
            @foreach($relatedProducts as $rel)
            @php $relImg = $rel->images->first(); @endphp
            <div class="product-card" onclick="window.location.href='{{ route('client.productDetail', $rel->id_product) }}'" style="cursor:pointer;">
                <div class="product-image-area" style="height:180px;">
                    <img src="{{ $relImg ? asset('storage/' . $relImg->path) : asset('images/placeholder-product.png') }}" 
                         alt="{{ $rel->nom }}" style="width:100%;height:100%;object-fit:cover;">
                </div>
                <div class="product-info" style="padding:14px;">
                    <div class="product-name" style="font-size:0.9rem;">{{ Str::limit($rel->nom, 30) }}</div>
                    <div class="product-price-row" style="margin-top:8px;">
                        <span class="product-price" style="font-size:1rem;">{{ number_format($rel->prix, 2) }} <span class="currency">DT</span></span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- TOAST -->
<div class="toast-notification" id="toast">
    <i class="fas fa-check-circle"></i>
    <span id="toastMsg">Added to basket!</span>
</div>

<script>
// ===== IMAGE GALLERY =====
function changeMainImage(src, thumb) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('.pd-thumb').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
}

// ===== QUANTITY =====
function adjustQty(delta) {
    const input = document.getElementById('qtyInput');
    const cartQty = document.getElementById('cartQty');
    let val = parseInt(input.value) + delta;
    const max = parseInt(input.max);
    if (val < 1) val = 1;
    if (val > max) val = max;
    input.value = val;
    cartQty.value = val;
}

// ===== REVIEW FORM =====
let currentRating = 0;

function toggleReviewForm() {
    const form = document.getElementById('reviewForm');
    form.classList.toggle('active');
    if (form.classList.contains('active')) {
        form.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

function setRating(val) {
    currentRating = val;
    document.getElementById('ratingValue').value = val;
    updateStars(val);
}

function hoverRating(val) {
    updateStars(val);
}

function resetHover() {
    updateStars(currentRating);
}

function updateStars(val) {
    document.querySelectorAll('#starInput i').forEach((star, idx) => {
        if (idx < val) {
            star.classList.remove('far');
            star.classList.add('fas', 'active');
        } else {
            star.classList.remove('fas', 'active');
            star.classList.add('far');
        }
    });
}

function validateReview() {
    if (currentRating === 0) {
        showToast('Please select a star rating', 'error');
        return false;
    }
    return true;
}

// ===== TOAST =====
function showToast(msg, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMsg = document.getElementById('toastMsg');
    toast.className = 'toast-notification ' + type;
    toast.querySelector('i').className = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
    toastMsg.textContent = msg;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
}

// ===== LIKE REVIEW =====
function likeReview(reviewId) {
    fetch('{{ url('client/review') }}/' + reviewId + '/like', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) showToast('Marked as helpful!');
    });
}

// Show toast on cart add
@if(session('success'))
    showToast('{{ session('success') }}', 'success');
@endif
@if(session('error'))
    showToast('{{ session('error') }}', 'error');
@endif
</script>

@endsection