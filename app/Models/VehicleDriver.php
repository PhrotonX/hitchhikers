<?php

namespace App\Models;

use App\Models\Driver;
use App\Models\Vehicle;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleDriver extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleDriverFactory> */
    use HasFactory, Auditable;

    public $timestamps = false;

    public function driver(){
        return $this->belongsTo(Driver::class);
    }

    public function vehicle(){
        return $this->belongsTo(Vehicle::class);
    }
}
