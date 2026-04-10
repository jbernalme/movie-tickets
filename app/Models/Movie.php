<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Movie extends Model
{
    use HasSlug;

    protected $fillable = [
        'title',
        'tmdb_id',
        'original_title',
        'overview',
        'slug',
        'poster_path',
        'genres',
        'release_date',
        'backdrop_path',
        'imdb_id',
        'runtime',
        'tagline',
        'status',
        'last_synced_at',
        'needs_detail_sync',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function screenings(): HasMany
    {
        return $this->hasMany(Screening::class);
    }
}
