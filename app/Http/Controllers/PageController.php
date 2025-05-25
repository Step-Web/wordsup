<?php

namespace App\Http\Controllers;


use App\Models\page;
use App\Models\WordGroup;
use Illuminate\Http\Request;

class PageController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        $page = new Page();
        $sID = $req->query('section');


        return view('admin.page.index', [
            'pages' => $page->where('section_id',$sID)->orderBy('id','DESC')->get(),
            'section'=>$page->allSection(),
            'table'=>$page->getTable()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.page.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {    $page = new WordGroup();
        $page->name = $req->input('name');
        $page->color = $req->input('color');
        $page->save();

        return redirect('/word/group')->with('status', 'Запись была добавлена');
    }

    /**
     * Display the specified resource.
     */
    public function show( $cat,$url)
    {
        $page = Page::where('url', $url.'.html')->first();

        return view('page', ['page' => $page, 'section' => $page->curSection()]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $page = Page::find($id);
        return view('admin.page.edit',['item' => $page]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req, string $id)
    {

        $page = Page::findOrFail($id);

        $page->mtitle = ($req->input('mtitle')) ? $req->input('mtitle') : $req->input('title');
        $page->mdesc = ($req->input('mdesc'))  ? $req->input('mdesc') : $req->input('title');
        $page->mkey = ($req->input('mkey'))  ? $req->input('mkey') : $req->input('title');
        $page->title = $req->input('title');
        $page->content = $req->input('content');
        $page->url = ($req->input('url')) ?? $page->url;
        $page->save();
        return redirect('/admin/page')->with('status', 'Запись была обновлена');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Page::destroy($id);
        return redirect('/admin/page')->with('status', 'Запись была удалена');
    }
}
