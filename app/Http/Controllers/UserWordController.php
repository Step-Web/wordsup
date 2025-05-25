<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserWord;
use App\Models\WordList;
use cijic\phpMorphy\Morphy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\select;

class UserWordController extends Controller
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
        $w = $req->input('word');
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

        if(session()->get('user.words') >= session()->get('user.limit.words')) return response()->json(['error'=>'Ваш лимит на добавление новых слов исчерпан']);
        if(UserWord::where('word', $w)->where('group_id', $gID)->first()) return response()->json(['error'=>'Слово '.$w.' уже есть в группе']);
        $arr = DB::table('dictonary_en')->select('word','ts','translate','audio')->where('word', $w)->first();
        $translate = (!empty($translate))? $translate :$arr->translate??'';
        $data = UserWord::create([
            'word'=>$w,
            'user_id'=>$req->input('user_id'),
            'group_id'=>$gID,
            'ts'=>$arr->ts ?? '',
            'translate'=> $translate,
            'audio'=>$arr->audio ?? '',
        ]);
        $userGroup->updateTotal($gID,'words');
        return  response()->json($data);

    }

    public function findTranslate(string $word){

        $morphy = new Morphy('en');
        dd($morphy->findTranslate($word));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $group = UserGroup::find($id);
        return view('words.mygroup',['group'=>$group]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $req,string $id)
    {    $view = (!empty($req->query('modal')))?'words.modal.setWordTranslate':'words.modal.editWord';
        $word = UserWord::find($id);
        return view($view,['word'=>$word]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req, string $id)
    {
        $data = UserWord::find($id);
        $data->word = $req->input('word');
        $data->ts = $req->input('ts');
        $data->translate = $req->input('translate');
        $res = $data->save();
        return response()->json($res);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $userWord = new UserWord();
        $UserGroup = new UserGroup();
        $group = $userWord->find($id);
        $res = $userWord->destroy($id);
        $UserGroup->updateTotal($group->group_id,'words');
        return response()->json($res);
    }

      public function setProgressWord(int $id,int $progress){

          $data = UserWord::find($id);
          $data->progress = $progress;
          $res = $data->save();
          return response()->json($res);

      }

    public function resetProgress(Request $req){
        if($req->group_id){
            $res = UserWord::where('progress','>','0')->where('group_id',$req->group_id)->update(['progress' => 0]);
        } else {
            $res = UserWord::where('progress','>','0')->whereIn('id',$req->id)->update(['progress' => 0]);
        }
         return response()->json($res);
    }

    public function deleteMultiple(Request $req,UserGroup $userGroup){
        $res = UserWord::whereIn('id',$req->id)->delete();
         $userGroup->updateTotal($req->group_id,'words');
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



        $words_old = DB::table('userwords')->select('word')->where('group_id',$group_new)->get();

        if($act == 'cut'){
            $words_old = DB::table('userwords')->select('word')->where('group_id',$group_new)->get();
            $qw = DB::table('userwords')->select('id','word')->whereIn('id',$move)->get();
            foreach($qw AS $row){
              if(!$words_old->contains('word',$row->word)){
                  DB::table('userwords')->where('id', $row->id)->update(['group_id' => $group_new]);
                  $res++;
              } else {
                  DB::table('userwords')->where('id', $row->id)->delete();
              }
            }

        } elseif($act == 'copy'){

            $words_new = DB::table('userwords')->select('word','ts','translate','progress','audio')->whereIn('id',$move)->get();
            $user_id = session()->get('user.id');
            foreach($words_new AS $row) {
                $word = new UserWord();
                if (!$words_old->contains('word', $row->word)) {
                    $word->user_id = $user_id;
                    $word->group_id = $group_new;
                    $word->word = $row->word;
                    $word->ts = $row->ts;
                    $word->translate = $row->translate;
                    $word->progress = $row->progress;
                    $word->audio = $row->audio;
                    $word->save();
                  $res++;
                }
            }

// Массовая вставка

        }
        $userGroup->updateTotal($group_old,'words');
        $userGroup->updateTotal($group_new,'words');

        if(!empty($redictgroup)){
           $result['redirect'] = '/words/group/'.$group_new;
        }
        $result['status'] = $res;

        return response()->json($result);
    }
    public function formTransfer(string $act, string $group_id){
    //    if(auth()->check()){
       $user_id = session()->get('user.id');
        $groups = UserGroup::where([['user_id',$user_id],['type','words']])->get()->keyBy('id');
        return view('words.modal.formTransfer',['act'=>$act,'groups'=>$groups,'group_id'=>$group_id]);
     //  } else {
       //     return redirect()->route('login');
      //  }
    }


    public function addword(Request $req,int $word){

        $user_id = session()->get('user.id');
        $groups = UserGroup::where(['user_id'=>$user_id,'type'=>'words'])->get()->keyBy('id');
        if(!empty($req->get('type')) && $req->get('type') == 'wordgroup'){
            $aWord = WordList::find($word);
        } else {
            $aWord = UserWord::find($word);
        }

        return view('words.modal.formAddWord',['word'=>$aWord,'groups'=>$groups]);
    }





}
