<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    protected $table = 'usergroups';
     protected $fillable = ['id', 'user_id', 'lang', 'type', 'qty', 'name', 'color', 'owner_id', 'old_id', 'is_public'];

    public function words()
    {
        return $this->hasMany(UserWord::class, 'group_id','id')->orderBy('id','DESC');
    }
    public function phrases()
    {
        return $this->hasMany(UserPhrase::class, 'group_id','id')->orderBy('id','DESC');
    }

    public function updateTotal($group_id,$type='words'){

        if(auth()->check()){
            $user_id = auth()->user()->id;
            $user_qty = ($type == 'words')?UserWord::where('user_id', $user_id)->count():UserPhrase::where('user_id', $user_id)->count();
            User::where('id',$user_id)->update([$type => $user_qty]);
            session()->put('user.'.$type,$user_qty);
        }
        $qty = ($type == 'words')?UserWord::where('group_id', $group_id)->count():UserPhrase::where('group_id', $group_id)->count();
        return $this->where('id',$group_id)->update(['qty' => $qty]);
    }


}
