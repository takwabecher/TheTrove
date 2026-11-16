@extends('layouts.app')

@section('title', 'Browse Treasures — TheTrove')

@section('content')

<style>
/* ===== ROOT VARIABLES ALREADY IN LAYOUT ===== */

/* ===== BROWSE LAYOUT ===== */
.browse-layout {
    display: flex;
    min-height: 100vh;
    background: var(--bg-primary);
    padding-top: 80px;
}

/* ===== SIDEBAR FILTERS ===== */
.browse-sidebar {
    width: 280px;
    background: var(--bg-secondary);
    border-right: 1px solid var(--border);
    padding: 24px 20px;
    position: fixed;
    left: 0;
    top: 0;
    height: 100vh;
    overflow-y: auto;
    z-index: 100;
    transition: transform 0.3s ease;
}

.sidebar-toggle-btn {
    display: none;
    position: fixed;
    left: 20px;
    bottom: 30px;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dim) 100%);
    color: #000;
    border: none;
    font-size: 1.3rem;
    cursor: pointer;
    z-index: 101;
    box-shadow: 0 8px 32px rgba(201,168,76,0.4);
    transition: all 0.3s ease;
}

.sidebar-toggle-btn:hover {
    transform: scale(1.1) rotate(15deg);
}

.sidebar-section {
    margin-bottom: 28px;
}

.sidebar-title {
    font-family: 'Cinzel', serif;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-primary);
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.sidebar-title i {
    color: var(--accent);
    font-size: 0.75rem;
}

/* Search in sidebar */
.sidebar-search-box {
    position: relative;
    margin-bottom: 20px;
}

.sidebar-search-box input {
    width: 100%;
    padding: 12px 40px 12px 16px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 12px;
    color: var(--text-primary);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    outline: none;
}

.sidebar-search-box input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-glow);
}

.sidebar-search-box input::placeholder {
    color: var(--text-muted);
}

.sidebar-search-box button {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: none;
    background: var(--accent);
    color: #000;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    transition: all 0.3s;
}

.sidebar-search-box button:hover {
    transform: translateY(-50%) scale(1.1);
}

/* AI Image Search */
.ai-search-section {
    background: linear-gradient(135deg, rgba(201,168,76,0.08) 0%, rgba(201,168,76,0.02) 100%);
    border: 1px dashed var(--accent);
    border-radius: 16px;
    padding: 20px;
    text-align: center;
    margin-bottom: 24px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.ai-search-section:hover {
    background: linear-gradient(135deg, rgba(201,168,76,0.15) 0%, rgba(201,168,76,0.05) 100%);
    transform: translateY(-2px);
}

.ai-search-section i {
    font-size: 2rem;
    color: var(--accent);
    margin-bottom: 8px;
    display: block;
}

.ai-search-section span {
    font-size: 0.8rem;
    color: var(--accent);
    font-weight: 600;
    letter-spacing: 1px;
}

.ai-search-section p {
    font-size: 0.7rem;
    color: var(--text-muted);
    margin-top: 4px;
}

#aiImageInput {
    display: none;
}

/* Categories */
.filter-list {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.filter-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 14px;
    border-radius: 10px;
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 0.85rem;
    transition: all 0.3s ease;
    cursor: pointer;
    border: 1px solid transparent;
}

.filter-item:hover,
.filter-item.active {
    background: var(--accent-glow);
    color: var(--accent);
    border-color: var(--border-hover);
}

.filter-item .count {
    font-size: 0.7rem;
    color: var(--text-muted);
    background: var(--bg-tertiary);
    padding: 2px 8px;
    border-radius: 10px;
    transition: all 0.3s;
}

.filter-item:hover .count,
.filter-item.active .count {
    background: var(--accent);
    color: #000;
}

/* Boutiques */
.boutique-filter-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 10px;
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 0.85rem;
    transition: all 0.3s ease;
    cursor: pointer;
    border: 1px solid transparent;
}

.boutique-filter-item:hover,
.boutique-filter-item.active {
    background: var(--accent-glow);
    color: var(--accent);
    border-color: var(--border-hover);
}

.boutique-filter-item img {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    object-fit: cover;
    border: 1px solid var(--border);
}

/* Price Range */
.price-range-container {
    padding: 8px 4px;
}

.price-inputs {
    display: flex;
    gap: 8px;
    margin-bottom: 16px;
}

.price-inputs input {
    flex: 1;
    padding: 10px 12px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--text-primary);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.85rem;
    outline: none;
    transition: all 0.3s;
}

.price-inputs input:focus {
    border-color: var(--accent);
}

.price-slider-track {
    position: relative;
    height: 6px;
    background: var(--bg-tertiary);
    border-radius: 3px;
    margin: 0 8px 20px;
}

.price-slider-fill {
    position: absolute;
    height: 100%;
    background: linear-gradient(90deg, var(--accent), var(--accent-dim));
    border-radius: 3px;
    top: 0;
}

.price-slider-thumb {
    position: absolute;
    width: 18px;
    height: 18px;
    background: var(--accent);
    border: 3px solid var(--bg-card);
    border-radius: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(201,168,76,0.4);
    transition: transform 0.2s;
    z-index: 2;
}

.price-slider-thumb:hover {
    transform: translate(-50%, -50%) scale(1.3);
}

.price-display {
    display: flex;
    justify-content: space-between;
    font-size: 0.8rem;
    color: var(--text-muted);
}

/* Rating Filter */
.rating-filter {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.rating-option {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 0.85rem;
    color: var(--text-secondary);
}

.rating-option:hover {
    background: var(--bg-hover);
}

.rating-option input[type="radio"] {
    accent-color: var(--accent);
}

.rating-stars {
    color: #f59e0b;
    font-size: 0.75rem;
    letter-spacing: 1px;
}

/* Clear Filters */
.clear-filters-btn {
    width: 100%;
    padding: 12px;
    border-radius: 12px;
    border: 1px solid var(--border);
    background: transparent;
    color: var(--text-muted);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.clear-filters-btn:hover {
    border-color: #ef4444;
    color: #ef4444;
    background: rgba(239,68,68,0.05);
}

/* ===== MAIN CONTENT AREA ===== */
.browse-main {
    flex: 1;
    margin-left: 280px;
    padding: 24px 32px 60px;
    min-height: 100vh;
}

/* Top Bar */
.browse-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--border);
    flex-wrap: wrap;
    gap: 16px;
}

.breadcrumb-path {
    font-size: 0.85rem;
    color: var(--text-muted);
}

.breadcrumb-path a {
    color: var(--accent);
    text-decoration: none;
    font-weight: 500;
}

.breadcrumb-path a:hover {
    text-decoration: underline;
}

.results-count {
    font-size: 0.9rem;
    color: var(--text-secondary);
}

.results-count strong {
    color: var(--accent);
    font-family: 'Cinzel', serif;
}

.sort-dropdown {
    display: flex;
    align-items: center;
    gap: 8px;
}

.sort-dropdown label {
    font-size: 0.8rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.sort-dropdown select {
    padding: 10px 16px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--text-primary);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.85rem;
    cursor: pointer;
    outline: none;
    transition: all 0.3s;
}

.sort-dropdown select:focus {
    border-color: var(--accent);
}

.view-toggle {
    display: flex;
    gap: 4px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 4px;
}

.view-toggle button {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: none;
    background: transparent;
    color: var(--text-muted);
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.view-toggle button.active {
    background: var(--accent);
    color: #000;
}

/* ===== PRODUCTS GRID — 4 PER ROW ===== */
.products-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 40px;
}

@media (max-width: 1400px) {
    .products-grid { grid-template-columns: repeat(3, 1fr); }
}

@media (max-width: 1024px) {
    .products-grid { grid-template-columns: repeat(2, 1fr); }
    .browse-sidebar { transform: translateX(-100%); }
    .browse-sidebar.active { transform: translateX(0); }
    .browse-main { margin-left: 0; }
    .sidebar-toggle-btn { display: flex; }
    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(4px);
        z-index: 99;
    }
    .sidebar-overlay.active { display: block; }
}

@media (max-width: 640px) {
    .products-grid { grid-template-columns: 1fr; }
    .browse-main { padding: 16px; }
}

/* ===== PRODUCT CARD — MODERN MARKETPLACE STYLE ===== */
.product-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    display: flex;
    flex-direction: column;
    cursor: pointer;
    group: product-card;
}

.product-card:hover {
    border-color: var(--border-hover);
    transform: translateY(-8px);
    box-shadow: 0 24px 60px var(--shadow), 0 0 40px var(--accent-glow);
}

/* Image Area */
.product-image-area {
    position: relative;
    height: 240px;
    overflow: hidden;
    background: var(--bg-tertiary);
}

.product-image-area img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

.product-card:hover .product-image-area img {
    transform: scale(1.08);
}

/* Badges */
.product-badges {
    position: absolute;
    top: 12px;
    left: 12px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    z-index: 5;
}

.badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.badge-new {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    box-shadow: 0 4px 12px rgba(16,185,129,0.3);
}

.badge-sale {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    box-shadow: 0 4px 12px rgba(239,68,68,0.3);
}

.badge-featured {
    background: linear-gradient(135deg, var(--accent), var(--accent-dim));
    color: #000;
    box-shadow: 0 4px 12px rgba(201,168,76,0.3);
}

/* Wishlist Button */
.wishlist-action {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 1px solid rgba(255,255,255,0.2);
    background: rgba(0,0,0,0.4);
    backdrop-filter: blur(10px);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 5;
    font-size: 1rem;
}

.wishlist-action:hover {
    background: rgba(239,68,68,0.6);
    border-color: rgba(239,68,68,0.5);
    transform: scale(1.15);
}

.wishlist-action.active {
    background: rgba(239,68,68,0.8);
    color: white;
}

/* Quick Actions Overlay */
.product-quick-actions {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 16px;
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
    display: flex;
    gap: 8px;
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.3s ease;
    z-index: 5;
}

.product-card:hover .product-quick-actions {
    opacity: 1;
    transform: translateY(0);
}

.quick-btn {
    flex: 1;
    padding: 10px;
    border-radius: 10px;
    border: none;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.quick-btn-cart {
    background: var(--accent);
    color: #000;
}

.quick-btn-cart:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(201,168,76,0.4);
}

.quick-btn-view {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    color: white;
    border: 1px solid rgba(255,255,255,0.2);
}

.quick-btn-view:hover {
    background: rgba(255,255,255,0.25);
}

/* Product Info */
.product-info {
    padding: 18px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.product-store {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.75rem;
    color: var(--accent);
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 8px;
}

.product-store img {
    width: 16px;
    height: 16px;
    border-radius: 4px;
    object-fit: cover;
}

.product-name {
    font-family: 'Cinzel', serif;
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 6px;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-category-tag {
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Rating */
.product-rating {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 10px;
}

.product-rating .stars {
    color: #f59e0b;
    font-size: 0.8rem;
    letter-spacing: 1px;
}

.product-rating .rating-text {
    font-size: 0.75rem;
    color: var(--text-muted);
}

/* Price & Stock */
.product-price-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: auto;
    padding-top: 12px;
    border-top: 1px solid var(--border);
}

.product-price {
    font-family: 'Cinzel', serif;
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--accent);
}

.product-price .currency {
    font-size: 0.8rem;
    color: var(--accent-dim);
    font-weight: 400;
}

.product-stock-tag {
    font-size: 0.7rem;
    padding: 4px 10px;
    border-radius: 20px;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.stock-in {
    background: rgba(16,185,129,0.15);
    color: #10b981;
}

.stock-low {
    background: rgba(245,158,11,0.15);
    color: #f59e0b;
}

.stock-out {
    background: rgba(239,68,68,0.15);
    color: #ef4444;
}

/* ===== PAGINATION ===== */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    margin-top: 40px;
    padding-top: 30px;
    border-top: 1px solid var(--border);
}

.pagination-wrapper .page-link,
.pagination-wrapper .page-item span {
    padding: 10px 16px;
    border-radius: 12px;
    border: 1px solid var(--border);
    background: var(--bg-card);
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
}

.pagination-wrapper .page-link:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: var(--accent-glow);
}

.pagination-wrapper .page-item.active span {
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dim) 100%);
    color: #000;
    border-color: var(--accent);
    font-weight: 700;
}

.pagination-wrapper .page-item.disabled span {
    opacity: 0.4;
    cursor: not-allowed;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 100px 20px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 24px;
}

.empty-state i {
    font-size: 4rem;
    color: var(--accent);
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-state h3 {
    font-family: 'Cinzel', serif;
    font-size: 1.5rem;
    color: var(--text-primary);
    margin-bottom: 12px;
}

.empty-state p {
    color: var(--text-muted);
    font-size: 0.95rem;
    max-width: 400px;
    margin: 0 auto;
}

/* ===== AI SEARCH MODAL ===== */
.ai-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.85);
    backdrop-filter: blur(12px);
    z-index: 3000;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    padding: 20px;
}

.ai-modal-overlay.active {
    opacity: 1;
    visibility: visible;
}

.ai-modal {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 24px;
    width: 100%;
    max-width: 500px;
    padding: 40px;
    text-align: center;
    transform: scale(0.9) translateY(20px);
    transition: all 0.3s ease;
}

.ai-modal-overlay.active .ai-modal {
    transform: scale(1) translateY(0);
}

.ai-modal h3 {
    font-family: 'Cinzel', serif;
    font-size: 1.5rem;
    color: var(--text-primary);
    margin-bottom: 8px;
}

.ai-modal p {
    color: var(--text-muted);
    font-size: 0.9rem;
    margin-bottom: 24px;
}

.ai-upload-zone {
    border: 2px dashed var(--border);
    border-radius: 20px;
    padding: 40px 20px;
    margin-bottom: 20px;
    cursor: pointer;
    transition: all 0.3s;
    background: var(--bg-card);
}

.ai-upload-zone:hover {
    border-color: var(--accent);
    background: var(--accent-glow);
}

.ai-upload-zone i {
    font-size: 3rem;
    color: var(--accent);
    margin-bottom: 12px;
}

.ai-upload-zone span {
    display: block;
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 500;
}

.ai-upload-zone small {
    color: var(--text-muted);
    font-size: 0.75rem;
}

.ai-preview {
    max-width: 100%;
    max-height: 300px;
    border-radius: 12px;
    margin-bottom: 16px;
    border: 1px solid var(--border);
}

.ai-search-btn {
    width: 100%;
    padding: 14px;
    border-radius: 12px;
    border: none;
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dim) 100%);
    color: #000;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.95rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.ai-search-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(201,168,76,0.3);
}

.ai-search-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

.ai-close-btn {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 40px;
    height: 40px;
    border-radius: 12px;
    border: 1px solid var(--border);
    background: var(--bg-card);
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.3s;
    font-size: 1rem;
}

.ai-close-btn:hover {
    background: rgba(239,68,68,0.1);
    border-color: rgba(239,68,68,0.3);
    color: #ef4444;
}

/* AI Results Banner */
.ai-results-banner {
    background: linear-gradient(135deg, rgba(201,168,76,0.1) 0%, rgba(201,168,76,0.03) 100%);
    border: 1px solid var(--border-hover);
    border-radius: 16px;
    padding: 16px 24px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.ai-results-banner i {
    font-size: 1.5rem;
    color: var(--accent);
}

.ai-results-banner h4 {
    font-family: 'Cinzel', serif;
    font-size: 1rem;
    color: var(--text-primary);
    margin-bottom: 2px;
}

.ai-results-banner p {
    font-size: 0.8rem;
    color: var(--text-muted);
}

/* ===== SCROLLBAR ===== */
.browse-sidebar::-webkit-scrollbar { width: 4px; }
.browse-sidebar::-webkit-scrollbar-track { background: transparent; }
.browse-sidebar::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }
.browse-sidebar::-webkit-scrollbar-thumb:hover { background: var(--accent); }

/* ===== ANIMATIONS ===== */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.product-card {
    animation: fadeInUp 0.5s ease-out forwards;
    opacity: 0;
}

@for $i from 1 through 20 {
    .product-card:nth-child(#{$i}) {
        animation-delay: #{$i * 0.05}s;
    }
}
</style>

<!-- SIDEBAR TOGGLE (Mobile) -->
<button class="sidebar-toggle-btn" id="sidebarToggle" title="Open filters">
    <i class="fas fa-sliders-h"></i>
</button>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="browse-layout">
    <!-- SIDEBAR FILTERS -->
    <aside class="browse-sidebar" id="browseSidebar">
        <!-- AI Visual Search -->
        <div class="sidebar-section">
            <div class="ai-search-section" onclick="openAiModal()">
                <i class="fas fa-camera"></i>
                <span>Visual Search</span>
                <p>Upload a photo to find similar items</p>
            </div>
        </div>

        <!-- Text Search -->
        <div class="sidebar-section">
            <div class="sidebar-title"><i class="fas fa-search"></i> Search</div>
            <div class="sidebar-search-box">
                <input type="text" id="searchInput" placeholder="Search products..." 
                       value="{{ request('search') }}" onkeyup="if(event.key==='Enter') applyFilters()">
                <button onclick="applyFilters()"><i class="fas fa-arrow-right"></i></button>
            </div>
        </div>

        <!-- Categories -->
        <div class="sidebar-section">
            <div class="sidebar-title"><i class="fas fa-th-large"></i> Categories</div>
            <div class="filter-list">
                <a href="{{ route('client.browse') }}" class="filter-item {{ !request('category') ? 'active' : '' }}">
                    <span>All Products</span>
                    <span class="count">{{ $total_products ?? $produits->total() }}</span>
                </a>
                @foreach($categories ?? [] as $cat)
                <a href="{{ route('client.browse', ['category' => $cat->id_cat ?? $cat->id]) }}" 
                   class="filter-item {{ request('category') == ($cat->id_cat ?? $cat->id) ? 'active' : '' }}">
                    <span>{{ $cat->name ?? $cat->nom }}</span>
                    <span class="count">{{ $cat->produits_count ?? $cat->produits->count() ?? 0 }}</span>
                </a>
                @endforeach
            </div>
        </div>

        <!-- Boutiques -->
        <div class="sidebar-section">
            <div class="sidebar-title"><i class="fas fa-store"></i> Vaults</div>
            <div class="filter-list">
                @foreach($vaults ?? [] as $vault)
                <a href="{{ route('client.browse', ['boutique' => $vault->id]) }}" 
                   class="boutique-filter-item {{ request('boutique') == $vault->id ? 'active' : '' }}">
                    <img src="{{ asset('images/boutiquemini1.png') }}" alt="">
                    <span>{{ $vault->name ?? $vault->nom }}</span>
                </a>
                @endforeach
            </div>
        </div>

        <!-- Price Range -->
        <div class="sidebar-section">
            <div class="sidebar-title"><i class="fas fa-tag"></i> Price Range</div>
            <div class="price-range-container">
                <div class="price-inputs">
                    <input type="number" id="priceMin" placeholder="Min" min="0" 
                           value="{{ request('price_min') }}" onchange="applyFilters()">
                    <input type="number" id="priceMax" placeholder="Max" min="0"
                           value="{{ request('price_max') }}" onchange="applyFilters()">
                </div>
                <div class="price-slider-track" id="priceTrack">
                    <div class="price-slider-fill" id="priceFill"></div>
                    <div class="price-slider-thumb" id="thumbMin" style="left: 0%"></div>
                    <div class="price-slider-thumb" id="thumbMax" style="left: 100%"></div>
                </div>
                <div class="price-display">
                    <span id="priceMinDisplay">0 DT</span>
                    <span id="priceMaxDisplay">5000+ DT</span>
                </div>
            </div>
        </div>

        <!-- Rating Filter -->
        <div class="sidebar-section">
            <div class="sidebar-title"><i class="fas fa-star"></i> Rating</div>
            <div class="rating-filter">
                <label class="rating-option">
                    <input type="radio" name="rating" value="4" {{ request('rating') == '4' ? 'checked' : '' }} onchange="applyFilters()">
                    <span class="rating-stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i></span>
                    <span class="rating-text">& Up</span>
                </label>
                <label class="rating-option">
                    <input type="radio" name="rating" value="3" {{ request('rating') == '3' ? 'checked' : '' }} onchange="applyFilters()">
                    <span class="rating-stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i></span>
                    <span class="rating-text">& Up</span>
                </label>
                <label class="rating-option">
                    <input type="radio" name="rating" value="2" {{ request('rating') == '2' ? 'checked' : '' }} onchange="applyFilters()">
                    <span class="rating-stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i></span>
                    <span class="rating-text">& Up</span>
                </label>
                <label class="rating-option">
                    <input type="radio" name="rating" value="" {{ !request('rating') ? 'checked' : '' }} onchange="applyFilters()">
                    <span class="rating-text">Any Rating</span>
                </label>
            </div>
        </div>

        <!-- Clear Filters -->
        <button class="clear-filters-btn" onclick="window.location.href='{{ route('client.browse') }}'">
            <i class="fas fa-undo"></i> Clear All Filters
        </button>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="browse-main">
        <!-- Top Bar -->
        <div class="browse-topbar">
            <div>
                <div class="breadcrumb-path">
                    <a href="{{ route('client.dashboard') }}">Dashboard</a> 
                    <i class="fas fa-chevron-right" style="font-size:0.6rem; margin:0 8px; color:var(--text-muted)"></i>
                    <span style="color:var(--text-secondary)">Browse</span>
                    @if(request('search'))
                        <i class="fas fa-chevron-right" style="font-size:0.6rem; margin:0 8px; color:var(--text-muted)"></i>
                        <span style="color:var(--accent)">"{{ request('search') }}"</span>
                    @endif
                </div>
                <div class="results-count" style="margin-top:6px;">
                    Showing <strong>{{ $produits->firstItem() ?? 0 }}–{{ $produits->lastItem() ?? 0 }}</strong> of <strong>{{ $produits->total() }}</strong> treasures
                </div>
            </div>

            <div style="display:flex; align-items:center; gap:16px;">
                <div class="sort-dropdown">
                    <label>Sort by</label>
                    <select id="sortSelect" onchange="applyFilters()">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Top Rated</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                    </select>
                </div>
            </div>
        </div>

        @if(session('ai_search'))
        <div class="ai-results-banner">
            <i class="fas fa-magic"></i>
            <div>
                <h4>AI Visual Search Results</h4>
                <p>Showing products similar to your uploaded image</p>
            </div>
            <a href="{{ route('client.browse') }}" style="margin-left:auto; color:var(--accent); font-size:0.8rem; font-weight:600;">
                <i class="fas fa-times"></i> Clear
            </a>
        </div>
        @endif

        <!-- Products Grid -->
        <div class="products-grid" id="productsGrid">
            @forelse($produits as $produit)
            @php
                $primaryImage = $produit->images->firstWhere('is_primary', true) ?? $produit->images->first();
                $avgRating = $produit->reviews_avg_rating ?? 0;
                $reviewCount = $produit->reviews_count ?? 0;
                $isNew = $produit->created_at && $produit->created_at->diffInDays(now()) <= 7;
                $isLowStock = $produit->stock > 0 && $produit->stock <= 5;
            @endphp
            <div class="product-card" onclick="openProductDetail({{ $produit->id_product }})" style="animation-delay: {{ $loop->index * 0.05 }}s">
                <div class="product-image-area">
                    <img src="{{ $primaryImage ? asset('storage/' . $primaryImage->path) : asset('images/placeholder-product.png') }}" 
                         alt="{{ $produit->nom }}"
                         onerror="this.src='{{ asset('images/placeholder-product.png') }}'">

                    <div class="product-badges">
                        @if($isNew)
                            <span class="badge badge-new">New</span>
                        @endif
                        @if($produit->stock <= 0)
                            <span class="badge badge-sale">Sold Out</span>
                        @endif
                    </div>

                    @if(in_array($produit->id_product, $favoris_products ?? []))
                        <button class="wishlist-action active" onclick="event.stopPropagation(); toggleWishlist({{ $produit->id_product }}, true)">
                            <i class="fas fa-heart"></i>
                        </button>
                    @else
                        <button class="wishlist-action" onclick="event.stopPropagation(); toggleWishlist({{ $produit->id_product }}, false)">
                            <i class="far fa-heart"></i>
                        </button>
                    @endif

                    <div class="product-quick-actions" onclick="event.stopPropagation()">
                        <form action="{{ route('client.addToPanier', $produit->id_product) }}" method="POST" style="flex:1;">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="quick-btn quick-btn-cart" {{ $produit->stock <= 0 ? 'disabled' : '' }}>
                                <i class="fas fa-shopping-bag"></i> Add to Cart
                            </button>
                        </form>
                        <button class="quick-btn quick-btn-view" onclick="openProductDetail({{ $produit->id_product }})">
                            <i class="fas fa-eye"></i> View
                        </button>
                    </div>
                </div>

                <div class="product-info">
                    <div class="product-store">
                        <img src="{{ asset('images/boutiquemini.png') }}" alt="">
                        {{ $produit->boutique->name ?? 'Unknown Vault' }}
                    </div>
                    <h3 class="product-name">{{ $produit->nom }}</h3>
                    <div class="product-category-tag">
                        <i class="fas fa-tag" style="font-size:0.6rem; color:var(--accent)"></i>
                        {{ $produit->categorie->nom ?? 'Uncategorized' }}
                    </div>

                    @if($reviewCount > 0)
                    <div class="product-rating">
                        <span class="stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($avgRating))
                                    <i class="fas fa-star"></i>
                                @elseif($i - 0.5 <= $avgRating)
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </span>
                        <span class="rating-text">({{ $reviewCount }})</span>
                    </div>
                    @endif

                    <div class="product-price-row">
                        <span class="product-price">{{ number_format($produit->prix, 2) }} <span class="currency">DT</span></span>
                        @if($produit->stock > 5)
                            <span class="product-stock-tag stock-in">In Stock</span>
                        @elseif($produit->stock > 0)
                            <span class="product-stock-tag stock-low">Only {{ $produit->stock }} left</span>
                        @else
                            <span class="product-stock-tag stock-out">Out of Stock</span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-search"></i>
                <h3>No Treasures Found</h3>
                <p>Try adjusting your filters or search for something else.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($produits->hasPages())
        <div class="pagination-wrapper">
            {{ $produits->appends(request()->query())->links() }}
        </div>
        @endif
    </main>
</div>

<!-- AI VISUAL SEARCH MODAL -->
<div class="ai-modal-overlay" id="aiModal">
    <button class="ai-close-btn" onclick="closeAiModal()"><i class="fas fa-times"></i></button>
    <div class="ai-modal">
        <h3><i class="fas fa-magic" style="color:var(--accent); margin-right:8px;"></i>Visual Search</h3>
        <p>Upload a photo of any item and we'll find similar treasures for you</p>

        <div class="ai-upload-zone" id="aiUploadZone" onclick="document.getElementById('aiImageInput').click()">
            <i class="fas fa-cloud-upload-alt"></i>
            <span>Click to upload or drag & drop</span>
            <small>Supports JPG, PNG up to 5MB</small>
        </div>

        <img id="aiPreview" class="ai-preview" style="display:none;">

        <input type="file" id="aiImageInput" accept="image/*" onchange="handleAiImage(event)">

        <button class="ai-search-btn" id="aiSearchBtn" onclick="performAiSearch()" disabled>
            <i class="fas fa-search"></i> Find Similar Items
        </button>
    </div>
</div>

<!-- HIDDEN FORMS FOR WISHLIST -->
<form id="wishlistAddForm" action="" method="POST" style="display:none;">@csrf</form>
<form id="wishlistRemoveForm" action="" method="POST" style="display:none;">@csrf</form>

<script>
// ===== SIDEBAR TOGGLE =====
const sidebarToggle = document.getElementById('sidebarToggle');
const sidebarOverlay = document.getElementById('sidebarOverlay');
const browseSidebar = document.getElementById('browseSidebar');

sidebarToggle.addEventListener('click', () => {
    browseSidebar.classList.toggle('active');
    sidebarOverlay.classList.toggle('active');
});

sidebarOverlay.addEventListener('click', () => {
    browseSidebar.classList.remove('active');
    sidebarOverlay.classList.remove('active');
});

// ===== APPLY FILTERS =====
function applyFilters() {
    const params = new URLSearchParams();

    const search = document.getElementById('searchInput').value;
    if (search) params.set('search', search);

    const priceMin = document.getElementById('priceMin').value;
    if (priceMin) params.set('price_min', priceMin);

    const priceMax = document.getElementById('priceMax').value;
    if (priceMax) params.set('price_max', priceMax);

    const sort = document.getElementById('sortSelect').value;
    if (sort) params.set('sort', sort);

    const rating = document.querySelector('input[name="rating"]:checked')?.value;
    if (rating) params.set('rating', rating);

    // Preserve existing category/boutique params
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('category')) params.set('category', urlParams.get('category'));
    if (urlParams.has('boutique')) params.set('boutique', urlParams.get('boutique'));

    window.location.href = '{{ route('client.browse') }}?' + params.toString();
}

// ===== PRICE SLIDER =====
const priceMinInput = document.getElementById('priceMin');
const priceMaxInput = document.getElementById('priceMax');
const priceFill = document.getElementById('priceFill');
const thumbMin = document.getElementById('thumbMin');
const thumbMax = document.getElementById('thumbMax');

function updatePriceSlider() {
    const min = parseInt(priceMinInput.value) || 0;
    const max = parseInt(priceMaxInput.value) || 5000;
    const minPercent = (min / 5000) * 100;
    const maxPercent = (max / 5000) * 100;

    thumbMin.style.left = minPercent + '%';
    thumbMax.style.left = maxPercent + '%';
    priceFill.style.left = minPercent + '%';
    priceFill.style.width = (maxPercent - minPercent) + '%';

    document.getElementById('priceMinDisplay').textContent = min + ' DT';
    document.getElementById('priceMaxDisplay').textContent = max + ' DT';
}

updatePriceSlider();
priceMinInput.addEventListener('input', updatePriceSlider);
priceMaxInput.addEventListener('input', updatePriceSlider);

// ===== AI VISUAL SEARCH =====
let aiImageFile = null;

function openAiModal() {
    document.getElementById('aiModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeAiModal() {
    document.getElementById('aiModal').classList.remove('active');
    document.body.style.overflow = '';
    aiImageFile = null;
    document.getElementById('aiPreview').style.display = 'none';
    document.getElementById('aiUploadZone').style.display = 'block';
    document.getElementById('aiSearchBtn').disabled = true;
}

function handleAiImage(event) {
    const file = event.target.files[0];
    if (!file) return;

    aiImageFile = file;
    const reader = new FileReader();
    reader.onload = (e) => {
        document.getElementById('aiPreview').src = e.target.result;
        document.getElementById('aiPreview').style.display = 'block';
        document.getElementById('aiUploadZone').style.display = 'none';
        document.getElementById('aiSearchBtn').disabled = false;
    };
    reader.readAsDataURL(file);
}

function performAiSearch() {
    if (!aiImageFile) return;

    const btn = document.getElementById('aiSearchBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Analyzing...';

    const formData = new FormData();
    formData.append('image', aiImageFile);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('{{ route('client.aiSearch') }}', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            alert('No similar items found. Try another image.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-search"></i> Find Similar Items';
        }
    })
    .catch(err => {
        console.error(err);
        alert('Something went wrong. Please try again.');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-search"></i> Find Similar Items';
    });
}

// ===== WISHLIST TOGGLE =====
function toggleWishlist(productId, isFav) {
    if (isFav) {
        const form = document.getElementById('wishlistRemoveForm');
        form.action = '{{ url('client/remove-from-favoris') }}/' + productId;
        form.submit();
    } else {
        const form = document.getElementById('wishlistAddForm');
        form.action = '{{ url('client/add-to-favoris') }}/' + productId;
        form.submit();
    }
}

// ===== PRODUCT DETAIL =====
function openProductDetail(id) {
    window.location.href = '{{ url('client/product') }}/' + id;
}

// ===== DRAG & DROP FOR AI =====
const uploadZone = document.getElementById('aiUploadZone');
uploadZone.addEventListener('dragover', (e) => { e.preventDefault(); uploadZone.style.borderColor = 'var(--accent)'; });
uploadZone.addEventListener('dragleave', () => { uploadZone.style.borderColor = 'var(--border)'; });
uploadZone.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadZone.style.borderColor = 'var(--border)';
    const files = e.dataTransfer.files;
    if (files.length) {
        document.getElementById('aiImageInput').files = files;
        handleAiImage({ target: { files: files } });
    }
});
</script>

@endsection