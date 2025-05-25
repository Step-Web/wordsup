<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidateWordGroupRequest;
use App\Models\User;
use App\Models\WordGroup;
use Illuminate\Http\Request;

class WordGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = new WordGroup();
        // dd( $page->orderBy('id','DESC')->get());
        $pages = $pages->withCount('words')->get();

        return view('admin.wordgroup.index', ['pages' =>$pages,'table'=>'wordlists']);
      //  return view('admin.wordgroup.index', ['pages' => $words,'table'=>$page->getTable()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.wordgroup.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        $page = new WordGroup();

        $base24 = $req->input('imagebase24');

        $page->user_id = $req->input('user_id');
        $page->owner_id = $req->input('user_id');
        $page->mtitle = ($req->input('mtitle')) ? $req->input('mtitle') : $req->input('name');
        $page->mdesc = ($req->input('mdesc'))  ? $req->input('mdesc') : $req->input('name');
        $page->mkey = ($req->input('mkey'))  ? $req->input('mkey') : $req->input('name');
        $page->name = $req->input('name');
        $page->description = $req->input('description');
        $page->content = $req->input('content');
        $page->url = ($req->input('url')) ?? $page->url;
        //   }
        $page->save();

        if(!empty($base24)){
            $patch = 'wordgroup';
            $id =$page->id;
            $img = storeImage($base24,$patch,$page->id);
            WordGroup::where('id', $id)->update(array('image' => $img));
        }
        return redirect('/admin/wordgroup')->with('status', 'Запись была добавлена');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $url)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $page = WordGroup::find($id);
        return view('admin.wordgroup.edit',['item' => $page]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ValidateWordGroupRequest $req, int $id)
    {

        $page = WordGroup::findOrFail($id);
        $base24 = $req->input('imagebase24');

        if(!empty($base24)){
            $patch = 'wordgroup';
            $img = storeImage($base24,$patch,$id);
            $page->image = $img;
        }
        $page->content = $req->input('content');
        $page->mtitle = ($req->input('mtitle')) ? $req->input('mtitle') : $req->input('name');
        $page->mdesc = ($req->input('mdesc'))  ? $req->input('mdesc') : $req->input('name');
        $page->mkey = ($req->input('mkey'))  ? $req->input('mkey') : $req->input('name');
        $page->name = $req->input('name');
        $page->description = $req->input('description');
        $page->content = $req->input('content');
        $page->url = ($req->input('url')) ?? $page->url;
    //   }
        $page->save();
        return redirect('/admin/wordgroup')->with('status', 'Группа была обновлена');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        WordGroup::destroy($id);
        return redirect('/admin/wordgroup')->with('status', 'Группа была удалена');
    }








    public function deleteImage($id){
        $user = User::find($id);
        $file = mb_substr($user->userpic, 9); // обрезаем /storage/
        $res = 0;
        $del = Storage::disk('public')->delete($file);
        if($del){
            $user->image = NULL;
            $user->save();
            $res = $user->id;
        }
        return $res;
    }





}
