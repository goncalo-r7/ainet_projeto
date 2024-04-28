<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    use HasFactory;

    protected $fillable = [
        'course',
        'year',
        'semester',
        'abbreviation',
        'name',
        'name_pt',
        'ECTS',
        'hours',
        'optional',
    ];

    public $timestamps = false;
}
