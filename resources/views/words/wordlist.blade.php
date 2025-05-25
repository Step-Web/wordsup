@extends('layouts.app')
@include('layouts.inc.meta',['title'=>$group->name,'mdesc'=>$group->name,'mkey'=>$group->name,'index'=>'noindex'])
@include('layouts.inc.breadcrumbs',['url'=>'wordlist','title'=>'списки слов'])
@section('headinfo')
    <div class="container-fluid pb-1" style="background: #0b2242">
        <div class="container">
            <div class="row">
                <div class="col-sm pb-2 text-center"> <img class="img-fluid" src="{{asset($group->image)}}" alt="{{$group->mtitle}}"></div>
                <div class="col-sm" style="flex-grow: 3"><h1 class="text-white text-uppercase">{{$group->name}}</h1>
                    <div class="mt-1 mb-3 text-white">{{$group->description}}</div>
                    <span class="btn btn-info" data-url="{{route('group.copygroup',$group->id)}}?type=wordgroup" onclick="copyGroup(this)">Добавить группу с словарь</span>
            </div>
        </div>

        </div>
    </div>
@endsection
@section('content')
    <div class="container">
        <input type="hidden" name="group_id" id="group_id" value="{{$group->id}}">
        <input type="hidden" name="model" id="model" value="wordgroup">
        <link rel="stylesheet" type="text/css" href="/assets/css/mytable.css">
        <script src="/assets/js/mytable.js"></script>
   <div class="block mywordlist mb-4">
     @if(isset($group->words))
               <div class="table-responsive">
                   <table id="oTable" class="table table-striped mytable"><thead>
                       <tr>
                           <th class="rating"></th>
                           <th>Слова</th>
                           <th class="w-75">Перевод</th>
                           <th class="progress-text"></th>
                           <th class="no-sort"></th>
                       </tr>
                       </thead>
                       <tbody>
                       @foreach($group->words AS $w)
           <tr id="{{$w->id}}">
               <td class="position-relative p-1"><div class="borderline bgrating{{rate($w->wgroup)}}">{{$w->wgroup}}</div></td>
               <td><div class="wordblock"><div class="audio-icon"  data-audio="{{$w->audio}}" data-voice="f" onclick="playWord(this)"><i class="icon-play"></i></div> <div><div class="word">{{$w->word}}</div><span class="ts"> {{$w->ts}}</span></div></div></td>
               <td><div class="translate">{{$w->translate}}</div></td>
               <td><div class="stat l0"></div></td>
               <td>
                   <div class="btn-group"><a data-bs-toggle="modal" data-bs-target="#winModal" data-word="{{$w->word}}" data-id="{{$w->id}}" class="btn btn-primary" onclick="addword(this)" title="Добавить слово в словарь"><i class="fas fa-graduation-cap d-lg-none"></i> <span class="d-none d-lg-inline">в словарь</span></a></div>
               </td>
           </tr>
       @endforeach
       </tbody>
           </table>



   </div>

           @else

    <b>Нет слов</b>

           @endif

   </div>


        @if(isset($group->content))
        <div class="block mb-4">
            {{$group->content}}
        </div>
        @endif
    </div>
    @include('layouts.inc.modal',['id'=>'winModal'])
    @include('layouts.inc.modalExercise',['type'=>'word'])
     <script src="/assets/js/word.js"></script>
        <script>

             document.addEventListener("DOMContentLoaded", () => {

                 fakeSelect('#studymode');
             });
            option = {
                sorting:true,
                searching:true,
                search_column:[0,1],
                checkboxs:false,
                action:false,
                tophtml:'<div class="mb-2 btn-group"> <a href="/wordlist" type="button" class="btn btn-primary" style="width: 40px"><i class="fas fa-chevron-circle-left"></i></a><select class="form-select selectpicker" id="studymode" name="studymode"  style="display: none;">   <option value="translate">Выбери перевод</option> <option value="reverse">Обратный перевод</option> <option value="write">Напиши слово</option> <option value="assemble">Собери слово</option> <option value="sprint">Спринт</option></select><span class="btn btn-danger"  data-checked="false" data-bs-toggle="offcanvas" data-bs-target="#exerciseModal" aria-controls="exerciseModal"><i class="fas fa-play-circle"></i> Учить слова</span></div>',
                bottomhtml:'<div></div>'
            }
            const oTable = new MyTable('#oTable',option);
















        </script>




        <style>

            .sortable{ display: flex;flex-wrap: wrap;}
            .sortable > div{ background: #fff; border: 1px solid #999; padding:0.3rem 0.5rem; margin:0 3px 3px 0; cursor: ew-resize;  text-wrap: nowrap;  }
            .sortable  > div span::after{content: " ";padding:0.3em 0 0 0.2em; display: inline-block; width: 1em; height:1em;cursor: pointer; background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23000'%3e%3cpath d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/%3e%3c/svg%3e") center/1em auto no-repeat;   }

        </style>

@endsection
