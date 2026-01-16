<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seat extends Model
{
    public function hall() : BelongsTo {
        return $this->belongsTo(Hall::class);
    }

    public function detailsTicket(): BelongsToMany
    {
        return $this->belongsToMany(Ticket::class);
    }
}
