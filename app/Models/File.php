<?php
namespace App\Models;

trait File{
    /**
     * Retrieves the extension of a filepath.
     * 
     * @param path The path of the file.
     * @param hasDot True if the file extension must return a dot followed by an extension, false otherwise.
     * 
     * @return extension The extension of the filepath of string type.
     */
    public function getFileExtension(string $path, bool $hasDot) : string{
        $parts = explode(".", $path);
        $extension = $parts[count($parts) - 1];

        if($hasDot){
            return "." . $extension;
        }else{
            return $extension;
        }
    }

    /**
     * Retrieves the filepath without an extension.
     * 
     * @param path The path of the file.
     * 
     * @return filepath The filepath without an extension of string type.
     */
    public function removeFileExtension(string $path) : string{
        $parts = explode(".", $path);
        $filepath = $parts[0];

        return $filepath;
    }
}