@extends('layouts.app')

@section('title', 'Settings — TheTrove')

@section('content')
<div class="vault-dashboard">

    <!-- MOBILE MENU BUTTON -->
    <button class="menu-toggle" id="menuToggle" aria-label="Open menu">
        <i class="fas fa-bars"></i>
    </button>

    <!-- SIDEBAR OVERLAY -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- MAIN SLIDING SIDEBAR -->
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
            <a href="{{ route('client.browse') }}" class="sidebar-nav-item ">
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
            <a href="{{ route('client.settings') }}" class="sidebar-nav-item active">
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

    <!-- SETTINGS PAGE -->
    <div class="settings-container">
        <div class="settings-layout">

            <!-- LEFT PROFILE MENU -->
            <aside class="settings-sidebar">
                <div class="settings-user-card">
                    <div class="settings-avatar-wrap">
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/profilmini1.png') }}" alt="avatar">
                        <label for="avatarInput" class="avatar-camera">
                            <i class="fas fa-camera"></i>
                        </label>
                    </div>
                    <h3 class="settings-user-name">{{ $user->name }}</h3>
                    <p class="settings-user-email">{{ $user->email }}</p>
                    <span class="settings-role-badge">CLIENT</span>
                </div>

                <nav class="settings-menu">
                    <button class="settings-menu-item active" data-tab="profile">
                        <span>Profile Info</span>
                    </button>
                    <button class="settings-menu-item" data-tab="orders">
                        <span>My Orders</span>
                        @if($commandes_count > 0)
                            <span class="menu-badge">{{ $commandes_count }}</span>
                        @endif
                    </button>
                    <button class="settings-menu-item" data-tab="history">
                        <span>Login History</span>
                    </button>
                    <button class="settings-menu-item" data-tab="security">
                        <span>Security</span>
                    </button>
                </nav>
            </aside>

            <!-- RIGHT CONTENT -->
            <div class="settings-content">


                <!-- PROFILE TAB -->
                <div class="settings-panel active" id="tab-profile">
                    <div class="panel-header">
                        <h2> Personal Information</h2>
                    </div>
                    <form action="{{ route('client.settings.profile') }}" method="POST" enctype="multipart/form-data" class="settings-form">
                        @csrf
                        <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display: none;" onchange="this.form.submit()">

                        <div class="form-row">
                            <div class="form-group">
                                <label>Full Name</label>
                                <div class="input-icon">
                                    <i class="fas fa-user"></i>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                                </div>
                                @error('name')<span class="form-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <div class="input-icon">
                                    <i class="fas fa-envelope"></i>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                                </div>
                                @error('email')<span class="form-error">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="form-group">
    <label>Phone Number</label>
    <div class="input-icon">
        <i class="fas fa-phone"></i>
        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="e.g. +216 12 345 678">
    </div>
    @error('phone')<span class="form-error">{{ $message }}</span>@enderror
</div>
<div class="form-group">
    <label>Address</label>
    <div class="input-icon">
        <i class="fas fa-map-marker-alt"></i>
        <input type="text" name="address" value="{{ old('address', $user->address) }}" placeholder="Your delivery address">
    </div>
    @error('address')<span class="form-error">{{ $message }}</span>@enderror
</div>

                        <div class="form-actions">
                            <button type="submit" class="btn-save">
                                <span>Save Changes</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ORDERS TAB -->
                <div class="settings-panel" id="tab-orders">
                    <div class="panel-header">
                        <h2> My Orders</h2>
                    </div>
                    @if($commandes->count() > 0)
                        <div class="orders-list-compact">
                            @foreach($commandes as $cmd)
                            <div class="order-row">
                                <div class="order-row-info">
                                    <span class="order-row-id">{{ $cmd->getFormattedIdAttribute() }}</span>
                                    <span class="order-row-vault"><i class="fas fa-eye"></i></span>
                                    <span class="order-row-date">{{ \Carbon\Carbon::parse($cmd->date_cmd)->format('M d, Y') }}</span>
                                </div>
                                <div class="order-row-meta">
                                    <span class="order-row-items">{{ $cmd->items->sum('quantity') }} item(s)</span>
                                    <span class="order-row-total">DT{{ number_format($cmd->total_price, 2) }}</span>
                                    <span class="order-row-status {{ $cmd->status }}">{{ ucfirst($cmd->status) }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-tab">
                            <i class="fas fa-shopping-bag"></i>
                            <p>No orders yet.</p>
                        </div>
                    @endif
                </div>

                <!-- LOGIN HISTORY TAB -->
                <div class="settings-panel" id="tab-history">
                    <div class="panel-header">
                        <h2>Login History</h2>
                    </div>
                    @if($loginHistory->count() > 0)
                        <div class="history-list">
                            @foreach($loginHistory as $log)
                            <div class="history-row">
                                <div class="history-icon">
                                    <i class="fas fa-desktop"></i>
                                </div>
                                <div class="history-details">
                                    <span class="history-device">{{ $log->user_agent ? \Illuminate\Support\Str::limit($log->user_agent, 60) : 'Unknown device' }}</span>
                                    <span class="history-ip">IP: {{ $log->ip_address ?? '—' }}</span>
                                </div>
                                <span class="history-time">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-tab">
                            <i class="fas fa-clock"></i>
                            <p>No login history recorded yet.</p>
                        </div>
                    @endif
                </div>

                <!-- SECURITY TAB -->
                <div class="settings-panel" id="tab-security">
                    <div class="panel-header">
                        <h2>Change Password</h2>
                    </div>
                    <form action="{{ route('client.settings.password') }}" method="POST" class="settings-form">
                        @csrf
                        <div class="form-row">
                            <div class="form-group">
                                <label>Current Password</label>
                                <div class="input-icon">
                                    <i class="fas fa-lock"></i>
                                    <input type="password" name="current_password" required>
                                </div>
                                @error('current_password')<span class="form-error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>New Password</label>
                                <div class="input-icon">
                                    <i class="fas fa-key"></i>
                                    <input type="password" name="password" required>
                                </div>
                                @error('password')<span class="form-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label>Confirm New Password</label>
                                <div class="input-icon">
                                    <i class="fas fa-key"></i>
                                    <input type="password" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn-save">
                                <i class="fas fa-lock"></i>
                                <span>Update Password</span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
/* ===== MAIN SIDEBAR (same z-index fix) ===== */
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
    z-index: 1102;
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
    z-index: 1100;
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
    z-index: 1101;
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

/* ===== SETTINGS LAYOUT ===== */
.vault-dashboard { background: var(--bg-primary); min-height: 100vh; padding: 140px 0 80px; }

.settings-container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
.settings-layout { display: grid; grid-template-columns: 320px 1fr; gap: 28px; }

/* ===== LEFT SIDEBAR ===== */
.settings-sidebar { display: flex; flex-direction: column; gap: 20px; }

.settings-user-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 32px 24px;
    text-align: center;
}

.settings-avatar-wrap {
    position: relative;
    width: 100px; height: 100px;
    margin: 0 auto 16px;
}
.settings-avatar-wrap img {
    width: 100%; height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--border);
}
.avatar-camera {
    position: absolute;
    bottom: 0; right: 0;
    width: 32px; height: 32px;
    border-radius: 50%;
    background: var(--accent);
    color: #000;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    border: 3px solid var(--bg-card);
    font-size: 0.8rem;
    transition: transform 0.2s;
}
.avatar-camera:hover { transform: scale(1.1); }

.settings-user-name { font-family: 'Cinzel', serif; font-size: 1.2rem; color: var(--text-primary); margin-bottom: 4px; }
.settings-user-email { font-size: 0.85rem; color: var(--text-muted); margin-bottom: 12px; }
.settings-role-badge {
    display: inline-block;
    padding: 6px 18px;
    border-radius: 20px;
    background: rgba(245,158,11,0.12);
    color: #f59e0b;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 2px;
    border: 1px solid rgba(245,158,11,0.25);
}

.settings-menu {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}
.settings-menu-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px 24px;
    background: none;
    border: none;
    border-left: 3px solid transparent;
    color: var(--text-secondary);
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: left;
    width: 100%;
    font-family: inherit;
}
.settings-menu-item:hover {
    background: var(--accent-glow);
    color: var(--accent);
}
.settings-menu-item.active {
    border-left-color: var(--accent);
    background: var(--accent-glow);
    color: var(--accent);
}
.settings-menu-item i { width: 20px; text-align: center; }
.menu-badge {
    margin-left: auto;
    background: var(--accent);
    color: #000;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 10px;
}

/* ===== RIGHT CONTENT ===== */
.settings-content { display: flex; flex-direction: column; gap: 24px; }

.settings-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}
.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
}
.stat-icon {
    width: 48px; height: 48px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
}
.stat-number { display: block; font-size: 1.4rem; font-weight: 700; color: var(--text-primary); }
.stat-label { display: block; font-size: 0.8rem; color: var(--text-muted); margin-top: 2px; }

/* ===== PANELS ===== */
.settings-panel { display: none; }
.settings-panel.active { display: block; }

.panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
}
.panel-header h2 {
    font-family: 'Cinzel', serif;
    font-size: 1.3rem;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 10px;
}
.panel-header h2 i { color: var(--accent); }

.settings-form {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 32px;
}

.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
.form-group { display: flex; flex-direction: column; gap: 8px; }
.form-group label { font-size: 0.8rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }

.input-icon {
    position: relative;
    display: flex;
    align-items: center;
}
.input-icon i {
    position: absolute;
    left: 14px;
    color: var(--text-muted);
    font-size: 0.9rem;
}
.input-icon input {
    width: 100%;
    padding: 12px 14px 12px 42px;
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 12px;
    color: var(--text-primary);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.95rem;
    outline: none;
    transition: all 0.3s ease;
}
.input-icon input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-glow);
}

.form-error { font-size: 0.8rem; color: #ef4444; margin-top: 4px; }

.form-actions { margin-top: 8px; }
.btn-save {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 28px;
    border-radius: 12px;
    border: none;
    background: var(--accent);
    color: #000;
    font-family: 'Cinzel', serif;
    font-size: 0.9rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
}
.btn-save:hover { transform: translateY(-2px); box-shadow: 0 8px 25px var(--accent-glow); }

/* ===== ORDERS COMPACT ===== */
.orders-list-compact { display: flex; flex-direction: column; gap: 12px; }
.order-row {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 20px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
}
.order-row:hover { border-color: var(--border-hover); }
.order-row-info { display: flex; align-items: center; gap: 20px; flex: 1; }
.order-row-id { font-family: 'Cinzel', serif; font-weight: 700; color: var(--text-primary); min-width: 60px; }
.order-row-vault { color: var(--text-secondary); font-size: 0.9rem; }
.order-row-date { color: var(--text-muted); font-size: 0.85rem; margin-left: auto; padding-right: 20px; }
.order-row-meta { display: flex; align-items: center; gap: 20px; }
.order-row-items { color: var(--text-muted); font-size: 0.85rem; }
.order-row-total { font-family: 'Cinzel', serif; font-weight: 700; color: var(--accent); }
.order-row-status {
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}
.order-row-status.pending { background: rgba(245,158,11,0.1); color: #f59e0b; border: 1px solid rgba(245,158,11,0.2); }
.order-row-status.confirmed { background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2); }
.order-row-status.cancelled { background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); }

/* ===== LOGIN HISTORY ===== */
.history-list { display: flex; flex-direction: column; gap: 10px; }
.history-row {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 18px 24px;
    display: flex;
    align-items: center;
    gap: 16px;
}
.history-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
    background: var(--bg-secondary);
    color: var(--accent);
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
}
.history-details { flex: 1; display: flex; flex-direction: column; gap: 4px; }
.history-device { color: var(--text-primary); font-size: 0.9rem; font-weight: 500; }
.history-ip { color: var(--text-muted); font-size: 0.8rem; }
.history-time { color: var(--text-muted); font-size: 0.85rem; white-space: nowrap; }

.empty-tab {
    text-align: center;
    padding: 60px 20px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    color: var(--text-muted);
}
.empty-tab i { font-size: 3rem; margin-bottom: 16px; display: block; opacity: 0.3; }

/* ===== ALERTS ===== */
.alert {
    padding: 14px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-size: 0.9rem;
    font-weight: 500;
}
.alert-success { background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2); }
.alert-error { background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); }

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .settings-layout { grid-template-columns: 1fr; }
    .settings-sidebar { max-width: 400px; margin: 0 auto; width: 100%; }
    .settings-stats { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 768px) {
    .form-row { grid-template-columns: 1fr; }
    .settings-stats { grid-template-columns: 1fr; }
    .order-row { flex-direction: column; gap: 12px; align-items: flex-start; }
    .order-row-info { flex-direction: column; gap: 6px; align-items: flex-start; }
    .order-row-date { margin-left: 0; padding-right: 0; }
    .vault-dashboard { padding-top: 100px; }
}
</style>

<script>
// ===== MAIN SIDEBAR TOGGLE =====
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

// ===== SETTINGS TABS =====
const tabButtons = document.querySelectorAll('.settings-menu-item');
const tabPanels = document.querySelectorAll('.settings-panel');

tabButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        const target = btn.dataset.tab;

        tabButtons.forEach(b => b.classList.remove('active'));
        tabPanels.forEach(p => p.classList.remove('active'));

        btn.classList.add('active');
        document.getElementById('tab-' + target).classList.add('active');
    });
});

// Auto-open tab from URL hash
if (window.location.hash) {
    const hash = window.location.hash.replace('#', '');
    const btn = document.querySelector(`[data-tab="${hash}"]`);
    if (btn) btn.click();
}
</script>
@endsection