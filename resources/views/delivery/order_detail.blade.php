@extends('delivery.layout')

@section('title', 'Order ' . $commande->id_cmd . ' — Delivery')
@section('page_title', 'Order ' . $commande->id_cmd)

@section('content')
@php
    $orderKey = $commande->id_cmd;
    $boutiqueNames = $commande->items
        ->map(fn($item) => $item->produit?->boutique?->name ?? $item->produit?->boutique?->nom)
        ->filter()
        ->unique()
        ->values();
@endphp

<div class="order-detail-page">

    <!-- Status Flow Tracker -->
    <div class="status-tracker">
        @php $statuses = ['pending' => 'Pending', 'picked_up' => 'Picked Up', 'in_transit' => 'In Transit', 'delivered' => 'Delivered']; @endphp
        @foreach($statuses as $key => $label)
            @php
                $isActive = $commande->delivery_status === $key;
                $isDone = array_search($commande->delivery_status, array_keys($statuses)) > array_search($key, array_keys($statuses));
            @endphp
            <div class="tracker-step {{ $isActive ? 'active' : '' }} {{ $isDone ? 'done' : '' }}">
                <div class="step-dot">
    @php
        $stepImg = match(true) {
            $isDone => 'accept.png',
            $key === 'pending' => 'waitmini.png',
            $key === 'picked_up' => 'prepareM.png',
            $key === 'in_transit' => 'deliveryM.png',
            default => 'approvedmini.png',
        };
    @endphp
    <img src="{{ asset('images/' . $stepImg) }}" alt="{{ $label }}">
</div>
                <span class="step-label">{{ $label }}</span>
            </div>
            @if(!$loop->last)
                <div class="tracker-line {{ $isDone ? 'done' : '' }}"></div>
            @endif
        @endforeach
    </div>

    <div class="detail-grid">
        <!-- Left: Client Info -->
        <div class="detail-card client-card">
            <h3 class="card-title"> Client Information</h3>
            <div class="client-profile">
                <div class="client-avatar-large">{{ strtoupper(substr($commande->client?->name ?? 'U', 0, 1)) }}</div>
                <div class="client-details">
                    <span class="client-name-lg">{{ $commande->client?->name ?? 'Unknown' }}</span>
                    <span class="client-email">{{ $commande->client?->email ?? '-' }}</span>
                </div>
            </div>
            <div class="info-list">
                <div class="info-row"><img src="{{ asset('images/phonemini1.png') }}" alt="call"><span>{{ $commande->client?->phone ?? 'N/A' }}</span></div>
                <div class="info-row"><img src="{{ asset('images/posmini1.png') }}" alt="pos"><span>{{ $commande->client?->address ?? 'No address provided' }}</span></div>
            </div>
            <a href="tel:{{ $commande->client?->phone ?? '' }}" class="call-btn" onclick="return logCall(this);">
     Call Client
</a>
        </div>

        <!-- Right: Order Info -->
        <div class="detail-card order-card">
            <h3 class="card-title">Order Details</h3>
            <div class="info-list">
                @php
    $formattedOrder = 'ORD-' . ($commande->created_at?->format('Y') ?? now()->format('Y')) . '-' . str_pad($orderKey, 4, '0', STR_PAD_LEFT);
@endphp
<div class="info-row"><strong>Order </strong><span class="order-number">{{ $formattedOrder }}</span></div>
                <div class="info-row">
                    <strong>Boutique(s)</strong>
                    <span>
                        @if($boutiqueNames->isEmpty())
                            N/A
                        @else
                            {{ $boutiqueNames->join(', ') }}
                        @endif
                    </span>
                </div>
                <div class="info-row"> <strong>Total</strong>
                    <span class="price">{{ number_format(($commande->total_price ?? 0) + (auth('delivery')->user()->shipping_rate ?? 0), 2) }} DT</span>
                </div>
                <div class="info-row"><strong>Date</strong>
                    <span>{{ $commande->created_at?->format('M d, Y H:i') ?? '-' }}</span>
                </div>
                <div class="info-row"><strong>Status</strong>
                    <span class="status-badge {{ $commande->delivery_status }}">{{ ucfirst(str_replace('_', ' ', $commande->delivery_status)) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="detail-card items-card">
        <h3 class="card-title">Order Items</h3>
        <div class="items-list">
            @forelse($commande->items ?? [] as $ligne)
            <div class="item-row">
                <div class="item-info">
                    <span class="item-name">{{ $ligne->produit?->nom ?? 'Unknown Product' }}</span>
                    <span class="item-qty">Qty: {{ $ligne->quantite ?? 1 }}</span>
                </div>
                <span class="item-price">{{ number_format($ligne->prix_unitaire ?? $ligne->prix ?? $ligne->produit?->prix ?? 0, 2) }} DT</span>
            </div>
            @empty
            <p class="empty-text">No items found for this order.</p>
            @endforelse
        </div>
    </div>

    <!-- Update Status -->
    @if($commande->delivery_status !== 'delivered' && $commande->delivery_status !== 'cancelled')
    <div class="detail-card status-card">
        <h3 class="card-title">Update Delivery Status</h3>
        <form action="{{ route('delivery.orders.status', $orderKey) }}" method="POST" class="status-form">
            @csrf
            @method('PATCH')
            <div class="form-row">
                <select name="delivery_status" class="status-select" required>
                    <option value="pending" {{ $commande->delivery_status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="picked_up" {{ $commande->delivery_status === 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                    <option value="in_transit" {{ $commande->delivery_status === 'in_transit' ? 'selected' : '' }}>In Transit</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <input type="text" name="notes" class="notes-input" placeholder="Add delivery notes (optional)">
                <button type="submit" class="btn-update"><img src="{{ asset('images/save.png') }}" alt="wait"> Update</button>
            </div>
        </form>
    </div>
    @endif

    <!-- Call History -->
    <div class="detail-card calls-card">
        <h3 class="card-title">Call History</h3>
        @forelse($commande->calls ?? [] as $call)
        <div class="call-row">
            <div class="call-info">
                <span class="call-type {{ $call->call_type }}"><i class="fas {{ $call->call_type === 'outgoing' ? '' : '' }}"></i> {{ ucfirst($call->call_type) }}</span>
                <span class="call-time">{{ $call->called_at?->format('M d, Y H:i') ?? '-' }}</span>
            </div>
            <div class="call-meta">
                <span class="call-duration"> {{ $call->duration_formatted ?? '0:00' }}</span>
                @if($call->notes)
                <span class="call-notes">{{ $call->notes }}</span>
                @endif
            </div>
        </div>
        @empty
        <p class="empty-text">No calls logged yet.</p>
        @endforelse
    </div>

</div>

<style>
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
    width: 20px;
    height: 20px;
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
    margin-bottom: 12px;
}

.sidebar-user img {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
}

.sidebar-logout {
    width: 100%;
    padding: 12px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--text-secondary);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.sidebar-logout:hover {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border-color: rgba(239, 68, 68, 0.3);
}
.order-detail-page { max-width: 1200px; margin: 0 auto; padding-top: 100px; }
.status-tracker { display: flex; align-items: center; justify-content: center; gap: 0; margin-bottom: 32px; padding: 24px; background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; flex-wrap: wrap; }
.tracker-step { display: flex; flex-direction: column; align-items: center; gap: 8px; }
.step-dot {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: var(--bg-tertiary);
    border: 2px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    overflow: hidden; /* keep image inside circle */
}

.step-dot img {
    width: 22px;
    height: 22px;
    object-fit: contain;
}

/* Active & done states stay the same */
.tracker-step.active .step-dot { 
    background: var(--accent-glow); 
    border-color: var(--accent); 
    box-shadow: 0 0 20px var(--accent-glow); 
}
.tracker-step.done .step-dot { 
    background: #10b981; 
    border-color: #10b981; 
}
.tracker-line { flex: 1; min-width: 40px; height: 2px; background: var(--border); margin: 0 8px; margin-bottom: 24px; }
.tracker-line.done { background: #10b981; }
.detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px; }
.detail-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 24px; }
.card-title { font-family: 'Cinzel', serif; font-size: 1rem; font-weight: 600; color: var(--text-primary); margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
.card-title i { color: var(--accent); }
.client-profile { display: flex; align-items: center; gap: 16px; margin-bottom: 20px; }
.client-avatar-large { width: 60px; height: 60px; border-radius: 50%; background: var(--accent-glow); display: flex; align-items: center; justify-content: center; color: var(--accent); font-weight: 700; font-size: 1.4rem; }
.client-details { display: flex; flex-direction: column; }
.client-name-lg { font-size: 1.1rem; font-weight: 700; color: var(--text-primary); }
.client-email { color: var(--text-muted); font-size: 0.85rem; }
.info-list { display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px; }
.info-row { display: flex; align-items: center; gap: 12px; color: var(--text-secondary); font-size: 0.9rem; }
.info-row i { color: var(--accent); width: 18px; text-align: center; }
.info-row strong { color: var(--text-primary); min-width: 100px; }
.price { color: var(--accent); font-weight: 700; font-size: 1.1rem; }
.call-btn { display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 14px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #fff; border: none; border-radius: 12px; text-decoration: none; font-weight: 600; font-size: 0.95rem; transition: all 0.3s ease; }
.call-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(16,185,129,0.3); }
.items-card, .status-card, .calls-card { margin-bottom: 24px; }
.items-list { display: flex; flex-direction: column; gap: 12px; }
.item-row { display: flex; justify-content: space-between; align-items: center; padding: 14px 16px; background: var(--bg-tertiary); border-radius: 10px; }
.item-info { display: flex; flex-direction: column; }
.item-name { font-weight: 600; color: var(--text-primary); }
.item-qty { color: var(--text-muted); font-size: 0.8rem; }
.item-price { font-weight: 700; color: var(--accent); }
.status-form { display: flex; gap: 12px; flex-wrap: wrap; }
.form-row { display: flex; gap: 12px; width: 100%; flex-wrap: wrap; }
.status-select, .notes-input { padding: 12px 16px; background: var(--bg-tertiary); border: 1px solid var(--border); border-radius: 10px; color: var(--text-primary); font-family: 'DM Sans', sans-serif; font-size: 0.9rem; }
.status-select { min-width: 160px; }
.notes-input { flex: 1; min-width: 200px; }
.status-select:focus, .notes-input:focus { outline: none; border-color: var(--accent); }
.btn-update { padding: 12px 24px; background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dim) 100%); color: var(--bg-primary); border: none; border-radius: 10px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease; }
.btn-update:hover { transform: translateY(-2px); box-shadow: 0 8px 24px var(--accent-glow); }
.call-row { display: flex; justify-content: space-between; align-items: center; padding: 14px 16px; background: var(--bg-tertiary); border-radius: 10px; margin-bottom: 8px; }
.call-info { display: flex; align-items: center; gap: 16px; }
.call-type { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
.call-type.outgoing { background: rgba(59,130,246,0.12); color: #3b82f6; }
.call-type.incoming { background: rgba(16,185,129,0.12); color: #10b981; }
.call-time { color: var(--text-muted); font-size: 0.8rem; }
.call-meta { display: flex; align-items: center; gap: 12px; }
.call-duration { color: var(--text-secondary); font-size: 0.85rem; }
.call-notes { color: var(--text-muted); font-size: 0.8rem; font-style: italic; }
.empty-text { color: var(--text-muted); text-align: center; padding: 20px; font-size: 0.9rem; }
.order-number {
    font-family: 'Cinzel', serif;
    font-weight: 600;
    color: var(--accent);
    letter-spacing: 1px;
}

/* Modal */
.modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(8px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 2000;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.modal-backdrop.active {
    display: flex;
    opacity: 1;
}
.modal-box {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    width: 100%;
    max-width: 440px;
    margin: 20px;
    box-shadow: 0 30px 60px var(--shadow);
    transform: translateY(20px);
    transition: transform 0.3s ease;
}
.modal-backdrop.active .modal-box {
    transform: translateY(0);
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
}
.modal-header h3 {
    font-family: 'Cinzel', serif;
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.modal-header h3 i { color: var(--accent); }
.modal-close {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid var(--border);
    background: var(--bg-tertiary);
    color: var(--text-muted);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}
.modal-close:hover {
    border-color: var(--accent);
    color: var(--accent);
}
.modal-body {
    padding: 24px;
}
.modal-sub {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 20px;
    line-height: 1.5;
}
.modal-sub strong { color: var(--text-primary); }
.modal-phone {
    color: var(--accent);
    font-weight: 700;
    font-family: 'Cinzel', serif;
}
.form-group {
    margin-bottom: 16px;
}
.form-group label {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 8px;
}
.radio-group {
    display: flex;
    gap: 10px;
}
.radio-chip {
    cursor: pointer;
    flex: 1;
}
.radio-chip input { display: none; }
.radio-chip span {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px;
    border-radius: 10px;
    border: 1px solid var(--border);
    background: var(--bg-tertiary);
    color: var(--text-secondary);
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.3s;
}
.radio-chip input:checked + span {
    border-color: var(--accent);
    background: var(--accent-glow);
    color: var(--accent);
}
.modal-input {
    width: 100%;
    padding: 12px 14px;
    background: var(--bg-tertiary);
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--text-primary);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem;
    outline: none;
    transition: border-color 0.3s;
    resize: none;
}
.modal-input:focus {
    border-color: var(--accent);
}
.modal-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}
.btn-ghost-sm {
    flex: 1;
    padding: 12px;
    background: transparent;
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--text-secondary);
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.3s;
}
.btn-ghost-sm:hover {
    border-color: var(--border-hover);
    color: var(--text-primary);
}
.btn-confirm {
    flex: 2;
    padding: 12px;
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dim) 100%);
    color: var(--bg-primary);
    border: none;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.85rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s;
}
.btn-confirm:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px var(--accent-glow);
}
@media (max-width: 768px) { .detail-grid { grid-template-columns: 1fr; } .status-form .form-row { flex-direction: column; } .call-row { flex-direction: column; gap: 8px; align-items: flex-start; } }
</style>
<script>
function logCall(el) {
    const phoneUrl = el.href;

    fetch('{{ route("delivery.orders.call", $orderKey) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            call_type: 'outgoing',
            duration_seconds: 0,
            notes: null
        })
    })
    .then(async res => {
        if (!res.ok) {
            const text = await res.text();
            throw new Error(text);
        }
        return res.json();
    })
    .then(() => {
        // Open dialer ONLY after successful DB save
        window.location.href = phoneUrl;
    })
    .catch(err => {
        console.error(err);
        alert('Failed to log call: ' + err.message);
    });

    return false; // <-- stops the link from firing immediately
}
</script>
@endsection