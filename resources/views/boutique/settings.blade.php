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
            <a href="{{ route('boutique.categories') }}" class="sidebar-nav-item ">
                <img src="{{ asset('images/categoriemini1.png') }}" alt="categoriemini">
                <span>Categories</span>
            </a>
            <a href="{{ route('boutique.produits') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/produitmini1.png') }}" alt="produitmini">
                <span>Items</span>
            </a>
            <a href="{{ route('boutique.commandes') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/ordermini1.png') }}" alt="ordermini">
                <span>Orders</span>
            </a>
            <a href="{{ route('boutique.settings') }}" class="sidebar-nav-item active">
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
    <div class="container" style="max-width: 800px; margin: 0 auto; padding: 0 5%;">

        <!-- HEADER -->
        <div class="vault-header reveal">
            <h2 class="vault-title">
                <img src="{{ asset('images/boutiquemini.png') }}" alt="boutiquemini">Vault <span style="color: var(--accent); font-weight: 600;">Settings</span>
            </h2>
            <p class="vault-subtitle">
                Manage your vault profile and contact information.
            </p>
        </div>

        <!-- SUCCESS MESSAGE -->
        @if(session('success'))
        <div class="alert-success reveal">
            <img src="{{ asset('images/accept.png') }}" alt="accept">
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div class="alert-error reveal">
            <img src="{{ asset('images/cancel.png') }}" alt="cancel">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- SETTINGS FORM -->
        <form action="{{ route('boutique.settings.update') }}" method="POST" enctype="multipart/form-data" class="settings-form reveal">
            @csrf
            @method('PUT')

            <!-- PROFILE IMAGE -->
            <div class="settings-section">
                <h3 class="settings-section-title">
                    Vault Image
                </h3>
                <div class="image-upload">
                    <div class="image-preview">
                        <img src="{{ auth()->user()->image ? asset('storage/' . auth()->user()->image) : asset('images/placeholder-boutique.png') }}" 
                             alt="Profile" 
                             id="imagePreview">
                    </div>
                    <div class="image-upload-controls">
                        <label for="image" class="upload-btn">
                            <img src="{{ asset('images/upload.png') }}" alt="upload">
                            <span>Choose Image</span>
                        </label>
                        <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(this)">
                        <p class="upload-hint">Recommended: 400x400px, JPG or PNG</p>
                    </div>
                </div>
            </div>

            <!-- BASIC INFO -->
            <div class="settings-section">
                <h3 class="settings-section-title">
                    Vault Information
                </h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Vault Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" name="address" id="address" value="{{ old('address', auth()->user()->address ?? '') }}">
                    </div>
                </div>
            </div>

            <!-- DESCRIPTION -->
            <div class="settings-section">
                <h3 class="settings-section-title">
                    About Your Vault
                </h3>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="4">{{ old('description', auth()->user()->description ?? '') }}</textarea>
                </div>
            </div>

            <!-- PASSWORD -->
            <div class="settings-section">
                <h3 class="settings-section-title">
                    Security
                </h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" name="current_password" id="current_password">
                    </div>
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" name="password" id="password">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation">
                    </div>
                </div>
            </div>

            <!-- ACTIONS -->
            <div class="settings-actions">
                <button type="submit" class="btn-save">
                    <img src="{{ asset('images/save.png') }}" alt="save">
                    <span>Save Changes</span>
                </button>
            </div>
        </form>
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

/* ===== ALERTS ===== */
.alert-success, .alert-error {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 24px;
    font-size: 0.95rem;
}
.alert-success {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: #10b981;
}
.alert-error {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
}
.alert-error ul { margin: 0; padding-left: 16px; }

/* ===== SETTINGS FORM ===== */
.settings-form {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.settings-section {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 28px;
    transition: all 0.4s ease;
}
.settings-section:hover {
    border-color: var(--border-hover);
}

.settings-section-title {
    font-family: 'Cinzel', serif;
    font-size: 1.1rem;
    color: var(--text-primary);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.settings-section-title i {
    color: var(--accent);
    font-size: 1rem;
}

/* ===== IMAGE UPLOAD ===== */
.image-upload {
    display: flex;
    align-items: center;
    gap: 24px;
}
.image-preview {
    width: 120px;
    height: 120px;
    border-radius: 20px;
    overflow: hidden;
    background: var(--bg-tertiary);
    border: 2px solid var(--border);
}
.image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.image-upload-controls {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.upload-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 10px;
    background: var(--accent-glow);
    border: 1px solid var(--border-hover);
    color: var(--accent);
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}
.upload-btn:hover {
    background: var(--accent);
    color: #000;
}
.image-upload input[type="file"] {
    display: none;
}
.upload-hint {
    font-size: 0.8rem;
    color: var(--text-muted);
    margin: 0;
}

/* ===== FORM GRID ===== */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
@media (max-width: 640px) {
    .form-grid { grid-template-columns: 1fr; }
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.form-group label {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 1px;
}
.form-group input,
.form-group textarea {
    padding: 14px 16px;
    border-radius: 12px;
    border: 1px solid var(--border);
    background: var(--bg-secondary);
    color: var(--text-primary);
    font-size: 0.95rem;
    transition: all 0.3s ease;
}
.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-glow);
}
.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

/* ===== ACTIONS ===== */
.settings-actions {
    display: flex;
    justify-content: flex-end;
    padding-top: 20px;
}
.btn-save {
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
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}
.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px var(--accent-glow);
}

/* ===== ANIMATIONS ===== */
.reveal { opacity: 0; transform: translateY(30px); transition: all 0.6s ease; }
.reveal.active { opacity: 1; transform: translateY(0); }

@media (max-width: 768px) {
    .vault-title { font-size: 1.8rem; }
    .slide-sidebar { width: 280px; }
    .settings-section { padding: 20px; }
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
});

// ===== IMAGE PREVIEW =====
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
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