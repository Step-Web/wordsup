<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WordGroup extends Model
{  protected $table = 'wordgroups';

    public function words()
    {

        return $this->hasMany(WordList::class, 'group_id', 'id');
    }




}
