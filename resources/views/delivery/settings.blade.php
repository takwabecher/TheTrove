@extends('delivery.layout')

@section('title', 'Company Settings')

@section('content')
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
    .settings-container {
        max-width: 880px;
        margin: 20px auto 40px;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 16px rgba(0,0,0,0.06);
        padding: 120px 34px;
    }
    .settings-header {
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 14px;
        margin-bottom: 24px;
    }
    .settings-header h2 {
        margin: 0;
        font-size: 22px;
        color: #1a1a1a;
        font-weight: 700;
    }
    .form-section {
        margin-bottom: 26px;
    }
    .section-title {
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #888;
        font-weight: 700;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 1px solid #eee;
    }
    .form-row {
        display: flex;
        gap: 18px;
        margin-bottom: 14px;
        flex-wrap: wrap;
    }
    .form-group {
        flex: 1;
        min-width: 200px;
    }
    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-size: 13px;
        font-weight: 600;
        color: #444;
    }
    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        font-size: 14px;
        background: #fff;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-sizing: border-box;
    }
    .form-control:focus {
        outline: none;
        border-color: #c9a227;
        box-shadow: 0 0 0 3px rgba(201, 162, 39, 0.12);
    }
    .form-control:disabled {
        background: #f3f4f6;
        color: #6b7280;
        cursor: not-allowed;
    }
    input[type="number"] {
        appearance: textfield;
    }
    .logo-section {
        display: flex;
        align-items: flex-start;
        gap: 22px;
        flex-wrap: wrap;
    }
    .logo-preview {
        width: 110px;
        height: 110px;
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #f9fafb;
        flex-shrink: 0;
    }
    .logo-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        padding: 8px;
    }
    .logo-preview .no-logo {
        color: #9ca3af;
        font-size: 12px;
        text-align: center;
        padding: 10px;
    }
    .logo-upload {
        flex: 1;
        min-width: 200px;
    }
    .file-input-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
        width: 100%;
    }
    .file-input-wrapper input[type=file] {
        position: absolute;
        left: -9999px;
    }
    .file-input-label {
        display: block;
        padding: 10px 14px;
        border: 1px dashed #9ca3af;
        border-radius: 10px;
        text-align: center;
        cursor: pointer;
        color: #4b5563;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s;
        background: #f9fafb;
    }
    .file-input-label:hover {
        border-color: #c9a227;
        background: #fffbeb;
        color: #c9a227;
    }
    .file-name {
        margin-top: 6px;
        font-size: 12px;
        color: #6b7280;
        min-height: 18px;
    }
    .btn-save {
        background: #c9a227; /* matches your "Get Started" gold */
        color: #fff;
        border: none;
        padding: 12px 32px;
        font-size: 14px;
        font-weight: 600;
        border-radius: 10px;
        cursor: pointer;
        transition: background 0.2s, transform 0.1s;
    }
    .btn-save:hover {
        background: #b08d1f;
    }
    .btn-save:active {
        transform: scale(0.98);
    }
    .alert-success {
        background: #d1fae5;
        color: #065f46;
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 22px;
        font-size: 14px;
        border: 1px solid #a7f3d0;
    }
    .text-danger {
        color: #dc2626;
        font-size: 12px;
        margin-top: 4px;
        display: block;
    }
    @media (max-width: 640px) {
        .settings-container {
            margin: 12px;
            padding: 20px;
        }
        .form-row {
            flex-direction: column;
            gap: 0;
        }
    }
</style>

<div class="settings-container">
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
            <a href="{{ route('delivery.dashboard') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/mainmini1.png') }}" alt="main">
                <span>Dashboard</span>
            </a>
            <a href="{{ route('delivery.orders') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/boxmini1.png') }}" alt="orders">
                <span>Orders</span>
            </a>
            <a href="{{ route('delivery.order_history') }}" class="sidebar-nav-item">
                <img src="{{ asset('images/historymini1.png') }}" alt="history">
                <span>History</span>
            </a>
            <a href="{{ route('delivery.call_history') }}" class="sidebar-nav-item active">
                <img src="{{ asset('images/phonemini1.png') }}" alt="calls">
                <span>Calls</span>
            </a>
        </div>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <img src="{{ asset('images/deliverymini.png') }}" alt="delivery">
                <div>
                    <div style="font-weight: 600; color: var(--text-primary);">{{ auth('delivery')->user()->name }}</div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">Delivery Partner</div>
                </div>
            </div>
            <form action="{{ route('delivery.logout') }}" method="POST" style="margin-top: 12px;">
                @csrf
                <button type="submit" class="sidebar-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </aside>
    <div class="settings-header">
        <h2>Company Settings</h2>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('delivery.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- COMPANY INFO --}}
        <div class="form-section">
            <div class="section-title">Company Information</div>

            <div class="form-row">
                <div class="form-group">
                    <label>Company Name</label>
                    <input type="text" value="{{ $company->name }}" disabled class="form-control">
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control"
                           value="{{ old('email', $company->email) }}">
                    @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" name="phone" id="phone" class="form-control"
                           value="{{ old('phone', $company->phone) }}">
                    @error('phone')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" class="form-control"
                           value="{{ old('address', $company->address) }}">
                    @error('address')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group" style="max-width: 180px;">
                    <label for="shipping_rate">Shipping Rate (TND)</label>
                    <input type="number" step="0.01" min="0" name="shipping_rate" id="shipping_rate"
                           class="form-control" value="{{ old('shipping_rate', $company->shipping_rate) }}">
                    @error('shipping_rate')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        {{-- LOGO --}}
        <div class="form-section">
            <div class="section-title">Company Logo</div>
            <div class="logo-section">
                <div class="logo-preview">
                    @if($company->logo)
                        <img src="{{ asset('storage/' . $company->logo) }}?v={{ time() }}" alt="Current Logo">
                    @else
                        <span class="no-logo">No Logo</span>
                    @endif
                </div>
                <div class="logo-upload">
                    <label style="font-size: 13px; font-weight: 600; color: #444; margin-bottom: 8px; display: block;">
                        Upload New Logo
                    </label>
                    <div class="file-input-wrapper">
                        <input type="file" name="logo" id="logo" accept="image/*"
                               onchange="document.getElementById('file-name').textContent = this.files[0] ? this.files[0].name : 'No file selected'">
                        <label for="logo" class="file-input-label">Choose File</label>
                    </div>
                    <div class="file-name" id="file-name">No file selected</div>
                    @error('logo')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        {{-- PASSWORD --}}
        <div class="form-section">
            <div class="section-title">Change Password</div>
            <div class="form-row">
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" name="password" id="password" class="form-control"
                           placeholder="Leave blank to keep current password">
                    @error('password')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="form-control" placeholder="Re-type new password">
                </div>
            </div>
        </div>

        {{-- SAVE --}}
        <div style="text-align: right; padding-top: 6px;">
            <button type="submit" class="btn-save">Save Changes</button>
        </div>
    </form>
</div>
@endsection