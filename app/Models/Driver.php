<?php

namespace App\Models;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    /** @use HasFactory<\Database\Factories\DriverFactory> */
    use HasFactory;

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
     * @return array Returns an array of vehicles associated with the account.
     */
    public function getVehicles(){
        return Vehicle::where('driver_id', $this->id)->get();
    }
}
