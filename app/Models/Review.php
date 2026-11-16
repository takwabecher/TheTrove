<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $table = 'reviews';
    protected $primaryKey = 'id_review';

    protected $fillable = [
        'id_product',
        'id_client',
        'comment',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the product that owns the review.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Produit::class, 'id_product', 'id_product');
    }

    /**
     * Get the user (client) that wrote the review.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_client', 'id');
    }

    /**
     * Get the rating associated with this review.
     */
    public function rating()
    {
        return $this->hasOne(Rating::class, 'id_product', 'id_product')
                    ->where('id_client', $this->id_client);
    }
}