@extends('layouts.app')

@section('title', 'Orders — TheTrove')

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
            <a href="{{ route('client.browse') }}" class="sidebar-nav-item">
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
            <a href="{{ route('client.commandes') }}" class="sidebar-nav-item active">
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
                <span class="section-label">Active Orders</span>
            </div>
            <h2 class="vault-title">
                <img src="{{ asset('images/ordermini1.png') }}" alt="orders">Your <span style="color: var(--accent); font-weight: 600;">Orders</span>
            </h2>
            <p class="vault-subtitle">
                Track and manage your active orders and deliveries.
            </p>
        </div>

        @php
            $activeStatuses = ['pending', 'picked_up', 'in_transit', 'by the delivery company'];
            $filteredCommandes = $commandes->filter(function($cmd) use ($activeStatuses) {
                if ($cmd->status === 'pending') return true;
                if (in_array($cmd->delivery_status, $activeStatuses)) return true;
                return false;
            });
        @endphp

        @if($filteredCommandes->count() > 0)
        <div class="orders-list">
            @foreach($filteredCommandes as $cmd)
            @php
                $invoiceNumber = 'INV-' . date('Y') . '-' . strtoupper(str_pad($cmd->id_cmd, 5, '0', STR_PAD_LEFT));
                $invoiceItems = $cmd->items->map(function($ligne) {
                    $boutique = $ligne->produit->boutique ?? null;
                    return [
                        'nom' => $ligne->produit->nom,
                        'quantity' => $ligne->quantity,
                        'price' => $ligne->price,
                        'boutique' => $boutique ? $boutique->name : 'Unknown Vault'
                    ];
                })->values();
            @endphp
            <div class="order-card reveal">
                <!-- ORDER HEADER -->
                <div class="order-header">
                    <div class="order-meta">
                        <div class="order-number">
                            <span class="order-label">Invoice #</span>
                            <span class="order-id">{{ $invoiceNumber }}</span>
                        </div>
                    </div>
                    <div class="order-status-date">
                        @if($cmd->status === 'pending')
                            <span class="order-status pending">Pending Confirmation</span>
                        @else
                            <span class="order-status {{ $cmd->delivery_status }}">
                                {{ ucfirst(str_replace('_', ' ', $cmd->delivery_status)) }}
                            </span>
                        @endif
                        <span class="order-date">{{ \Carbon\Carbon::parse($cmd->date_cmd)->format('M d, Y') }}</span>
                        <button class="invoice-eye-btn"
                            data-invoice="{{ $invoiceNumber }}"
                            data-date="{{ $cmd->date_cmd }}"
                            data-status="{{ $cmd->status }}"
                            data-delivery="{{ $cmd->delivery_status }}"
                            data-delivery-company="{{ $cmd->deliveryCompany->name ?? 'N/A' }}"
                            data-shipping-address="{{ $cmd->shipping_address ?? 'N/A' }}"
                            data-items='@json($invoiceItems)'
                            data-total="{{ number_format($cmd->total_price, 2) }}"
                            onclick="openInvoiceModalFromBtn(this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- ORDER ITEMS -->
                <div class="order-items">
                    @foreach($cmd->items as $ligne)
                    @php
                        $itemBoutique = $ligne->produit->boutique ?? null;
                    @endphp
                    <div class="order-item">
                        <div class="item-info">
                            <span class="item-name">{{ $ligne->produit->nom }}</span>
                            @if($itemBoutique)
                            <span class="item-vault">{{ $itemBoutique->name }}</span>
                            @endif
                            <span class="item-qty">x{{ $ligne->quantity }}</span>
                        </div>
                        <span class="item-price">DT{{ number_format($ligne->price * $ligne->quantity, 2) }}</span>
                    </div>
                    @endforeach
                </div>

                <!-- ORDER FOOTER -->
                <div class="order-footer">
                    <div class="order-total">
                        <span class="total-label">Total</span>
                        <span class="total-value">DT{{ number_format($cmd->total_price, 2) }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state reveal">
            <div class="empty-icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <h3>No Active Orders</h3>
            <p>You don't have any pending or in-transit orders right now.</p>
            <a href="{{ route('client.browse') }}" class="btn-browse">
                <i class="fas fa-shopping-bag"></i>
                <span>Browse Products</span>
            </a>
        </div>
        @endif
    </div>
</div>

<!-- ==================== ORDER INVOICE MODAL ==================== -->
<div class="modal-backdrop" id="invoiceModalBackdrop" onclick="closeInvoiceModal()"></div>
<div class="modal invoice-modal" id="invoiceModal">
    <div class="invoice-paper" id="invoicePaper">
        <div class="invoice-header-row">
            <h2 class="invoice-title"><img src="{{ asset('images/ordermini1.png') }}" alt="receipt"> Order Invoice</h2>
            <button class="modal-close" onclick="closeInvoiceModal()" aria-label="Close">
                <img src="{{ asset('images/cancel.png') }}" alt="cancel">
            </button>
        </div>

        <div class="invoice-brand-row">
            <div class="invoice-brand">
                <img src="{{ asset('images/TheTroveLogo.png') }}" alt="TheTrove">
                <span>TheTrove</span>
            </div>
            <div class="invoice-meta">
                <div><strong>Invoice #</strong> <span id="invNumber"></span></div>
                <div><strong>Date:</strong> <span id="invDate"></span></div>
            </div>
        </div>

        <div class="invoice-bill-to">
            <h4>Order Details</h4>
            <div class="bill-client">
                <div id="billName">{{ auth()->user()->name }}</div>
                <div style="color: var(--text-muted); font-size: 0.9rem;">{{ auth()->user()->email }}</div>
            </div>

            <div class="invoice-detail-grid">
                <div class="invoice-detail-box">
                    <span class="detail-label">Shipping Address</span>
                    <span class="detail-value" id="invShippingAddress"></span>
                </div>
                <div class="invoice-detail-box">
                    <span class="detail-label">Delivery Company</span>
                    <span class="detail-value" id="invDeliveryCompany"></span>
                </div>
                <div class="invoice-detail-box">
                    <span class="detail-label">Status</span>
                    <span class="detail-value" id="invStatus"></span>
                </div>
            </div>
        </div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th class="text-left">Item</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody id="invoiceTableBody"></tbody>
        </table>

        <div class="invoice-totals-block">
            <div class="inv-total-row">
                <span>Subtotal</span>
                <span id="invSubtotal">DT0.00</span>
            </div>
            <div class="inv-total-row grand">
                <span>Total</span>
                <span id="invGrandTotal">DT0.00</span>
            </div>
        </div>

        <div class="invoice-actions-row">
            <button type="button" class="btn-invoice-print" onclick="window.print()">
                <img src="{{ asset('images/printpdf.png') }}" alt="print"> Print / Save PDF
            </button>
        </div>
    </div>
</div>

<style>
/* ===== SIDEBAR ===== */
.menu-toggle {
    position: fixed;
    top: 125px;
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

/* ===== ORDER CARDS ===== */
.orders-list {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.order-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.4s ease;
}
.order-card:hover {
    border-color: var(--border-hover);
    box-shadow: 0 20px 50px var(--shadow);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px;
    border-bottom: 1px solid var(--border);
}

.order-meta {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.order-number {
    display: flex;
    align-items: center;
    gap: 10px;
}

.order-label {
    font-size: 0.8rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 2px;
}

.order-id {
    font-family: 'Cinzel', serif;
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text-primary);
}

.order-status-date {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 6px;
}

.order-status {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}
.order-status.pending {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
    border: 1px solid rgba(245, 158, 11, 0.3);
}
.order-status.picked_up {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
    border: 1px solid rgba(59, 130, 246, 0.3);
}
.order-status.in_transit {
    background: rgba(139, 92, 246, 0.1);
    color: #8b5cf6;
    border: 1px solid rgba(139, 92, 246, 0.3);
}
.order-status.by {
    background: rgba(6, 182, 212, 0.1);
    color: #06b6d4;
    border: 1px solid rgba(6, 182, 212, 0.3);
}
.order-status.confirmed {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}
.order-status.declined, .order-status.cancelled {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.order-date {
    font-size: 0.85rem;
    color: var(--text-muted);
}

.invoice-eye-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid var(--border);
    background: var(--bg-secondary);
    color: var(--accent);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 4px;
}
.invoice-eye-btn:hover {
    background: var(--accent-glow);
    border-color: var(--accent);
    transform: scale(1.1);
}

/* ===== ORDER ITEMS ===== */
.order-items {
    padding: 20px 24px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.order-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    background: var(--bg-secondary);
    border-radius: 12px;
    border: 1px solid var(--border);
}

.item-info {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.item-name {
    font-weight: 600;
    color: var(--text-primary);
}

.item-vault {
    font-size: 0.75rem;
    color: var(--accent);
    padding: 2px 10px;
    background: var(--accent-glow);
    border-radius: 6px;
    border: 1px solid var(--border-hover);
    font-weight: 600;
}

.item-qty {
    font-size: 0.85rem;
    color: var(--text-muted);
    padding: 2px 10px;
    background: var(--bg-card);
    border-radius: 6px;
    border: 1px solid var(--border);
}

.item-price {
    font-family: 'Cinzel', serif;
    font-weight: 700;
    color: var(--accent);
    white-space: nowrap;
    margin-left: 12px;
}

/* ===== ORDER FOOTER ===== */
.order-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-top: 1px solid var(--border);
    background: var(--bg-secondary);
}

.order-total {
    display: flex;
    align-items: center;
    gap: 12px;
}

.total-label {
    font-size: 0.85rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.total-value {
    font-family: 'Cinzel', serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
}

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 80px 20px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
}
.empty-icon { font-size: 5rem; color: var(--accent); margin-bottom: 24px; opacity: 0.3; }
.empty-state h3 { font-family: 'Cinzel', serif; color: var(--text-primary); margin-bottom: 12px; font-size: 1.5rem; }
.empty-state p { color: var(--text-secondary); margin-bottom: 32px; }

.btn-browse {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 32px;
    border-radius: 12px;
    background: var(--accent);
    color: #000;
    font-family: 'Cinzel', serif;
    font-size: 0.95rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s ease;
}
.btn-browse:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px var(--accent-glow);
}

/* ===== INVOICE MODAL (Panier Style) ===== */
.modal-backdrop {
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.7);
    backdrop-filter: blur(6px);
    z-index: 1000;
    opacity: 0; visibility: hidden;
    transition: all 0.3s ease;
}
.modal-backdrop.active { opacity: 1; visibility: visible; }

.modal {
    position: fixed; z-index: 1001;
    opacity: 0; visibility: hidden;
    transition: all 0.3s ease;
}
.modal.active { opacity: 1; visibility: visible; }

.modal-close {
    width: 36px; height: 36px; border-radius: 10px;
    border: 1px solid var(--border); background: var(--bg-card);
    color: var(--text-secondary); display: flex;
    align-items: center; justify-content: center;
    cursor: pointer; transition: all 0.2s;
}
.modal-close:hover { background: rgba(239,68,68,0.1); color: #ef4444; border-color: rgba(239,68,68,0.3); }

.invoice-modal {
    top: 50%; left: 50%;
    transform: translate(-50%, -50%) scale(0.95);
    width: 92%; max-width: 700px; max-height: 92vh;
    overflow-y: auto;
}
.invoice-modal.active { transform: translate(-50%, -50%) scale(1); }

.invoice-paper {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 32px;
    box-shadow: 0 25px 50px rgba(0,0,0,0.3);
}

.invoice-header-row {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 24px;
}
.invoice-title {
    font-family: 'Cinzel', serif; font-size: 1.3rem;
    color: var(--accent); margin: 0;
    display: flex; align-items: center; gap: 10px;
}

.invoice-brand-row {
    display: flex; align-items: center; justify-content: space-between;
    padding-bottom: 20px; margin-bottom: 20px;
    border-bottom: 1px solid var(--border);
}
.invoice-brand {
    display: flex; align-items: center; gap: 10px;
    font-family: 'Cinzel', serif; font-weight: 700;
    color: var(--text-primary); font-size: 1.1rem;
}
.invoice-brand img { width: 32px; height: 32px; object-fit: contain; }
.invoice-meta {
    text-align: right; font-size: 0.85rem; color: var(--text-secondary);
}
.invoice-meta div { margin-bottom: 4px; }

.invoice-bill-to { margin-bottom: 24px; }
.invoice-bill-to h4 {
    font-family: 'Cinzel', serif; font-size: 0.9rem;
    color: var(--text-muted); text-transform: uppercase;
    letter-spacing: 1px; margin-bottom: 12px;
}
.bill-client { margin-bottom: 12px; }
.bill-client div:first-child {
    font-weight: 600; color: var(--text-primary); font-size: 1.05rem;
}

.invoice-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 12px;
}
.invoice-detail-box {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 12px 14px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.detail-label {
    font-size: 0.7rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
}
.detail-value {
    font-size: 0.9rem;
    color: var(--text-primary);
    font-weight: 600;
}

.invoice-table {
    width: 100%; border-collapse: collapse; margin-bottom: 20px;
    font-size: 0.9rem;
}
.invoice-table thead th {
    text-align: left; padding: 12px 8px;
    border-bottom: 2px solid var(--border);
    color: var(--text-muted); font-weight: 600;
    font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;
}
.invoice-table tbody td {
    padding: 14px 8px;
    border-bottom: 1px solid var(--border);
    color: var(--text-primary);
}
.invoice-table .text-center { text-align: center; }
.invoice-table .text-right { text-align: right; }
.invoice-table .item-name-cell { font-weight: 600; }
.invoice-table .item-shop-cell {
    font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;
}

.invoice-totals-block {
    background: var(--bg-secondary);
    border-radius: 12px; padding: 20px;
    margin-bottom: 20px;
}
.inv-total-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 8px 0; font-size: 0.95rem; color: var(--text-secondary);
}
.inv-total-row.grand {
    font-family: 'Cinzel', serif; font-size: 1.3rem;
    font-weight: 700; color: var(--accent);
    border-top: 2px solid var(--border); margin-top: 8px; padding-top: 16px;
}

.invoice-actions-row {
    display: flex; gap: 12px;
}
.btn-invoice-print {
    width: 100%;
    padding: 14px 20px; border-radius: 12px;
    background: var(--accent);
    color: #000; font-family: 'Cinzel', serif;
    font-size: 0.95rem; font-weight: 700; border: none;
    cursor: pointer; display: flex; align-items: center;
    justify-content: center; gap: 10px;
    transition: all 0.3s ease;
}
.btn-invoice-print:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px var(--accent-glow);
}

/* ===== ANIMATIONS ===== */
.reveal { opacity: 0; transform: translateY(30px); transition: all 0.6s ease; }
.reveal.active { opacity: 1; transform: translateY(0); }

@media (max-width: 768px) {
    .order-header { flex-direction: column; gap: 16px; align-items: flex-start; }
    .order-status-date { align-items: flex-start; }
    .order-footer { flex-direction: column; gap: 16px; align-items: flex-start; }
    .vault-title { font-size: 1.8rem; }
    .slide-sidebar { width: 280px; }
    .invoice-modal { width: 96%; }
    .invoice-paper { padding: 20px; }
    .invoice-detail-grid { grid-template-columns: 1fr; }
}

@media print {
    body * { visibility: hidden; }
    #invoiceModal, #invoiceModal * { visibility: visible; }
    #invoiceModal {
        position: absolute; top: 0; left: 0;
        width: 100%; max-width: 100%; max-height: none;
        transform: none; overflow: visible;
    }
    .invoice-paper {
        box-shadow: none; border: none; border-radius: 0;
    }
    .modal-backdrop, .modal-close, .invoice-actions-row { display: none !important; }
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
    if (e.key === 'Escape' && document.getElementById('invoiceModal').classList.contains('active')) {
        closeInvoiceModal();
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

// ===== INVOICE MODAL =====
function openInvoiceModalFromBtn(btn) {
    openInvoiceModal(
        btn.dataset.invoice,
        btn.dataset.date,
        btn.dataset.status,
        btn.dataset.delivery,
        btn.dataset.deliveryCompany,
        btn.dataset.shippingAddress,
        JSON.parse(btn.dataset.items),
        btn.dataset.total
    );
}

function openInvoiceModal(invNumber, date, status, deliveryStatus, deliveryCompany, shippingAddress, items, total) {
    document.getElementById('invNumber').textContent = invNumber;
    document.getElementById('invDate').textContent = new Date(date).toLocaleDateString('en-US', {
        year: 'numeric', month: 'long', day: 'numeric'
    });

    const statusLabel = status === 'pending'
        ? 'Pending Confirmation'
        : deliveryStatus.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    const statusColor = status === 'pending' ? '#f59e0b' : '#10b981';

    document.getElementById('invShippingAddress').textContent = shippingAddress;
    document.getElementById('invDeliveryCompany').textContent = deliveryCompany;
    document.getElementById('invStatus').innerHTML = `<span style="color: ${statusColor};">${statusLabel}</span>`;

    let subtotal = 0;
    let tbody = '';
    items.forEach(item => {
        const lineTotal = parseFloat(item.price) * parseInt(item.quantity);
        subtotal += lineTotal;
        tbody += `
            <tr>
                <td>
                    <div class="item-name-cell">${escapeHtml(item.nom)}</div>
                    <div class="item-shop-cell">${escapeHtml(item.boutique)}</div>
                </td>
                <td class="text-center">${item.quantity}</td>
                <td class="text-right">DT${parseFloat(item.price).toFixed(2)}</td>
                <td class="text-right">DT${lineTotal.toFixed(2)}</td>
            </tr>
        `;
    });

    document.getElementById('invoiceTableBody').innerHTML = tbody;
    document.getElementById('invSubtotal').textContent = 'DT' + subtotal.toFixed(2);
    document.getElementById('invGrandTotal').textContent = 'DT' + total;

    document.getElementById('invoiceModal').classList.add('active');
    document.getElementById('invoiceModalBackdrop').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeInvoiceModal() {
    document.getElementById('invoiceModal').classList.remove('active');
    document.getElementById('invoiceModalBackdrop').classList.remove('active');
    document.body.style.overflow = '';
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
@endsection