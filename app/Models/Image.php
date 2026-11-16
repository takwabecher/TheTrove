<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $table = 'images';
    protected $primaryKey = 'id_image';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'path',
        'alt',
        'is_primary',
        'id_product',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    // ── Relations ──────────────────────────────────────────

    public function produit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Produit::class, 'id_product', 'id_product');
    }

    // ── Helpers ────────────────────────────────────────────

    /**
     * Définit cette image comme principale et retire le flag
     * des autres images du même produit (unicité garantie).
     */
    public function setAsPrimary(): void
    {
        // Retirer is_primary des autres images du même produit
        static::where('id_product', $this->id_product)
            ->where('id_image', '!=', $this->id_image)
            ->update(['is_primary' => false]);

        $this->update(['is_primary' => true]);
    }
}