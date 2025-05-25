@extends('layouts.app')
@include('layouts.inc.meta',['title'=>$group->name,'mdesc'=>$group->name,'mkey'=>$group->name,'index'=>'noindex'])

@section('content')
    <div class="container">
   <div class="block pt-1"><div class="title"><h1>{{$group->name}}</h1></div>
       <input type="hidden" name="model" id="model" value="mygroup">
       <link rel="stylesheet" type="text/css" href="/assets/css/mytable.css">
       <script src="/assets/js/mytable.js"></script>
       <div class="row">
           <div class="col-sm-12">
               <form id="newword" method="post" onsubmit="return addRow()">
                   @csrf
                   <div class="input-group">
                       <input type="search" name="phrase" id="add_words" autocomplete="off" placeholder="Вводите слово..." value="" class="form-control">
                       <input type="hidden" name="group_id" id="group_id" value="{{$group->id}}">
                       <input type="hidden" name="user_id" id="user_id" value="{{$group->user_id}}">
                       <button id="addword" class="btn btn-primary"><i class="icon-plus"></i> <span class="hidden-xs">Добавить фразу</span></button>
                   </div>
               </form>
               <div style="position: relative;"><ul id="search_result" class="search_result" style="display: none"></ul></div>
           </div>

       </div>
   </div>
   <div class="row mt-2">
       <div class="col text-end shrink">
           @include('layouts.inc.studyMode',['type'=>'phrase','model'=>'mygroup'])
       </div>

   </div>
   <div class="block mywordlist">
     @if(isset($group->phrases))

           <form id="formtab">
               <div class="table-responsive">

                   <table id="oTable" class="table  table-striped mytable"><thead >
                       <tr>
                           <th class="no-sort"><input type="checkbox" name="select_all" class="select-all" value="1" onchange="oTable.selectAll(this)"></th>
                           <th class="w-50">фраза</th>
                           <th class="w-50">Перевод</th>
                           <th class="progress-text"></th>
                           <th class="no-sort"></th>
                       </tr>
                       </thead>
                       <tbody>

                       @foreach($group->phrases AS $w)
           <tr id="{{$w->id}}">
               <td><input type="checkbox" name="id[]" value="{{$w->id}}" class="che" onchange="oTable.showChecked()"></td>
               <td><div class="wordblock"><div class="audio-icon" data-audio="{{$w->tID}}" onclick="playPhrase(this)"><i class="icon-play"></i></div> <div><div class="phrase fw-bold">{{$w->phrase}}</div></div></div></td>
               <td><div class="translate">{{$w->translate}}</div></td>
               <td><div class="stat l{{$w->progress}}" onclick="setProgressWord(this)"></div></td>
               <td>
                   <div class="dropdown-toggle" id="act{{$w->id}}" data-bs-toggle="dropdown" aria-expanded="false"> <i class="fas fa-ellipsis-v"></i></div>
                   <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="act{{$w->id}}">
                       <li><div class="fas fa-pencil-alt dropdown-item editRow" data-bs-toggle="modal" data-bs-target="#winModal" data-id="{{$w->id}}"><span>Изменить</span></div></li>
                       <li><div class="fas fa-trash-alt dropdown-item delRow"><span>Удалить</span></div></li>
                   </ul>
               </td>
           </tr>
       @endforeach
       </tbody>
           </table>



   </div>
           </form>
           @else

    <b>Нет фраз</b>

           @endif

   </div>
    </div>
    @include('layouts.inc.modal',['id'=>'winModal'])
    @include('layouts.inc.modalExercise',['type'=>'phrase'])


        <script>


             window.addEventListener('load', function () {
                 fakeSelect('#studymode');
             })

            function showHide(el) {
                let e = document.querySelector(el);
                e.style.opacity = (e.style.opacity > 0)?0:1;
            }
             const group_id = document.getElementById('group_id').value;

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
                    formData.append("phrases", w.join(','));
                } else {
                    formData.delete("phrases");
                }

                const req = new XMLHttpRequest();
                req.open('POST', '/learnphrase/getPhrases/group', false);
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







            option = {
                sorting:true,
                searching:true,
                search_column:[1,2],
                checkboxs:true,
                action:true,
                tophtml:'<div class="btn-group mb-2"><a href="/phrases/group/" type="button" class="btn btn-primary"><i class="fas fa-angle-left"></i> Мои группы фраз</a><button id="transfer" type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" disabled="disabled"> <i class="icon-check" aria-hidden="true"></i> Выделенные <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-end"><li><span class="dropdown-item" data-bs-toggle="modal" data-bs-target="#winModal" onclick="formTransfer(\'copy\')"><i class="icon-copy" aria-hidden="true"></i> Копировать</span></li><li><span data-bs-toggle="modal" data-bs-target="#winModal" class="dropdown-item" onclick="formTransfer(\'cut\')"><i class="icon-cut" aria-hidden="true"></i> Перенести</span></li><li><span class="dropdown-item" data-checked="true" data-bs-toggle="offcanvas" data-bs-target="#exerciseModal"><i class="icon-study"></i> Изучить отмеченные</span></li><li><span class="dropdown-item"  onclick="resetProgress()"><i class="icon-reset" aria-hidden="true"></i> Сбросить прогресс</span></li><li><span class="dropdown-item" onclick="deleteMultiple()"><i class="icon-trash" aria-hidden="true"></i> Удалить отмеченные</span></li></ul></div>',
                bottomhtml:'<div></div>'
            }
            const oTable = new MyTable('#oTable',option);
            const sr = document.getElementById('search_result');

            function editRow(e) {
                let tr = e.closest('tr');
                let req = new XMLHttpRequest();req.open('GET','/phrases/userphrase/'+tr.id+'/edit',false);req.send(null);
                document.querySelector('#winModal .modal-content').innerHTML = req.responseText;
            }


            function savePhrase(form){
                const url = form.getAttribute('action');
                const formData = new FormData(form);
                let req = new XMLHttpRequest(); req.open("POST", url,false);req.send(formData);
                if(req.responseText){
                    let tr = document.getElementById(formData.get('id'));
                    tr.querySelector('.phrase').textContent = formData.get('phrase');
                    tr.querySelector('.translate').textContent = formData.get('translate');
                    form.querySelector('button[data-bs-dismiss="modal"]').click();
                    messBlock('Фраза обновлена','success',2000);
                } else {
                    document.querySelector('#winModal .modal-body').innerHTML = 'Что то пошло не так';
                }
                return false;

            }

            function delRow(e) {
                let tr = e.closest('tr');
                tr.classList.add('fade');
                let formData = new FormData();
                formData.append('_token', csrf_token);
                formData.append('_method', "DELETE");
                fetch("/phrases/userphrase/" + tr.id, {
                    headers:{  "X-Requested-With": "XMLHttpRequest"},
                    method: "POST",
                    body: formData
                })
                    .then((res) => res.json())
                    .then((response) => {
                        console.log(response);
                        if(response > 0)  {tr.remove(); oTable.setTotal(); messBlock('Фраза удалена из группы','success');
                        } else{
                            messBlock('Не удалось фразу из группы','danger')
                        }
                    });
            }




            function addRow(e='') {
                const total = oTable.total();
                const w = (e) ? e.dataset.word:searchInput.value.trim();
                searchInput.value = w;
                if(w=='') {
                    messBlock('Введите фразу','warning');
                } else if(total >= 100) {
                    messBlock('Максимально допустимое фраз в группе 100','danger');
                } else {
                    const form = document.getElementById('newword');
                    const formData = new FormData(form);
             // console.log(formData); return false;
                    fetch('{{route('userphrase.store')}}', {
                        method: "POST",
                        body: formData
                    })
                        .then((response) => response.json())
                        .then((res) => {
                           /// console.log(res); return false;
                            if (res.error) { messBlock(res.error); return false}
                            let audio = '';
                            if (res.translate =='') {
                                winModal = new bootstrap.Modal(document.getElementById('winModal'));
                                let req = new XMLHttpRequest();req.open('GET','/phrases/userphrase/' + res.id + '/edit?act=add',false);req.send(null);
                                document.querySelector('#winModal .modal-content').innerHTML = req.responseText;
                                setTimeout(() => { document.getElementById('inputtranslate').focus(); }, 500);
                                winModal.show();
                            } else {
                                 audio = 'data-audio="'+(res.tID) ? res.tID : 's'+res.id+'" onclick="playPhrase(this)"';
                            }


                            let newRow = [
                                '<input type="checkbox" name="id[]" value="' + res.id + '" class="che" onchange="oTable.showChecked()">',
                                '<div class="wordblock"><div class="audio-icon" '+audio+'><i class="icon-play"></i></div><div><div class="phrase fw-bold text-info">' + res.phrase + '</div></div></div>',
                                '<div class="translate">' + res.translate + '</div>',
                                '<div class="stat l0" onclick="setProgressWord(this)"></div>',
                                '<div class="dropdown-toggle" id="act' + res.id + '" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div><ul class="dropdown-menu dropdown-menu-end" aria-labelledby="act' + res.id + '"><li><div class="fas fa-pencil-alt dropdown-item editRow" data-bs-toggle="modal" data-bs-target="#winModal" data-id="' + res.id + '"><span>Изменить</span></div></li> <li><div class="fas fa-trash-alt dropdown-item delRow"><span>Удалить</span></div></li></ul>'
                            ];
                            let tableRow = document.querySelector("#oTable tbody");
                            let row = tableRow.insertRow(0);
                            row.setAttribute('id', res.id);
                            for (let i = 0; i < newRow.length; i++) {
                                let cell = row.insertCell(i);
                                cell.innerHTML = newRow[i];
                            }
                            oTable.refresh();
                        });

                    searchInput.value = '';
                    sr.innerHTML = '';
                    sr.style.display = 'none';
                }
                return false;


            }





            function resetProgress(){
                const formData = new FormData(document.getElementById('formtab'));
                formData.append('_token', csrf_token);
                fetch("{{route('userphrase.resetProgress')}}", {
                    method: "POST",
                    body: formData
                }).then((res) => res.json())
                  .then((response) => {
                      if(response > 0)  {
                          document.querySelectorAll('tbody input.che:checked').forEach(function(c) { c.closest('tr').querySelector('.stat').className = 'stat l0'; });
                      } else{
                          messBlock('Не удалось изменить прогресс','error')
                      }
                  });

            }









            let searchInput = document.getElementById('add_words');
            let inputEvent = function (e) {
                let w = this.value;
                if(this.value.length >= 2){
                    let req = new XMLHttpRequest();req.open('GET','{{route('searchPhrases')}}?word='+w,false);req.send(null);
                    let obj = JSON.parse(req.responseText);
                    let li = '';
                    for (const k of Object.keys(obj)) {
                        let re = new RegExp(w,"g",''); // search for all instances
                        let nw = obj[k].phrase.replace(re, '<span class="text-danger">'+w+'</span>');
                        let nt = obj[k].translate.replace(re, '<span class="text-danger">'+w+'</span>');
                        li += '<li class="addwоrd" data-id="'+obj[k].id+'" data-word="'+obj[k].phrase+'" onclick="addRow(this)"><i class="far fa-plus-square fa-lg"></i> <b id="'+obj[k].id+'">'+nw+'</b> - '+nt+'</li>';
                    }

                    sr.innerHTML = li;
                    sr.style.display = 'block';
                } else {
                    sr.innerHTML = '';
                    sr.style.display = 'none';
                }
            };

            searchInput.addEventListener('click', inputEvent, false);
            searchInput.addEventListener('input', inputEvent, false);










            function formTransfer(act){
                let req = new XMLHttpRequest();req.open('GET','/phrases/userphrase/formTransfer/'+act+'/'+group_id,false);req.send(null);
                document.querySelector('#winModal .modal-content').innerHTML = req.responseText;
                fakeSelect('#groupnew');
            }

             function transferWords(){
                 let cb = document.querySelectorAll('input.che:checked');
                 let w = '';
                 for (let i = 0; i < cb.length; i++) { w+= cb[i].value+','; }
                 w = w.slice(0, -1);
                 let formData = new FormData(document.getElementById('transferForm'));
                 formData.append('move',w);
                 let req = new XMLHttpRequest();req.open('POST','{{route('userphrase.transferWords')}}',false);req.send(formData);
                 const res = JSON.parse(req.responseText);
                 if(res.status){
                     if(res.redirect) window.location.href = res.redirect;
                     let txt = 'копирования';
                     if(formData.get('act') == 'cut'){
                         document.querySelectorAll('tbody input.che:checked').forEach(function(c) { c.closest('tr').remove(); });
                          txt = 'переноса';
                     }
                     messBlock('Прогресс '+txt+' завершен, перенесено '+res.status+' записей','success');
                     oTable.refresh();
                 } else {
                     messBlock('Новые записи не добавлены, после удаления копий','warning',3000);
                 }
                 document.querySelector('#winModal .btn-close').click();
                 return false;
             }


            function deleteMultiple(){
                const formData = new FormData(document.getElementById('formtab'));
                formData.append('_token', csrf_token);
                formData.append('group_id', group_id);
                 let req = new XMLHttpRequest();req.open('POST','{{route('userphrase.deleteMultiple')}}',false);req.send(formData);
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
                fetch('/phrases/userphrase/setProgressWord/'+id+'/'+level)
                    .then((res) => res.json()).then((response) => {  if(response > 0)  e.className = 'stat l'+level; });
            }




        </script>




@endsection
