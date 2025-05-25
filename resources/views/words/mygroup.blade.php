@extends('layouts.app')
@include('layouts.inc.meta',['title'=>$group->name,'mdesc'=>$group->name,'mkey'=>$group->name,'index'=>'noindex'])

@section('content')
    <div class="container">
   <div class="block pt-1 mb-2"><div class="title"><h1>{{$group->name}}</h1></div>
       <div class="row">
           <div class="col-sm-12">
               <form id="newword" method="post" onsubmit="return addRow()">
                   @csrf
                   <div class="input-group">
                       <input type="search" name="word" id="add_words" autocomplete="off" placeholder="Вводите слово..." value="" class="form-control">
                       <input type="hidden" name="group_id" id="group_id" value="{{$group->id}}">
                       <input type="hidden" name="user_id" id="user_id" value="{{$group->user_id}}">
                       <button id="addword" class="btn btn-primary"><i class="icon-plus"></i> <span class="d-none d-sm-inline">Добавить слово</span></button>
                   </div>
               </form>
               <div style="position: relative;"><ul id="search_result" class="search_result" style="display: none"></ul></div>
           </div>
       </div>
   </div>
        <div class="row">
            <div class="col text-end shrink">
                @include('layouts.inc.studyMode',['type'=>'word','model'=>'mygroup'])
            </div>
        </div>
   <div class="block mywordlist">
       <link rel="stylesheet" type="text/css" href="/assets/css/mytable.css">
       <script src="/assets/js/mytable.js"></script>
     @if($group->words)
           <form id="formtab">
               <div class="table-responsive">

                   <table id="oTable" class="table table-striped mytable"><thead >
                       <tr>
                           <th class="no-sort"><input type="checkbox" name="select_all" class="select-all" value="1" onchange="oTable.selectAll(this)"></th>
                           <th>Слова</th>
                           <th class="w-50">Перевод</th>
                           <th class="progress-text"></th>
                           <th class="no-sort"></th>
                       </tr>
                       </thead>
                       <tbody>

                       @foreach($group->words AS $w)
           <tr id="{{$w->id}}">
               <td><input type="checkbox" name="id[]" value="{{$w->id}}" class="che" onchange="oTable.showChecked()"></td>
               <td><div class="wordblock">
                             @if($w->audio)
                               <div class="audio-icon" data-audio="{{$w->audio}}" data-voice="f" onclick="playWord(this)"><i class="icon-play"></i>
                             @else
                              <div class="audio-icon"><i class="icon-play"></i>
                             @endif</div> <div><div class="word">{{$w->word}}</div><span class="ts">{{$w->ts}}</span></div></div></td>
               <td><div class="translate">{{$w->translate}}</div></td>
               <td><div class="stat l{{$w->progress}}" onclick="setProgressWord(this)"></div></td>
               <td>
                   <div class="dropdown-toggle" id="act{{$w->id}}" data-bs-toggle="dropdown" aria-expanded="false"> <i class="fas fa-ellipsis-v"></i></div>
                   <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="act{{$w->id}}">
                       <li><div class="fas fa-pencil-alt dropdown-item editRow" data-bs-toggle="modal" data-bs-target="#winModal" data-id="{{$w->id}}"><span  role="button">Изменить</span></div></li>
                       <li><div class="fas fa-trash-alt dropdown-item delRow"><span  role="button">Удалить</span></div></li>
                   </ul>
               </td>
           </tr>
       @endforeach
       </tbody>
           </table>
   </div>
           </form>
           @endif

   </div>
    </div>
    @include('layouts.inc.modal',['id'=>'winModal'])
    @include('layouts.inc.modalExercise',['type'=>'word'])
        <script>
            window.addEventListener('load', function () {
                fakeSelect('#studymode');
            })
            option = {
                sorting:true,
                searching:true,
                search_column:[1,2],
                checkboxs:true,
                action:true,
                tophtml:'<div class="btn-group mb-2"><a href="/words/group/" type="button" class="btn btn-primary"><i class="fas fa-angle-left"></i> Мои группы слов</a><button id="transfer" type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" disabled="disabled"> <i class="icon-check" aria-hidden="true"></i> Выделенные <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-end"><li><span class="dropdown-item" data-bs-toggle="modal" data-bs-target="#winModal" onclick="formTransfer(\'copy\')"><i class="icon-copy" aria-hidden="true"></i> Копировать</span></li><li><span data-bs-toggle="modal" data-bs-target="#winModal" class="dropdown-item" onclick="formTransfer(\'cut\')"><i class="icon-cut" aria-hidden="true"></i> Перенести</span></li><li><span class="dropdown-item" data-checked="true" data-bs-toggle="offcanvas" data-bs-target="#exerciseModal"><i class="icon-study"></i> Изучить отмеченные</span></li><li><span class="dropdown-item"  onclick="resetProgress()"><i class="icon-reset" aria-hidden="true"></i> Сбросить прогресс</span></li><li><span class="dropdown-item" onclick="deleteMultiple()"><i class="icon-trash" aria-hidden="true"></i> Удалить отмеченные</span></li></ul></div>',
                bottomhtml:'<div></div>'
            }
            const oTable = new MyTable('#oTable',option);
        </script>
    <script src="/assets/js/sortable.js"></script>
   <script src="/assets/js/wordMyGroup.js"></script>
@endsection
