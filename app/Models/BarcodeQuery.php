<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarcodeQuery extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'user_uuid',
        'uuid',
        'expire_time',
        'valid_time',
        'controller_uuid',
        'place_uuid'
    ];
}
