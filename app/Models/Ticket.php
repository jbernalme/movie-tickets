<?php

namespace App\Models;

use App\Models\Screening;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        return $this->belongsToMany(Seat::class)->withPivot('price');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function screening(): BelongsTo
    {
        return $this->belongsTo(Screening::class);
    }
    public function payment(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
