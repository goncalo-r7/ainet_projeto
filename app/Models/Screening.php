<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Screening extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'seat_id',
        'theater_id',
        'date',
        'start_time',
    ];

    public $timestamps = true;

    public function theater(): BelongsTo
    {
        return $this->belongsTo(Theater::class);
    }

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
