<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panier extends Model
{
    use HasFactory;

    protected $table = 'paniers';
    protected $primaryKey = 'id_panier';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_client',
        'id_product',
        'dateCreation',
        'quantity',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'id_client');
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class, 'id_product', 'id_product');
    }

    public function ajouterProduit()
    {
        // Add to basket
    }

    public function supprimerProduit()
    {
        $this->delete();
    }

    public function consulterProduit()
    {
        return $this->produit;
    }
}