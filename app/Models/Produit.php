<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $table = 'produits';
    protected $primaryKey = 'id_product';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nom',
        'prix',           // Customer pays this (final price)
        'prix_boutique',  // What boutique earns
        'site_fee',       // Site commission
        'stock',
        'description',
        'status',
        'id_cat',
        'id_boutique',
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'prix_boutique' => 'decimal:2',
        'site_fee' => 'decimal:2',
    ];

    // ── Relations ──────────────────────────────────────────

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'id_cat', 'id_cat');
    }

    public function boutique()
    {
        return $this->belongsTo(User::class, 'id_boutique');
    }

    public function favoris()
    {
        return $this->hasMany(Favoris::class, 'id_product');
    }

    public function paniers()
    {
        return $this->hasMany(Panier::class, 'id_product');
    }

    /** Toutes les images du produit */
    public function images()
    {
        return $this->hasMany(Image::class, 'id_product', 'id_product');
    }

    /** Image principale uniquement */
    public function imagePrincipale()
    {
        return $this->hasOne(Image::class, 'id_product', 'id_product')
                    ->where('is_primary', true);
    }

    // ── Helpers images ─────────────────────────────────────

    public function getImagePrincipale(): ?Image
    {
        return $this->images->firstWhere('is_primary', true)
            ?? $this->images->first();
    }

    public function hasAtLeastOneImage(): bool
    {
        return $this->images()->exists();
    }

    // ── Stock ──────────────────────────────────────────────

    public function hasEnoughStock(int $quantity): bool
    {
        return $this->stock >= $quantity;
    }

    public function decreaseStock(int $quantity): void
    {
        $this->decrement('stock', $quantity);
    }

    public function increaseStock(int $quantity): void
    {
        $this->increment('stock', $quantity);
    }

    // ── CRUD ───────────────────────────────────────────────

    public function supprimer(): void
    {
        $this->delete();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_product', 'id_product');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'id_product', 'id_product');
    }

    public function getAverageRatingAttribute()
    {
        return $this->ratings()->avg('rating') ?? 0;
    }

    public function getRatingsCountAttribute()
    {
        return $this->ratings()->count();
    }

    public function scopeWithReviewCount($query)
    {
        return $query->withCount('reviews');
    }

    public function scopeWithRatingCount($query)
    {
        return $query->withCount('ratings');
    }
}