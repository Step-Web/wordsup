<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    public $table = 'pages';

    public function allSection(){
        $collection =  Section::all('id','title','url');
        return $collection->keyBy('id');
    }

    public function curSection(){
        return  Section::where('id',$this->section_id)->first();
    }
}
