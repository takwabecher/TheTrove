<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryCall extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_company_id',
        'commande_id',
        'client_name',
        'client_phone',
        'call_type',
        'duration_seconds',
        'notes',
        'called_at',
    ];

    protected $casts = [
        'called_at' => 'datetime',
    ];

    public function deliveryCompany()
    {
        return $this->belongsTo(DeliveryCompany::class);
    }

    public function commande()
    {
        // FIX: specify foreign key and owner key since Commande uses id_cmd
        return $this->belongsTo(Commande::class, 'commande_id', 'id_cmd');
    }

    public function getDurationFormattedAttribute()
    {
        $m = floor($this->duration_seconds / 60);
        $s = $this->duration_seconds % 60;
        return sprintf('%02d:%02d', $m, $s);
    }
}