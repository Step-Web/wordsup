@extends('layouts.app')
@include('layouts.inc.meta',['title'=>$group->name,'mdesc'=>$group->name,'mkey'=>$group->name,'index'=>'noindex'])

@section('content')
    <div class="container">
   <div class="block pt-1">

          <div class="title" style="display: flex; align-items: center; flex-wrap: wrap"><h1 style="flex-grow:1">{{$group->name}} </h1>
              <div class="title-user">
                  <div style="display:flex;height: 37px; margin-bottom: 15px">
                      <div class="btn btn-primary" data-url="{{route('group.copygroup',$group->id)}}?type=usergroup" onclick="copyGroup(this)" title="Добавить группу в свой словарь">Добавить в словарь</div>
                  <a href="{{route('usershow',$user->id)}}"><img src="{{$user->userpic}}" alt="{{$user->username}}" style="width:37px"></a>
                  <div style="display: flex; flex-direction: column; margin-left: 5px; line-height: 1.2em"><b>{{$user->username}}</b> <small class="text-muted">создал(а) группу</small></div></div>
              </div>
          </div>




       <link rel="stylesheet" type="text/css" href="/assets/css/mytable.css">
       <script src="/assets/js/mytable.js"></script>
       <input type="hidden" name="group_id" id="group_id" value="{{$group->id}}">
       <input type="hidden" name="model" id="model" value="usergroup">


   </div><br>
   <div class="block mywordlist">
     @if(isset($group->words))
               <div class="table-responsive">
                   <table id="oTable" class="table table-striped mytable"><thead>
                       <tr>
                           <th>Слова</th>
                           <th class="w-75">Перевод</th>
                           <th class="progress-text"></th>
                           <th class="no-sort"></th>
                       </tr>
                       </thead>
                       <tbody>
                       @foreach($group->words AS $w)
           <tr id="{{$w->id}}">
               <td><div class="wordblock"><div class="audio-icon" @if($w->audio)data-audio="{{$w->audio}}" data-voice="f" onclick="playWord(this)"@endif><i class="icon-play"></i></div> <div><div class="word">{{$w->word}}</div><span class="ts"> {{$w->ts}}</span></div></div></td>
               <td><div class="translate">{{$w->translate}}</div></td>
               <td><div class="stat l0"></div></td>
               <td>
              <div class="btn-group"><a data-bs-toggle="modal" data-bs-target="#winModal" data-word="{{$w->word}}" data-id="{{$w->id}}" class="btn btn-primary" onclick="addword(this)" title="Добавить слово в словарь"><i class="fas fa-graduation-cap d-lg-none"></i> <span class="d-none d-lg-inline">в словарь</span></a></div></td>
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
            .mytable-top-panel{ padding: 0!important;}
            .slider__item input{height: 40px; width: 40px;text-align: center; border: 1px solid #ddd}
            .slider__item input.green{ border-color:#298514; color:#298514;}
            .slider__item input.red{ border-color:#df6055; color:#df6055}

            p.translate{  margin: 0; line-height: normal}
            .slider  b.text-primary{ margin-bottom: 15px;}
            .trueVariant{ font-size:32px; min-height:28px; line-height: normal;margin-bottom: 18px;  color:#298514;}
            .trueVariant span:before{ content: "?"; border: 1px solid #ddd; display: inline-block; width: 1em; margin: 2px; line-height: 1em;   font-size:1em; color:#ddd;height: 1em}
            #showAnswers{ max-width: 320px; text-align: center; margin: auto; font-size: 1.1em}
            #showAnswers .through{ color: #000; text-decoration: line-through;}
            #showAnswers .through span{ color: #df6055}
            #showAnswers .small{  color: #ddd; font-size: 0.8em}
            .checkquestion{ font-weight: bold; font-size: 1.4em}
            .red{ color: #df6055}
            .green{ color: #37b11b}
            .grey{ color: grey}
            .clickletters div {
                width: 36px;
                height: 36px;
                line-height: 34px;
                text-align: center;
                display: inline-block;
                background-color: #02397b;
                font-weight: bold;
                color: #ddd;
                position: relative;
                margin: 3px 2px;
                cursor: pointer;
            }
            .clickletters div:hover{ background-color: #0a84f8;}
            .badge {
                display: inline-block;
                min-width: 10px;
                padding: 3px 7px;
                font-size: 13px;
                font-weight: bold;
                color: #fff;
                line-height: 1;
                vertical-align: middle;
                white-space: nowrap;
                text-align: center;
                background-color: #7d7d7d;
                border-radius: 10px;
            }


            .clickletters .badge {
                position: absolute;
                top: -2px;
                right: -2px;
                font-size: 0.8em;
                padding: 1px 4px;
                background-color:#222;
                color:#ccc;
            }
            #newword{ margin-bottom: 15px}

            #close{ position: absolute; display: none;top:10px; z-index: 10; right: 15px; cursor: pointer;}
            .block{ position: relative;}
            .who,#addword{ border-radius: 0;}
            #tab_wrapper .row:first-child{ display: none; }
            .search_result { margin: 0; padding: 0;
                width: 100%;
                background: #FFF;
                color: #666;
                border:1px solid #ccc;
                max-height: 299px;
                overflow-y: scroll;
                display: none;
                position: absolute;
                z-index: 9;
            }
            .search_result li { cursor: pointer;
                width: 100%; margin: 0;
                list-style: none;
                padding: 10px 10px;
                border-top: 1px #ccc solid;
                transition: 0.3s;
            }
            .search_result li:first-child {

                border-top: none;

            }
            .sortable{ display: flex;flex-wrap: wrap;}
            .sortable > div{ background: #fff; border: 1px solid #999; padding:0.3rem 0.5rem; margin:0 3px 3px 0; cursor: ew-resize;  text-wrap: nowrap;  }
            .sortable  > div span::after{content: " ";padding:0.3em 0 0 0.2em; display: inline-block; width: 1em; height:1em;cursor: pointer; background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23000'%3e%3cpath d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/%3e%3c/svg%3e") center/1em auto no-repeat;   }

        </style>

@endsection
