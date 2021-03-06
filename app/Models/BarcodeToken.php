<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarcodeToken extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'expire_time',
        'valid_time',
        'barcode_token'
    ];
}
