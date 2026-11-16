@extends('layouts.app')

@section('title', 'Add Delivery Company')

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
            <a href="{{ route('admin.produits') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/produitmini1.png') }}" alt="item">
                <span>Items</span>
            </a>
            <a href="{{ route('admin.delivery_companies') }}" class="sidebar-nav-item active">
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
    <div class="container" style="max-width: 800px; margin: 0 auto; padding: 0 5%;">

        <!-- WELCOME HEADER -->
        <div class="vault-header reveal">
            <div class="vault-header-top">
                <span class="section-label">Management</span>
            </div>
            <h2 class="vault-title">
                <img src="{{ asset('images/delivery.png') }}" alt="deliverymini">
                Add Delivery Company
            </h2>
            <p class="vault-subtitle">
                Create a new delivery company account.
            </p>
        </div>

        <!-- FORM CARD -->
        <div class="form-card reveal">
            <form action="{{ route('admin.delivery_companies.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label>Company Name *</label>
                        <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                        @error('name')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Email Address *</label>
                        <input type="email" name="email" class="form-input" value="{{ old('email') }}" required>
                        @error('email')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Password *</label>
                        <input type="password" name="password" class="form-input" required>
                        @error('password')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Confirm Password *</label>
                        <input type="password" name="password_confirmation" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" class="form-input" value="{{ old('phone') }}">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" class="form-input" value="{{ old('address') }}">
                    </div>
                    <div class="form-group full-width">
                        <label>Logo</label>
                        <input type="file" name="logo" class="form-input" accept="image/*">
                    </div>
                    <div class="form-group full-width">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_active" value="1" checked>
                            <span>Active Account</span>
                        </label>
                    </div>
                </div>
                <div class="form-actions">
                    <a href="{{ route('admin.delivery_companies') }}" class="btn-cancel">
                        <img src="{{ asset('images/cancel.png') }}" alt="cancel"> Cancel
                    </a>
                    <button type="submit" class="btn-save">
                        <img src="{{ asset('images/add.png') }}" alt="add"> Create Company
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<style>
/* MENU TOGGLE */
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

.navbar, #navbar, nav.navbar {
    z-index: 90 !important;
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


/* SIDEBAR FOOTER */
.sidebar-footer {
    padding: 20px 24px;
    border-top: 1px solid var(--border);
}

.sidebar-user {
    display: flex;
    align-items: center;
    gap: 12px;
}

.sidebar-user img {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
}

/* VAULT DASHBOARD */
.vault-dashboard {
    padding: 140px 0 80px;
    background: var(--bg-primary);
    min-height: 100vh;
}

/* WELCOME HEADER */
.vault-header {
    text-align: center;
    margin-bottom: 40px;
}

.vault-header-top {
    margin-bottom: 16px;
}

.section-label {
    color: var(--accent);
    font-size: 0.75rem;
    font-weight: 500;
    letter-spacing: 4px;
    text-transform: uppercase;
}

.vault-title {
    font-family: 'Cinzel', serif;
    font-size: 2.4rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 16px;
}

.vault-title img {
    width: 40px;
    height: 40px;
    vertical-align: middle;
    margin-right: 12px;
}

.vault-subtitle {
    font-size: 1.1rem;
    color: var(--text-secondary);
    font-weight: 300;
    max-width: 520px;
    margin: 0 auto;
}

/* FORM CARD */
.form-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 40px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    color: var(--text-secondary);
    font-size: 0.85rem;
    font-weight: 600;
}

.form-input {
    padding: 14px 16px;
    background: var(--bg-tertiary);
    border: 1px solid var(--border);
    border-radius: 12px;
    color: var(--text-primary);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.form-input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-glow);
}

.error-text {
    color: #ef4444;
    font-size: 0.8rem;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.checkbox-label input {
    width: 18px;
    height: 18px;
    accent-color: var(--accent);
}

/* FORM ACTIONS */
.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid var(--border);
}

.btn-cancel, .btn-save {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 28px;
    border-radius: 12px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    flex: 1;
    justify-content: center;
}

.btn-cancel {
    background: var(--bg-tertiary);
    color: var(--text-secondary);
    border: 1px solid var(--border);
}

.btn-cancel:hover {
    border-color: var(--text-secondary);
    color: var(--text-primary);
    background: var(--bg-hover);
}

.btn-save {
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dim) 100%);
    color: var(--bg-primary);
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px var(--accent-glow);
}

.btn-cancel img, .btn-save img {
    width: 18px;
    height: 18px;
}

/* MOBILE */
@media (max-width: 768px) {
    .slide-sidebar {
        width: 280px;
    }
    .vault-title {
        font-size: 1.8rem;
    }
    .form-card {
        padding: 24px;
    }
    .form-grid {
        grid-template-columns: 1fr;
    }
    .form-actions {
        flex-direction: column;
    }
}
</style>

<script>
// SIDEBAR TOGGLE
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
});

// Scroll reveal
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