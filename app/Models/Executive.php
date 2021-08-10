<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Executive extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'place_id',
    ];

    public function places(): HasOne
    {
        return $this->hasOne('places', 'place_id', 'id');
    }
}
