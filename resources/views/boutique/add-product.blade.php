@extends('layouts.app')

@section('title', 'Ajouter un Produit')

@section('content')
<div class="vault-dashboard">
    <div class="container" style="max-width: 700px; margin: 0 auto; padding: 140px 5% 80px;">

        <!-- HEADER -->
        <div class="vault-header reveal" style="margin-bottom: 40px;">
            <h2 class="vault-title">
                <img src="{{ asset('images/add.png') }}" alt="add" style="width: 36px; height: 36px; vertical-align: middle; margin-right: 12px;">
                Add <span style="color: var(--accent); font-weight: 600;">New Product</span>
            </h2>
            <p class="vault-subtitle">
                Fill in the details below to add a new treasure to your collection.
            </p>
        </div>

        <!-- SITE FEE INFO CARD -->
        <div class="card reveal" style="background: var(--accent-glow); border: 1px solid var(--accent); border-radius: 16px; padding: 16px 20px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
            <img src="{{ asset('images/info.png') }}" alt="info" style="width: 24px; height: 24px; opacity: 0.8;">
            <div>
                <p style="margin: 0; color: var(--accent); font-weight: 600; font-size: 0.9rem;">
                    Platform Fee: <span id="feePercentDisplay">{{ $siteFeePercent ?? 10 }}</span>%
                </p>
                <p style="margin: 0; color: var(--text-secondary); font-size: 0.8rem;">
                    The site takes <span id="feePercentSmall">{{ $siteFeePercent ?? 10 }}</span>% commission. Your earnings will be calculated automatically.
                </p>
            </div>
        </div>

        <div class="card reveal" style="background: var(--bg-card); border: 1px solid var(--border); border-radius: 24px; overflow: hidden;">
            <div class="card-body p-4" style="padding: 32px;">
                <form action="{{ route('boutique.addProduct') }}" method="POST" enctype="multipart/form-data" id="addProductForm">
                    @csrf

                    <!-- Nom -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="nom" class="form-label">Product Name</label>
                        <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                               id="nom" name="nom" value="{{ old('nom') }}" required
                               placeholder="Enter product name"
                               style="padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border); background: var(--bg-secondary); color: var(--text-primary); width: 100%; font-family: 'DM Sans', sans-serif;">
                        @error('nom')
                            <div class="invalid-feedback" style="color: #ef4444; font-size: 0.85rem; margin-top: 6px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Catégorie -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="id_cat" class="form-label">Category</label>
                        <select class="form-select @error('id_cat') is-invalid @enderror" 
                                id="id_cat" name="id_cat" required
                                style="padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border); background: var(--bg-secondary); color: var(--text-primary); width: 100%; font-family: 'DM Sans', sans-serif; cursor: pointer; appearance: none; background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'12\' height=\'12\' fill=\'%236b7280\' viewBox=\'0 0 16 16\'%3E%3Cpath d=\'M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z\'/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 16px center; padding-right: 40px;">
                            <option value="">-- Select a category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id_cat }}" {{ old('id_cat') == $cat->id_cat ? 'selected' : '' }}>
                                    {{ $cat->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_cat')
                            <div class="invalid-feedback" style="color: #ef4444; font-size: 0.85rem; margin-top: 6px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" required
                                  placeholder="Describe your product..."
                                  style="padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border); background: var(--bg-secondary); color: var(--text-primary); width: 100%; font-family: 'DM Sans', sans-serif; resize: vertical;">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback" style="color: #ef4444; font-size: 0.85rem; margin-top: 6px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Prix & Stock (2 columns) -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div class="form-group">
                            <label for="prix" class="form-label">Your Price (DT) <small style="color: var(--text-muted); font-weight: 400;">— What you want to earn</small></label>
                            <input type="number" step="0.01" min="0" class="form-control @error('prix') is-invalid @enderror" 
                                   id="prix" name="prix" value="{{ old('prix') }}" required
                                   placeholder="0.00"
                                   style="padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border); background: var(--bg-secondary); color: var(--text-primary); width: 100%; font-family: 'DM Sans', sans-serif;">
                            @error('prix')
                                <div class="invalid-feedback" style="color: #ef4444; font-size: 0.85rem; margin-top: 6px;">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" min="0" class="form-control @error('stock') is-invalid @enderror" 
                                   id="stock" name="stock" value="{{ old('stock') }}" required
                                   placeholder="0"
                                   style="padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border); background: var(--bg-secondary); color: var(--text-primary); width: 100%; font-family: 'DM Sans', sans-serif;">
                            @error('stock')
                                <div class="invalid-feedback" style="color: #ef4444; font-size: 0.85rem; margin-top: 6px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- PRICE BREAKDOWN -->
                    <div class="form-group" style="margin-bottom: 24px;">
                        <div style="background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 16px; padding: 20px;">
                            <p style="margin: 0 0 12px 0; font-weight: 600; color: var(--text-secondary); font-size: 0.9rem;">💰 Price Breakdown</p>
                            
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px dashed var(--border);">
                                <span style="color: var(--text-muted); font-size: 0.85rem;">Your Earning (Boutique)</span>
                                <span id="boutiqueEarning" style="font-weight: 600; color: var(--text-primary); font-size: 1rem;">0.00 DT</span>
                            </div>
                            
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px dashed var(--border);">
                                <span style="color: var(--text-muted); font-size: 0.85rem;">Site Fee (<span class="feePercent">{{ $siteFeePercent ?? 10 }}</span>%)</span>
                                <span id="siteFee" style="font-weight: 600; color: var(--accent); font-size: 1rem;">0.00 DT</span>
                            </div>
                            
                            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 4px;">
                                <span style="color: var(--text-secondary); font-size: 0.9rem; font-weight: 600;">Customer Pays</span>
                                <span id="customerPays" style="font-weight: 700; color: var(--accent); font-size: 1.2rem;">0.00 DT</span>
                            </div>
                            
                            <input type="hidden" id="prix_final" name="prix_final" value="{{ old('prix_final') }}">
                            <input type="hidden" id="site_fee_amount" name="site_fee_amount" value="{{ old('site_fee_amount') }}">
                        </div>
                    </div>

                    <!-- Images (Multi-upload with Drag & Drop + Preview) -->
                    <div class="form-group" style="margin-bottom: 24px;">
                        <label class="form-label">
                            Product Images 
                            <small style="color: var(--text-muted); font-weight: 400;">(First = Primary)</small>
                        </label>
                        
                        <div class="file-upload-wrapper" id="addDropZone" 
                             style="position: relative; border: 2px dashed var(--border); border-radius: 16px; padding: 40px 30px; text-align: center; cursor: pointer; transition: all 0.3s ease; background: var(--bg-secondary); overflow: hidden;">
                            
                            <input type="file" id="images" name="images[]" accept="image/*" multiple 
                                   class="@error('images') is-invalid @enderror @error('images.*') is-invalid @enderror"
                                   style="position: absolute; inset: 0; opacity: 0; cursor: pointer; z-index: 2; width: 100%; height: 100%;"
                                  onchange="handleMultiImagePreview(this, 'previewContainer', 'uploadLabel', 'add')">
                            
                            <div class="file-upload-label" id="uploadLabel" style="display: flex; flex-direction: column; align-items: center; gap: 10px; color: var(--text-muted); pointer-events: none;">
                                <img src="{{ asset('images/upload.png') }}" alt="upload" style="width: 48px; height: 48px; opacity: 0.6;">
                                <span style="font-weight: 500;">Click or drag images here</span>
                                <small style="color: var(--text-muted);">You can select multiple images. The first will be primary.</small>
                            </div>
                            
                            <!-- Preview Container -->
                            <div id="previewContainer" class="multi-preview-container" 
                                 style="display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 12px; margin-top: 20px;"></div>
                        </div>
                        
                        @error('images')
                            <div class="invalid-feedback" style="color: #ef4444; font-size: 0.85rem; margin-top: 6px;">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div class="invalid-feedback" style="color: #ef4444; font-size: 0.85rem; margin-top: 6px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div style="display: flex; gap: 12px;">
                        <button type="submit" class="btn-submit" style="flex: 1; padding: 14px 24px; border-radius: 12px; border: none; background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dim) 100%); color: #000; font-weight: 600; cursor: pointer; transition: all 0.3s ease; font-family: 'DM Sans', sans-serif; display: inline-flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 15px rgba(201,168,76,0.3); font-size: 0.95rem;">
                            <img src="{{ asset('images/add.png') }}" alt="add" style="width: 18px; height: 18px;">
                            Add Product
                        </button>
                        <a href="{{ route('boutique.produits') }}" class="btn-cancel" 
                           style="padding: 14px 24px; border-radius: 12px; border: 1px solid var(--border); background: transparent; color: var(--text-secondary); font-weight: 600; text-decoration: none; transition: all 0.3s ease; font-family: 'DM Sans', sans-serif; display: inline-flex; align-items: center; justify-content: center; gap: 8px; font-size: 0.95rem;">
                            <img src="{{ asset('images/cancel.png') }}" alt="cancel" style="width: 18px; height: 18px;">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.vault-dashboard { padding: 0 0 80px; background: var(--bg-primary); min-height: 100vh; }
.vault-header { text-align: center; }
.vault-header-top { margin-bottom: 16px; }
.vault-title { font-family: 'Cinzel', serif; font-size: 2rem; font-weight: 600; color: var(--text-primary); margin-bottom: 12px; letter-spacing: -0.5px; }
.vault-subtitle { font-size: 1rem; color: var(--text-secondary); font-weight: 300; line-height: 1.6; max-width: 520px; margin: 0 auto; }
.section-label {
    display: inline-block; padding: 8px 20px; border-radius: 20px;
    background: var(--accent-glow); border: 1px solid var(--border-hover);
    color: var(--accent); font-size: 0.8rem; font-weight: 600;
    letter-spacing: 2px; text-transform: uppercase; font-family: 'Cinzel', serif;
}
.form-label { font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); display: block; margin-bottom: 8px; }
.form-control:focus, .form-select:focus { outline: none; border-color: var(--accent) !important; box-shadow: 0 0 0 3px var(--accent-glow); }

/* Drag & Drop States */
.file-upload-wrapper.dragover { border-color: var(--accent) !important; background: var(--accent-glow) !important; }

/* Preview Items */
.preview-item {
    position: relative; border-radius: 12px; overflow: hidden;
    border: 2px solid var(--border); aspect-ratio: 1;
    transition: all 0.3s ease;
}
.preview-item.primary { border-color: var(--accent); box-shadow: 0 0 0 2px var(--accent-glow); }
.preview-item img { width: 100%; height: 100%; object-fit: cover; }
.preview-badge {
    position: absolute; top: 6px; left: 6px;
    padding: 3px 8px; border-radius: 6px; font-size: 0.65rem;
    font-weight: 700; background: var(--accent); color: #000;
    text-transform: uppercase; letter-spacing: 0.5px;
}
.preview-remove {
    position: absolute; top: 6px; right: 6px;
    width: 24px; height: 24px; border-radius: 50%;
    background: rgba(239,68,68,0.9); color: white;
    border: none; cursor: pointer; font-size: 0.8rem;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.2s ease;
}
.preview-remove:hover { background: #ef4444; transform: scale(1.1); }
.preview-order {
    position: absolute; bottom: 6px; left: 6px;
    width: 22px; height: 22px; border-radius: 50%;
    background: rgba(0,0,0,0.7); color: white;
    border: none; font-size: 0.7rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
}

.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(201,168,76,0.4); }
.btn-cancel:hover { border-color: var(--text-primary); color: var(--text-primary); }

.reveal { opacity: 0; transform: translateY(30px); transition: all 0.6s ease; }
.reveal.active { opacity: 1; transform: translateY(0); }

@media (max-width: 640px) {
    .vault-title { font-size: 1.6rem; }
    div[style*="grid-template-columns: 1fr 1fr"] { grid-template-columns: 1fr !important; }
}
</style>

<script>
// ===== SITE FEE CONFIGURATION =====
const SITE_FEE_PERCENT = {{ $siteFeePercent ?? 10 }}; // Pass this from your controller

// ===== PRICE CALCULATOR =====
const prixInput = document.getElementById('prix');
const boutiqueEarningEl = document.getElementById('boutiqueEarning');
const siteFeeEl = document.getElementById('siteFee');
const customerPaysEl = document.getElementById('customerPays');
const prixFinalInput = document.getElementById('prix_final');
const siteFeeAmountInput = document.getElementById('site_fee_amount');

function calculatePriceBreakdown() {
    const boutiquePrice = parseFloat(prixInput.value) || 0;
    
    // Calculate: Customer pays = Boutique price / (1 - fee_percent)
    // Example: Boutique wants 90 DT, fee is 10% → Customer pays 100 DT, site takes 10 DT
    const customerPays = boutiquePrice > 0 ? boutiquePrice / (1 - SITE_FEE_PERCENT / 100) : 0;
    const siteFee = customerPays - boutiquePrice;
    
    // Update display
    boutiqueEarningEl.textContent = boutiquePrice.toFixed(2) + ' DT';
    siteFeeEl.textContent = siteFee.toFixed(2) + ' DT';
    customerPaysEl.textContent = customerPays.toFixed(2) + ' DT';
    
    // Update hidden inputs for backend
    prixFinalInput.value = customerPays.toFixed(2);
    siteFeeAmountInput.value = siteFee.toFixed(2);
}

prixInput.addEventListener('input', calculatePriceBreakdown);

// Initialize on page load
calculatePriceBreakdown();

// ===== DRAG & DROP =====
const dropZone = document.getElementById('addDropZone');
const fileInput = document.getElementById('images');

dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('dragover');
});
dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('dragover');
});
dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('dragover');
    fileInput.files = e.dataTransfer.files;
    handleImagePreview(fileInput);
});

// ===== MULTI-IMAGE PREVIEW =====
let accumulatedFiles = { add: [], edit: [] };

function handleMultiImagePreview(input, containerId, labelId, context) {
    const container = document.getElementById(containerId);
    const label = document.getElementById(labelId);
    const newFiles = Array.from(input.files);
    
    if (newFiles.length === 0) return;
    
    accumulatedFiles[context] = accumulatedFiles[context].concat(newFiles);
    
    const dt = new DataTransfer();
    accumulatedFiles[context].forEach(file => dt.items.add(file));
    input.files = dt.files;
    
    if (accumulatedFiles[context].length > 0) {
        label.style.display = 'none';
    }
    
    container.innerHTML = '';
    
    accumulatedFiles[context].forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'multi-preview-item' + (index === 0 ? ' primary' : '');
            div.innerHTML = `
                <img src="${e.target.result}" alt="Preview ${index + 1}">
                ${index === 0 ? '<span class="preview-badge">PRIMARY</span>' : ''}
                <button type="button" class="preview-remove" onclick="removePreviewImage(${index}, '${context}', '${containerId}', '${labelId}', '${input.id}')" title="Remove">×</button>
            `;
            container.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

function removePreviewImage(index, context, containerId, labelId, inputId) {
    accumulatedFiles[context].splice(index, 1);
    
    const input = document.getElementById(inputId);
    const dt = new DataTransfer();
    accumulatedFiles[context].forEach(file => dt.items.add(file));
    input.files = dt.files;
    
    handleMultiImagePreview(input, containerId, labelId, context);
    
    if (accumulatedFiles[context].length === 0) {
        document.getElementById(labelId).style.display = 'flex';
        document.getElementById(containerId).innerHTML = '';
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
    
    setTimeout(() => {
        revealElements.forEach(el => el.classList.add('active'));
    }, 100);
});
</script>
@endsection