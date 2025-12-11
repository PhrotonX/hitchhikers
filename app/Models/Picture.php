<?php

namespace App\Models;
use App\Models\File;
use App\Models\FilePicture;
use App\Models\HasNullPicture;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Picture extends Model
{
    # Traits
    use HasFactory;
    use File;
    use FilePicture;
    use HasNullPicture;
    use Auditable;

    protected $primaryKey = "picture_id";
    protected $table = "pictures";
    protected $keyType = 'int';

    protected $fillable = [
        'picture_path',
        'type',
        'title',
        'description',
        'alt_text',
    ];

    /**
     * Checks if the current picture path is null or is set to question mark image.
     * 
     * @return bool Returns true is picture_path is null. False otherwise.
     */
    public function isNull() : bool{
        if(($this->picture_path == null) || ($this->picture_path == "") || ($this->picture_path == "../img/question_mark.png"))
            return true;
        return false;
    }

    
}
