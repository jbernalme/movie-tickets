<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'screening_id',
        'discount_id',
        'total_price',
        'discount_price',
        'final_price',
        'ticket_code',
        'status',
        'expires_at',
        'used_at',
    ];
    public function seats(): BelongsToMany
    {
        return $this->belongsToMany(Seat::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
