<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_id',
        'amount',
        'currency',
        'payment_gateway',
        'transaction_id',
        'status',
        'paid_at',
        'raw_response',
    ];

    protected $casts = [
        'paid_at'     => 'datetime',
        'raw_response'=> 'array',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
