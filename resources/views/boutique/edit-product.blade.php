@extends('layouts.app')

@section('title', 'Éditer Produit')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-edit"></i> Éditer Produit</h4>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('boutique.updateProduct', $produit->id_product) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Nom -->
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom du Produit</label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" name="nom" value="{{ old('nom', $produit->nom) }}" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Catégorie -->
                        <div class="mb-3">
                            <label for="id_cat" class="form-label">Catégorie</label>
                            <select class="form-select @error('id_cat') is-invalid @enderror" 
                                    id="id_cat" name="id_cat" required>
                                <option value="">-- Sélectionnez une catégorie --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id_cat }}" {{ old('id_cat', $produit->id_cat) == $cat->id_cat ? 'selected' : '' }}>
                                        {{ $cat->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_cat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" required>{{ old('description', $produit->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Prix -->
                        <div class="mb-3">
                            <label for="prix" class="form-label">Prix (DH)</label>
                            <input type="number" step="0.01" class="form-control @error('prix') is-invalid @enderror" 
                                   id="prix" name="prix" value="{{ old('prix', $produit->prix) }}" required>
                            @error('prix')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Stock -->
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                   id="stock" name="stock" value="{{ old('stock', $produit->stock) }}" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image -->
                        <!-- Images -->
<div class="mb-3">
    <label class="form-label">Images Actuelles</label>
    
    @if($produit->images->count() > 0)
        <div class="d-flex flex-wrap gap-2 mb-3">
            @foreach($produit->images as $img)
                <div class="position-relative">
                    <img src="{{ asset('storage/' . $img->path) }}" 
                         class="img-thumbnail {{ $img->is_primary ? 'border-primary border-3' : '' }}"
                         style="max-width: 150px; max-height: 150px;">
                    @if($img->is_primary)
                        <span class="badge bg-primary position-absolute top-0 start-0">Principale</span>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <p class="text-muted">Aucune image</p>
    @endif

    <label for="images" class="form-label">Ajouter des Images</label>
    <input type="file" class="form-control @error('images') is-invalid @enderror" 
           id="images" name="images[]" accept="image/*" multiple>
    @error('images')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    @error('images.*')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                        <!-- Submit -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-save"></i> Mettre à jour
                            </button>
                            <a href="{{ route('boutique.produits') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection