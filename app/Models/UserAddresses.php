<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddresses extends Model
{
    /** @use HasFactory<\Database\Factories\UserAddressesFactory> */
    use HasFactory, Auditable;
}
