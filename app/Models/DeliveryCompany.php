<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class DeliveryCompany extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'delivery';

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'address', 'logo', 'is_active',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

   public function commandes()
{
    return $this->hasMany(Commande::class, 'delivery_company_id');
}

    public function activeCommandes()
    {
        return $this->commandes()->whereIn('delivery_status', ['pending','picked_up','in_transit']);
    }

    public function completedCommandes()
    {
        return $this->commandes()->where('delivery_status', 'delivered');
    }

    public function calls()
    {
        return $this->hasMany(DeliveryCall::class);
    }
}