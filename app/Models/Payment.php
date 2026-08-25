<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'ticket_id',
        'amount',
        'payment_method',
        'transaction_id',
        'gateway_id',
        'gateway_payment_id',
        'currency',
        'status',
        'metadata',
        'failure_reason',
        'paid_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'paid_at' => 'datetime',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
