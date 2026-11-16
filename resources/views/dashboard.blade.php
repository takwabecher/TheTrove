@extends('layouts.app')

@section('title', 'Dashboard — TheTrove')

@section('content')

<!-- DASHBOARD HERO -->
<section class="hero" style="min-height: 50vh; padding-top: 140px;">
<div class="hero-mesh"></div>
<div class="hero-content" style="max-width: 800px;">
  <div class="hero-top" style="margin-bottom: 0;">
    <div class="hero-tag">Welcome Back, {{ Auth::user()->name ?? 'Treasure Hunter' }}</div>
    <h1 style="font-size: 3.5rem;">Your <em>Dashboard.</em></h1>
    <p class="hero-desc">Manage your treasures, track orders, and explore new opportunities.</p>
    <div class="hero-actions">
      @if(Auth::user()->role === 'client')
        <a href="{{ route('client.panier') }}" class="btn-main"><i class="fas fa-shopping-bag"></i> My Cart</a>
        <a href="{{ route('client.favoris') }}" class="btn-ghost"><i class="fas fa-heart"></i> Favorites</a>
      @elseif(Auth::user()->role === 'boutique')
        <a href="{{ route('boutique.produits') }}" class="btn-main"><i class="fas fa-box"></i> My Products</a>
        <a href="{{ route('boutique.commandes') }}" class="btn-ghost"><i class="fas fa-clipboard-list"></i> Orders</a>
      @elseif(Auth::user()->role === 'admin')
        <a href="{{ route('admin.dashboard') }}" class="btn-main"><i class="fas fa-shield-alt"></i> Admin Panel</a>
        <a href="{{ route('admin.statistics') }}" class="btn-ghost"><i class="fas fa-chart-line"></i> Statistics</a>
      @endif
    </div>
  </div>
</div>
</section>

<!-- QUICK STATS -->
<section class="stats" style="padding: 60px 5%;">
<div class="stats-grid" style="max-width: 900px;">
  @if(Auth::user()->role === 'client')
    <div class="stat-box reveal">
      <div class="stat-icon">🛒</div>
      <div class="stat-number">{{ Auth::user()->paniers->count() ?? 0 }}</div>
      <div class="stat-label">Items in Cart</div>
    </div>
    <div class="stat-box reveal">
      <div class="stat-icon">❤️</div>
      <div class="stat-number">{{ Auth::user()->favoris->count() ?? 0 }}</div>
      <div class="stat-label">Favorites</div>
    </div>
    <div class="stat-box reveal">
      <div class="stat-icon">📦</div>
      <div class="stat-number">{{ Auth::user()->commandes->count() ?? 0 }}</div>
      <div class="stat-label">Orders</div>
    </div>
    <div class="stat-box reveal">
      <div class="stat-icon">💰</div>
      <div class="stat-number">$0</div>
      <div class="stat-label">Total Spent</div>
    </div>
  @elseif(Auth::user()->role === 'boutique')
    <div class="stat-box reveal">
      <div class="stat-icon">📦</div>
      <div class="stat-number">{{ Auth::user()->boutique->produits->count() ?? 0 }}</div>
      <div class="stat-label">Products</div>
    </div>
    <div class="stat-box reveal">
      <div class="stat-icon">📋</div>
      <div class="stat-number">{{ Auth::user()->boutique->commandes->count() ?? 0 }}</div>
      <div class="stat-label">Orders</div>
    </div>
    <div class="stat-box reveal">
      <div class="stat-icon">💵</div>
      <div class="stat-number">$0</div>
      <div class="stat-label">Revenue</div>
    </div>
    <div class="stat-box reveal">
      <div class="stat-icon">⭐</div>
      <div class="stat-number">{{ Auth::user()->boutique->rating ?? '0.0' }}</div>
      <div class="stat-label">Rating</div>
    </div>
  @else
    <div class="stat-box reveal">
      <div class="stat-icon">🏪</div>
      <div class="stat-number">0</div>
      <div class="stat-label">Boutiques</div>
    </div>
    <div class="stat-box reveal">
      <div class="stat-icon">📦</div>
      <div class="stat-number">0</div>
      <div class="stat-label">Products</div>
    </div>
    <div class="stat-box reveal">
      <div class="stat-icon">👥</div>
      <div class="stat-number">0</div>
      <div class="stat-label">Users</div>
    </div>
    <div class="stat-box reveal">
      <div class="stat-icon">💰</div>
      <div class="stat-number">$0</div>
      <div class="stat-label">Revenue</div>
    </div>
  @endif
</div>
</section>

<!-- QUICK ACTIONS -->
<section class="categories" style="padding: 80px 5%;">
<div class="section-label reveal">Quick Actions</div>
<h2 class="section-title reveal">What Would You Like To Do?</h2>
<div class="hex-grid" style="max-width: 700px;">
  @if(Auth::user()->role === 'client')
    <a href="{{ route('client.browse') }}" class="hex-item reveal" style="text-decoration: none;">
      <span class="hex-icon">🔍</span>
      <span class="hex-name">Browse</span>
    </a>
    <a href="{{ route('client.panier') }}" class="hex-item reveal" style="text-decoration: none;">
      <span class="hex-icon">🛒</span>
      <span class="hex-name">My Cart</span>
    </a>
    <a href="{{ route('client.favoris') }}" class="hex-item reveal" style="text-decoration: none;">
      <span class="hex-icon">❤️</span>
      <span class="hex-name">Favorites</span>
    </a>
    <a href="{{ route('client.commandes') }}" class="hex-item reveal" style="text-decoration: none;">
      <span class="hex-icon">📦</span>
      <span class="hex-name">Orders</span>
    </a>
  @elseif(Auth::user()->role === 'boutique')
    <a href="{{ route('boutique.produits') }}" class="hex-item reveal" style="text-decoration: none;">
      <span class="hex-icon">📦</span>
      <span class="hex-name">Products</span>
    </a>
    <a href="{{ route('boutique.addProductForm') }}" class="hex-item reveal" style="text-decoration: none;">
      <span class="hex-icon">➕</span>
      <span class="hex-name">Add Product</span>
    </a>
    <a href="{{ route('boutique.commandes') }}" class="hex-item reveal" style="text-decoration: none;">
      <span class="hex-icon">📋</span>
      <span class="hex-name">Orders</span>
    </a>
    <a href="{{ route('boutique.dashboard') }}" class="hex-item reveal" style="text-decoration: none;">
      <span class="hex-icon">📊</span>
      <span class="hex-name">Analytics</span>
    </a>
  @elseif(Auth::user()->role === 'admin')
    <a href="{{ route('admin.boutiques') }}" class="hex-item reveal" style="text-decoration: none;">
      <span class="hex-icon">🏪</span>
      <span class="hex-name">Boutiques</span>
    </a>
    <a href="{{ route('admin.produits') }}" class="hex-item reveal" style="text-decoration: none;">
      <span class="hex-icon">📦</span>
      <span class="hex-name">Products</span>
    </a>
    <a href="{{ route('admin.statistics') }}" class="hex-item reveal" style="text-decoration: none;">
      <span class="hex-icon">📊</span>
      <span class="hex-name">Statistics</span>
    </a>
    <a href="{{ route('admin.settings') }}" class="hex-item reveal" style="text-decoration: none;">
      <span class="hex-icon">⚙️</span>
      <span class="hex-name">Settings</span>
    </a>
  @endif
</div>
</section>

<!-- RECENT ACTIVITY -->
<section class="how-it-works" style="padding: 80px 5%;">
<div class="section-label reveal">Recent Activity</div>
<h2 class="section-title reveal">Latest Updates</h2>
<div class="timeline" style="max-width: 700px;">
  <div class="timeline-item reveal">
    <div class="timeline-content">
      <h3>Welcome to TheTrove!</h3>
      <p>Your account is now active. Start exploring treasures or open your own vault.</p>
    </div>
    <div class="timeline-dot">✓</div>
    <div class="timeline-visual">🎉</div>
  </div>
  <div class="timeline-item reveal">
    <div class="timeline-visual">📧</div>
    <div class="timeline-dot">✓</div>
    <div class="timeline-content">
      <h3>Email Verified</h3>
      <p>Your email has been successfully verified. You now have full access to all features.</p>
    </div>
  </div>
  <div class="timeline-item reveal">
    <div class="timeline-content">
      <h3>Account Created</h3>
      <p>Your TheTrove account was created. Welcome to the community!</p>
    </div>
    <div class="timeline-dot">✓</div>
    <div class="timeline-visual">👤</div>
  </div>
</div>
</section>

@endsection