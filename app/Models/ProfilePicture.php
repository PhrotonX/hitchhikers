<?php

namespace App\Models;

use App\Models\Picture;
use App\Models\File;
use App\Models\FilePicture;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilePicture extends Model
{
    use HasFactory;
    use File;
    use FilePicture;

    protected $primaryKey = "pfp_id";
    protected $table = "profile_pictures";
    public $timestamps = false;
    protected $keyType = 'int';

    protected $fillable = [
        'picture_id',
        'pfp_xs',
        'pfp_small',
        'pfp_medium',
        'pfp_large',
    ];

    // Size suffix constants
    public const SIZE_LARGE_SUFFIX = '_l';
    public const SIZE_MEDIUM_SUFFIX = '_m';
    public const SIZE_SMALL_SUFFIX = '_s';
    public const SIZE_XS_SUFFIX = '_xs';

    /**
     * Define relationship to the base Picture model
     */
    public function picture(){
        return $this->belongsTo(Picture::class, 'picture_id', 'picture_id');
    }

    /**
     * Define relationship to users through pivot table
     */
    public function users(){
        return $this->belongsToMany(User::class, 'user_profile_picture', 'pfp_id', 'user_id');
    }

    /**
     * Check if a specific size is null or empty
     * 
     * @param string $size The size suffix to check (e.g., '_l', '_m', '_s', '_xs')
     * @return bool Returns true if the size is null or empty
     */
    public function isNull(string $size): bool
    {
        $field = match($size) {
            self::SIZE_LARGE_SUFFIX => 'pfp_large',
            self::SIZE_MEDIUM_SUFFIX => 'pfp_medium',
            self::SIZE_SMALL_SUFFIX => 'pfp_small',
            self::SIZE_XS_SUFFIX => 'pfp_xs',
            default => null
        };

        if (!$field) {
            return true;
        }

        return empty($this->$field) || $this->$field === null || strpos($this->$field, 'question_mark') !== false;
    }

    /**
     * Get the profile picture path for a specific size
     * 
     * @param string $size The size suffix
     * @return string|null The path or null if not found
     */
    public function getPath(string $size): ?string
    {
        $field = match($size) {
            self::SIZE_LARGE_SUFFIX => 'pfp_large',
            self::SIZE_MEDIUM_SUFFIX => 'pfp_medium',
            self::SIZE_SMALL_SUFFIX => 'pfp_small',
            self::SIZE_XS_SUFFIX => 'pfp_xs',
            default => null
        };

        return $field ? $this->$field : null;
    }
}
