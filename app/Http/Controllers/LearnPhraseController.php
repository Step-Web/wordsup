<?php

namespace App\Http\Controllers;

use App\Models\SentenceEn;
use App\Models\Statistic;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserPhrase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class LearnPhraseController extends Controller
{
    public function getPhrases(Request $req,string $type) {



        $messege = '';
        $group_id = $req->input('group_id');
        $studymode = $req->input('studymode');

        if($type == 'group'){
            $group = UserGroup::find($group_id);
            if(empty($req->input('phrases'))){
                $items = UserPhrase::where('group_id',$group->id)->orderBy('progress')->get();

            } else {
                $words = explode(',',$req->input('phrases'));
                $items = UserPhrase::wherein('id',$words)->orderBy('progress')->get();
            }
        } elseif ($type == 'wordgroup'){
           // $group = WordGroup::find($group_id);
           // $items = WordList::select('id','phrase','tID','translate')->where('group_id',$group->id)->get();
        } elseif ($type == 'phrases'){
            $group = new SentenceEn();
            $words = explode(',',$req->input('words'));
            $items = $group->select('id','phrase','tID','translate')->whereIn('id',$words)->get();
            //  echo '<pre>'; print_r($words);echo '</pre>';exit();
            // echo '<pre>'; print_r($items->toArray());echo '</pre>';exit();
        } elseif ($type == 'errors'){
            $group = new SentenceEn();
            $array = explode('::',$req->input('items'));

            foreach ($array as $k=>$v) {
                $arr = explode('||',$v);
                $items[] = ['id'=>$k,'phrase'=>$arr[0],'translate'=>$arr[1],'audio'=>'0'];
            }
            $items = collect($items);
        }

        if($items){
            switch ($studymode) {
                case 'collatewords':
                    $phrases = $items->toArray();
                    $view = 'phrases.learn.collatewords';
                    break;
                case 'write':
                    $data = [];
                    $words = $items->toArray();
                    foreach ($words as $row) {
                        $arr = explode(',',trim($row['translate']));
                        $row['translate'] = trim($arr[0]);
                        $data[$row['id']] = $row;
                    }
                    $phrases = $data;
                    $view = 'phrases.learn.write';
                    break;
                case 'assemble':
                    $data = [];
                    $words = $items->toArray();
                    foreach ($words as $row) {
                        $arr = explode(',',trim($row['translate']));
                        $row['translate'] = trim($arr[0]);
                        $data[$row['id']] = $row;
                    }
                    $phrases = $data;
                    $view = 'phrases.learn.assemble';
                    break;
            }

        }
       // echo '<pre>'; print_r($phrases);echo '</pre>';
       // return '';
        return view($view,['phrases'=>$phrases])->with(['messege' => $messege]);
        //return  response()->json($group);
    }




    public function settingModal(Request $req) {

        $lang = (!empty($req->query('lang'))) ? $req->query('lang') : 'en';
        $act = $req->query('act');
        if($act == 'get'){
            $groups = DB::table('dictonary_'.$lang)->select('wgroup')->distinct()->limit(100)->get();
            $learngroups= (Cookie::get('learngroup'))?explode(',',Cookie::get('learngroups')):[];
            return view('words.modal.settingModal',['groups'=>$groups,'learngroups'=>$learngroups]);
        } elseif($act == 'set'){
            $groups = $req->query('groups');
            if(!empty($groups)){
                $res =  Cookie::queue('learngroups', $groups, 60*24*365);
            } else {
                $res = Cookie::queue('learngroups', '');
            }
            return 1;

        }
    }


    public function levels(Request $req) {
        $lang = (!empty($req->query('lang'))) ? $req->query('lang') : 'en';
        $group = (!empty($req->query('group'))) ? rtrim($req->query('group'),','):3;
        $aGroups = explode(',', $group);;
//dd($group);
        $cashlimit = (Cookie::get('cashlimit'))?Cookie::get('cashlimit'):25;
        $limit = (!empty($req->query('limit'))) ? $req->query('limit') :$cashlimit;
        $words = DB::table('dictonary_'.$lang)->inRandomOrder()->select('id','ts','audio','word','translate','wgroup')->whereIn('wgroup',$aGroups)->limit($limit)->get();
        Cookie::queue('cashlimit', $limit, 60*24*30);
        Cookie::queue('learngroups', $group, 60*24*365);
        if(empty($req->query('ajax'))){
            return view('words.levels',['words'=>$words,'group'=>$group,'limit'=>$limit]);
        } else {
            //$words->wgroup = rate($words->wgroup);
            return response()->json($words);
        }
    }




    public function random(){
        $words = DB::table('dictonary_en')->inRandomOrder()->select('id','ts','audio','word','translate','wgroup')->where([['ts','!=', ''],['wgroup','<=',20000],['is_public',1]])->limit(20)->get();
        return view('words.random',['words'=>$words,'group'=>0]);
    }

    public function saveUserStatistics(Request $req){

        $score = session()->get('user.score',0);
        $curscore = 0;
        if($req->input('myanwers') == NULL){ return false; }
        $myanwers = preg_replace('/<[^>]*>.*?<\/[^>]*>/', '', $req->input('myanwers')); // Удаляем теги с содержимым
        $aWords = explode(';;',$myanwers);

        $model = ($req->input('model'))?$req->input('model'):'';
        $cur_erorrs = [];
        $cur_correct = [];
        if($model == 'mygroup'){ // Если это группа принадлежит пользователю
            foreach ($aWords AS $arr){
                $a = explode('::',$arr);
                $word = UserPhrase::find($a[0]);
                if($a[1] == 1){ // Если верный ответ
                    $score = $score + 1;
                    $curscore = $curscore + 1;
                    $word->progress += ($word->progress != 4)? 1: 0;
                    $cur_correct[] = $a[2].'||'.$a[3];
                } else {
                    $word->progress -= ($word->progress != 0)? 1: 0;
                    $cur_erorrs[] = $a[2].'||'.$a[3];

                }
                $word->save(); // Сохраняем прогресс изучения слова
                $res[$word->id] = $word->progress;
            }

        } else {
            $progress = 0;
            foreach ($aWords AS $arr){
                $a = explode('::',$arr);
                if($a[1] == 1){ // Если верный ответ
                    $score = $score + 1;
                    $curscore = $curscore + 1;
                    $cur_correct[] = $a[2].'||'.$a[3];
                    $progress = 4;
                } else {
                    $progress = -1;
                    $cur_erorrs[] = $a[2].'||'.$a[3];
                }

                $res[$a[0]] = $progress;

            }

        }
        $errors = $this->setErrors($cur_erorrs,$cur_correct);


        session(['user.score'=>$score]);

        if(auth()->check()){
            $user_id = auth()->user()->id;
            User::where('id',$user_id)->update(['score'=>$score]);
            Statistic::setStatistic($user_id, $curscore,'phrase');
        }


        return response()->json($res);
    }


    public function setErrors($newError,$correct){
        //  Cookie::queue('wordsErrors', ''); return true;
        $myerrors = (Cookie::get('phrasesErrors'))?explode('::',Cookie::get('phrasesErrors')):[];

        if(!empty($correct) && !empty($myerrors)){
            $myerrors = array_diff($myerrors, $correct); // Удаляем из массива с ошибками корректные значения
            if(empty($myerrors)){
                Cookie::queue('phrasesErrors', ''); // если ошибок нет удаляем куку
            }
        }
        // echo '<pre>'; print_r($correct);echo '</pre>';
        if(!empty($newError)){
            foreach ($newError as $v) {
                if (!in_array($v, $myerrors)) {
                    // echo "НЕ Нашел ".$v."<br>";
                    array_unshift($myerrors , $v);
                }
            }
        }
        //  $errors = array_unique($errors);
        Cookie::queue('phrasesErrors', implode('::',$myerrors), 60*24);
        // echo Cookie::get('wordsErrors');
        return $myerrors;



    }







}
