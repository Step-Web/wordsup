<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserWord;
use App\Models\WordGroup;
use Illuminate\Http\Request;

class UserGroupController extends Controller
{

    protected $colors = ['#2bcbba','#16a085','#2bb2eb','#0c70e2','#fed330','#f39c12','#d35400','#d52e34'];

    public function index(){
          $type =  request()->segment(1);
          $page['title'] = ($type == 'words')?'Группы слов':'Группы фраз';
          $groups = UserGroup::where('user_id',auth()->user()->id)->where('type',$type)->orderBy('id','DESC')->get();
          return view($type.'.groups',['page'=>$page,'groups'=>$groups]);
    }

    public function show(Request $req, int $id)
    {
       $group = UserGroup::findOrFail($id);
       $user = User::select('id', 'username', 'userpic', 'score')->find($group->user_id);
       $view = ($req->getRequestUri() != '/usergroup/' . $id) ? $group->type.'.mygroup' : $group->type.'.usergroup';
       return view($view, ['group' => $group, 'user' => $user]);
    }

    public function create(){
        $type =  request()->segment(1);
        return view($type.'.modal.formAddGroup',['user_id'=>auth()->user()->id,'colors'=>$this->colors,'type'=>$type]);
    }


     public function edit($id)
     {
         $group = UserGroup::find($id);
         return view($group->type.'.modal.formEditGroup',['group'=>$group,'user_id'=>auth()->user()->id,'colors'=>$this->colors]);
     }

    public function store(Request $req)
    {
        $page = new UserGroup();
        $page->name = $req->input('name');
        $page->color = $req->input('color');
        $page->user_id = $req->input('user_id');
        $page->type = $req->input('type');
        $page->save();
        return redirect('/'.$page->type.'/group')->with('status', 'Группа была добавлена');
    }
    public function copygroup(Request $req, int $id){
        $user_id = session('user.id');
        if($req->query('type') === 'usergroup') {
            $m = new UserGroup();
        } elseif($req->query('type') === 'wordgroup') {
            $m = new WordGroup();
            $owner_id  = 1;
            $color  = '#0b2242';
        } else{
            return false;
        }

        $group = $m->find($id);
      //  echo '<pre>'; print_r($group);echo '</pre>'; exit();
        $words = session('user.words');
        $newwords = sizeof($group->words)+$words;
        $wordslimit = session('user.limit.words');
        if($newwords > $wordslimit){
            return view('words.modal.confirmCopyGroup',['message'=>'Ваш лимит в '.$wordslimit.' слов не позволяет добавить эту группу! У вас уже '.$words.' слов в словаре.','btn_txt'=>'В мои группы','group_id'=>$id]);
        }
        if (UserGroup::where([['name','=',$group->name],['user_id','=',$user_id]])->first() && empty($req->get('confirm'))) {
            return view('words.modal.confirmCopyGroup',['message'=>'Группа с таким названием уже существует в вашем словаре','btn_txt'=>'В мои группы','group_id'=>$id]);
        }
        $newgroup = UserGroup::create([
            'user_id' => $user_id,
            'lang'=>$group->lang,
            'type'=>'words',
            'qty'=>0,
            'color'=>$color??'#caced1',
            'name'=>$group->name,
            'owner_id'=>$group->user_id??$owner_id,
            'old_id'=>$id
        ]);



       $group_id = $newgroup->id;
        $words=[];
        foreach($group->words AS $row){
            $words[] = array('user_id' => $user_id,
            'group_id'=>$group_id,
            'word'=>$row->word,
            'ts'=>$row->ts,
            'translate'=>$row->translate,
            'progress'=>0,
            'audio'=>$row->audio,
            'example'=>$row->example);
            }
        $res = UserWord::insert($words);
        $usergroup = new UserGroup();
        $usergroup->updateTotal($group_id,'words');
      //  dd($res);
        if($res){
            return view('words.modal.confirmCopyGroup',['message'=>'Группа добавлена в ваш словарь','btn_txt'=>'В группу','group_id'=>$group_id]);
        } else{
            return view('words.modal.confirmCopyGroup',['message'=>'Произошла ошибка при добавлении слов в словарь','btn_txt'=>'Попробовать ещё','group_id'=>$id]);
         }
    }


    public function update(Request $req, string $id)
    {
        $page = UserGroup::findOrFail($id);
        $page->name = $req->input('name');
        $page->color = $req->input('color');
        $page->user_id = $req->input('user_id');
        $page->type = $req->input('type');
        $page->save();
        return redirect('/'.$page->type.'/group')->with('status', 'Группа была обновлена');
    }

    public function destroy(Request $req, int $id){
        $type = $req->input('type');
        UserGroup::destroy($id);
        $group = new UserGroup();
        $group->updateTotal($id,'words');
        return redirect('/'.$type.'/group')->with('status', 'Группа была удалена');
    }

    public function confirmDeleteGroup($id){
        $type =  request()->segment(1);
        $group = UserGroup::findOrFail($id);
        return view($type.'.modal.confirmDeleteGroup',['group'=>$group]);
    }



}
