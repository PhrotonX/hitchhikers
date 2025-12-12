<?php

namespace App\JSON;

use App\Models\Picture;

class PictureJSON
{
    public $picture_id;
    public $src;
    public $type;
    public $title;
    public $description;
    public $alt_text;

    /**
     * Set picture data from a Picture model instance
     * 
     * @param Picture $picture The picture model instance
     */
    public function setPicture(Picture $picture)
    {
        $this->picture_id = $picture->picture_id;
        $this->src = asset($picture->picture_path);
        $this->type = $picture->type;
        $this->title = $picture->title;
        $this->description = $picture->description;
        $this->alt_text = $picture->alt_text;
    }

    /**
     * Convert to array for JSON serialization
     */
    public function toArray(): array
    {
        return [
            'picture_id' => $this->picture_id,
            'src' => $this->src,
            'type' => $this->type,
            'title' => $this->title,
            'description' => $this->description,
            'alt_text' => $this->alt_text,
        ];
    }
}
