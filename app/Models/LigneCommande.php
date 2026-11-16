<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;  // ← MUST be this

class LigneCommande extends Model
{
    use HasFactory;

    protected $table = 'ligne_commandes';

    protected $fillable = [
        'id_cmd',
        'id_product',
        'quantity',
        'price',
    ];

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class, 'id_cmd');
    }

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class, 'id_product');
    }
}