<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movie extends Model
{
    use SoftDeletes;

    use HasFactory;
    protected $fillable = [
        'title',
        'genre_code',
        'year',
        'synopsis',
        'trailer_url',
       'photo_filename',
    ];

    public $timestamps = true;




    public function getImageExistsAttribute()
    {

        return Storage::exists("public/posters/{$this->poster_filename}");
    }

    public function getImageUrlAttribute()
    {
        if ($this->poster_filename && $this->imageExists) {
            return asset("storage/posters/{$this->poster_filename}");
        } else {
            return asset("storage/posters/_no_poster_1.png");
        }
    }

    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class, 'genre_code', 'code');
    }

    public function screeningsRef(): HasMany
    {
        return $this->hasMany(Screening::class);
    }



}
