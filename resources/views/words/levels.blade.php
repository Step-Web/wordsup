@extends('layouts.app')
@include('layouts.inc.meta',['title'=>'Учить слова','mdesc'=>'Учить случайные слова','mkey'=>'Учить слова'])
@section('content')
    <div class="container">
   <div class="block pt-1 mb-4"> <div class="title"><h1>Слова по уровням сложности</h1></div>
       <link rel="stylesheet" type="text/css" href="/assets/css/mytable.css">
       <script src="/assets/js/mytable.js"></script>

       <input type="hidden" name="model" id="model" value="dictonary">
       <div class="row wordgroup">
           <div class="col-lg-8">
               <ul class="row icontrumb">
                   <li class="col-6"><i class="flaticon-list"></i> Выберите группу слов ползунком <span id="fake-place">{{$group}}</span> или группы нажав на кнопку выбрать группы слов</li>
                   <li class="col-6"><i class="flaticon-gesture"></i> Выберите правильный перевод из 4 вариантов, ошибки можно посмотреть при завершении</li>
               </ul>
               <p class="small text-muted"><i> Все слова в группах, отсортированы по частоте употребления в английском языке, от большего к меньшему.</i></p>
           </div>
           <div class="col-lg-4">
               <div id="range" class="range text-center">
                   <img src="https://poliglot16.ru/views/theme/img/sliderate.svg" class="img-fluid"><div id="range-line"><div id="range-place"></div></div>
                   <input type="range" id="range-input" value="100" min="1" max="100" oninput="updateVal(this.value)" onchange="chooseVal(this.value)"/></div>
              <p class="text-center"><button class="btn btn-outline-primary setting" onclick="settingModal()" style="width: 270px; margin-top: 10px;">Выбрать несколько групп слов</button></p>
           </div>
       </div>
   </div>
   <div class="block mywordlist mb-4">

       <div class="d-flex align-items-center justify-content-between">
           <div><strong>Слова из группы:</strong> <b class="red" id="group_id">{{$group}}</b></div>
           <div class="d-flex align-items-center">
            <div class="me-2 fw-bold">по</div>   <select class="form-select" id="limit" name="limit" style="max-width:75px" onchange="chooseVal(document.getElementById('group_id').innerText)"><option value="5">5</option> <option value="10">10</option> <option value="25" selected>25</option> <option value="50">50</option><option value="100">100</option></select> <div class="ms-2 fw-bold">слов</div>
           </div>
       </div>
       <hr class="mb-1">

     @if(isset($words))
               <div class="table-responsive">
                   <table id="oTable" class="table table-striped mytable"><thead >
                       <tr>
                           <th class="rating"></th>
                           <th>Слова</th>
                           <th class="w-75">Перевод</th>
                           <th class="progress-text"></th>
                           <th class="no-sort"></th>
                       </tr>
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

        <div class="block pt-1 mb-4"> <div class="title"><h2>Как учить слова</h2></div>


        </div>
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
                tophtml:'<div class="mb-2 btn-group"> <span onclick="location.reload()" class="btn btn-primary" title="Обновить слова" style="width: 40px"><i class="fas fa-sync"></i></span><select class="form-select selectpicker" id="studymode" name="studymode"  style="display: none;">   <option value="translate">Выбери перевод</option> <option value="reverse">Обратный перевод</option> <option value="write">Напиши слово</option> <option value="assemble">Собери слово</option> <option value="sprint">Спринт</option></select><div class="btn btn-danger" data-checked="false" data-bs-toggle="offcanvas" data-bs-target="#exerciseModal" aria-controls="exerciseModal"><i class="fas fa-play-circle"></i> Учить <span class="d-none d-md-inline">слова</span></div></div>',
                bottomhtml:'<div><div class="btn btn-danger" data-checked="false" data-bs-toggle="offcanvas" data-bs-target="#exerciseModal" aria-controls="exerciseModal"><i class="fas fa-play-circle"></i> Учить слова</div></div>'
            }
            const oTable = new MyTable('#oTable',option);










            var rangePlace = document.getElementById('range-place');
            var rangeInp = document.getElementById('range-input');
            function fillItems(){document.querySelector('.indicator').style.display='block';}
             updateVal = function(val) {
                rangePlace.innerHTML = val;
                document.getElementById('fake-place').innerText = val;
                //document.getElementById('group_id').innerText = val;
                if(val == 1) {
                    val = 0;
                }else if(val > 80){
                    val = val - 11;
                } else if(val > 60){
                    val = val - 8;
                } else if(val > 30){
                    val = val - 5;
                }
                rangePlace.style.left = val + '%';
                 document.getElementById('limit').value = {{$limit}};



            };
            function chooseVal(group){
                let limit = document.getElementById('limit').value;
                let html = '';
                let tbody = document.querySelector('#oTable tbody');
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">загрузка слов...</td></tr>';
                const url = '/learnword/levels?group='+group;
               history.pushState(null, null, url);
                document.getElementById('group_id').innerText = group;

                fetch(url+'&ajax=1&limit='+limit).then((response) => response.json()).then(function(res) {
                   // console.log(res);
                    res.forEach(function(w) {
                    html += '<tr id="'+w.id+'">' +
                       ' <td class="position-relative"><div class="borderline bgrating'+rate(w.wgroup)+'">0</div></td>'+
                      '<td><div class="wordblock"><div class="audio-icon" data-audio="'+w.audio+'" data-voice="f" onclick="playWord(this)"><i class="icon-play"></i></div>' +
                      '<div><div class="word">'+w.word+'</div><span class="ts">'+w.ts+'</span></div></div></td>' +
                      '<td><div class="translate">'+w.translate+'</div></td>' +
                      '<td><div class="stat l0"></div></td>'+
                      '<td><div class="btn-group"><a data-bs-toggle="modal" data-bs-target="#winModal" data-word="'+w.word+'" class="btn btn-primary" title="Добавить слово в словарь" onclick="addword(this)"><i class="fas fa-graduation-cap d-lg-none"></i> <span class="d-none d-lg-inline">в словарь</span></a>' +
                      '<span class="btn btn-danger" onclick="removeWord(this)" title="Удалить слово из списка"><i class="fas fa-times"></i></span></div></div></td>' +
                        '</tr>';
                    tbody.innerHTML = html;
                    oTable.refresh();
                    });
                });

            }


            updateVal({{$group}});

            function settingModal(){
                let req = new XMLHttpRequest();req.open('GET','/learnword/settingModal/?act=get',false);req.send(null);
                document.querySelector('#winModal .modal-content').innerHTML = req.responseText;
                winModal.show();
                setInterval(function () {  totals(); }, 200);
            }

            function setSetting(){
                const checkedBoxes = document.querySelectorAll('input.rating:checked');
                let w = [];
                checkedBoxes.forEach(function(elem) { w.push(elem.value); });
                let group = w.join(',');
               let req = new XMLHttpRequest();req.open('GET','/learnword/settingModal/?act=set&groups='+group,false);req.send(null);
                history.pushState(null, null, '/learnword/levels?group='+group);
                chooseVal(group);
                winModal.hide();
                return false;

            }

           function declOfNum(number, titles) { cases = [2, 0, 1, 1, 1, 2];return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];}
            function totals(check='') {
                 let t = document.querySelectorAll('#cbox input[type="checkbox"]:checked').length;

                if(t > 10){
                  check.checked = false;
                  alert('Вы можете выбрать максимум 10 групп');
                  return false;
                }
                document.getElementById('count').innerHTML = '<b>'+t+'</b> <span class="text-muted">'+declOfNum(t,['группа','группы','групп'])+'</span>';

              if(t > 0){
                  document.getElementById('save-top').style.display = 'inline';
                  document.getElementById('save-foot').style.display = 'inline';
              } else {
                  document.getElementById('save-top').style.display = 'none';
                  document.getElementById('save-foot').style.display = 'none';
              }

            }


            function popular(e){
                let check =(e.checked)?true:false;
                document.querySelectorAll('.rating').forEach(function(checkbox,i) {
                   if(check && i<=10){
                       checkbox.checked = true
                   } else {
                       checkbox.checked = false;
                   }
                });
                e
                totals();
            }





          function rate(rating,max=100){
              let min = 1;
              return  Math.ceil(((rating - min)) / (max - min) * 9 + 1);
          }



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
            .range{ width: 270px; position: relative; margin: auto}
            #fake-place{ background: #0b2242; color: #fff;  font-size: 0.7em; text-align: center; width: 14px; height: 14px; line-height: 14px; border-radius: 50%; display: inline-block}
            #range-place{ position: absolute; top: 45px; width: 30px; height: 30px;line-height: 30px; text-align: center; background: #0b2242; color: #fff; font-size: 0.8em; font-weight: bold; cursor: pointer; border-radius:50%; }
            #range-place::before,#range-place::after{ position: absolute;  font-family: 'Font Awesome\ 5 Free';font-weight: 900; color: #0b2242; width: 20px; height:20px;text-align: center; opacity: 0;}
            #range-place::before{  content: '\f060'; left: -14px; }
            #range-place::after{ content: "\f061"; right: -14px }
            .range:hover #range-place::before,.range:hover #range-place::after{opacity: 1}
            #ajaxupdate{overflow: hidden;}




        </style>

@endsection
