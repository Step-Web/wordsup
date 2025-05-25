<?php

namespace App\Http\Controllers;

use App\Models\Statistic;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller

{
    protected $levels = ['beginner'=>'Начинающий','elementary'=>'Ниже среднего','intermediate'=>'Средний','upper-intermediate'=>'Выше среднего','advanced'=>'Продвинутый'];

    public function index(){
        if(auth()->check()){
        $user = User::find(auth()->user()->id);
        $user->mtitle = $user->name;
        return view('user.userhome', ['user' => $user]);
        } else {
        return redirect('/login');
        }
    }

    public function show($id){

        if (is_numeric($id)){
            $user = User::findOrFail($id);
        } else {
            $user = User::where('username', $id)->firstOrFail();
        }
        $view =(isset(auth()->user()->id) && ($user->id == session()->get('user.id')))?'user.myprofile':'user.profile';
        $user->mtitle = $user->username;
        $user->position = Statistic::userPosition($user->score);
        return view($view, ['user' => $user,'levels' => $this->levels]);
    }

    public function edit(int $id){
        $this->checkAccess($id);
        $user = User::find($id);
        return view('user.edit', ['user' => $user,'levels' => $this->levels]);
    }



    public function update(Request $req)
    {  $id = $req->input('id');
        $this->checkAccess($id);
            $base24 = $req->input('imagebase24');
            $user = User::find($id);
        if(!empty($base24)){
            $patch = 'user';
            $img = storeImage($base24,$patch,$id);
            $user->userpic = $img;
            session()->put('user.userpic', $user->userpic);
        }

        $user->name = $req->input('name');
        $user->surname = $req->input('surname');
        $user->email = $req->input('email');
        $user->age = ($req->input('age')) ?? NULL;
        $user->city = ($req->input('city')) ?? NULL;
        $user->level = $req->input('level');
        $user->save();

        return redirect('/user/'.mb_strtolower($user->username,'UTF-8'))->with('status', 'Данные были обновлены');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        if(auth()->check() && auth()->user()->id != $id) return redirect('/user/'.auth()->user()->id);
        $user = User::find($id);
        $file = mb_substr($user->userpic, 9); // обрезаем /storage/
        Storage::disk('public')->delete($file);
        UserGroup::where('user_id', $id)->each(function($item) {
            $item->delete();
        });
        User::destroy($id);


        return redirect('/user')->with('status', 'Пользователь был удален');
    }




public function confirmDestroyUser(int $id)
{     $user = User::find($id);

  // if(auth()->check() && auth()->user()->id != $id) {

  //   } else {

  //  }
    return view('user.confirmDeleteUser', ['user' => $user]);
}

public function deleteImage($id){
    $res = 0;
    $user = User::find($id);
    $file = mb_substr($user->userpic, 9); // обрезаем /storage/
    $del = Storage::disk('public')->delete($file);
   if($del){
       $user->userpic = NULL;
       $user->save();
       $res = $user->id;
       session()->put('user.userpic', '/storage/images/user/noimg.svg');
   }
    return $res;
}

    public function checkAccess($id) {
        $user_id = auth()->user()->id ?? 0;
        if($user_id > 0 && $id == $user_id) {
            return true;
        } else {
           // return view('auth.login');
            dd('Доступ запрещён');
        }   }


    public function errors(string $type='words'){
        $items = [];
        $cookies['words'] = (Cookie::get('wordsErrors'))?explode('::',Cookie::get('wordsErrors')):[];
        $cookies['phrases'] = (Cookie::get('phrasesErrors'))?explode('::',Cookie::get('phrasesErrors')):[];
        if($type == 'words'){

           foreach($cookies['words'] as $v){
               $w = explode('||',$v);
               $items[$w[0]] =$w[1];
           }
           // $error['words'] = sizeof($items);
            $view = 'words.errors';

        } elseif($type == 'phrases') {
            foreach($cookies['phrases'] as $v){
                $w = explode('||',$v);
                $items[$w[0]] =$w[1];
            }
            $view = 'phrases.errors';
        }
        $error['words'] = sizeof($cookies['words']);
        $error['phrases'] =  sizeof($cookies['phrases']);
        return view($view, ['items' => $items,'error' => $error,'cookies' => $cookies]);

    }



    public function clearErorrs(string $word,string $type){
      $key = ($type == 'words')?'wordsErrors':'phrasesErrors';
        if($word !== 'clearAll'){
            $olderrors = (Cookie::get($key))?explode('::',Cookie::get($key)):[];
            $myerrors = array_diff($olderrors, array($word));
            Cookie::queue($key, implode('::',$myerrors), 60*24);
            $t = sizeof($myerrors);
        } else {
            Cookie::queue($key, '');
            $t = 0;
        }
        return $t;
    }



}


