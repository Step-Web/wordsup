@extends('layouts.app')

@include('layouts.inc.meta',['title'=>'Подборки слов','mdesc'=>'Подборки слов','mkey'=>'Подборки слов'])

@section('content')
    <div class="container">

   <div class="block mywordlist mb-4">
       <div class="title"><h1>Подборки английских слов</h1></div>
    </div>

            @if($groups)
                <div class="row wordgroups">
                    @foreach($groups AS $group)
                        <div class="col-6 col-md-4 col-lg-3 mb-4">
                            <div class="block h-100">
                            <div class="img">
                                <a href="/wordlist/{{$group->url}}"><img class="img-fluid" src="{{asset($group->image)}}" alt="{{$group->name}}"></a>
                            </div>
                            <div class="desc p-2 h-100"> <div class="lang"><img src="{{asset('/storage/images/icons/'.$group->lang.'.svg')}}" alt=""></div>
                            <div class="title text-center"><p><a href="/wordlist/{{$group->url}}">{{$group->name}}</a></p></div>
                            <div class="small text-muted">{{$group->description}}</div>
                            </div>
                              <div class="foot">
                                  <div>{{$group->qty}} <small class="text-muted">слов</small></div>
                                  <div> <div class="btn-group"><span class="btn btn-info">в словарь</span> <span class="btn btn-danger">учить</span></div></div>
                              </div>
                        </div></div>
                    @endforeach
                </div>
            @endif
        </div>

    @include('layouts.inc.modal',['id'=>'winModal'])
    @include('layouts.inc.modalExercise',['type'=>'word'])
    <script src="/assets/js/word.js"></script>



    <script>
            let winModal;

             document.addEventListener("DOMContentLoaded", () => {
                  winModal = new bootstrap.Modal(document.getElementById('winModal'));
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
            const sr = document.getElementById('search_result');



            function copyGroup(btn){
                 let url = btn.dataset.url;
                 let req = new XMLHttpRequest();req.open('GET',url,false);req.send(null);
                 document.querySelector('#winModal .modal-content').innerHTML = req.responseText;
                 winModal.show();
                 return false;
             }



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
