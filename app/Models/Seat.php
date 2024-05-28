<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'theater_id',
        'row',
        'theater_id',
        'seat_number',
    ];

    public $timestamps = true;

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function theater(): BelongsTo
    {
        return $this->belongsTo(Theater::class);
    }

}
