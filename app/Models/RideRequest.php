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
        'from_latitude',
        'from_longitude',
        'to_latitude',
        'to_longitude',
        'pickup_at',
        'message',
        'created_at',
        'updated_at',
        'time',
        'status_updated_at',
        'status',
        'price',
        'profit',
    ];
}