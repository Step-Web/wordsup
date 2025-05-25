<?php

namespace App\Http\Controllers;

use App\Models\DictonaryEn;
use App\Models\SentenceEn;
use cijic\phpMorphy\Morphy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class SentenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {     $lang = $req->get('lang','en');
        return view('admin.sentence.index',['table'=>'sentences_'.$lang,'lang'=>$lang]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.sentence.modal.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        $data = new SentenceEn();
        $data->phrase = $req->input('phrase');
        $data->translate = $req->input('translate');
        $data->tID = $req->input('tID',0);
        $data->qty = mb_strlen($req->input('phrase'), 'UTF-8');
        $res = $data->save();
      // $admin = new adminController();
      // $response = Http::get(config('app.domain').'admin/setWordSentences/en?phrase='.$data->phrase);
      // $admin->setWordSentences($response);
        return redirect(route('sentence.index'))->with('status', 'Фраза была добавлена');
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
    public function edit(string $id)
    {    $item = SentenceEn::find($id);
         return view('admin.sentence.modal.edit',['item'=>$item]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req, string $id)
    {
        $data = SentenceEn::find($id);
        $data->phrase = $req->input('phrase');
        $data->translate = $req->input('translate');
        $data->tID = $req->input('tID',0);
        $data->qty = mb_strlen($req->input('phrase'), 'UTF-8');
        $res = $data->save();
        return response()->json($res);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function all(Request $req)
    {    $cur_page = $req->input('page', '');
        $sentences = SentenceEn::orderBy('id','DESC')->paginate(100);
        return view('sentence.index',['sentences' => $sentences,'cur_page' => $cur_page]);
    }

    public function search(Request $req,string $word){
        if(str_contains($word,' ')) {
            $word = preg_replace('/\s+/', '_', trim($word));
            return redirect()->route('sentence.search',[$word]);
        }
        //select * from sentences_en where concat(' ', en, ' ') like '% all %';
        $sentences = SentenceEn::whereRaw("concat(phrase) like ?", ['% '.$word.' %'])->paginate(25);
        $ru = '';
        $w = str_replace('_',' ',$word);
            $word = DictonaryEn::select('id','word','translate','ts','audio','wgroup')->where('word',$w)->first();
        $ru = (!empty($word))? substr($word->translate,0,strpos($word->translate,',')):'';
        if(empty($word)){
           $arr = explode(' ',$w);
            $word = DictonaryEn::select('id','word','translate','ts','audio','wgroup')->whereIn('word',$arr)->get();
        }

        $page  = collect();
        $npage = ($req->query('page') > 0)?' Страница №'.$req->query('page'):'';
        $page->mtitle = mb_strtoupper($w,'UTF-8').$npage.' примеры предложений с произношением со словом '.$w;
        $page->mdesc = $sentences->total(). ' английских предложения со словом '.$w.' Реальные примеры употребления в английском языке фраз со словом '.$w.' '.$ru.', все фразы озвучены носителем английского языка';
        $page->mkey = $w.', фразы со словом '.$w;
        $page->title =  'примеры фраз с '.$w;
        $page->index = ($sentences->count() >= 10)?'':'noindex';
        $morphy = new Morphy();
        $more = $morphy->findMoreForm($w,0);
      //  echo '<pre>'; print_r($more);echo '</pre>';

      return view('sentence.search',['sentences' => $sentences,'word' => $word,'w' => $w,'more'=>$more,'page' => $page]);
        //dd($sentences);
        //echo '<pre>'; print_r($sentences);echo '</pre>';
       // return $word;
    }


 function searchPhrases(Request $req)
 {   $word = $req->input('word');
     $res = SentenceEn::where('phrase','LIKE','%'.$word.'%')->orWhere('translate','LIKE','%'.$word.'%')->limit(20)->get();
     return json_encode($res);
 }

}
