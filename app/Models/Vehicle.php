<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleFactory> */
    use HasFactory;

    protected $fillable = [
        'plate_number',
        'vehicle_name',
        'vehicle_model',
        'vehicle_brand',
        'capacity',
        'coordinates',
        'color',
        'type',
        'latitude',
        'longitude',
    ];
}
