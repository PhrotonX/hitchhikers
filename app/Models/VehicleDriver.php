<?php

namespace App\Models;

use App\Model\Driver;
use App\Model\Vehicle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleDriver extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleDriverFactory> */
    use HasFactory;

    public $timestamps = false;

    public function driver(){
        return $this->belongsTo(Driver::class);
    }

    public function vehicle(){
        return $this->velongsTo(Vehicle::class);
    }
}
