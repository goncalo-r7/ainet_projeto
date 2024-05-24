<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Movie extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'gender_code',
        'year',
        'poster_filename',
        'synopsis',
        'trailer_url',
    ];

    public $timestamps = true;

    // nao esta corrigido em baixo

    public function getTrailerFullUrlAttribute()
    {
        if ($this->photo_url && Storage::exists("public/photos/{$this->trailer_url}")) {
            return asset("storage/photos/{$this->photo_url}");
        } else {
            return asset("storage/photos/blankTrailer.png"); //cuidado com este nome!!
        }
    }

    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class, 'genre_code', 'code');
    }

    public function screenings(): HasMany
    {
        return $this->hasMany(Screening::class);
    }

}
