<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'id_cat';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nom',
        'description',
        'image',
    ];

    public function produits()
    {
        return $this->hasMany(Produit::class, 'id_cat');
    }
}