<?php

namespace App\Models;

use App\Models\Model;

class SavedRide extends Model{
    protected static $table = "saved_rides";
    protected static $object = SavedRide::class;
    protected static $fillable = [
        'ride_id',
        'saved_ride_folder_id',
        'user_id',
        'notes',
        'created_at',
        'updated_at',
    ];
}