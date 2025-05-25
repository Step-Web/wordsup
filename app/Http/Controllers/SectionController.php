<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidateSectionRequest;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = new Section();
      // dd( $page->orderBy('id','DESC')->get());
        return view('admin.section.index', ['pages' => $page->withCount('pages')->get(),'table'=>$page->getTable()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.section.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ValidateSectionRequest $req)
    {    $page = new Section();
        $page->mtitle = ($req->input('mtitle')) ? $req->input('mtitle') : $req->input('title');
        $page->mdesc = ($req->input('mdesc'))  ? $req->input('mdesc') : $req->input('title');
        $page->mkey = ($req->input('mkey'))  ? $req->input('mkey') : $req->input('title');
        $page->title = $req->input('title');
        $page->content = $req->input('content');
        $page->url = $req->input('url');
        $page->save();

        return redirect('/admin/section')->with('status', 'Запись была добавлена');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $url)
    {
        return view('section', ['page' => Section::where('url', $url)->first()]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $page = Section::find($id);
        return view('admin.section.edit',['item' => $page]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ValidateSectionRequest $req, string $id)
    {

            $page = Section::findOrFail($id);

        $page->mtitle = ($req->input('mtitle')) ? $req->input('mtitle') : $req->input('title');
        $page->mdesc = ($req->input('mdesc'))  ? $req->input('mdesc') : $req->input('title');
        $page->mkey = ($req->input('mkey'))  ? $req->input('mkey') : $req->input('title');
        $page->title = $req->input('title');
        $page->content = $req->input('content');
        $page->url = ($req->input('url')) ?? $page->url;
        $page->save();
        return redirect('/admin/section')->with('status', 'Запись была обновлена');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Section::destroy($id);
        return redirect('/admin/section')->with('status', 'Запись была удалена');
    }
}
