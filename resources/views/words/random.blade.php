@extends('layouts.app')
@include('layouts.inc.meta',['title'=>'Случайные слова на английском','mdesc'=>'Учить случайные слова на английском языке','mkey'=>'Учить слова,случайные слова'])
@section('content')
    <div class="container">
   <div class="block pt-1 mb-4"> <div class="title"><h1>Случайные английские слова</h1></div>
       <link rel="stylesheet" type="text/css" href="/assets/css/mytable.css">
       <script src="/assets/js/mytable.js"></script>
   </div>
        <input type="hidden" name="model" id="model" value="dictonary">
        <input type="hidden" name="group_id" id="group_id" value="0">
   <div class="block mywordlist">
     @if(isset($words))
               <div class="table-responsive">
                   <table id="oTable" class="table table-striped mytable"><thead>
                       <tr>
                           <th class="rating"></th>
                           <th>Слова</th>
                           <th class="w-75">Перевод</th>
                           <th class="progress-text"></th>
                           <th class="no-sort"></th>
                       </thead>
                       <tbody>
                       @foreach($words AS $w)
           <tr id="{{$w->id}}">
               <td class="position-relative p-1"><div class="borderline bgrating{{rate($w->wgroup)}}">{{$w->wgroup}}</div></td>
               <td><div class="wordblock"><div class="audio-icon" data-audio="{{$w->audio}}" data-voice="f" onclick="playWord(this)"><i class="icon-play"></i></div> <div><div class="word">{{$w->word}}</div><span class="ts">{{$w->ts}}</span></div></div></td>
               <td><div class="translate">{{$w->translate}}</div></td>
               <td><div class="stat l0"></div></td>
               <td><div class="btn-group"><a data-bs-toggle="modal" data-bs-target="#winModal" data-word="{{$w->word}}" class="btn btn-primary" onclick="addword(this)" title="Добавить слово в словарь"><i class="fas fa-graduation-cap d-lg-none"></i> <span class="d-none d-lg-inline">в словарь</span></a>
                       <span class="btn btn-danger" onclick="removeWord(this)" title="Удалить слово из списка"><i class="fas fa-times"></i></span></div></td>
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
                 tophtml:'<div class="mb-2 btn-group"> <span onclick="location.reload()" class="btn btn-primary" title="Обновить слова" style="width: 40px"><i class="fas fa-sync"></i></span><select class="form-select selectpicker" id="studymode" name="studymode"  style="display: none;">   <option value="translate">Выбери перевод</option> <option value="reverse">Обратный перевод</option> <option value="write">Напиши слово</option> <option value="assemble">Собери слово</option> <option value="sprint">Спринт</option></select><span class="btn btn-danger"  data-checked="false" data-bs-toggle="offcanvas" data-bs-target="#exerciseModal" aria-controls="exerciseModal"><i class="fas fa-play-circle"></i> Учить слова</span></div>',
                 bottomhtml:'<div></div>'
             }
             const oTable = new MyTable('#oTable',option);





        </script>




        <style>


            input[type=range] {
                -webkit-appearance: none;
                width: 100%; margin-top: -10px; opacity:0;
            }
            input[type=range]::-webkit-slider-thumb {
                -webkit-appearance: none;
            }
            input[type=range]:focus {
                outline: none;
            }
            input[type=range]::-ms-track {
                width: 100%;
                cursor: pointer;
                background: transparent;
                border-color: transparent;
                color: transparent;
            }
            input[type=range]::-webkit-slider-thumb {
                -webkit-appearance: none;
                height: 40px;
                width: 40px;
                background: #555;
                cursor: pointer;
                margin-top: -12px;
            }
            input[type=range]::-moz-range-thumb {
                height: 40px;
                width: 40px;
                background: #555;
                cursor: pointer;
            }
            input[type=range]::-ms-thumb {
                height: 40px;
                width: 40px;
                background: #555;
                cursor: pointer;
            }
            input[type=range]::-webkit-slider-runnable-track {
                width: 100%;
                cursor: pointer;
                background: #ccc;
            }

            input[type=range]:active::-webkit-slider-runnable-track {
                background: #d6d6d6;
            }

            input[type=range]::-moz-range-track {
                width: 100%;
                height: 12px;
                cursor: pointer;
                background: #ccc;
            }

            input[type=range]::-ms-track {
                width: 100%;
                height: 12px;
                cursor: pointer;
                background: transparent;
                border-color: transparent;
                color: transparent;
            }

            input[type=range]::-ms-fill-lower {
                background: #ccc;
            }

            input[type=range]:focus::-ms-fill-lower {
                background: #ddd;
            }

            input[type=range]::-ms-fill-upper {
                background: #ccc;
            }

            input[type=range]:focus::-ms-fill-upper {
                background: #ddd;
            }
            .range{ width: 270px; position: relative;}
            #fake-place{ background: #0b2242; color: #fff;  font-size: 0.7em; text-align: center; width: 14px; height: 14px; line-height: 14px; border-radius: 50%; display: inline-block}
            #range-place{ position: absolute; top: 45px; width: 30px; height: 30px;line-height: 30px; text-align: center; background: #0b2242; color: #fff; font-size: 0.8em; font-weight: bold; cursor: pointer; border-radius:50%; }
            #range-place::before,#range-place::after{ position: absolute;  font-family: 'Font Awesome\ 5 Free';font-weight: 900; color: #0b2242; width: 20px; height:20px;text-align: center; opacity: 0;}
            #range-place::before{  content: '\f060'; left: -14px; }
            #range-place::after{ content: "\f061"; right: -14px }
            .range:hover #range-place::before,.range:hover #range-place::after{opacity: 1}
            #ajaxupdate{overflow: hidden;}












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
