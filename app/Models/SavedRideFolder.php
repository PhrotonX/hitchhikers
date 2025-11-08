<?php

namespace App\Models;

use App\Models\Model;

class SavedRideFolder extends Model{
    protected static $table = "saved_ride_folders";
    protected static $object = SavedRideFolder::class;
    protected static $fillable = [
        'name',
        'color',
        'icon',
        'description',
        'user_id',
        'created_at',
        'updated_at',
    ];
}