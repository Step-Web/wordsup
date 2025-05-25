<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPhrase extends Model
{    protected $table = 'userphrases';
    protected $fillable = ['user_id','group_id','tID','audio','phrase','translate','progress'];

}
