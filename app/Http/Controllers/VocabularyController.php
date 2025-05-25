<?php

namespace App\Http\Controllers;

use App\Models\DictonaryEn;
use App\Models\Infopage;
use App\Models\Statistic;
use App\Models\User;
use App\Models\Vocabulary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VocabularyController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $req)
    {
        $lang = ($req->input('lang')) ?? 'en';
       if($lang == 'en'){
           $words = DB::table('dictonary_'.$lang)->inRandomOrder()->select('id','word','translate','audio','wgroup')->where('is_test', 1)->whereBetween ('wgroup',[1,10])->limit(10)->get();
           $words2 = DB::table('dictonary_'.$lang)->inRandomOrder()->select('id','word','translate','audio','wgroup')->where('is_test', 1)->whereBetween ('wgroup',[11,20])->limit(10)->get();
           $words3 = DB::table('dictonary_'.$lang)->inRandomOrder()->select('id','word','translate','audio','wgroup')->where('is_test', 1)->whereBetween ('wgroup',[21,30])->limit(10)->get();
           $words4 = DB::table('dictonary_'.$lang)->inRandomOrder()->select('id','word','translate','audio','wgroup')->where('is_test', 1)->whereBetween ('wgroup',[31,40])->limit(10)->get();
           $words5 = DB::table('dictonary_'.$lang)->inRandomOrder()->select('id','word','translate','audio','wgroup')->where('is_test', 1)->whereBetween ('wgroup',[41,50])->limit(10)->get();
           $words6 = DB::table('dictonary_'.$lang)->inRandomOrder()->select('id','word','translate','audio','wgroup')->where('is_test', 1)->whereBetween ('wgroup',[51,60])->limit(10)->get();
           $words7 = DB::table('dictonary_'.$lang)->inRandomOrder()->select('id','word','translate','audio','wgroup')->where('is_test', 1)->whereBetween ('wgroup',[61,70])->limit(10)->get();
           $words8 = DB::table('dictonary_'.$lang)->inRandomOrder()->select('id','word','translate','audio','wgroup')->where('is_test', 1)->whereBetween ('wgroup',[71,80])->limit(10)->get();
           $words9 = DB::table('dictonary_'.$lang)->inRandomOrder()->select('id','word','translate','audio','wgroup')->where('is_test', 1)->whereBetween ('wgroup',[81,90])->limit(10)->get();
           $words10 = DB::table('dictonary_'.$lang)->inRandomOrder()->select('id','word','translate','audio','wgroup')->where('is_test', 1)->whereBetween ('wgroup',[91,100])->limit(10)->get();
           $words->push(...$words2);
           $words->push(...$words3);
           $words->push(...$words4);
           $words->push(...$words5);
           $words->push(...$words6);
           $words->push(...$words7);
           $words->push(...$words8);
           $words->push(...$words9);
           $words->push(...$words10);
           $words = $words->sortBy('wgroup');
       } else {
        $words = [];
       }

        $page = collect();
        $page->mtitle = 'Тест на словарный запас '.$lang;
        $page->mdesc = 'Тест на словарный запас '.$lang;
        $page->title = 'Тест на словарный запас '.$lang;


      return view('vocabulary.index',['page'=>$page,'lang'=>$lang,'words'=>$words]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($data = array())
    {

     //echo '<pre>'; print_r($data);echo '</pre>';
     //$arr['correct_words'] = implode(',',array_keys($data['correct_words']));
     //echo '<pre>'; print_r($arr);echo '</pre>';

        $arr = array();
        $totalwords = 15000;
        $arr['lang'] = (!empty($data['lang']))?$data['lang']:'en';
        $arr['user_id'] = (!empty(session()->get('user.id')))?session()->get('user.id'):0;
        $arr['all_words'] = $data['all_words'];
        $arr['maxscore'] = $data['maxscore'];
        $arr['score'] = array_sum($data['correct_words']);
        $arr['correct_words'] = implode(',',array_keys($data['correct_words']));
        $percent = floor(($arr['score'] / $arr['maxscore']) * 100); // узнаем процент
        $arr['correct'] = sizeof($data['correct_words']);
        $arr['cheat'] = ($data['cheat'] > 0)?intval($data['cheat']):0;
        $reduction = ($arr['cheat'] > 0)?($arr['cheat']*5)+$data['time']:$data['time']; // коэффициент понижения
        $arr['vocabulary'] = floor($totalwords * ($percent / 100))-$reduction; // узнаем словарный запас
        $arr['time'] = date('i:s',$data['time']);

       if($arr['vocabulary']){
           $newscore = round($arr['score'] /200);
           $score = session()->get('user.score',0);
           session(['user.score'=>$score+$newscore]); // Добавляем баллы пользователю
        if($arr['user_id'] > 0){
            User::where('id',$arr['user_id'])->update(['score'=>$score]);
            Statistic::setStatistic($arr['user_id'], $newscore, 'vocabulary');
        }
      }
       // dd($data);
       //echo '<pre>'; print_r($arr);echo '</pre>'; exit();
        return $arr;






    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req){
       unset($_POST['_token']);
       $data = $this->create($_POST);
        if(!empty(session()->get('user.id'))){
        $page = new Vocabulary();
        if($data['vocabulary'] > 350){
        $page->lang = $data['lang'];
        $page->user_id = $data['user_id'];
        $page->all_words = $data['all_words'];
        $page->correct_words = $data['correct_words'];
        $page->maxscore = $data['maxscore'];
        $page->cheat = $data['cheat'];
        $page->time = $data['time'];
        $page->correct = $data['correct'];
        $page->score = $data['score'];
        $page->vocabulary = $data['vocabulary'];
        $page->save();
         $id = $page->id;
            session()->forget('vocabulary');
        } else {
            session(['vocabulary' => $data]);
            $id = 0;
        }
        } else {
            session(['vocabulary' => $data]);
            $id = 0;
        }
        return redirect('/test/vocabulary/'.$id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if(!empty(session('vocabulary'))){
           $data = session('vocabulary');
        } else{
            $data = Vocabulary::findOrFail($id)->toArray();
        }
        $ids = explode(',',$data['all_words']);
        $words = DB::table('dictonary_'.$data['lang'])->select('id','word','audio','wgroup')->whereIn('id', $ids)->orderBy('wgroup')->get();
        $morethen['all'] = Vocabulary::where('id', '!=', $id)->count();
        $morethen['better'] = Vocabulary::where([['id', '!=', $id],['score', '<',$data['score']]])->count();
        $morethen['percent'] = intval(($morethen['better']/$morethen['all'])*100);
        $data['oldtests'] = [];
        if($data['user_id'] > 0){
            $data['oldtests'] = Vocabulary::select('id','correct','vocabulary','score','correct','cheat','time','added')->where([['user_id', $data['user_id']]])->orderBy('added','DESC')->get();

        }

        return view('vocabulary.result',['data'=>$data,'morethen'=>$morethen,'words'=>$words]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       $res = Vocabulary::destroy($id);
       return $res;
    }
}
