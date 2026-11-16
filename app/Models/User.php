<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;



class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function panier()
    {
        return $this->hasMany(Panier::class, 'id_client');
    }

    public function favoris()
    {
        return $this->hasMany(Favoris::class, 'id_client');
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class, 'id_client');
    }

    // Boutique relationships
    public function produits()
    {
        return $this->hasMany(Produit::class, 'id_boutique');
    }
    public function commandes_boutique()
    {
        return $this->hasMany(Commande::class, 'id_boutique');
    }
     public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function reviews()
{
    return $this->hasMany(Review::class, 'id_client', 'id') ; 
}

public function ratings()
{
    return $this->hasMany(Rating::class, 'id_client', 'id');
}
}