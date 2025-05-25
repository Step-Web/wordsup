<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWord extends Model
{
    protected $table = 'userwords';
    protected $fillable = ['word', 'user_id','group_id', 'ts','translate','audio'];




}
