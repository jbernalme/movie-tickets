<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ticket extends Model
{
    public function seats(): BelongsToMany
    {
        return $this->belongsToMany(Seat::class); 
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class); 
    }
}
