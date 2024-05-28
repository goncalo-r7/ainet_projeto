<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'screening_id',
        'seat_id',
        'purchase_id',
        'price',
        'qrcode_url',
        'nif',
        'status',
    ];

    public $timestamps = true;

    public function screening(): BelongsTo
    {
        return $this->belongsTo(Screening::class);
    }
    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }
    /* # talvez ??
    public function user()
    {
        return $this->belongsTo(User::class);
    } */



    public function getQrcodeFullUrlAttribute()
    {
        if ($this->qrcode_url && Storage::exists("public/photos/{$this->qrcode_url}")) {
            return asset("storage/photos/{$this->photo_url}");
        } else {
            return asset("storage/photos/blankTrailer.png"); //cuidado com este nome!!
        }
    }
}
