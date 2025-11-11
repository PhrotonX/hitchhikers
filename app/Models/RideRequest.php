<?php

namespace App\Models;

use App\Models\Model;

class RideRequest extends Model{
    protected static $table = "ride_requests";
    protected static $object = RideRequest::class;
    protected static $fillable = [
        'sender_user_id',
        'ride_id',
        'destination_id',
        'latitude',
        'longitude',
        'message',
        'timestamps',
        'status_updated_at',
        'status',
    ];
}