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


    public function getFileNameAttribute()
    {
        return strtoupper(trim($this->poster_filename));
    }

    public function getImageExistsAttribute()
    {
        return Storage::exists("public/posters/{$this->poster_filename}");
    }

    public function getImageUrlAttribute()
    {
        if ($this->imageExists) {
            return asset("storage/posters/{$this->fileName}");
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
