@extends('layouts.app')
@include('layouts.inc.meta',['title'=>$page->mtitle,'mdesc'=>$page->mdesc,'mkey'=>$page->mkey])
@include('layouts.inc.breadcrumbs')
@section('content')
   <div class="block"> <h1>{{$page->mtitle}}</h1>
    {{$page->content}}
   </div>
   <div class="block mt-3">
   @foreach($page->pages AS $p)
       <p><a href="/{{$page->url}}/{{$p->url}}">{{$p->title}}</a></p>
   @endforeach
   </div>
@endsection
