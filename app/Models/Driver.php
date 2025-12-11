<?php

namespace App\Models;

use App\Models\VehicleDriver;
use App\Models\Ride;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    /** @use HasFactory<\Database\Factories\DriverFactory> */
    use HasFactory, Auditable;

    protected $fillable = [
        'driver_account_name',
        'account_status',
        'company',
        'driver_type',
        'user_id',
    ];

    protected $guarded = [
        'id',
    ];

    /**
     * Returns the available rides of a driver.
     * @return Ride The available rides of a driver.
     */
    public function getRides(): ?Collection{
        return Ride::where('driver_id', $this->id)?->get() ?? null;
    }

    /**
     * @return boolean True if the driver has rides. False otherwise.
     */
    public function hasRides(): bool{
        return (count($this->getRides() ?? 0) > 0) ? true : false;
    }
}
