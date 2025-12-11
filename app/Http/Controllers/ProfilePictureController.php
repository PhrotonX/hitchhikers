<?php

namespace App\Http\Controllers;

use App\Http\Controllers\PictureController;
use App\JSON\PictureJSON;
use App\Models\Picture;
use App\Models\ProfilePicture;
use App\Models\UserProfilePicture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProfilePictureController extends PictureController
{
    //private ?ProfilePicture $profilePicture;

    /**
     * Retrieves all the profile pictures
     * @param id The account id that is associated with the picture.
     */
    public static function getPictures($id) : array{
        $accountProfilePictures = UserProfilePicture::where('user_id', $id)->get();

        $pictures = [];

        foreach($accountProfilePictures as $accountProfilePicture){
            $pictures[] = ProfilePicture::where('pfp_id', $accountProfilePicture->pfp_id)->first();
        }

        return $pictures;
    }

    public function getPicturesJson($user){
        // Handle both User model and ID
        $accountId = is_object($user) ? $user->id : $user;
        
        // Check authorization - users can only view their own pictures
        if ((int)Auth::id() !== (int)$accountId) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 403);
        }
        
        $pictures = $this->getPictures($accountId);

        $json = null;
        foreach ($pictures as $key => $value) {
            if ($value && $value->picture) {
                $json[$key] = new PictureJSON;
                // Use the setPicture method which includes asset() function
                $json[$key]->setPicture($value->picture);
                // Override with profile-specific data
                $json[$key]->picture_id = $value->pfp_id; // Use profile picture ID instead of picture ID
                $json[$key]->src = 'storage/' . $value->pfp_medium; // Use medium size profile picture (relative to public/)
            }
        }

        return response()->json($json ?? []);
    }

    /**
     * Returns the profile picture if the picture data has been saved into the database.
     * 
     * This function is only valid after onSaveToAssociativeTable() has been invoked after calling
     * store() function.
     * 
     * @return ?ProfilePicture The saved profile picture object.
     */
    /*public function getProfilePicture(): ?ProfilePicture{
        return $this->profilePicture;
    }*/

    protected function onSaveToAssociativeTable(Picture $picture){
        //Obtain the account ID.
        $accountId = Auth::id();

        // echo("ProfilePictureController AccountID: " . $accountId);

        //Create a ProfilePicture object.
        $profilePicture = new ProfilePicture();

        //Set the profile picture foreign ID with the same ID as the picture.
        $profilePicture['picture_id'] = $picture->picture_id;

        //Shrink the profile picture before saving.
        $profilePicture = $this->onShrinkPicture($picture, $profilePicture);

        $profilePicture->save();

        //$this->$profilePicture = $profilePicture;

        // dump($profilePicture->toArray());

        //Create an associative entity named UserProfilePicture.
        $accountProfilePicture = new UserProfilePicture();

        //Set the data into the associative entity.
        $accountProfilePicture['user_id'] = $accountId;
        $accountProfilePicture['pfp_id'] = $profilePicture->pfp_id;

        $accountProfilePicture->save();

        $user = Auth::user();
        $user->profile_picture_id = $profilePicture->pfp_id;
        $user->save();
    }

    /**
     * Shrinks photos witht the following sizes:
     * Large: 360x360
     * Medium: 160x160
     * Small: 90x90
     * Extra Small: 32x32
     * 
     * The resulting images will be saved on the same folder where the original images were stored
     * with the size of images appended on the end of file name.
     * 
     * Supported file extensions are JPG, JPEG, JPE, JFIF, PNG, and GIF.
     * 
     * @param picture The picture entity. Is required to retrieve the filepath of the original image.
     * @param profilePicture The profile picture entity. Is required to save the filepath of the
     * shrunken image.
     * 
     * @return ProfilePicture An updated profile picture.
     */
    public function onShrinkPicture(Picture $picture, ProfilePicture &$profilePicture) : ProfilePicture{
        //Retrieve the file extension for checking.
        $extension = $picture->getFileExtension($picture->picture_path, false);

        $image = null;
        static $SIZES = ['_l', '_m', '_s', '_xs'];
        $size_count = count($SIZES);

        //An anonymous function that resizes and saves the images with high quality.
        $resize = function() use ($size_count, $SIZES, &$extension, &$image, &$picture, &$profilePicture){
            $resizedImage = null;
            
            // Define target sizes: Large: 360x360, Medium: 160x160, Small: 90x90, Extra Small: 32x32
            $targetSizes = [360, 160, 90, 32];

            for($i = 0; $i < $size_count; $i++){
                $targetSize = $targetSizes[$i];
                
                // Use high-quality bicubic interpolation for smooth resizing
                $resizedImage[$i] = imagescale($image, $targetSize, $targetSize, IMG_BICUBIC);
                
                // Enable antialiasing for even better quality
                imageantialias($resizedImage[$i], true);

                //Make a new filepath with the size of image appended on the end of the file name.
                $newFilePath = $picture->removeFileExtension($picture->picture_path) . $SIZES[$i] . '.' . $extension;
                
                // Get full filesystem path for saving
                $fullNewPath = storage_path('app/public/' . $newFilePath);
                
                // Ensure directory exists
                $directory = dirname($fullNewPath);
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }

                //Save the image with appropriate format and high quality.
                $this->saveImageWithQuality($resizedImage[$i], $fullNewPath, $extension);

                //Save the filepath of shrunken images to database.
                switch($SIZES[$i]){
                    case '_l':
                        $profilePicture->pfp_large = $newFilePath;
                        break;
                    case '_m':
                        $profilePicture->pfp_medium = $newFilePath;
                        break;
                    case '_s':
                        $profilePicture->pfp_small = $newFilePath;
                        break;
                    case '_xs':
                        $profilePicture->pfp_xs = $newFilePath;
                        break;
                }
                
                // Clean up memory
                imagedestroy($resizedImage[$i]);
            }
        };

        // Checks if the file type is compatible for image resizing and then invokes the anonymous
        // function resize().
        
        // Get the full filesystem path for image processing
        $fullPath = storage_path('app/public/' . $picture->picture_path);
        
        switch($extension){
            case ProfilePicture::$EXTENSION_JPG;
            case ProfilePicture::$EXTENSION_JPEG;
            case ProfilePicture::$EXTENSION_JPEG;
            case ProfilePicture::$EXTENSION_JFIF:
                $image = imagecreatefromjpeg($fullPath);
                if ($image !== false) {
                    $resize();
                    imagedestroy($image); // Clean up memory
                }
                break;
            case ProfilePicture::$EXTENSION_PNG:
                $image = imagecreatefrompng($fullPath);
                if ($image !== false) {
                    $resize();
                    imagedestroy($image); // Clean up memory
                }
                break;
            case ProfilePicture::$EXTENSION_GIF:
                $image = imagecreatefromgif($fullPath);
                if ($image !== false) {
                    $resize();
                    imagedestroy($image); // Clean up memory
                }
                break;
            default:
                // echo "File extension " . $extension . " is not supported. JPG, PNG, and GIF are only supported.";
                return $profilePicture; // Return the profilePicture instead of null to avoid issues
                break;
        }

        //dd($profilePicture->toArray());
        
        //Return the updated profile picture.
        return $profilePicture;
    }
    
    /**
     * Has to be authenticated before use.
     * Enforces single profile picture policy by deleting old picture before uploading new one.
     */
    public function store($user = null){
        $file = request()->file('profile_picture');

        if($file && $file->isValid()){
            Log::debug("ProfilePictureController::store() - File received: " . $file->getClientOriginalName());
            $accountId = Auth::id();
            
            // Delete existing profile picture to maintain single picture policy
            $user = Auth::user();
            if ($user->profile_picture_id) {
                try {
                    $this->delete($user, $user->profile_picture_id);
                    Log::debug("Deleted old profile picture: " . $user->profile_picture_id);
                } catch (\Exception $e) {
                    Log::warning("Failed to delete old profile picture: " . $e->getMessage());
                    // Continue with upload even if deletion fails
                }
            }

            $this->directory .= $accountId . "/pfp";
            $this->type = 'profile_picture';

            parent::store($file);

            Log::debug("ProfilePictureController stored");
            
            return redirect()->back()->with('success', 'Profile picture uploaded successfully!');
        }

        return redirect()->back()->with('error', 'No file was uploaded.');
    }

    /**
     * Save image with appropriate format and quality settings.
     * 
     * @param resource $image The image resource to save
     * @param string $filePath The file path where to save the image
     * @param string $extension The file extension/format
     */
    private function saveImageWithQuality($image, string $filePath, string $extension): void
    {
        switch(strtolower($extension)){
            case ProfilePicture::$EXTENSION_JPG:
            case ProfilePicture::$EXTENSION_JPEG:
            case ProfilePicture::$EXTENSION_JPE:
            case ProfilePicture::$EXTENSION_JFIF:
                // Save as JPEG with 90% quality (high quality, reasonable file size)
                imagejpeg($image, $filePath, 90);
                break;
            case ProfilePicture::$EXTENSION_PNG:
                // Save as PNG with compression level 6 (good balance of quality vs size)
                imagepng($image, $filePath, 6);
                break;
            case ProfilePicture::$EXTENSION_GIF:
                // Save as GIF (no quality option available)
                imagegif($image, $filePath);
                break;
            default:
                // Fallback to JPEG with high quality
                imagejpeg($image, $filePath, 90);
                break;
        }
    }

    /**
     * Delete images from the file system and the database (including the image datum from associative tables).
     * 
     * Currently, the website does not support handling of multiple profile pictures.
     * 
     * Only the authenticated uploader can delete their own profile pictures through this function.
     * 
     * @param user The user model instance
     * @param pfp_id The ID of the profile picture to be deleted.
     */
    public function delete($user, $pfp_id){
        // Handle both User model and ID
        $userId = is_object($user) ? $user->id : $user;
        
        // Check authorization
        if ((int)Auth::id() !== (int)$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized - you can only delete your own pictures'
            ], 403);
        }
        
        try {
            //Obtain the ProfilePicture datum.
            $profilePicture = ProfilePicture::where('pfp_id', $pfp_id)->first();
            Log::debug($profilePicture);

            //Check if the ProfilePicture is not null.
            if(!$profilePicture){
                return response()->json([
                    'success' => false,
                    'message' => 'Profile picture not found'
                ], 404);
            }

            //Obtain the UserProfilePicture datum associated with the obtained ProfilePicture.
            $userProfilePicture = UserProfilePicture::where('pfp_id', $pfp_id)->first();

            //Check if the authenticated user ID matches the associated user ID on the profile picture.
            if(!$userProfilePicture || $userProfilePicture->user_id != Auth::user()->id){
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this profile picture'
                ], 403);
            }

            //Delete the datum from associative table named UserProfilePicture first.

            //Remove the foreign key set on the user datum.
            if(Auth::user()->profile_picture_id == $userProfilePicture->pfp_id){
                Auth::user()->profile_picture_id = null;
                Auth::user()->save();
            }

            //Delete the UserProfilePicture datum from the database.
            if($userProfilePicture){
                //$userId = $userProfilePicture->id;
                UserProfilePicture::where('user_id', '=', $userProfilePicture->user_id, 'and')
                    ->where('pfp_id', '=', $userProfilePicture->pfp_id)->delete();
            }

            //Obtain the Picture datum based on a ProfilePicture foreign ID.
            $picture = Picture::where('picture_id', $profilePicture->picture_id)->first();

            //Delete the files from the file system.
            //There is probably a better way to write this.

            //Delete the file from Profile datum.
            if($picture && !$picture->isNull()){
                $files = glob($picture->picture_path);
                foreach($files as $file){
                    if(is_file($file)){
                        unlink($file);
                    }
                }
            }
            
            $files = [];
            //Delete the file from ProfilePicture datum.
            if(!$profilePicture->isNull(ProfilePicture::SIZE_LARGE_SUFFIX)) $files[0] = glob($profilePicture->pfp_large)[0];
            if(!$profilePicture->isNull(ProfilePicture::SIZE_MEDIUM_SUFFIX)) $files[1] = glob($profilePicture->pfp_medium)[0];
            if(!$profilePicture->isNull(ProfilePicture::SIZE_SMALL_SUFFIX)) $files[2] = glob($profilePicture->pfp_small)[0];
            if(!$profilePicture->isNull(ProfilePicture::SIZE_XS_SUFFIX)) $files[3] = glob($profilePicture->pfp_xs)[0];
            foreach($files as $file){
                if(is_file($file)){
                    unlink($file);
                }
            }

            $profilePicture->delete();

            if($picture){
                $picture->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile picture deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting profile picture: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the profile picture'
            ], 500);
        }
    }
}
