@extends('layouts.app')
@include('layouts.inc.meta',['title'=>'Учить слова','mdesc'=>'Учить случайные слова','mkey'=>'Учить слова'])
@section('content')
    <div class="container">
   <div class="block pt-1"> <div class="title"><h1>Случайные слова</h1></div>
       <link rel="stylesheet" type="text/css" href="/assets/css/mytable.css">
       <script src="/assets/js/mytable.js"></script>


       <div class="row wordgroup">
           <div class="col-md-8">
               <ul class="row icontrumb">
                   <li class="col-xs-6"><i class="flaticon-list"></i> Выберите группу слов ползунком <span id="fake-place">3</span> или группы нажав на кнопку выбрать группы слов</li>
                   <li class="col-xs-6"><i class="flaticon-gesture"></i> Выберите правильный перевод из 4 вариантов, ошибки можно посмотреть при завершении</li>
               </ul>
               <p class="small text-muted"><i> Все слова в группах, отсортированы по частоте употребления в английском языке, от большего к меньшему.</i></p>
           </div>
           <div class="col-md-4">
               <div id="range" class="range">
                   <img src="https://poliglot16.ru/views/theme/img/sliderate.svg"><div id="range-line"><div id="range-place"></div></div>
                   <input type="range" id="range-input" value="100" min="1" max="100" oninput="updateVal(this.value)" onchange="chooseVal(this.value)"/></div>
              <button class="btn btn-default setting hidden-xs hidden-sm" data-bs-toggle="modal" data-bs-target="#winModal" style="width: 270px; margin-top: 10px;"><i class="glyphicon glyphicon-cog" aria-hidden="true"></i> выбрать группы слов</button>
           </div>
       </div>
       <div id="ajaxupdate">
           <p id="range-value">Слова из группы <b class="red">0</b></p><hr>
       </div>
   </div>

   <div class="block mywordlist">
     @if(isset($words))
               <div class="table-responsive">
                   <table id="oTable" class="table  table-striped mytable"><thead >
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
               <td><div class="wordblock"><div class="audio-icon"  data-audio="eye.mp3" data-voice="f" onclick="playWord(this)"><i class="icon-play"></i></div> <div><div class="word">{{$w->word}}</div><span class="ts"> {{$w->ts}}</span></div></div></td>
               <td><div class="translate">{{$w->translate}}</div></td>
               <td>
                   <a data-bs-toggle="modal" data-bs-target="#winModal" data-word="{{$w->word}}" class="btn btn-primary btn-sm" onclick="addword(this)">В словарь</a>
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
    <!-- Модальное окно -->
    <div class="modal fade" id="winModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content"></div></div>
    </div>
    <!-- Full screen modal -->
    <div class="offcanvas offcanvas-bottom" style="height: 100%" tabindex="-1" id="exerciseModal">
        <div class="offcanvas-header">
            <div class="score"><small>Баллы:</small> <span class="score-num"><b id="score">0</b></span></div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Закрыть"></button>
        </div>
        <div class="offcanvas-body"></div>
        <div class="offcanvas-footer">
            <div class="row" style="width: 100%">
                <div class="col-6"></div>
                <div class="col-6" style="text-align: right"><div id="btn-skip" class="btn btn-default" onclick="nextSlide()">Отвечу позже</div> <span id="btn-unknown" class="btn btn-primary" onclick="unKnown()">Не знаю</span> <button id="btn-close" type="button" class="btn btn-primary d-none" data-bs-dismiss="offcanvas">Закрыть</button></div>
            </div>
        </div>
        <audio id="audio"></audio>

        <link rel="stylesheet" type="text/css" href="/assets/css/exercise.css">
        <script src="/assets/js/slider.js"></script>
        <script src="/assets/js/fakeSelect.js"></script>
        <script src="/assets/js/exercise.js"></script>


        <script>
            let winModal;

             document.addEventListener("DOMContentLoaded", () => {
                  winModal = new bootstrap.Modal(document.getElementById('winModal'));
             });


             function copyGroup(btn){
                 let url = btn.dataset.url;
                 let req = new XMLHttpRequest();req.open('GET',url,false);req.send(null);
                 document.querySelector('#winModal .modal-content').innerHTML = req.responseText;
                 winModal.show();
                 return false;
             }





             const csrf_token = '{{csrf_token()}}';
             const myOffcanvas = document.getElementById('exerciseModal');
            myOffcanvas.addEventListener('show.bs.offcanvas', event => {
                let studymode = document.getElementById('studymode').value;

                let formData = new FormData();
                formData.append('_token', csrf_token);
                formData.append('group_id', group_id);
                formData.append('studymode', studymode);
                let btn = event.relatedTarget;
                let checked = btn.dataset.checked;

                if(checked == 'true'){
                    const checkedBoxes = document.querySelectorAll('input.che:checked');
                    let w = [];
                    checkedBoxes.forEach(function(elem) { w.push(elem.value); });
                    formData.append("words", w.join(','));
                } else {
                    formData.delete("words");
                }

                console.log(formData);
                const req = new XMLHttpRequest();
                req.open('POST', '/learnword/getCheckedWords/group?mode=random', false);
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


             function translateEdit(e){
                const block = e.parentNode;
                const act = block.className;
                 let show = 'save';
                 block.style.display = 'none';
                if(act === 'save'){
                     show = 'edit';
                    block.parentNode.querySelector('.edit span').innerText = block.parentNode.querySelector('input').value;
                } else {
                     show = 'save';
                }
                 block.parentNode.querySelector('.'+show).style.display = 'flex';
             }

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
            const sr = document.getElementById('search_result');
            fakeSelect('#studymode');







            var rangePlace = document.getElementById('range-place');
            var rangeInp = document.getElementById('range-input');
            function fillItems(){document.querySelector('.indicator').style.display='block';}
            var updateVal = function(val) {
                rangePlace.innerHTML = val;
                document.getElementById('fake-place').innerHTML = val;
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
            };
            function chooseVal(val){ alert(val);
               // $('.owl-carousel').trigger('destroy.owl.carousel');
               // $('#ajaxupdate').html('');
               // $('#ajaxupdate').load('/trenirovka-slov/?group='+val+'&ajax=exam');
               history.pushState(null, null, '/learnword/random?group='+val);
               // setInterval(function () {
               //     $('.owl-carousel').owlCarousel({items: 1,loop:false,dots:false,center:true,margin:0,mouseDrag:false,touchDrag:false,callbacks:true,URLhashListener:false,autoplayHoverPause:false,onInitialized: fillItems});
               // }, 200);
            }


            updateVal(3)











            function selectWоrd(e){
                let word = e.getAttribute('data-word');
                document.getElementById('add_words').value = word;
                sr.style.display = 'none';
                searchInput.focus();
            }





            function formTransfer(act){
                let req = new XMLHttpRequest();req.open('GET','/words/userword/formTransfer/'+act+'/'+group_id,false);req.send(null);
                document.querySelector('#winModal .modal-content').innerHTML = req.responseText;
                fakeSelect('#groupnew');
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


            function deleteMultiple(){
                const formData = new FormData(document.getElementById('formtab'));

                formData.append('_token', csrf_token);
                formData.append('group_id', group_id);
                 let req = new XMLHttpRequest();req.open('POST','{{route('userword.deleteMultiple')}}',false);req.send(formData);
                 if(req.responseText > 0) {
                     document.querySelectorAll('tbody input.che:checked').forEach(function(c) { c.closest('tr').remove(); });
                     messBlock('Выбранные записи удалены','success');
                     oTable.refresh();
                 } else {
                     messBlock('Ошибка при удалении','danger');
                 }
                 return false;
            }



            function setProgressWord(e) {
                let id = e.closest('tr').id;
                let p = Number(e.className.slice(-1));
                let level = (p != 4)?p+1:0;
                fetch('/words/userword/setProgressWord/'+id+'/'+level)
                    .then((res) => res.json()).then((response) => {  if(response > 0)  e.className = 'stat l'+level; });
            }

            function delTranslate(btn){
                let el = btn.closest('.sortable');
                btn.parentNode.remove();
                collectTranslate(el);
            }

            function collectTranslate(el){
                let id = el.getAttribute('id');
                let arr = Array.from(el.children);
                const res = arr.map(t => {return t.innerText.trim()})
                document.querySelector('.'+id).value = res.join(', ');
            }

            function showFieldTranslate(btn){
                btn.innerText='введите через запятую';
                btn.className = 'small text-warning'
                let sel = btn.dataset.target;
                document.getElementById(sel).style.display = 'none';
                document.querySelector('.'+sel).style.display = 'block';
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
