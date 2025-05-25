<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidatePageinfoRequest;
use App\Models\Infopage;
use App\Models\WordGroup;
use Illuminate\Http\Request;

class InfopageController extends Controller
{


    public function home(Request $req)
    {
        $Infopage = Infopage::where('url', '/')->first();
        $wordgroups = WordGroup::all();
        return view('home', ['page' => $Infopage,'wordgroups' => $wordgroups]);
    }

    public function show(string $url)
    {
        $page = Infopage::where('url', $url.'.html')->first();
        return view('infopage', ['page' => $page]);
    }
// Админка
    public function index()

    {
        $page = new Infopage;
      //  dd($test->getTable());
        return view('admin.infopage.index', ['pages' => $page->orderBy('id','DESC')->get(),'table'=>$page->getTable()]);
    }

    public function create()
    {
        return view('admin.infopage.create');
    }

    public function edit($id)
    {
        $page = Infopage::find($id);
        return view('admin.infopage.edit',['item' => $page]);
    }
    public function store(ValidatePageinfoRequest $req)
    {    $page = new Infopage();
        $page->mtitle = ($req->input('mtitle')) ? $req->input('mtitle') : $req->input('title');
        $page->mdesc = ($req->input('mdesc'))  ? $req->input('mdesc') : $req->input('title');
        $page->mkey = ($req->input('mkey'))  ? $req->input('mkey') : $req->input('title');
        $page->title = $req->input('title');
        $page->content = $req->input('content');
        $page->url = $req->input('url');
        $page->save();

        return redirect('/admin/infopage')->with('status', 'Запись была добавлена');
    }

    public function update(ValidatePageinfoRequest $req)
    {

        $page = Infopage::findOrFail($req->input('id') );
        $page->mtitle = ($req->input('mtitle')) ? $req->input('mtitle') : $req->input('title');
        $page->mdesc = ($req->input('mdesc'))  ? $req->input('mdesc') : $req->input('title');
        $page->mkey = ($req->input('mkey'))  ? $req->input('mkey') : $req->input('title');
        $page->title = $req->input('title');
        $page->content = $req->input('content');
        $page->url = ($req->input('url')) ?? $page->url;
        $page->save();

        return redirect('/admin/infopage')->with('status', 'Запись была обновлена');
    }


    public function destroy($id)
    {
        Infopage::destroy($id);
        return redirect('/admin/infopage')->with('status', 'Запись была удалена');
    }



}
