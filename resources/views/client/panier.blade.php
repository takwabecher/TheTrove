@extends('layouts.app')

@section('title', 'Basket — TheTrove')

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
            <a href="{{ route('client.panier') }}" class="sidebar-nav-item active">
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
                <img src="{{ asset('images/settingmini1.png') }}" alt="settings">
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
                <img src="{{ asset('images/paniermini.png') }}" alt="basket">Your <span style="color: var(--accent); font-weight: 600;">Basket</span>
            </h2>
            <p class="vault-subtitle">
                Review your selected treasures before claiming them.
            </p>
        </div>

        @if($panier_items->count() > 0)
        <div class="basket-layout reveal">
            <!-- ITEMS LIST -->
            <div class="basket-items">
                @php $grand_total = 0; @endphp
                @foreach($panier_items as $item)
                @php
                    $subtotal = $item->produit->prix * $item->quantity;
                    $grand_total += $subtotal;
                @endphp
                <div class="basket-item">
                    <div class="item-image">
                        <img src="{{ $item->produit->images->first() ? asset('storage/' . $item->produit->images->first()->path) : asset('images/placeholder-product.png') }}"
                            alt="{{ $item->produit->nom }}"
                            onerror="this.src='{{ asset('images/placeholder-product.png') }}'">
                    </div>
                    <div class="item-details">
                        <h3 class="item-name">{{ $item->produit->nom }}</h3>
                        <div class="item-vault">
                            <img src="{{ asset('images/boutiquemini1.png') }}" alt="vault">
                            <span>{{ $item->produit->boutique->name ?? 'Unknown Vault' }}</span>
                        </div>
                        <div class="item-meta">
                            <span class="item-price">TD{{ number_format($item->produit->prix, 2) }}</span>
                            <div class="qty-control">
                                <form action="{{ route('client.updatePanierQuantity', $item->id_product) }}" method="POST" class="qty-form" id="qty-form-{{ $item->id_product }}">
                                    @csrf
                                    <button type="button" class="qty-btn minus" onclick="updateQty({{ $item->id_product }}, -1, {{ $item->quantity }})" {{ $item->quantity <= 1 ? 'disabled' : '' }} aria-label="Decrease quantity">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" name="quantity" id="qty-input-{{ $item->id_product }}" value="{{ $item->quantity }}" min="1" max="{{ $item->produit->stock + $item->quantity }}" class="qty-input" onchange="document.getElementById('qty-form-{{ $item->id_product }}').submit()">
                                    <button type="button" class="qty-btn plus" onclick="updateQty({{ $item->id_product }}, 1, {{ $item->quantity }})" {{ $item->quantity >= $item->produit->stock ? 'disabled' : '' }} aria-label="Increase quantity">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="item-total">
                        <span class="total-label">Total</span>
                        <span class="total-value">TD{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <form action="{{ route('client.removeFromPanier', $item->id_product) }}" method="POST" class="item-remove">
                        @csrf
                        <button type="submit" aria-label="Remove item">
                            <img src="{{ asset('images/trashmini.png') }}" alt="remove">
                        </button>
                    </form>
                </div>
                @endforeach
            </div>

            <!-- SUMMARY CARD -->
            <div class="basket-summary reveal">
                <div class="summary-header">
                    <img src="{{ asset('images/ordermini1.png') }}" alt="receipt">
                    <h3>Order Summary</h3>
                </div>
                <div class="summary-body">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>TD{{ number_format($grand_total, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span class="free">Calculated at checkout</span>
                    </div>
                    <div class="summary-divider"></div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>TD{{ number_format($grand_total, 2) }}</span>
                    </div>
                </div>
                <div class="summary-actions">
                    <button type="button" class="btn-checkout" onclick="openInvoiceModal()">
                        <img src="{{ asset('images/approvedmini.png') }}" alt="receipt">
                        <span>Place Order</span>
                    </button>
                    <a href="{{ route('client.browse') }}" class="btn-continue">
                        <img src="{{ asset('images/produitmini1.png') }}" alt="browse">
                        <span>Continue Shopping</span>
                    </a>
                </div>
            </div>
        </div>
        @else
        <div class="empty-state reveal">
            <h3>Your Basket is Empty</h3>
            <p>Discover unique treasures from our curated vaults.</p>
            <a href="{{ route('client.browse') }}" class="btn-browse">
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
                <div><strong>Invoice #</strong> <span id="invNumber">INV-{{ date('Y') }}-{{ strtoupper(substr(uniqid(), -5)) }}</span></div>
                <div><strong>Date:</strong> <span id="invDate">{{ now()->format('M d, Y') }}</span></div>
            </div>
        </div>

        <div class="invoice-bill-to">
            <h4>Bill To</h4>
            <div class="bill-client">
                <div id="billName">{{ $user->name }}</div>
                <div id="billEmail" style="color: var(--text-muted); font-size: 0.9rem;">{{ $user->email }}</div>
            </div>

            <div class="invoice-edit-fields">
                <div class="field-group">
                    <label>Delivery Address</label>
                    <textarea id="addressInput" rows="2" placeholder="Enter your full delivery address...">{{ $user->address }}</textarea>
                </div>
                <div class="field-group">
                    <label>Phone Number</label>
                    <input type="text" id="phoneInput" value="{{ $user->phone }}" placeholder="Enter phone number">
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
            <tbody id="invoiceTableBody">
                <!-- Populated by JS -->
            </tbody>
        </table>

        <div class="invoice-totals-block">
            <div class="inv-total-row">
                <span>Subtotal</span>
                <span id="invSubtotal">TD0.00</span>
            </div>
            <div class="inv-total-row shipping-row">
                <span>Shipping</span>
                <div style="text-align: right;">
                    <span id="invShipping">TD0.00</span>
                    <div class="shipping-detail" id="shippingDetail"></div>
                </div>
            </div>
            <div class="inv-total-row grand">
                <span>Total</span>
                <span id="invGrandTotal">TD0.00</span>
            </div>
        </div>

        <div class="delivery-select">
            <label for="deliveryCompany">Delivery Company</label>
            <select id="deliveryCompany" onchange="recalculateInvoice()">
                <option value="" data-rate="0">-- Select Delivery Company --</option>
                @foreach($delivery_companies as $company)
                <option value="{{ $company->id }}" data-rate="{{ $company->shipping_rate }}">
                    {{ $company->name }} — {{ number_format($company->shipping_rate, 2) }} TD per shop
                </option>
                @endforeach
            </select>
        </div>

        <div class="invoice-actions-row">
            <button type="button" class="btn-invoice-print" onclick="printInvoice()">
                <img src="{{ asset('images/printpdf.png') }}" alt="print"> Print / Save PDF
            </button>
            <form action="{{ route('client.checkout') }}" method="POST" id="finalCheckoutForm" style="flex: 1;">
                @csrf
                <input type="hidden" name="delivery_company_id" id="formDeliveryCompanyId">
                <input type="hidden" name="shipping_address" id="formShippingAddress">
                <input type="hidden" name="phone" id="formPhone">
                <button type="submit" class="btn-invoice-confirm" id="btnConfirmOrder" disabled>
                    <img src="{{ asset('images/accept.png') }}" alt="accept"> Confirm Order
                </button>
            </form>
        </div>
        <div class="invoice-qr">
    <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode(config('app.url')) }}" alt="QR Code">
    <p>Scan to visit TheTrove</p>
</div>


    </div>
</div>

<style>
/* ===== SIDEBAR & BASKET (preserved from your original) ===== */
.menu-toggle {
    position: fixed; top: 100px; left: 24px;
    width: 48px; height: 48px; border-radius: 12px;
    border: 1px solid var(--border); background: var(--bg-card);
    color: var(--accent); display: flex; align-items: center; justify-content: center;
    cursor: pointer; z-index: 200; font-size: 1.2rem;
    transition: all 0.3s ease; backdrop-filter: blur(10px);
}
.menu-toggle:hover { background: var(--accent-glow); border-color: var(--border-hover); transform: scale(1.05); }

.sidebar-overlay {
    position: fixed; inset: 0; background: rgba(0,0,0,0.6);
    backdrop-filter: blur(4px); z-index: 998;
    opacity: 0; visibility: hidden; transition: all 0.3s ease;
}
.sidebar-overlay.active { opacity: 1; visibility: visible; }

.slide-sidebar {
    position: fixed; top: 0; left: 0; width: 320px; height: 100vh;
    background: var(--bg-secondary); border-right: 1px solid var(--border);
    z-index: 999; transform: translateX(-100%);
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

.basket-layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 32px;
    align-items: start;
}

.basket-items {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.basket-item {
    display: flex;
    align-items: center;
    gap: 20px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 20px;
    transition: all 0.3s ease;
    position: relative;
}
.basket-item:hover {
    border-color: var(--border-hover);
    box-shadow: 0 10px 30px var(--shadow);
}

.item-image {
    width: 100px;
    height: 100px;
    border-radius: 16px;
    overflow: hidden;
    background: var(--bg-tertiary);
    flex-shrink: 0;
}
.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-details {
    flex: 1;
    min-width: 0;
}

.item-name {
    font-family: 'Cinzel', serif;
    font-size: 1.1rem;
    color: var(--text-primary);
    margin-bottom: 6px;
    font-weight: 600;
}

.item-vault {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.8rem;
    color: var(--text-muted);
    margin-bottom: 12px;
}
.item-vault img {
    width: 16px;
    height: 16px;
    object-fit: contain;
    opacity: 0.6;
}

.item-meta {
    display: flex;
    gap: 16px;
    align-items: center;
    flex-wrap: wrap;
}

.item-price {
    font-family: 'Cinzel', serif;
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--accent);
}

.item-total {
    text-align: right;
    min-width: 80px;
}

.total-label {
    display: block;
    font-size: 0.75rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 4px;
}

.total-value {
    font-family: 'Cinzel', serif;
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text-primary);
}

.item-remove button {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}
.item-remove button:hover {
    background: #ef4444;
    color: white;
}
.item-remove button img { width: 18px; height: 18px; object-fit: contain; }

.basket-summary {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 28px;
    position: sticky;
    top: 140px;
}

.summary-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--border);
}
.summary-header img {
    width: 32px;
    height: 32px;
    object-fit: contain;
}
.summary-header h3 {
    font-family: 'Cinzel', serif;
    font-size: 1.2rem;
    color: var(--text-primary);
    margin: 0;
}

.summary-body {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-bottom: 24px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.95rem;
    color: var(--text-secondary);
}
.summary-row.total {
    font-family: 'Cinzel', serif;
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--text-primary);
}
.summary-row .free {
    color: #10b981;
    font-weight: 600;
}

.summary-divider {
    height: 1px;
    background: var(--border);
}

.summary-actions {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.btn-checkout {
    width: 100%;
    padding: 16px;
    border-radius: 12px;
    background: var(--accent);
    color: #000;
    font-family: 'Cinzel', serif;
    font-size: 0.95rem;
    font-weight: 700;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s ease;
}
.btn-checkout:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px var(--accent-glow);
}

.btn-continue {
    width: 100%;
    padding: 14px;
    border-radius: 12px;
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s ease;
}
.btn-continue:hover {
    border-color: var(--border-hover);
    color: var(--accent);
}

.empty-state {
    text-align: center;
    padding: 80px 20px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
}
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

.reveal { opacity: 0; transform: translateY(30px); transition: all 0.6s ease; }
.reveal.active { opacity: 1; transform: translateY(0); }

.qty-control { display: flex; align-items: center; }
.qty-form { display: flex; align-items: center; gap: 4px; }
.qty-btn {
    width: 32px; height: 32px; border-radius: 8px;
    background: var(--bg-tertiary); border: 1px solid var(--border);
    color: var(--text-secondary); display: flex;
    align-items: center; justify-content: center;
    cursor: pointer; transition: all 0.3s ease; font-size: 0.75rem;
}
.qty-btn:hover:not(:disabled) { background: var(--accent-glow); border-color: var(--accent); color: var(--accent); }
.qty-btn:disabled { opacity: 0.3; cursor: not-allowed; }
.qty-input {
    width: 50px; height: 32px; text-align: center;
    background: var(--bg-tertiary); border: 1px solid var(--border);
    border-radius: 8px; color: var(--text-primary);
    font-family: 'Cinzel', serif; font-size: 0.9rem; font-weight: 600;
    -moz-appearance: textfield;
}
.qty-input::-webkit-outer-spin-button,
.qty-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
.qty-input:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }

/* ===== MODAL SYSTEM ===== */
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

/* Invoice Modal */
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
.bill-client { margin-bottom: 16px; }
.bill-client div:first-child {
    font-weight: 600; color: var(--text-primary); font-size: 1.05rem;
}

.invoice-edit-fields {
    display: grid; gap: 12px;
    background: var(--bg-secondary);
    padding: 16px; border-radius: 12px;
    border: 1px solid var(--border);
}
.field-group { display: flex; flex-direction: column; gap: 6px; }
.field-group label {
    font-size: 0.8rem; font-weight: 600;
    color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;
}
.field-group input,
.field-group textarea {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: 10px; padding: 10px 14px;
    color: var(--text-primary); font-size: 0.95rem;
    resize: vertical; transition: all 0.2s;
}
.field-group input:focus,
.field-group textarea:focus {
    outline: none; border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-glow);
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
.shipping-detail {
    font-size: 0.75rem; color: var(--text-muted); margin-top: 2px;
}

.delivery-select {
    margin-bottom: 24px;
}
.delivery-select label {
    display: block; font-size: 0.8rem; font-weight: 600;
    color: var(--text-secondary); text-transform: uppercase;
    letter-spacing: 0.5px; margin-bottom: 8px;
}
.delivery-select select {
    width: 100%; padding: 12px 14px;
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: 12px; color: var(--text-primary);
    font-size: 0.95rem; cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23999' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 14px center;
}
.delivery-select select:focus {
    outline: none; border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-glow);
}

.invoice-actions-row {
    display: flex; gap: 12px;
}
.btn-invoice-print {
    padding: 14px 20px; border-radius: 12px;
    background: var(--bg-secondary); border: 1px solid var(--border);
    color: var(--text-secondary); font-size: 0.9rem; font-weight: 600;
    cursor: pointer; display: flex; align-items: center; gap: 8px;
    transition: all 0.3s ease; white-space: nowrap;
}
.btn-invoice-print:hover { border-color: var(--border-hover); color: var(--accent); }

.btn-invoice-confirm {
    width: 100%; padding: 14px;
    border-radius: 12px; background: var(--accent);
    color: #000; font-family: 'Cinzel', serif;
    font-size: 0.95rem; font-weight: 700; border: none;
    cursor: pointer; display: flex; align-items: center;
    justify-content: center; gap: 10px;
    transition: all 0.3s ease;
}
.btn-invoice-confirm:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px var(--accent-glow);
}
.btn-invoice-confirm:disabled {
    opacity: 0.4; cursor: not-allowed; transform: none;
    box-shadow: none;
}

.invoice-qr {
    text-align: center;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid var(--border);
}
.invoice-qr img {
    width: 100px;
    height: 100px;
    border-radius: 12px;
}
.invoice-qr p {
    font-size: 0.8rem;
    color: var(--text-muted);
    margin-top: 6px;
}

.item-image a { display: block; width: 100%; height: 100%; }
.item-image a img { width: 100%; height: 100%; object-fit: cover; }
.item-name a:hover { color: var(--accent); }
/* Print styles */
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

/* Responsive */
@media (max-width: 992px) {
    .basket-layout { grid-template-columns: 1fr; }
    .basket-summary { position: static; }
    .invoice-modal { width: 96%; padding: 0; }
    .invoice-paper { padding: 20px; }
}
@media (max-width: 640px) {
    .basket-item { flex-wrap: wrap; }
    .item-total {
        width: 100%; text-align: left;
        display: flex; justify-content: space-between;
        padding-top: 12px; border-top: 1px solid var(--border);
    }
    .item-remove {
        position: absolute; top: 20px; right: 20px;
    }
    .vault-title { font-size: 1.8rem; }
    .slide-sidebar { width: 280px; }
    .invoice-actions-row { flex-direction: column; }
    .btn-invoice-print { justify-content: center; }
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
    if (e.key === 'Escape' && slideSidebar.classList.contains('active')) closeSidebar();
});

// ===== SCROLL REVEAL =====
document.addEventListener('DOMContentLoaded', function() {
    const revealElements = document.querySelectorAll('.reveal');
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('active'); });
    }, { threshold: 0.1 });
    revealElements.forEach(el => revealObserver.observe(el));
});

// ===== QTY UPDATE =====
function updateQty(productId, delta, currentQty) {
    const input = document.getElementById('qty-input-' + productId);
    const newQty = parseInt(input.value) + delta;
    const min = parseInt(input.min);
    const max = parseInt(input.max);
    if (newQty >= min && newQty <= max) {
        input.value = newQty;
        input.form.submit();
    }
}

// ===== INVOICE MODAL =====
const invoiceBackdrop = document.getElementById('invoiceModalBackdrop');
const invoiceModal = document.getElementById('invoiceModal');
const invoiceTableBody = document.getElementById('invoiceTableBody');
const invSubtotal = document.getElementById('invSubtotal');
const invShipping = document.getElementById('invShipping');
const invGrandTotal = document.getElementById('invGrandTotal');
const shippingDetail = document.getElementById('shippingDetail');
const deliverySelect = document.getElementById('deliveryCompany');
const btnConfirm = document.getElementById('btnConfirmOrder');

// Basket data from PHP
@php
$basketData = $panier_items->map(function($item) {
    return [
        'nom' => $item->produit->nom,
        'boutique' => $item->produit->boutique->name ?? 'Unknown',
        'prix' => $item->produit->prix,
        'quantity' => $item->quantity,
    ];
})->values();
@endphp
const basketItems = @json($basketData);
const shopCount = {{ $shop_count }};
const subtotal = {{ $grand_total }};

function openInvoiceModal() {
    // Populate table
    invoiceTableBody.innerHTML = basketItems.map(item => `
        <tr>
            <td>
                <div class="item-name-cell">${escapeHtml(item.nom)}</div>
                <div class="item-shop-cell">${escapeHtml(item.boutique)}</div>
            </td>
            <td class="text-center">${item.quantity}</td>
            <td class="text-right">TD${formatMoney(item.prix)}</td>
            <td class="text-right">TD${formatMoney(item.prix * item.quantity)}</td>
        </tr>
    `).join('');

    invSubtotal.textContent = 'TD' + formatMoney(subtotal);
    recalculateInvoice();

    invoiceModal.classList.add('active');
    invoiceBackdrop.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeInvoiceModal() {
    invoiceModal.classList.remove('active');
    invoiceBackdrop.classList.remove('active');
    document.body.style.overflow = '';
}

function recalculateInvoice() {
    const option = deliverySelect.options[deliverySelect.selectedIndex];
    const rate = parseFloat(option.dataset.rate || 0);
    const shipping = rate * shopCount;
    const grand = subtotal + shipping;

    invShipping.textContent = 'TD' + formatMoney(shipping);
    shippingDetail.textContent = shopCount + ' shop' + (shopCount > 1 ? 's' : '') + ' × ' + formatMoney(rate) + ' TD';
    invGrandTotal.textContent = 'TD' + formatMoney(grand);

    // Enable confirm only if a company is selected
    btnConfirm.disabled = !deliverySelect.value;
}

function printInvoice() {
    // Sync hidden form fields before printing
    syncFormFields();
    window.print();
}

function syncFormFields() {
    document.getElementById('formDeliveryCompanyId').value = deliverySelect.value;
    document.getElementById('formShippingAddress').value = document.getElementById('addressInput').value;
    document.getElementById('formPhone').value = document.getElementById('phoneInput').value;
}

// Sync on confirm
document.getElementById('finalCheckoutForm').addEventListener('submit', function(e) {
    if (!deliverySelect.value) {
        e.preventDefault();
        alert('Please select a delivery company.');
        return;
    }
    syncFormFields();
});

// Close on Escape
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && invoiceModal.classList.contains('active')) closeInvoiceModal();
});

// Helpers
function formatMoney(num) {
    return parseFloat(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
@endsection