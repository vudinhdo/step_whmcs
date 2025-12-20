<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'product_group_id',
        'name',
        'slug',
        'description',
        'type',
        'is_active',
        'provisioning_module',
        'meta_title',
        'meta_description',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(ProductGroup::class, 'product_group_id');
    }

    public function pricing(): HasMany
    {
        return $this->hasMany(ProductPricing::class);
    }
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(ProductOption::class)->orderBy('sort_order');
    }

    public function pricingRules(): HasMany
    {
        return $this->hasMany(ProductPricingRule::class);
    }

}
