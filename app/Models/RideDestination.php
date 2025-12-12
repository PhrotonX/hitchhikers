<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RideDestination extends Model
{
    /** @use HasFactory<\Database\Factories\DestinationFactory> */
    use HasFactory, Auditable;

    public $timestamps = false;

    protected $fillable = [
        'longitude',
        'latitude',
        'order',
        'ride_id',
        'ride_address',
    ];
}
