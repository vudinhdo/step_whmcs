<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPricingRule extends Model
{
    protected $fillable = [
        'product_id','key','billing_cycle','price_per_unit','min','max','step'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

}
