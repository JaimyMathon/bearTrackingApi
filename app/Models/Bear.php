<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bear extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'city', 'region', 'latitude', 'longitude'
    ];
}
