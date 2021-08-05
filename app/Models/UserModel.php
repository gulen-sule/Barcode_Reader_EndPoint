<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    use HasFactory;
    //protected $fillable=[]; sadece doldurulabilecek alanlari belirtiyor
   // protected $guarded=[] doldurulmasini istemedigin columnlari belirtiyorsun
    protected $table = 'user_data';
    public $timestamps = false;

}
