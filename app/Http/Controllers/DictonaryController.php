<?php

namespace App\Http\Controllers;

use App\Models\DictonaryEn;
use App\Models\SentenceEn;
use App\Models\UserGroup;
use cijic\phpMorphy\Morphy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SSP;
use Yajra\DataTables\DataTables;

class DictonaryController extends Controller
{


    public function search(Request $req){

        $w = $req->get('word');
        $type = $req->get('type','words');
        if($type == 'sentences'){
            $res = DB::table('dictonary_en')->select('id','word','ts','translate','audio','wgroup','sentences')->where([['word', 'LIKE', '%'.$w.'%'],['sentences','>',0]])->orWhere('translate', 'LIKE', '%'.$w.'%')->orderBy('freq','DESC')->limit(20)->get();
        } else {
            $res = DB::table('dictonary_en')->select('id','word','ts','translate','audio','wgroup','sentences')->where('word', 'LIKE', '%'.$w.'%')->orWhere('translate', 'LIKE', '%'.$w.'%')->orderBy('freq','DESC')->limit(20)->get();
        }

        return $res;
    }

    public function translate(Request $req){
        $w = $req->get('word');
        $res = DB::table('dictonary_en')->select('id','word','ts','translate','audio','wgroup')->where('word', $w)->first();
        if(empty($res)){
            $morphy = new Morphy('en');
            $res = $morphy->findTranslate($w);
        }
       return view('words.modal.transalePanel',['word'=>$res]);

     //   return $res;
    }

    public function addword(Request $req, string $word){
        $wID = $req->get('id') ?? 0;
        $user_id = session()->get('user.id');
        $groups = UserGroup::where(['user_id'=>$user_id,'type'=>'words'])->get()->keyBy('id');
            if ($wID > 0) {
                $res = DB::table('dictonary_en')->select('id','word','ts','translate','audio')->where('id', $wID)->first();
            } else {
                $res = DB::table('dictonary_en')->select('id','word','ts','translate','audio')->where('word', $word)->first();
            }

       // dd($res);
        if(empty($res)){
            $morphy = new Morphy('en');
            $res = $morphy->findTranslate($word);
        }

        return view('words.modal.formAddWord',['word'=>$res,'groups'=>$groups]);
    }


    public function index(Request $req){
        $lang = $req->get('lang','en');
        return view('admin.dictonary.index',['table'=>'dictonary_'.$lang,'lang'=>$lang]);
    }


    public function edit(int $id){
        $word = DictonaryEn::find($id);
        return view('admin.dictonary.modal.edit',['word'=>$word]);
    }

    public function update(Request $req, int $id)
    {
        $data = DictonaryEn::find($id);
        $data->word = $req->input('word');
        $data->ts = $req->input('ts');
        $data->translate = rtrim($req->input('translate'),',');
        $data->freq = $req->input('freq');
        $data->wgroup = $req->input('wgroup');
        $res = $data->save();
        return response()->json($res);
    }



}
