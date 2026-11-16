@extends('layouts.app')

@section('title', 'Manage Categories — TheTrove')

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
            <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-item ">
                <img src="{{ asset('images/mainmini1.png') }}" alt="main">
                <span>Home</span>
            </a>
            <a href="{{ route('admin.categories') }}" class="sidebar-nav-item active">
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

    <!-- ADD MODAL OVERLAY -->
    <div class="modal-overlay" id="addModalOverlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <img src="{{ asset('images/add.png') }}" alt="add">
                    <h5>Add New Category</h5>
                </div>
                <button class="modal-close" onclick="hideAddModal()">
                    <img src="{{ asset('images/cancel.png') }}" alt="cancel">
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.addCategorie') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Name</label>
                        <input type="text" class="vault-input" name="nom" required placeholder="Enter category name">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea class="vault-input" name="description" rows="3" required placeholder="Enter description"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Image</label>
                        <input type="file" class="vault-input" name="image" accept="image/*" required>
                    </div>
                    <button type="submit" class="vault-submit-btn">
                        <img src="{{ asset('images/add.png') }}" alt="add"> Create Category
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- EDIT MODAL OVERLAY -->
    <div class="modal-overlay" id="editModalOverlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <img src="{{ asset('images/edit.png') }}" alt="edit">
                    <h5>Edit Category</h5>
                </div>
                <button class="modal-close" onclick="hideEditModal()">
                    <img src="{{ asset('images/cancel.png') }}" alt="cancel">
                </button>
            </div>
            <div class="modal-body">
                <form id="editCategoryForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Name</label>
                        <input type="text" class="vault-input" id="edit_nom" name="nom" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea class="vault-input" id="edit_description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Current Image</label>
                        <div id="currentImageBox" style="width: 100px; height: 100px; border-radius: 12px; overflow: hidden; background: var(--bg-tertiary); display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                            <img id="currentImage" src="" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                            <span id="noImageText" style="color: var(--text-muted); font-size: 0.8rem;">No image</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">New Image (optional)</label>
                        <input type="file" class="vault-input" name="image" accept="image/*">
                    </div>
                    <button type="submit" class="vault-submit-btn">
                        <img src="{{ asset('images/save.png') }}" alt="save"> Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 5%;">

        <!-- WELCOME HEADER -->
        <div class="vault-header reveal">
            <div class="vault-header-top">
                <span class="section-label">Management</span>
            </div>
            <h2 class="vault-title">
                <img src="{{ asset('images/categoriemini.png') }}" alt="categoriemini">
                Categories
            </h2>
            <p class="vault-subtitle">
                Manage product categories for your marketplace.
            </p>
        </div>

        <!-- ADD CATEGORY BUTTON -->
        <div class="add-category-section reveal">
            <button class="add-category-btn" onclick="showAddModal()">
                <img src="{{ asset('images/add.png') }}" alt="add">
                Add Category
            </button>
        </div>

        <!-- EXISTING CATEGORIES -->
        <div class="categories-section reveal">
            <div class="section-header">
                <h3 class="section-title">
                    My Categories
                </h3>
                <span class="category-count">{{ $categories->count() }} categories</span>
            </div>

            <div class="categories-grid">
                @forelse($categories as $cat)
                    <div class="category-card-admin">
                        <!-- CLICKABLE IMAGE -->
                        <a href="{{ route('admin.category.items', $cat->id_cat) }}" class="category-image-link">
                            <div class="category-image">
                                <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->nom }}">
                            </div>
                        </a>
                        <div class="category-content">
                            <h4 class="category-name">{{ $cat->nom }}</h4>
                            <p class="category-description">{{ $cat->description }}</p>
                        </div>
                        <div class="category-actions">
                            <button class="action-btn plain" onclick="showEditModal({{ $cat->id_cat }}, '{{ $cat->nom }}', '{{ $cat->description }}', '{{ asset('storage/' . $cat->image) }}')">
                                <img src="{{ asset('images/edit.png') }}" alt="edit"> Edit
                            </button>
                            <form action="{{ route('admin.deleteCategorie', $cat->id_cat) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                <button type="submit" class="action-btn plain">
                                    <img src="{{ asset('images/delete.png') }}" alt="delete"> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-state-categories">
                        <p>No categories found.</p>
                        <p class="empty-hint">Click "Add Category" to create your first category.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

<style>
/* MODAL OVERLAY */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(8px);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-content {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    width: 100%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    animation: slideUp 0.3s ease;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-hover) 100%);
    border-radius: 20px 20px 0 0;
}

.modal-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.modal-title h5 {
    font-family: 'Cinzel', serif;
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.modal-title img {
    width: 20px;
    height: 20px;
}

.modal-close {
    width: 36px;
    height: 36px;
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

.modal-close:hover {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.modal-close img {
    width: 16px;
    height: 16px;
}

.modal-body {
    padding: 24px;
}

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

.vault-title {
    font-family: 'Cinzel', serif;
    font-size: 2.4rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 16px;
}

.vault-subtitle {
    font-size: 1.1rem;
    color: var(--text-secondary);
    font-weight: 300;
    max-width: 520px;
    margin: 0 auto;
}

/* ADD CATEGORY BUTTON */
.add-category-section {
    display: flex;
    justify-content: center;
    margin-bottom: 40px;
}

.add-category-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 32px;
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dim) 100%);
    color: var(--bg-primary);
    border: none;
    border-radius: 12px;
    font-family: 'DM Sans', sans-serif;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.add-category-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 40px var(--accent-glow);
}

/* FORM STYLES */
.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 500;
    margin-bottom: 8px;
}

.vault-input {
    width: 100%;
    padding: 14px 16px;
    background: var(--bg-tertiary);
    border: 1px solid var(--border);
    border-radius: 12px;
    color: var(--text-primary);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    outline: none;
}

.vault-input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-glow);
}

.vault-submit-btn {
    width: 100%;
    padding: 14px 24px;
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dim) 100%);
    color: var(--bg-primary);
    border: none;
    border-radius: 12px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.vault-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px var(--accent-glow);
}

/* CATEGORIES SECTION */
.categories-section {
    margin-top: 60px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    max-width: 1000px;
    margin-left: auto;
    margin-right: auto;
}

.section-title {
    font-family: 'Cinzel', serif;
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.category-count {
    background: var(--accent-glow);
    color: var(--accent);
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    border: 1px solid var(--border-hover);
}

/* CATEGORIES GRID */
.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
    max-width: 1000px;
    margin: 0 auto;
}

.category-card-admin {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.4s ease;
}

.category-card-admin:hover {
    transform: translateY(-8px);
    border-color: var(--border-hover);
    box-shadow: 0 20px 50px var(--shadow);
}

/* CLICKABLE IMAGE LINK */
.category-image-link {
    display: block;
    text-decoration: none;
    cursor: pointer;
}

.category-image-link:hover .category-image {
    opacity: 0.85;
}

.category-image-link:hover .category-image img {
    transform: scale(1.05);
}

.category-image {
    width: 100%;
    height: 180px;
    background: var(--bg-tertiary);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.category-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.category-content {
    padding: 24px;
}

.category-name {
    font-family: 'Cinzel', serif;
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 8px;
}

.category-description {
    font-size: 0.9rem;
    color: var(--text-muted);
    line-height: 1.6;
    margin: 0;
}

.category-actions {
    display: flex;
    gap: 10px;
    padding: 0 24px 24px;
}

/* PLAIN ACTION BUTTONS - No colors */
.action-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    border-radius: 10px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 1px solid var(--border);
    text-decoration: none;
    flex: 1;
    justify-content: center;
    background: var(--bg-tertiary);
    color: var(--text-secondary);
}

.action-btn:hover {
    background: var(--bg-hover);
    color: var(--text-primary);
    border-color: var(--border-hover);
}

.action-btn img {
    width: 16px;
    height: 16px;
    opacity: 0.7;
}

/* EMPTY STATE */
.empty-state-categories {
    grid-column: 1 / -1;
    text-align: center;
    padding: 80px 40px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    color: var(--text-muted);
}

.empty-state-categories i {
    font-size: 3rem;
    color: var(--accent);
    margin-bottom: 16px;
}

.empty-state-categories p {
    margin: 0 0 8px;
    font-size: 1rem;
}

.empty-hint {
    font-size: 0.85rem;
    opacity: 0.7;
}

/* MOBILE */
@media (max-width: 768px) {
    .slide-sidebar {
        width: 280px;
    }
    .vault-title {
        font-size: 1.8rem;
    }
    .categories-grid {
        grid-template-columns: 1fr;
    }
    .section-header {
        flex-direction: column;
        gap: 12px;
        text-align: center;
    }
    .modal-content {
        margin: 20px;
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
    if (e.key === 'Escape') {
        hideAddModal();
        hideEditModal();
    }
});

// ADD MODAL
const addModalOverlay = document.getElementById('addModalOverlay');

function showAddModal() {
    hideEditModal();
    addModalOverlay.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function hideAddModal() {
    addModalOverlay.style.display = 'none';
    document.body.style.overflow = '';
}

// EDIT MODAL
const editModalOverlay = document.getElementById('editModalOverlay');

function showEditModal(id, name, description, imageUrl) {
    hideAddModal();
    
    document.getElementById('editCategoryForm').action = '/admin/update-categorie/' + id;
    document.getElementById('edit_nom').value = name;
    document.getElementById('edit_description').value = description;
    
    const currentImage = document.getElementById('currentImage');
    const noImageText = document.getElementById('noImageText');
    
    if (imageUrl) {
        currentImage.src = imageUrl;
        currentImage.style.display = 'block';
        noImageText.style.display = 'none';
    } else {
        currentImage.style.display = 'none';
        noImageText.style.display = 'block';
    }
    
    editModalOverlay.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function hideEditModal() {
    editModalOverlay.style.display = 'none';
    document.body.style.overflow = '';
}

// Close modals when clicking outside
addModalOverlay.addEventListener('click', function(e) {
    if (e.target === addModalOverlay) {
        hideAddModal();
    }
});

editModalOverlay.addEventListener('click', function(e) {
    if (e.target === editModalOverlay) {
        hideEditModal();
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