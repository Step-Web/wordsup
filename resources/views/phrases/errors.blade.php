
@extends('layouts.app')
@include('layouts.inc.meta',['title'=>'Ваши ошибки в фразах','index'=>'noindex'])
@section('content')
    <div class="container">
        <div class="block pt-1 mb-4">
            <div class="title d-flex justify-content-between flex-wrap" style=""><h1>Ваши ошибки</h1><div class="btn-group">
                    <a href="{{route('userErrors','words')}}" class="btn btn-outline-primary"> в словах @if($error['words'] > 0)<b class="badge bg-secondary ms-1 rounded-pill">{{$error['words']}}</b>@endif</a>
                    <a href="#" class="btn btn-primary"> в фразах @if($error['phrases'] > 0)<b id="errorbadge" class="badge bg-danger ms-1 rounded-pill">{{$error['phrases']}}</b>@endif</a>
                </div></div>
            <input type="hidden" name="model" id="model" value="errors">
            <input type="hidden" id="errortype" value="phrases">

    @if($items)

        <link rel="stylesheet" href="/assets/css/mytable.css">
        <script src="/assets/js/mytable.js"></script>
        <div class="mywordlist">


            <b class="d-none" id="group_id">0</b>
                <div class="table-responsive">
                    <table id="oTable" class="table  table-striped mytable"><thead >
                        <tr>
                            <th class="w-50">Фразы</th>
                            <th class="w-50">Перевод</th>
                            <th class="progress-text"></th>
                            <th class="no-sort"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i=0; @endphp
                        @foreach($items AS $w=>$v)
                            <tr id="{{$i++}}" data-item="{{$w}}||{{$v}}">
                                <td><div class="word">{{$w}}</div></td>
                                <td><div class="translate">{{$v}}</div></td>
                                <td><div class="stat l0"></div></td>
                                <td><div class="btn-group"><a data-bs-toggle="modal" data-bs-target="#winModal" data-word="{{$w}}" class="btn btn-primary" onclick="addword(this)" title="Добавить фразу в словарь"><i class="fas fa-graduation-cap d-lg-none"></i> <span class="d-none d-lg-inline">в словарь</span></a>
                                        <span class="btn btn-danger" onclick="clearErorrs(this,'{{$w}}||{{$v}}')" title="Удалить фразу из списка"><i class="fas fa-times"></i></span></div></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>


        </div>



    </div>

        @include('layouts.inc.modal',['id'=>'winModal'])
        @include('layouts.inc.modalExercise',['type'=>'phrase'])


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
                tophtml:'<div class="mb-2 btn-group"><select class="form-select selectpicker" id="studymode" name="studymode"  style="display: none;"> <option value="collatewords">Собери фразу из слов</option><option value="assemble">Собери фразу из букв</option> <option value="write">Напиши фразу</option></select><span class="btn btn-danger"  data-checked="false" data-bs-toggle="offcanvas" data-bs-target="#exerciseModal" aria-controls="exerciseModal"><i class="fas fa-play-circle"></i> Исправить</span></div>',
                bottomhtml:'<div><span class="btn btn-danger" onclick="clearErorrs(this)">Очистить фразы</span></div>'
            }
            const oTable = new MyTable('#oTable',option);


            const csrf_token = '{{csrf_token()}}';
            const myOffcanvas = document.getElementById('exerciseModal');
            myOffcanvas.addEventListener('show.bs.offcanvas', event => {
                let studymode = document.getElementById('studymode').value;

                let formData = new FormData();
                formData.append('_token', csrf_token);
                formData.append('studymode', studymode);
                const words = document.querySelectorAll('#oTable tbody tr');
                let w = [];
                words.forEach(function(elem) { w.push(elem.dataset.item); });
                formData.append("items", w.join('::'));


                //console.log(formData);
                const req = new XMLHttpRequest();
                req.open('POST', '/learnphrase/getPhrases/errors', false);
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



            @else


            <div class="text-success">Так держать! Мы не нашли слова с ошибками</div>

            @endif

        </div>




    <style>

    </style>



@endsection
