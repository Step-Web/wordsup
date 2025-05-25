<?php

namespace App\Http\Controllers;

use App\Models\DictonaryEn;
use App\Models\SentenceEn;
use App\Models\User;
use App\Models\UserWord;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use mysql_xdevapi\Table;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;


class AdminController extends Controller
{

    //protected $redirectTo = '/admin/index';

    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function index()
    {
        if (auth()->check() && auth()->user()->role == 'admin') {
            return view('admin.index', ['page' => '']);
        }
        return redirect('/login')->with('error', 'You are not authorized to access this page.');


    }


    public function setflag(Request $req,string $tab, int $id, string $field, string $flag)
    {     $tab = ($tab == 'dictonary') ? 'dictonary_en' : $tab;
       // dd($tab,$field);
        return DB::table($tab)->where('id', $id)->update([$field => $flag]);
    }

    public function deleteImage(string $tab, int $id) {
        $tab = $tab.'s';
        $item = DB::table($tab)->where('id', $id)->first();
        $file = mb_substr($item->image, 9); // обрезаем /storage/
        $res = 0;
        $del = Storage::disk('public')->delete($file);
        if($del){
            $res = DB::table($tab)->where('id', $id)->update(['image' => NULL]);
        }
        return $res;
    }



    public function processingWord()
    {
        $words = DictonaryEn::select(['id','word','ts','translate','wgroup','is_test','is_public','freq','sentences']);

        return DataTables::of($words)
            ->setRowId('id') // via column name if exists else just return the value
            ->editColumn('freq', function($data){ return '<div class="" style="display:block;width:10px;height:40px;font-size:1px" title="'.$data->freq.'">'.$this->rate($data->wgroup).'</div>'; })
            ->editColumn('word', function($data){ return '<b class="word">'.$data->word.'</b>'; })
            ->editColumn('ts', function($data){ return '<small class="ts">'.$data->ts.'</small>'; })
            ->editColumn('translate', function($data){ return '<small class="text-muted translate">'.$data->translate.'</small>'; })
            ->editColumn('sentences', function($data){ return '<a href="/sentence/word/'.$data->word.'" class="sentences">'.$data->sentences.'</a>'; })
            ->editColumn('wgroup', function($data){ return '<span class="wgroup">'.$data->wgroup.'</span>'; })
            ->editColumn('is_test', function($data){ return '<i data-url="dictonary/'.$data->id.'/is_test" class="setflag status'.$data->is_test.'" onclick="setFlag(this)">1</i>'; })
            ->editColumn('is_public', function($data){ return '<i data-url="dictonary/'.$data->id.'/is_public" class="setflag status'.$data->is_public.'" onclick="setFlag(this)">1</i>'; })
            ->addColumn('action', function($word) {
                return '<a href="#" class="btn btn-sm btn-dark" data-id="'.$word->id.'" data-act="edit" data-bs-toggle="modal" data-bs-target="#wordModal"><i class="fas fa-pencil-alt"></a>';
            })
            ->addColumn('del', function($word) {
                return '<a href="#" class="btn btn-sm btn-danger" data-id="'.$word->id.'" data-bs-toggle="modal" data-bs-target="#wordModal"><i class="fas fa-trash-alt"></a>';
            })
            ->rawColumns(['action','wgroup','word','ts','del','translate','freq','is_test','is_public','sentences'])
            ->make(true);
    }

    public function processingSentence()
    {
        $phrases = SentenceEn::select(['id','phrase','translate','qty','tID']);

        return DataTables::of($phrases)
            ->setRowId('id') // via column name if exists else just return the value
            ->editColumn('phrase', function($data){$id = ($data->tID > 0)?$data->tID:'s'.$data->id; return '<span class="audio-icon" data-audio="'.$id.'" data-voice="f" onclick="playPhrase(this)"><i class="fas fa-play-circle"></i></span> <b class="phrase">'.$data->phrase.'</b>'; })
            ->editColumn('translate', function($data){ return '<span class="text-muted translate">'.$data->translate.'</span>'; })
            ->editColumn('qty', function($data){ return '<span class="qty">'.$data->qty.'</span>'; })
            ->editColumn('tID', function($data){ return '<span class="tID">'.$data->tID.'</span>'; })
            ->addColumn('action', function($phrase) {
                return '<a href="#" class="btn btn-sm btn-dark" data-id="'.$phrase->id.'" data-act="edit" data-bs-toggle="modal" data-bs-target="#phraseModal"><i class="fas fa-pencil-alt"></i></a>';
            })
            ->addColumn('calc', function($phrase) {
                return '<span class="btn btn-sm btn-secondary" data-id="'.$phrase->id.' "onclick="calcWords(this)"><i class="fas fa-calculator"></i></span>';
            })
            ->addColumn('del', function($phrase) {
                return '<span href="#" class="btn btn-sm btn-danger" data-id="'.$phrase->id.'" data-bs-toggle="modal" data-bs-target="#phraseModal"><i class="fas fa-trash-alt"></i></a>';
            })
            ->rawColumns(['action','del','calc','phrase','translate','qty','tID'])
            ->make(true);
    }


    private function rate($rating, $max = 100)
    {
        $min = 1;
        //echo 'рейтинг слова: ' . $rating . '<br><br><br>';
        $res = ceil(($rating-$min)/($max-$min)*9+1);
        return $res;
    }

    public function setWordSentences(Request $req,$lang='en'){

         if (!empty($req->query('phrase'))){
             $filtered = preg_split('/\s+/', preg_replace('/[^\w\s]/u', '', $req->query('phrase')), -1, PREG_SPLIT_NO_EMPTY); // оставляем только слова и создаем массив слов
             $ids = DictonaryEn::whereIn('word',$filtered)->pluck('id')->toArray();
         } else {
             $ids = (!empty($req->query('ids'))) ? explode(',',$req->query('ids')) : [];
         }
        $words = DictonaryEn::whereIn('id',$ids)->get();
            foreach ($words as $w) {
                $count = SentenceEn::whereRaw("concat(phrase) like ?", ['% '.$w->word.' %'])->count();
                if($count > 0){
                $w->update(['sentences' => $count]);
                }
                echo $w->id.' '.$w->word.' '.$count.'<br>';
            }

        }

    public function setAllWordSentences(Request $req)
    {   $start_id =$req->query('start_id');
        DictonaryEn::where('id','>=',$start_id)->orderBy('id','ASC')->chunk(1000, function ($words) {
            foreach($words as $k=>$w){
              // echo $k.' '. $w->word.' <br>';
              $count = SentenceEn::whereRaw("concat(phrase) like ?", ['% '.$w->word.' %'])->count();
              if($count > 0){
              $w->sentences = $count;
              $w->save();
              }
                echo $w->id.' '. $w->word.' '.$count.'<br>';
            }

         ///   $count = SentenceEn::whereRaw("concat(en) like ?", ['% '.$w->word.' %'])->count();
         //   $w->each->update(['sentences' => $count]);
        });
    }

    public function createAudioPhrase(Request $req)
    {
        $phrase = $req->input('phrase');
        $url = 'https://app.memrise.com/v1.24/membot/83/missions/mission-1-ordering-your-favourite-coffee/text_to_speech/?text_tgt='.urlencode($phrase);
        $fileContent = file_get_contents($url);
        $pathfile = $req->input('pathfile');
        Storage::disk('audio')->put($pathfile, $fileContent);
        return Storage::disk('audio')->url($pathfile);
    }

public function tests(Request $req)
{   $group_id = 95;
    $items = UserWord::where('group_id',$group_id)->orderBy('progress')->get();



    $test = setVariants($items, 'translate');
    echo '<pre>'; print_r($test);echo '</pre>';
}
}

    function setVariants( $words, string $direction = 'translate', int $optionsCount = 4, int $questionCount = 0): array
    {
        $questionCount = ($questionCount > 0) ? $questionCount : count($words); // сколько выводить слов в тесте
        $words->each(function($item){
            return $item->translate = current(explode(',', trim($item->translate)));
        });
        $words = $words->toArray();
        // Проверяем, достаточно ли слов для создания теста
        if (count($words) < $questionCount) {
           return 'Not enough words to generate the test';
        }

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
