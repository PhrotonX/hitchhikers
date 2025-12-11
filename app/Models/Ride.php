<?php

namespace App\Models;

use App\Models\Ride;
use App\Models\RideDestination;
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

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function getRideDestinations(){
        return RideDestination::where('ride_id', $this->id);
    }

    
}
