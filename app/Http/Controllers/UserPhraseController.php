<?php

namespace App\Http\Controllers;

use App\Models\SentenceEn;
use App\Models\UserGroup;
use App\Models\UserPhrase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserPhraseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req,UserGroup $userGroup)
    {
        $w = $req->input('phrase');
        $translate = rtrim($req->input('translate'),',');


        if($req->input('group_id')){
            $gID = $req->input('group_id');
        } else {
            $page = new UserGroup();
            $page->name = $req->input('name');
            $page->user_id = $req->input('user_id');
            $page->save();
            $gID = $page->id;
        }

        if(session()->get('user.phrases') >= session()->get('user.limit.phrases')) return response()->json(['error'=>'Ваш лимит на добавление новых фраз исчерпан']);
        if(UserPhrase::where('phrase', $w)->where('group_id', $gID)->first()) return response()->json(['error'=>'Фраза '.$w.' уже есть в группе']);
        $arr = DB::table('sentences_en')->select('phrase','translate','tID')->where('phrase', $w)->first();
        $translate = (!empty($translate))? $translate :$arr->translate??'';
        $data = UserPhrase::create([
            'phrase'=>$w,
            'user_id'=>$req->input('user_id'),
            'group_id'=>$gID,
            'translate'=> $translate,
            'tID'=>$arr->tID ?? 0,
            'audio'=>$arr->audio ?? 0,
        ]);
        $userGroup->updateTotal($gID,'phrases');
        return  response()->json($data);

    }

    public function addPhraseByID(Request $req,int $id,UserGroup $userGroup)
    {
        $translate = rtrim($req->input('translate'),',');
        if($req->input('group_id') > 0){
            $gID = $req->input('group_id');
        } else {
            $page = new UserGroup();
            $page->name = $req->input('name');
            $page->user_id = $req->input('user_id');
            $page->type = $req->input('type');
            $page->save();
            $gID = $page->id;
        }

        if(session()->get('user.phrases') >= session()->get('user.limit.phrases')) return response()->json(['error'=>'Ваш лимит на добавление новых фраз исчерпан']);
        $arr = DB::table('sentences_en')->select('phrase','translate','tID')->where('id', $id)->first();
        if(UserPhrase::where('phrase', $arr->phrase)->where('group_id', $gID)->first()) return response()->json(['error'=>'Фраза уже есть в группе']);
        $translate = (!empty($translate))? $translate :$arr->translate??'';
        $data = UserPhrase::create([
            'phrase'=>$arr->phrase,
            'user_id'=>session()->get('user.id'),
            'group_id'=>$gID,
            'translate'=> $translate,
            'tID'=> ($arr->tID > 0) ? $arr->tID:'s'.$id,
            'audio'=> 1
        ]);
         $userGroup->updateTotal($gID,'phrases');

        return  response()->json($data);

    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $req, string $id)
    {    $headTxt = (empty($req->query('act')))?'Изменить':'Добавить';
        $phrase = UserPhrase::find($id);
        return view('phrases.modal.editPhrase',['item'=>$phrase,'headTxt'=>$headTxt]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req, string $id)
    {
        $data = UserPhrase::find($id);
        $data->phrase = $req->input('phrase');
        $data->translate = $req->input('translate');
        $res = $data->save();
        return response()->json($res);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $userPhrase = new UserPhrase();
        $UserGroup = new UserGroup();
        $group = $userPhrase->find($id);
        $res = $userPhrase->destroy($id);
        $UserGroup->updateTotal($group->group_id,'phrases');
        return response()->json($res);
    }

    public function addphrase(Request $req,int $id){

        $user_id = session()->get('user.id');
        $groups = UserGroup::where(['user_id'=>$user_id,'type'=>'phrases',])->get()->keyBy('id');
        $phrase = SentenceEn::find($id);
        return view('phrases.modal.formAddPhrase',['phrase'=>$phrase,'groups'=>$groups]);
    }


    public function setProgressWord(int $id,int $progress){

        $data = UserPhrase::find($id);
        $data->progress = $progress;
        $res = $data->save();
        return response()->json($res);

    }

    public function resetProgress(Request $req){
        if($req->group_id){
            $res = UserPhrase::where('progress','>','0')->where('group_id',$req->group_id)->update(['progress' => 0]);
        } else {
            $res = UserPhrase::where('progress','>','0')->whereIn('id',$req->id)->update(['progress' => 0]);
        }
        return response()->json($res);
    }

    public function deleteMultiple(Request $req){
        $userGroup = new UserGroup();
        $res = UserPhrase::whereIn('id',$req->id)->delete();
        $userGroup->updateTotal($req->group_id,'phrases');
        return $res;
    }

    public function transferWords(Request $req,UserGroup $userGroup){

        $res = 0;
        $result = [];
        $redictgroup = $req->input('redictgroup');
        $group_new = $req->input('group_new');
        $group_old = $req->input('group_old');
        $move = explode(',',$req->input('move'));
        $act = $req->input('act');



        $words_old = DB::table('userphrases')->select('phrase')->where('group_id',$group_new)->get();

        if($act == 'cut'){
            $words_old = DB::table('userphrases')->select('phrase')->where('group_id',$group_new)->get();
            $qw = DB::table('userphrases')->select('id','phrase')->whereIn('id',$move)->get();
            foreach($qw AS $row){
                if(!$words_old->contains('phrase',$row->phrase)){
                    DB::table('userphrases')->where('id', $row->id)->update(['group_id' => $group_new]);
                    $res++;
                } else {
                    DB::table('userphrases')->where('id', $row->id)->delete();
                }
            }

        } elseif($act == 'copy'){

            $words_new = DB::table('userphrases')->whereIn('id',$move)->get();
            $user_id = session()->get('user.id');
            foreach($words_new AS $row) {
                $word = new UserPhrase();
                if (!$words_old->contains('phrase', $row->phrase)) {
                    $word->user_id = $user_id;
                    $word->group_id = $group_new;
                    $word->phrase = $row->phrase;
                    $word->tID = $row->tID;
                    $word->audio = $row->audio;
                    $word->translate = $row->translate;
                    $word->progress = $row->progress;
                    $word->save();
                    $res++;
                }
            }

        }
        $userGroup->updateTotal($group_old,'phrases');
        $userGroup->updateTotal($group_new,'phrases');

        if(!empty($redictgroup)){
            $result['redirect'] = '/phrases/group/'.$group_new;
        }
        $result['status'] = $res;
 //echo '<pre>'; print_r($result);echo '</pre>';
        return response()->json($result);
    }
    public function formTransfer(string $act, string $group_id){
        //    if(auth()->check()){
        $user_id = session()->get('user.id');
        $groups = UserGroup::where([['user_id',$user_id],['type','phrases']])->get()->keyBy('id');
        return view('phrases.modal.formTransfer',['act'=>$act,'groups'=>$groups,'group_id'=>$group_id]);
        //  } else {
        //     return redirect()->route('login');
        //  }
    }




}
