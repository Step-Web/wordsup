@extends('layouts.app')
@include('layouts.inc.meta',['title'=>'Ваши ошибки в словах','index'=>'noindex'])
@section('content')
<div class="container">
    <div class="block pt-1 mb-4">
        <div class="title" style="display:flex;justify-content:space-between;flex-wrap: wrap"><h1>Ваши ошибки</h1><div class="btn-group">
                <a href="#" class="btn btn-primary"> в словах @if($error['words'] > 0)<b id="errorbadge" class="badge bg-danger ms-1">{{$error['words']}}</b>@endif</a>
                <a href="{{route('userErrors','phrases')}}" class="btn btn-outline-primary"> в фразах @if($error['phrases'] > 0)<b class="badge bg-secondary ms-1">{{$error['phrases']}}</b>@endif</a>
            </div></div>

        @php
           echo '<pre>'; print_r(Cookie::get('wordsErrors'));echo '</pre>';
        @endphp
        <input type="hidden" name="model" id="model" value="errors">
        <input type="hidden" id="errortype" value="words">
        @if($items)

        <link rel="stylesheet" href="/assets/css/mytable.css">
        <script src="/assets/js/mytable.js"></script>
        <div class="mywordlist">

            <b class="d-none" id="group_id">0</b>
                <div class="table-responsive">
                    <table id="oTable" class="table  table-striped mytable"><thead >
                        <tr>
                            <th>Слова</th>
                            <th class="w-50">Перевод</th>
                            <th class="progress-text"></th>
                            <th class="no-sort"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items AS $w=>$v)
                            <tr id="{{$v['id']}}">
                                <td><div class="wordblock"><div class="audio-icon" data-audio="{{$v['audio']}}" data-voice="f" onclick="playWord(this)"><i class="icon-play"></i></div> <div><div class="word">{{$w}}</div><span class="ts">{{$v['ts']}}</span></div></div></td>
                                <td><div class="translate">{{$v['translate']}}</div></td>
                                <td><div class="stat l0"></div></td>
                                <td><div class="btn-group"><a data-bs-toggle="modal" data-bs-target="#winModal" data-word="{{$w}}" class="btn btn-primary" onclick="addword(this)" title="Добавить слово в словарь"><i class="fas fa-graduation-cap d-lg-none"></i> <span class="d-none d-lg-inline">в словарь</span></a>
                                    <span class="btn btn-danger" onclick="clearErorrs(this,'{{$w}}')" title="Удалить слово из списка"><i class="fas fa-times"></i></span></div></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
        </div>



    <script>
        option = {
            sorting:true,
            searching:true,
            search_column:[0,1],
            checkboxs:false,
            action:false,
            tophtml:'<div class="mb-2 btn-group"><select class="form-select selectpicker" id="studymode" name="studymode"  style="display: none;">   <option value="translate">Выбери перевод</option> <option value="reverse">Обратный перевод</option> <option value="write">Напиши слово</option> <option value="assemble">Собери слово</option> <option value="sprint">Спринт</option></select><span class="btn btn-danger"  data-checked="false" data-bs-toggle="offcanvas" data-bs-target="#exerciseModal" aria-controls="exerciseModal"><i class="fas fa-play-circle"></i> Исправить</span></div>',
            bottomhtml:'<div><span class="btn btn-danger" onclick="clearErorrs(this)">Очистить слова</span></div>'
        }
        const oTable = new MyTable('#oTable',option);
        fakeSelect('#studymode');
    </script>




            @else


        <div class="text-success">Мы не нашли слова с вашими ошибками в основной базе!</div>

            @endif

</div>
@if(sizeof($items) != sizeof($cookies['words']))
    <div class="block pt-1 mb-4">
        <div class="title"><h2>Слова без перевода</h2></div>
        @php $i = 1; @endphp
        <table>
            @foreach($cookies['words'] AS $v)
                @if(empty($items[$v]))
                    <tr>
                        <td><small class="text-muted">{{$i++}}.</small> <span class="fw-bold">{{$v}}</span> <span role="button" onclick="clearErorrs(this,'{{$v}}')" title="Удалить фразу из списка"><i class="fas fa-times text-danger"></i></span></td>
                    </tr>
                @endif
            @endforeach
        </table>
    </div>
@endif
</div>
@include('layouts.inc.modal',['id'=>'winModal'])
@include('layouts.inc.modalExercise',['type'=>'word'])


<script>
    let winModal;
    document.addEventListener("DOMContentLoaded", () => {
        winModal = new bootstrap.Modal(document.getElementById('winModal'));

    });



    const csrf_token = '{{csrf_token()}}';
    const myOffcanvas = document.getElementById('exerciseModal');
    myOffcanvas.addEventListener('show.bs.offcanvas', event => {
        let studymode = document.getElementById('studymode').value;

        let formData = new FormData();
        formData.append('_token', csrf_token);
        formData.append('studymode', studymode);
        const words = document.querySelectorAll('#oTable tbody tr');
        let w = [];
        words.forEach(function(elem) { w.push(elem.id); });
        formData.append("words", w.join(','));


        //console.log(formData);
        const req = new XMLHttpRequest();
        req.open('POST', '/learnword/getWords/words', false);
        req.send(formData);
        if (req.readyState == 4 && req.status == 200) {
            myOffcanvas.querySelector('.offcanvas-body').innerHTML = req.responseText;
            setTimeout(function() { activateSlider('.slider');},100);
            if(studymode == 'write') {
                setTimeout(function() { document.querySelector('#slider input:first-of-type').focus(); },1000);
            }
        }

    });

    myOffcanvas.addEventListener('hide.bs.offcanvas', event => {
        if(setresult == 0) setResult();
        myOffcanvas.querySelector('.offcanvas-body').innerHTML = '';
        let sc = myOffcanvas.querySelector('#score');
        sc.textContent = '0';
        sc.className = '';
        document.getElementById('btn-skip').style.display = '';
        document.getElementById('btn-unknown').style.display = '';
        document.getElementById('btn-close').classList.add('d-none');
    });




    function addword(btn){
        const w = (btn.dataset.id) ? btn.dataset.id:btn.dataset.word;
        let req = new XMLHttpRequest();req.open('GET','/dictonary/addword/'+w+'/',false);req.send(null);
        document.querySelector('#winModal .modal-content').innerHTML = req.responseText;
        fakeSelect('#groupnew');
    }

    function insertword(form){
        const formData = new FormData(form);
        //console.log(formData); return false;
        fetch('/words/userword', {
            method: "POST",
            body: formData
        })
            .then((response) => response.json())
            .then((res) => {
                console.log(res);
                if (res.error) { messBlock(res.error, 'warning'); return false}
                if(res.word){
                    form.querySelector('.btn-close').click();
                    messBlock('Слово добавлено','success');
                }

            });
        return false;
    }









    function transferWords(){
        let cb = document.querySelectorAll('input.che:checked');
        let w = '';
        for (let i = 0; i < cb.length; i++) { w+= cb[i].value+','; }
        w = w.slice(0, -1);
        let formData = new FormData(document.getElementById('transferForm'));
        formData.append('movewords',w);
        console.log(formData);
        let req = new XMLHttpRequest();req.open('POST','{{route('userword.transferWords')}}',false);req.send(formData);
        console.log(req.responseText);
        const res = JSON.parse(req.responseText);
        if(res.status){
            if(res.redirect) window.location.href = res.redirect;

            let txt = 'копирования';
            if(formData.get('act') == 'cut'){
                document.querySelectorAll('tbody input.che:checked').forEach(function(c) { c.closest('tr').remove(); });
                let txt = 'переноса';
            }
            messBlock('Прогресс '+txt+' завершен','success');
            oTable.refresh();
        } else {
            messBlock('Ошибка при выполнении операции','danger');
        }
        return false;
    }




</script>
    <!-- Модальное окно -->
    <div class="modal fade" id="winModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content"></div></div>
    </div>





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
