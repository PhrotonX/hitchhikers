<?php

namespace App\Models;

use App\Models\Model;

class Review extends Model{
    protected static $table = "reviews";
    protected static $object = Review::class;
    protected static $fillable = [
        'description',
        'rating',
        'created_at',
        'updated_at',
        'deleted_at',
        'user_id',
        'ride_id',
    ];
}