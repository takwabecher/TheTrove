<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favoris extends Model
{
    use HasFactory;

    protected $table = 'favoris';
    protected $primaryKey = 'id_fav';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_product',
        'id_client',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class, 'id_product', 'id_product');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'id_client');
    }

    public function ajouterFav()
    {
        // Add to wishlist
    }

    public function supprimerFav()
    {
        $this->delete();
    }

    public function consulterFav()
    {
        return $this->produit;
    }
}