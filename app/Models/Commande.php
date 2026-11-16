<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;        // ← MUST be this
use Illuminate\Database\Eloquent\Relations\BelongsTo;     // ← MUST be this
class Commande extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_cmd';
    public $incrementing = true;
    protected $keyType = 'int';  // ← Add this if your PK is id_cmd


    protected $fillable = [
    'id_client',
    'date_cmd',
    'status',
    'delivery_status',
    'total_price',
    'boutique_earning',
    'site_fee',
    'delivery_company_id',
    'shipping_address',   // <-- add
    'phone',              // <-- add
    'picked_up_at',
    'delivered_at',
    'delivery_notes',
    'lat',  // ← AJOUTE
    'lng',
];

    protected $casts = [
    'date_cmd'      => 'datetime',
    'picked_up_at'  => 'datetime',
    'delivered_at'  => 'datetime',
    'created_at'    => 'datetime',
    'updated_at'    => 'datetime',
];
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_client');
    }

    public function items(): HasMany
    {
        return $this->hasMany(LigneCommande::class, 'id_cmd');
    }

    public function confirmer()
    {
        $this->update(['status' => 'confirmed']);
    }

    public function annuler()
    {
        $this->update(['status' => 'declined']);
    }
public function deliveryCompany()
{
    return $this->belongsTo(DeliveryCompany::class, 'delivery_company_id');
}

public function calls()
{
    return $this->hasMany(DeliveryCall::class, 'commande_id');
}

public function history()
{
    return $this->hasMany(CommandeHistory::class, 'commande_id');
}

public function getFormattedIdAttribute()
{
    return 'ORD-' . ($this->created_at?->format('Y') ?? now()->format('Y'))
         . '-' . strtoupper(substr(md5($this->id_cmd . 'thetrove'), 0, 5));
}
}