@extends('layouts.app')

@section('title', 'TheTrove — Find Your Treasure. Be the Treasure.')

@section('content')

<!-- PRODUCT MODAL -->
<div class="product-modal" id="productModal">
    <div class="modal-overlay" onclick="closeModal()"></div>
    <div class="modal-card">
        <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
        <div class="modal-image" id="modalImageWrapper">
            <img id="modalImg" src="" alt="">
        </div>
        <div class="modal-info">
            <span class="modal-category" id="modalCategory"></span>
            <h3 class="modal-name" id="modalName"></h3>
            <p class="modal-desc" id="modalDesc"></p>
            <div class="modal-meta">
                <div class="modal-price" id="modalPrice"></div>
                <div class="modal-vault" id="modalVault"></div>
            </div>
            <div class="modal-actions">
                <a href="{{ route('choice') }}" class="modal-btn wishlist">
                    <i class="fas fa-heart"></i> Wishlist
                </a>
                <a href="{{ route('choice') }}" class="modal-btn basket">
                    <i class="fas fa-shopping-bag"></i> Basket
                </a>
            </div>
        </div>
    </div>
</div>

<!-- HERO - Logo Centered with Orbit Rings -->
<section class="hero" id="home">
<div class="hero-mesh"></div>
<div class="hero-content">
  <div class="hero-top">
    <h1>Find Your <em>Treasure.</em><br>Be the <em>Treasure.</em></h1>
    <p class="hero-desc">Every treasure has a story.<BR> Every keeper has a passion.<BR> Every seeker finds a little magic.</p>
    <div class="hero-tag">TheTrove isn't about everything. It's about everything worth having.</div>
    <div class="hero-actions">
      <a href="#featured" class="btn-main">Start Hunting <i class="fas fa-arrow-right"></i></a>
      <a href="#how" class="btn-ghost">See How It Works</a>
    </div>
  </div>
  <div class="hero-logo-stage">
    <div class="hero-glow"></div>
    <div class="orbit-ring orbit-ring-3">
      <div class="orbit-dot"></div>
    </div>
    <div class="orbit-ring orbit-ring-2">
      <div class="orbit-dot"></div>
    </div>
    <div class="orbit-ring orbit-ring-1">
      <div class="orbit-dot"></div>
    </div>
    <div class="hero-logo-main">
      <img src="{{ asset('images/TheTroveLogo.png') }}" alt="TheTrove Treasure Chest">
    </div>
  </div>
</div>
<div class="scroll-hint">
  <span>Scroll to explore</span>
  <i class="fas fa-chevron-down"></i>
</div>
</section>

<!-- MARQUEE -->
<div class="marquee-strip">
  <div class="marquee-content">
    <span class="marquee-item"><i class="fas fa-star"></i> Free Shipping Worldwide</span>
    <span class="marquee-item"><i class="fas fa-star"></i> Verified Vaults Only</span>
    <span class="marquee-item"><i class="fas fa-star"></i> 24h Express Delivery</span>
    <span class="marquee-item"><i class="fas fa-star"></i> Buyer Protection</span>
    <span class="marquee-item"><i class="fas fa-star"></i> Instant Payouts</span>
    <span class="marquee-item"><i class="fas fa-star"></i> 500K+ Treasures</span>
    <span class="marquee-item"><i class="fas fa-star"></i> Free Shipping Worldwide</span>
    <span class="marquee-item"><i class="fas fa-star"></i> Verified Vaults Only</span>
    <span class="marquee-item"><i class="fas fa-star"></i> 24h Express Delivery</span>
    <span class="marquee-item"><i class="fas fa-star"></i> Buyer Protection</span>
    <span class="marquee-item"><i class="fas fa-star"></i> Instant Payouts</span>
    <span class="marquee-item"><i class="fas fa-star"></i> 500K+ Treasures</span>
  </div>
</div>

<!-- CATEGORIES -->
<section class="categories" id="categories">
<div class="section-label reveal">Browse by Category</div>
<h2 class="section-title reveal">What Are You Hunting For?</h2>
<div class="hex-grid">
  <div class="hex-item reveal">
    <span class="hex-icon"><img src="{{ asset('images/clothing.png') }}" alt="clothing"></span>
    <span class="hex-name">Fashion & Clothing</span>
  </div>
  <div class="hex-item reveal">
    <span class="hex-icon"><img src="{{ asset('images/computer.png') }}" alt="computer"></span>
    <span class="hex-name" align='center'>Computers & Electronics</span>
  </div>
  <div class="hex-item reveal">
    <span class="hex-icon"><img src="{{ asset('images/home.png') }}" alt="home"></span>
    <span class="hex-name">Home & Living</span>
  </div>
  <div class="hex-item reveal">
    <span class="hex-icon"><img src="{{ asset('images/sport.png') }}" alt="sport"></span>
    <span class="hex-name">Sports & Fitness</span>
  </div>
  <div class="hex-item reveal " onclick="window.location.href='{{ route('categories.all') }}'">
    <span class="hex-icon"><img src="{{ asset('images/more.png') }}" alt="more"></span>
    <span class="hex-name">And So Much More!</span>
  </div>
</div><BR>
<div class="timeline-content">
      <h3 align='center'>The Deeper You Dig, The Brighter It Shines.!</h3>
    </div>
</section>

<!-- HOW IT WORKS - TIMELINE -->
<section class="how-it-works" id="how">
<div class="section-label reveal">The Journey</div>
<h2 class="section-title reveal">How TheTrove Works</h2>
<div class="timeline">
  <div class="timeline-item reveal">
    <div class="timeline-content">
      <h3>Browse & Discover</h3>
      <p>Begin Your Journey!<BR>    Explore thousands of treasures. Every Keepers' vault holds something waiting to be found.</p>
    </div>
    <div class="timeline-dot">1</div>
    <div class="timeline-visual"><img src="{{ asset('images/search.png') }}" alt="search"></div>
  </div>
  <div class="timeline-item reveal">
    <div class="timeline-visual"><img src="{{ asset('images/panier.png') }}" alt="panier"></div>
    <div class="timeline-dot">2</div>
    <div class="timeline-content">
      <h3>Secure Checkout</h3>
      <p>Your Treasure, Secured!<BR>Add to your cart, watch savings appear automatically, and checkout with complete peace of mind.</p>
    </div>
  </div>
  <div class="timeline-item reveal">
    <div class="timeline-content">
      <h3>Track & Receive</h3>
      <p>The journey unfold!<BR>Watch your treasure make its way to you. Arrives next-day with our double fast delivery guarantee.</p>
    </div>
    <div class="timeline-dot">3</div>
    <div class="timeline-visual"><img src="{{ asset('images/livraison.png') }}" alt="livraison"></div>
  </div>
</div>
</section>

<!-- FEATURED - HORIZONTAL SCROLL -->
<section class="featured" id="featured">
  <div>
    <div class="section-label reveal" >Trending Now</div>
    <h2 class="section-title reveal">Featured Treasures</h2>
  </div>
  <div class="gallery-nav">
    <button onclick="scrollGallery(-1)"><i class="fas fa-chevron-left"></i></button>
    <button onclick="scrollGallery(1)"><i class="fas fa-chevron-right"></i></button>
  </div>
<div class="gallery-scroll" id="galleryScroll">
  @forelse($featuredProducts as $product)
    @php
        $productImages = $product->images;
        $primaryImage = $product->imagePrincipale;
        $displayImage = $primaryImage ?? $productImages->first();
        $imageCount = $productImages->count();
        $imageUrl = $displayImage ? asset('storage/' . $displayImage->path) : asset('images/placeholder-product.png');
    @endphp
    <div class="gallery-card reveal">
      <div class="gallery-img" onclick="openModal({{ $product->id_product }})"
           data-images='@json($productImages->map(fn($img) => ['path' => $img->path, 'is_primary' => $img->is_primary ?? false]))'>
        <img src="{{ $imageUrl }}"
             alt="{{ $product->nom }}"
             onerror="this.src='{{ asset('images/placeholder-product.png') }}'">
        @if($imageCount > 1)
            <div class="gallery-img-count">
                <i class="fas fa-images"></i> {{ $imageCount }}
            </div>
        @endif
      </div>
      <div class="gallery-info">
        <h4 onclick="openModal({{ $product->id_product }})">{{ $product->nom }}</h4>
        <div class="product-category">{{ $product->categorie->nom ?? 'Uncategorized' }}</div>
        <div class="price">{{ number_format($product->prix, 2) }} DT</div>
        <div class="store"><img src="{{ asset('images/boutiquemini2.png') }}" alt="boutique"></i> {{ $product->boutique->name ?? 'TheTrove' }}</div>
        <div class="product-actions">
          <a href="{{ route('login') }}" class="action-btn wishlist">
            <img src="{{ asset('images/wishmini.png') }}" alt="wish"> Wishlist
          </a>
          <a href="{{ route('login') }}" class="action-btn basket">
            <img src="{{ asset('images/paniermini1.png') }}" alt="basket"> Basket
          </a>
        </div>
      </div>
    </div>
  @empty
    <div class="gallery-card reveal">
      <div class="gallery-info">
        <h4>No Products Yet</h4>
        <div class="price">--</div>
        <div class="store"><i class="fas fa-store"></i> TheTrove</div>
        <button class="add-btn" disabled style="opacity:0.5;cursor:not-allowed;">Coming Soon</button>
      </div>
    </div>
  @endforelse
</div>
</section>

<!-- VAULTS - MASONRY (NO IMAGES) -->
<section class="vaults" id="vaults">
<div class="section-label reveal">Top Keepers</div>
<h2 class="section-title reveal">Meet Our Best Vaults</h2>
<div class="gallery-nav">
    <button onclick="scrollGallery(-1)"><i class="fas fa-chevron-left"></i></button>
    <button onclick="scrollGallery(1)"><i class="fas fa-chevron-right"></i></button>
  </div>
<div class="vaults-container">
    @forelse($vaults as $vault)
      <div class="vault-card-new reveal" onclick="event.stopPropagation(); window.location.href='{{ route('login') }}'">
        <!-- Animated border glow -->
        <div class="vault-border-glow"></div>

        <!-- Content -->
        <div class="vault-content">
          <h3 class="vault-name-new">{{ $vault->name }}</h3>
          <p class="vault-type-new">Treasure Keeper</p>
          <div class="vault-metrics">
            <div class="metric">
              <div class="metric-icon"><i class="fas fa-box-open"></i></div>
              <div class="metric-value">{{ $vault->produits_count ?? 0 }}</div>
              <div class="metric-label">Products</div>
            </div>
            <div class="metric">
              <div class="metric-icon"><i class="fas fa-star"></i></div>
              <div class="metric-value">5.0</div>
              <div class="metric-label">Rating</div>
            </div>
          </div>

          <button class="vault-explore-btn" onclick="event.stopPropagation(); window.location.href='{{ route('login') }}'">
            Explore Vault
          </button>
        </div>
      </div>
    @empty
      <div class="vault-card-new reveal empty-vault">
        <div class="vault-content">
          <h3 class="vault-name-new">No Vaults Yet</h3>
          <p class="vault-type-new">Coming Soon</p>
          <div class="vault-divider">
            <span></span>
            <img src="{{ asset('images/waitmini.png') }}" alt="waitmini">
            <span></span>
          </div>
          <p style="color: var(--text-muted); font-size: 0.9rem;">Treasure keepers are preparing their vaults...</p>
        </div>
      </div>
    @endforelse
  </div>
</section>

<!-- CTA -->
<section class="cta">
<div class="cta-ring"></div>
<div class="cta-ring"></div>
<div class="cta-ring"></div>
<div class="cta-content reveal">
  <h2>Ready to Join TheTrove?</h2>
  <p>Whether you want to hunt treasures or open your dream vault, your journey starts with one click.</p>
  <div class="cta-buttons">
    <a href="{{ route('login') }}" class="btn-main">Sign In</a>
    <a href="{{ route('register') }}" class="btn-ghost">Sign Up</a>
  </div>
</div>
</section>

<script>
// ===== BUILD PRODUCTS DATA =====
@php
$productsData = $featuredProducts->map(function($p) {
    $productImages = $p->images;
    $primaryImage = $p->imagePrincipale;
    $displayImage = $primaryImage ?? $productImages->first();
    return [
        'id_product' => $p->id_product,
        'nom' => $p->nom,
        'description' => $p->description,
        'prix' => $p->prix,
        'categorie' => $p->categorie ? ['name' => $p->categorie->name] : null,
        'boutique' => $p->boutique ? ['name' => $p->boutique->name] : null,
        'image' => $displayImage ? asset('storage/' . $displayImage->path) : asset('images/placeholder-product.png'),
        'images' => $productImages->map(function($img) {
            return [
                'path' => $img->path,
                'is_primary' => $img->is_primary ?? false,
            ];
        })->values(),
    ];
})->keyBy('id_product');
@endphp

const productsData = @json($productsData);
const storageUrl = '{{ asset('storage') }}';

// Gallery scroll function
function scrollGallery(dir) {
  const gallery = document.getElementById('galleryScroll');
  gallery.scrollBy({ left: dir * 304, behavior: 'smooth' });
}

// Modal functions
function openModal(id) {
    const product = productsData[id];
    if (!product) return;

    const imageWrapper = document.getElementById('modalImageWrapper');
    const images = product.images || [];

    if (images.length > 1) {
        // Multi-image carousel in modal
        let carouselHtml = `<div class="modal-image-carousel" id="modalCarousel" data-current="0" data-total="${images.length}">`;
        images.forEach((img, index) => {
            carouselHtml += `<img src="${storageUrl}/${img.path}" alt="${product.nom}" class="carousel-slide ${index === 0 ? 'active' : ''}" data-index="${index}" onerror="this.src='{{ asset('images/placeholder-product.png') }}'">`;
        });
        carouselHtml += `
            <button type="button" class="carousel-arrow carousel-prev" onclick="event.stopPropagation(); modalCarouselPrev()"><i class="fas fa-chevron-left"></i></button>
            <button type="button" class="carousel-arrow carousel-next" onclick="event.stopPropagation(); modalCarouselNext()"><i class="fas fa-chevron-right"></i></button>
            <div class="carousel-dots">`;
        images.forEach((img, index) => {
            carouselHtml += `<span class="carousel-dot ${index === 0 ? 'active' : ''}" data-index="${index}" onclick="event.stopPropagation(); goToModalSlide(${index})"></span>`;
        });
        carouselHtml += `</div><div class="modal-image-count"><i class="fas fa-images"></i> ${images.length}</div></div>`;
        imageWrapper.innerHTML = carouselHtml;
    } else {
        // Single image or placeholder
        imageWrapper.innerHTML = `<img src="${product.image}" alt="${product.nom}" onerror="this.src='{{ asset('images/placeholder-product.png') }}'">`;
    }

    document.getElementById('modalName').textContent = product.nom;
    document.getElementById('modalDesc').textContent = product.description || 'No description available.';
    document.getElementById('modalCategory').textContent = product.categorie?.name || 'Uncategorized';
    document.getElementById('modalPrice').textContent = parseFloat(product.prix).toFixed(2) + ' DT';
    document.getElementById('modalVault').innerHTML = '<i class="fas fa-store"></i> ' + (product.boutique?.name || 'TheTrove');

    document.getElementById('productModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('productModal').classList.remove('active');
    document.body.style.overflow = '';
}

// ===== MODAL CAROUSEL =====
function modalCarouselNext() {
    const carousel = document.getElementById('modalCarousel');
    if (!carousel) return;
    const total = parseInt(carousel.dataset.total);
    let current = parseInt(carousel.dataset.current);
    current = (current + 1) % total;
    carousel.dataset.current = current;
    updateModalCarousel(carousel, current);
}

function modalCarouselPrev() {
    const carousel = document.getElementById('modalCarousel');
    if (!carousel) return;
    const total = parseInt(carousel.dataset.total);
    let current = parseInt(carousel.dataset.current);
    current = (current - 1 + total) % total;
    carousel.dataset.current = current;
    updateModalCarousel(carousel, current);
}

function goToModalSlide(index) {
    const carousel = document.getElementById('modalCarousel');
    if (!carousel) return;
    carousel.dataset.current = index;
    updateModalCarousel(carousel, index);
}

function updateModalCarousel(carousel, current) {
    const slides = carousel.querySelectorAll('.carousel-slide');
    const dots = carousel.querySelectorAll('.carousel-dot');
    slides.forEach((slide, index) => {
        slide.classList.toggle('active', index === current);
    });
    dots.forEach((dot, index) => {
        dot.classList.toggle('active', index === current);
    });
}

// Close modal on Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeModal();
});
</script>

<style>
/* ===== PRODUCT MODAL ===== */
.product-modal {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}
.product-modal.active {
    opacity: 1;
    visibility: visible;
}

.modal-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.7);
    backdrop-filter: blur(8px);
}

.modal-card {
    position: relative;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 24px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transform: scale(0.9);
    transition: transform 0.3s ease;
}
.product-modal.active .modal-card {
    transform: scale(1);
}

.modal-close {
    position: absolute;
    top: 16px;
    right: 16px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    color: var(--text-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: all 0.3s ease;
}
.modal-close:hover {
    background: #ef4444;
    color: white;
    border-color: #ef4444;
}

.modal-image {
    width: 100%;
    height: 280px;
    overflow: hidden;
    background: var(--bg-tertiary);
    position: relative;
}
.modal-image > img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Modal image carousel */
.modal-image-carousel {
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
}
.modal-image-carousel .carousel-slide {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    object-fit: cover;
    opacity: 0;
    transition: opacity 0.4s ease;
    pointer-events: none;
}
.modal-image-carousel .carousel-slide.active {
    opacity: 1;
    pointer-events: auto;
}
.modal-image-carousel .carousel-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 1px solid rgba(255,255,255,0.2);
    background: rgba(0,0,0,0.5);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    backdrop-filter: blur(4px);
}
.modal-image-carousel .carousel-arrow:hover {
    background: var(--accent);
    color: #000;
    border-color: var(--accent);
}
.modal-image-carousel .carousel-prev { left: 12px; }
.modal-image-carousel .carousel-next { right: 12px; }
.modal-image-carousel .carousel-dots {
    position: absolute;
    bottom: 12px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 6px;
    z-index: 10;
}
.modal-image-carousel .carousel-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: rgba(255,255,255,0.4);
    transition: all 0.3s ease;
    cursor: pointer;
}
.modal-image-carousel .carousel-dot.active {
    background: var(--accent);
    width: 18px;
    border-radius: 3px;
}
.modal-image-count {
    position: absolute;
    top: 12px;
    right: 12px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
    background: rgba(0,0,0,0.6);
    color: white;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
    z-index: 10;
}

.modal-info {
    padding: 28px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.modal-category {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 20px;
    background: var(--accent-glow);
    border: 1px solid var(--border-hover);
    color: var(--accent);
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    align-self: flex-start;
}

.modal-name {
    font-family: 'Cinzel', serif;
    font-size: 1.6rem;
    color: var(--text-primary);
    margin: 0;
}

.modal-desc {
    font-size: 0.95rem;
    color: var(--text-secondary);
    line-height: 1.7;
    margin: 0;
}

.modal-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 16px;
    border-top: 1px solid var(--border);
}

.modal-price {
    font-family: 'Cinzel', serif;
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--accent);
}

.modal-vault {
    font-size: 0.9rem;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 8px;
}

.modal-actions {
    display: flex;
    gap: 12px;
    padding-top: 16px;
}

.modal-btn {
    flex: 1;
    padding: 14px;
    border-radius: 12px;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.modal-btn.wishlist {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}
.modal-btn.wishlist:hover {
    background: #ef4444;
    color: white;
}

.modal-btn.basket {
    background: var(--accent);
    color: #000;
    border: 1px solid var(--accent);
}
.modal-btn.basket:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px var(--accent-glow);
}

/* ===== GALLERY IMAGE COUNT BADGE ===== */
.gallery-img-count {
    position: absolute;
    bottom: 10px;
    right: 10px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
    background: rgba(0,0,0,0.6);
    color: white;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
    z-index: 5;
    pointer-events: none;
}

/* ===== PRODUCT CARD UPDATES ===== */
.gallery-img {
    cursor: pointer;
    transition: transform 0.3s ease;
    position: relative;
}
.gallery-img:hover {
    transform: scale(1.05);
}

.gallery-info h4 {
    cursor: pointer;
    transition: color 0.3s ease;
}
.gallery-info h4:hover {
    color: var(--accent);
}

.product-category {
    font-size: 0.8rem;
    color: var(--accent);
    margin-bottom: 4px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.product-actions {
    display: flex;
    gap: 8px;
    margin-top: 12px;
}

.action-btn {
    flex: 1;
    padding: 10px;
    border-radius: 10px;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.3s ease;
}

.action-btn.wishlist {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}
.action-btn.wishlist:hover {
    background: #ef4444;
    color: white;
}

.action-btn.basket {
    background: var(--accent-glow);
    color: var(--accent);
    border: 1px solid var(--border-hover);
}
.action-btn.basket:hover {
    background: var(--accent);
    color: #000;
}

@media (max-width: 640px) {
    .modal-card {
        width: 95%;
        max-height: 95vh;
    }
    .modal-image {
        height: 200px;
    }
    .modal-actions {
        flex-direction: column;
    }
}

/* ===== VAULTS (NO IMAGES) ===== */
.vaults-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
  gap: 30px;
  max-width: 1400px;
  margin: 0 auto;
  position: relative;
  z-index: 1;
}

.vault-card-new {
  position: relative;
  border-radius: 24px;
  overflow: hidden;
  background: var(--bg-card);
  border: 1px solid var(--border);
  cursor: pointer;
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  height: auto;
  min-height: 320px;
  display: flex;
  flex-direction: column;
}

.vault-card-new:hover {
  transform: translateY(-8px) scale(1.02);
  border-color: rgba(212, 175, 55, 0.4);
  box-shadow: 
    0 20px 60px rgba(0, 0, 0, 0.4),
    0 0 0 1px rgba(212, 175, 55, 0.2),
    0 0 40px rgba(212, 175, 55, 0.1);
}

/* Animated Border Glow */
.vault-border-glow {
  position: absolute;
  inset: -1px;
  border-radius: 24px;
  padding: 1px;
  background: linear-gradient(
    45deg,
    transparent 40%,
    rgba(212, 175, 55, 0.6) 50%,
    transparent 60%
  );
  background-size: 300% 300%;
  opacity: 0;
  transition: opacity 0.3s ease;
  z-index: 2;
  pointer-events: none;
  -webkit-mask: 
    linear-gradient(#fff 0 0) content-box, 
    linear-gradient(#fff 0 0);
  -webkit-mask-composite: xor;
  mask-composite: exclude;
}

.vault-card-new:hover .vault-border-glow {
  opacity: 1;
  animation: borderGlow 3s ease infinite;
}

@keyframes borderGlow {
  0%, 100% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
}

/* Content Area */
.vault-content {
  padding: 28px;
  flex: 1;
  display: flex;
  flex-direction: column;
  position: relative;
  z-index: 2;
}

.vault-name-new {
  font-family: 'Cinzel', serif;
  font-size: 1.5rem;
  color: var(--text-primary);
  margin: 0 0 6px 0;
  line-height: 1.2;
}

.vault-type-new {
  color: var(--text-muted);
  font-size: 0.9rem;
  margin: 0 0 20px 0;
  letter-spacing: 0.5px;
}

/* Divider */
.vault-divider {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 20px;
  color: var(--accent);
  opacity: 0.5;
}

.vault-divider span {
  flex: 1;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--border-hover), transparent);
}

.vault-divider i {
  font-size: 0.7rem;
}

/* Metrics */
.vault-metrics {
  display: flex;
  justify-content: space-around;
  margin-bottom: 24px;
  gap: 8px;
}

.metric {
  text-align: center;
  flex: 1;
}

.metric-icon {
  width: 40px;
  height: 40px;
  border-radius: 12px;
  background: var(--bg-secondary);
  border: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 8px;
  color: var(--accent);
  font-size: 0.9rem;
  transition: all 0.3s ease;
}

.vault-card-new:hover .metric-icon {
  background: rgba(212, 175, 55, 0.1);
  border-color: rgba(212, 175, 55, 0.3);
  transform: translateY(-2px);
}

.metric-value {
  font-family: 'Cinzel', serif;
  font-size: 1.2rem;
  font-weight: 700;
  color: var(--text-primary);
  line-height: 1;
}

.metric-label {
  font-size: 0.75rem;
  color: var(--text-muted);
  margin-top: 4px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Explore Button */
.vault-explore-btn {
  margin-top: auto;
  width: 100%;
  padding: 14px 24px;
  border-radius: 14px;
  border: 1px solid var(--border-hover);
  background: var(--bg-secondary);
  color: var(--text-primary);
  font-family: inherit;
  font-size: 0.9rem;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.vault-explore-btn::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, var(--accent) 0%, #b8941f 100%);
  opacity: 0;
  transition: opacity 0.3s ease;
  z-index: -1;
}

.vault-explore-btn:hover {
  border-color: var(--accent);
  color: #000;
  transform: translateY(-2px);
  box-shadow: 0 10px 30px rgba(212, 175, 55, 0.2);
}

.vault-explore-btn:hover::before {
  opacity: 1;
}

.vault-explore-btn i {
  transition: transform 0.3s ease;
}

.vault-explore-btn:hover i {
  transform: translateX(4px);
}

/* Empty State */
.empty-vault {
  opacity: 0.7;
  cursor: default;
}

.empty-vault:hover {
  transform: none;
  border-color: var(--border);
  box-shadow: none;
}

/* Responsive */
@media (max-width: 768px) {
  .vaults-container {
    grid-template-columns: 1fr;
    max-width: 400px;
  }
}

@media (max-width: 480px) {
  .vault-content {
    padding: 20px;
  }
  .vault-name-new {
    font-size: 1.3rem;
  }
}
</style>

@endsection