<?php

namespace App\Http\Controllers;

use App\Http\Resources\Words\LearnWordResource;
use App\Models\DictonaryEn;
use App\Models\Statistic;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserWord;
use App\Models\WordGroup;
use App\Models\WordList;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\error;

class LearnWordController extends Controller
{
    public function getUserWords(Request $req,string $type) {


if($type == 'group'){
    $messege = '';
        $group_id = $req->input('group_id');
        $studymode = $req->input('studymode');
         $group = UserGroup::find($group_id);

        if(empty($req->input('words'))){
              $items = UserWord::where('group_id',$group->id)->orderBy('progress')->get();
        } else {
            $words = explode(',',$req->input('words'));
            $items = UserWord::wherein('id',$words)->orderBy('progress')->get();

          //  $fields = LearnWordResource::collection($items);
        }


    switch ($studymode) {
        case 'translate':
            $group->words = $this->setVariants($items,$studymode);
            $view = 'words.learn.translate';
            //if(sizeof($items) < 5) $messege ='Для изучения необходимо не менее 5 слов';
            break;
        case 'reverse':
            $group->words = $this->setVariants($items,$studymode);
            $view = 'words.learn.reverse';
            if(sizeof($items) < 5) $messege ='Для изучения необходимо не менее 5 слов';
            break;
        case 'write':
            $data = [];
            $words = $items->toArray();
            foreach ($words as $row) {
                $arr = explode(',',trim($row['translate']));
                $row['translate'] = trim($arr[0]);
                $data[$row['id']] = $row;
            }
            $group->words = $data;
            $view = 'words.learn.write';
            break;
        case 'assemble':
            $data = [];
            $words = $items->toArray();
            foreach ($words as $row) {
                $arr = explode(',',trim($row['translate']));
                $row['translate'] = trim($arr[0]);
                $data[$row['id']] = $row;
            }
            $group->words = $data;
            $view = 'words.learn.assemble';
            break;
        case 'sprint':
            $group->words = $items->toArray();;
            $view = 'words.learn.sprint';
            if(sizeof($items) < 3) $messege ='Для изучения необходимо не менее 3 слов';
            break;
    }



 //return $group;
        // echo '<pre>'; print_r($row);echo '</pre>';
    }
       // if(!auth()->check()) $messege ='Похоже ваша сессия истекла, попробуйте обновить страницу';
     return view($view,['group'=>$group])->with(['messege' => $messege]);
     //return  response()->json($group);
    }

    public function getWords(Request $req,string $type) {



            $messege = '';
            $group_id = $req->input('group_id');
            $studymode = $req->input('studymode');
            if($type == 'group' || $type == 'usergroup'){
                $group = UserGroup::find($group_id);
                if(empty($req->input('words'))){
                    $items = UserWord::where('group_id',$group->id)->orderBy('progress')->get();
                } else {
                    $words = explode(',',$req->input('words'));
                    $items = UserWord::wherein('id',$words)->orderBy('progress')->get();
                }
            } elseif ($type == 'wordgroup'){
                $group = WordGroup::find($group_id);
                $items = WordList::select('id','word','ts','translate','audio')->where('group_id',$group->id)->get();
            } elseif ($type == 'words'){
                $group = new DictonaryEn();
                $words = explode(',',$req->input('words'));
                $items = $group->select('id','word','ts','translate','audio')->whereIn('id',$words)->get();
            } elseif ($type == 'errors'){
                $group = new DictonaryEn();
                $array = explode('::',$req->input('items'));

                foreach ($array as $k=>$v) {
                    $arr = explode('||',$v);
                    $items[] = ['id'=>$k,'word'=>$arr[0],'translate'=>$arr[1],'audio'=>''];
                }
             $items = collect($items);
            }
         //   echo '<pre>'; print_r($items);echo '</pre>';
         // return '';

if($items){
            switch ($studymode) {
                case 'translate':
                    if(count($items) < 4) {  return '<div class="slider text-danger fs-5">Не достаточно слов для выполнения теста в этом режиме</div>';}
                    $group->words = $this->setVariants($items,$studymode,4);
                    $view = 'words.learn.translate';
                    break;
                case 'reverse':
                    if(count($items) < 4) {  return '<div class="slider text-danger fs-5">Не достаточно слов для выполнения теста в этом режиме</div>';}
                    $group->words = $this->setVariants($items,$studymode,4);
                    $view = 'words.learn.reverse';
                    break;
                case 'write':
                    $data = [];
                    $words = $items->toArray();
                    foreach ($words as $row) {
                        $arr = explode(',',trim($row['translate']));
                        $row['translate'] = trim($arr[0]);
                        $data[$row['id']] = $row;
                    }
                    $group->words = $data;
                    $view = 'words.learn.write';
                    break;
                case 'assemble':
                    $data = [];
                    $words = $items->toArray();
                    foreach ($words as $row) {
                        $arr = explode(',',trim($row['translate']));
                        $row['translate'] = trim($arr[0]);
                        $data[$row['id']] = $row;
                    }
                    $group->words = $data;
                    $view = 'words.learn.assemble';
                    break;
                case 'sprint':
                    $group->words = $items->toArray();;
                    $view = 'words.learn.sprint';
                //    if(sizeof($items) < 3) $messege ='Для изучения необходимо не менее 3 слов';
                    break;
            }

}


//echo '<pre>'; print_r($group->words);echo '</pre>'; return '';
       // if(!auth()->check()) $messege ='Похоже ваша сессия истекла, попробуйте обновить страницу';
        return view($view,['group'=>$group])->with(['messege' => $messege]);
        //return  response()->json($group);
    }



    function setVariants($words, string $direction = 'translate', int $optionsCount = 4, int $questionCount = 0): array
    {  $words = $words->toArray();
         // echo '<pre>'; print_r($words);echo '</pre>';
        // Проверяем, достаточно ли слов для создания теста

            $questionCount = ($questionCount > 0) ? $questionCount : sizeof($words); // сколько выводить слов в тесте
            foreach ($words as $k=>$v) {
                $words[$k]['translate'] = current(explode(',', trim($v['translate'])));
            }
        //    $words->each(function($item){  return $item->translate = current(explode(',', trim($item->translate)));   });
           // $words = $words->toArray();
        // Определяем поля в зависимости от направления
        $questionField = $direction === 'translate' ? 'word' : 'translate';
        $answerField = $direction === 'translate' ? 'translate' : 'word';
        // Выбираем случайные слова для вопросов
        $questions = Arr::random($words, $questionCount);

        // Собираем все возможные переводы для создания вариантов ответов
        $allTranslations = array_column($words, $answerField);

        $res = [];
        foreach ($questions as $question) {
            // Создаем массив вариантов ответов, начиная с правильного
            $options = [$question[$answerField]];

            // Добавляем случайные неправильные варианты
            while (count($options) < $optionsCount) {
                $randomTranslation = Arr::random($allTranslations);

                // Убедимся, что вариант еще не добавлен и не совпадает с правильным ответом
                if (!in_array($randomTranslation, $options) && $randomTranslation !== $question[$answerField]) {
                    $options[] = $randomTranslation;
                }
            }

            // Перемешиваем варианты ответов
            shuffle($options);

            $res[$question['id']] = [
                'id' => $question['id'],
                'audio' => $question['audio'],
                'word' => $question[$questionField],
                'translate' => $question[$answerField],
                'variants' => $options,
            ];
        }

        return $res;
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
                $word = UserWord::find($a[0]);
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

        $this->setErrors($cur_erorrs,$cur_correct);

       // return '';
        session(['user.score'=>$score]);

        if(auth()->check()){
            $user_id = auth()->user()->id;
            User::where('id',$user_id)->update(['score'=>$score]);
            Statistic::setStatistic($user_id, $curscore,'word');
        }


        return response()->json($res);
        }


    public function setErrors($newError,$correct){
      //  Cookie::queue('wordsErrors', ''); return true;
        $myerrors = (Cookie::get('wordsErrors'))?explode('::',Cookie::get('wordsErrors')):[];

   if(!empty($correct) && !empty($myerrors)){
       $myerrors = array_diff($myerrors, $correct); // Удаляем из массива с ошибками корректные значения
      if(empty($myerrors)){
          Cookie::queue('wordsErrors', ''); // если ошибок нет удаляем куку
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
        Cookie::queue('wordsErrors', implode('::',$myerrors), 60*24);
      // echo Cookie::get('wordsErrors');
return $myerrors;



    }

   public function clearWordsErorrs(string $word){

      if($word !== 'clearAll'){
        $olderrors = (Cookie::get('wordsErrors'))?explode('::',Cookie::get('wordsErrors')):[];
        $myerrors = array_diff($olderrors, array($word));
       // echo implode(',',$myerrors);
       Cookie::queue('wordsErrors', implode('::',$myerrors), 60*24);
      // echo '<pre>'; print_r($olderrors);echo '</pre>';
      // echo '<pre>'; print_r($myerrors);echo '</pre>'; exit();
    } else {
         Cookie::queue('wordsErrors', '');
    }

     return true;
     }




}
