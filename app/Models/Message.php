<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /** @use HasFactory<\Database\Factories\MessageFactory> */
    use HasFactory, Auditable;

    protected $fillable = [
        'chat_content',
        'content_type',
        'driver_id',
        'passenger_id',
        'ride_request_id',
    ];
}
