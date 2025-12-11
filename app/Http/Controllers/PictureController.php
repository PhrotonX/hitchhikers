<?php

namespace App\Http\Controllers;

use App\Models\Picture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

abstract class PictureController extends Controller
{
    public const STORAGE_DIRECTORY = 'storage/';
    public const IMAGE_DIRECTORY = 'img/';
    protected $directory = '';
    //protected $errorImage = 'res/img/question_mark.png';
    protected $type = '';

    public function __construct()
    {
        $this->directory = self::IMAGE_DIRECTORY;
    }

    public function add(){

    }

    public function store($files){
        // Handle both single file and array of files
        if (!is_array($files)) {
            $files = [$files];
        }

        $storedPictures = [];

        foreach ($files as $file) {
            if ($file) {
                $storedPictures[] = $this->storeSingleFile($file);
            }
        }

        return $storedPictures;
    }

    protected function storeSingleFile($file){
        //Create model object.
        $data = new Picture();
        $accountId = Auth::id();

        //Handle image
        if($file){
            //Add filename and filepath into the image
            $timestamp = date('YmdHis') . '_';
            $originalName = $file->getClientOriginalName();
            $filename = $timestamp . $originalName;

            //Move the image into the directory set initially.
            $storedPath = $file->storeAs($this->directory, $filename, 'public');
            
            // Don't add 'storage/' prefix - the file is in storage/app/public/
            // and will be accessed via public/storage/ symlink
            $filepath = $storedPath;

            //Put the filepath into the DB - use correct column name
            $data['picture_path'] = $filepath;
        }else{
            //Put the error image filepath into the DB
            $data['picture_path'] = Picture::obtainNullPicture();
        }

        //Set the type of the image such as pfp.
        $data['type'] = $this->type;

        $data->save();

        $this->onSaveToAssociativeTable($data);

        return $data;
    }

    public function view(){

    }

    abstract protected function onSaveToAssociativeTable(Picture $picture);
    abstract public function getPicturesJson($accountId);
}
