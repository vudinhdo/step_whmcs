<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'order_item_id',
        'status',
        'billing_cycle',
        'start_date',
        'next_due_date',
        'terminate_date',
        'custom_fields',
        'notes',
    ];

    protected $casts = [
        'start_date'    => 'date',
        'next_due_date' => 'date',
        'terminate_date'=> 'date',
        'custom_fields' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
