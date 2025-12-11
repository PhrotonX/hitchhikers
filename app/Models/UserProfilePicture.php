<?php

namespace App\Models;

use App\Models\User;
use App\Models\ProfilePicture;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * This model refers to the author of a profile picture.
 */
class UserProfilePicture extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "user_profile_picture";

    protected $fillable = [
        'user_id',
        'pfp_id',
    ];

    /**
     * Access a User model through an ID.
     * $account_pfp->account;
     */
    public function account(){
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Access a ProfilePicture model through an ID.
     * 
     * Usage: $account_pfp->picture;
     */
    public function profilePicture(){
        return $this->belongsTo(ProfilePicture::class, 'pfp_id');
    }
}
