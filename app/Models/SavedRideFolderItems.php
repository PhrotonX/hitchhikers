<?php

namespace App\Models;

use App\Models\AssociativeModel;

class SavedRideFolderItems extends AssociativeModel{
    protected static $table = "saved_ride_folder_items";
    protected static $object = SavedRideFolderItems::class;
    protected static $primary = ['saved_ride_id', 'saved_ride_folder_id'];
}