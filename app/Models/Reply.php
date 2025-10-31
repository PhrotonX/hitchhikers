<?php

namespace App\Models;

use App\Models\Model;

class Reply extends Model{
    protected static $table = "replies";
    protected static $object = Reply::class;
    protected static $fillable = [
        'replied_review_id',
        'mentioned_account_id',
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
        'user_id',
        'ride_id',
    ];
}