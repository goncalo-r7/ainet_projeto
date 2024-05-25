<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name'
    ];

    public $timestamps = true;

    protected $primaryKey = 'code';

    public $incrementing = false;

    protected $keyType = 'string';

    public function genre(): HasMany
    {
        return $this->hasMany(Genre::class, 'genre_code', 'code');
    }
}

