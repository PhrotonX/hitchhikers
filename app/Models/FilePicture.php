<?php

namespace App\Models;

/**
 * This trait consists of supported file extensions for images.
 */
trait FilePicture{
    public static string $EXTENSION_JPG = "jpg";
    public static string $EXTENSION_JPEG = "jpeg";
    public static string $EXTENSION_JPE = "jpe";
    public static string $EXTENSION_JFIF = "jfif";
    public static string $EXTENSION_PNG = "png";
    public static string $EXTENSION_GIF = "gif";

    public static string $SIZE_L = "_l";
    public static string $SIZE_M = "_m";
    public static string $SIZE_S = "_s";
    public static string $SIZE_XS = "_xs";

    /**
     * Obtain a null picture if no picture can be found.
     */
    public static function obtainNullPicture(string $size = null) : string{
        return 'img/question_mark.png';
    }
}