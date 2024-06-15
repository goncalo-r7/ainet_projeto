<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
class Genre extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'code',
        'name'
    ];

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    protected $primaryKey = 'code';

    public $incrementing = false;

    protected $keyType = 'string';

    public function genre(): HasMany
    {
        return $this->hasMany(Genre::class, 'genre_code', 'code');
    }
}

