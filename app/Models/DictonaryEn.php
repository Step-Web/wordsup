<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DictonaryEn extends Model
{   public $timestamps = false;
    protected $table = 'dictonary_en';
    protected $fillable = ['word', 'user_id','group_id', 'ts','translate','audio','sentences'];
}
