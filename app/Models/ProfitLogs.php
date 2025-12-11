<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfitLogs extends Model
{
    /** @use HasFactory<\Database\Factories\ProfitLogsFactory> */
    use HasFactory, Auditable;

    protected $table = "profit_logs";

    protected $fillable = [
        'driver_id',
        'ride_id',
        'ride_request_id',
        'from_latitude',
        'from_longitude',
        'from_address',
        'to_latitude',
        'to_longitude',
        'to_address',
        'profit',
    ];
}
