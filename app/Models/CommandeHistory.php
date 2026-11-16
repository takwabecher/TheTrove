<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandeHistory extends Model
{
    use HasFactory;

    protected $table = 'commande_history';

    protected $fillable = [
        'commande_id', 'delivery_company_id', 'action', 'old_status',
        'new_status', 'notes', 'metadata', 'acted_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'acted_at' => 'datetime',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function deliveryCompany()
    {
        return $this->belongsTo(DeliveryCompany::class);
    }
}