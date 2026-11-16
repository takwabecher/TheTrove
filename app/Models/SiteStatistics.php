<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteStatistics extends Model
{
    use HasFactory;

    protected $table = 'site_statistics';
    protected $fillable = ['activity_percentage', 'total_orders', 'total_revenue'];
}