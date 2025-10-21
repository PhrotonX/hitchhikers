<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    /** @use HasFactory<\Database\Factories\RideFactory> */
    use HasFactory;

    protected $fillable = [
        'ride_name',
        'status',
        'fare_rate',
        'rating',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
