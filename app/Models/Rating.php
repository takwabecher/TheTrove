<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    protected $table = 'ratings';
    protected $primaryKey = 'id_rating';

    protected $fillable = [
        'id_product',
        'id_client',
        'rating',
    ];

    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the product that owns the rating.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Produit::class, 'id_product', 'id_product');
    }

    /**
     * Get the user (client) that gave the rating.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_client', 'id');
    }
}