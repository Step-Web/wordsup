<?php

namespace App\Http\Controllers;



use App\Models\WordGroup;
use App\Models\WordList;
use Illuminate\Http\Request;

class WordListController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function all(Request $req)
    {
        //$page =  WordList::select('id','word','translate','ts','audio')->where('group_id',$req->query('group'))->get();
        //$page->keyBy('id');
        $groups = WordGroup::all();
        return view('words.wordlists', ['groups' => $groups]);
    }
    public function index(Request $req)
    {
       //$page =  WordList::select('id','word','translate','ts','audio')->where('group_id',$req->query('group'))->get();
        //$page->keyBy('id');

        $group = WordGroup::find($req->query('group'));
        return view('admin.wordlist.index', ['group' => $group]);
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
    public function store(Request $req)
    {   $group_id = $req->input('group_id');
        $wordlist = new WordList();

       // dd($users->count);
        $wordlist->word = $req->input('word');
        $wordlist->ts = $req->input('ts');
        $wordlist->translate = rtrim($req->input('translate'),',');
        $wordlist->audio = $req->input('audio');
        $wordlist->group_id = $group_id;
        $wordlist->wgroup = $req->input('wgroup');
        $wordlist->save();
        $qty = $wordlist->where('group_id',$group_id)->get()->count();
        WordGroup::where('id', $group_id)->update(['qty' => $qty]);
        return redirect('/admin/wordlist?group='.$group_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $url)
    {
        $group = WordGroup::where('url', $url)->firstOrFail();

        return view('words.wordlist',['group'=>$group,'words'=>$group->words]);
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

        public function update(Request $req, string $id){

            $page = WordList::findOrFail($id);
            $page->word = $req->input('word');
            $page->ts = $req->input('ts');
            $page->translate = rtrim($req->input('translate'),',');
            $page->example = $req->input('example');
           // $page->example = $req->input('example');
            $page->save();
             return response()->json($page->id);

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $req,int $id)
    {   $group_id = $req->input('group_id');
        WordList::destroy($id);
        $qty = WordList::where('group_id',$group_id)->get()->count();
        WordGroup::where('id', $group_id)->update(['qty' => $qty]);
        return redirect('/admin/wordlist?group='.$group_id);
    }
}
