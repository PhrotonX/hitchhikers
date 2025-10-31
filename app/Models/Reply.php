<?php

namespace App\Models;

use App\Models\Model;

class Reply extends Model{
    protected static $table = "replies";
    protected static $object = Reply::class;
    // protected static $fillable = [
    //     'description',
    // ];

    public ?int $mentioned_account_id;
    public string $description;
    public ?object $created_at;
    public ?object $updated_at;
    public ?object $deleted_at;
}