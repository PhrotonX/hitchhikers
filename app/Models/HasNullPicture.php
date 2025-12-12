<?php

namespace App\Models;

/**
 * This trait is kept for backward compatibility but doesn't add new methods.
 * The obtainNullPicture method is provided by FilePicture trait.
 */
trait HasNullPicture
{
    // This trait is intentionally empty to avoid collision with FilePicture::obtainNullPicture
    // The obtainNullPicture method is already provided by the FilePicture trait
}
