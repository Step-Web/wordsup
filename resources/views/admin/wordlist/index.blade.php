@extends('admin.layouts.app')

@section('content')
    <div class="container">
    <h3 class="mt-3 fw-bold">{{$group->name}}</h3>

        <form id="newword" method="post" action="{{route('wordlist.store')}}" onsubmit="return addRow(this)" >
            @csrf
            <div class="input-group">
                <input type="search" name="word" id="add_words" autocomplete="off" placeholder="Вводите слово..." value="" class="form-control">

                <input type="text" name="word" id="word" class="form-control" value="" style="display: none">
                <input type="text" name="ts" id="ts" class="form-control" value="" style="display: none">
                <input type="text" name="translate" id="translate" class="form-control" value="" style="display: none">
                <input type="hidden" name="audio" id="audiofile" class="form-control" value="" style="display: none">
                <input type="hidden" name="wgroup" id="wgroup" class="form-control" value="" style="display: none">
                <input type="hidden" name="group_id" id="group_id" value="{{$group->id}}">
                <button id="addword" class="btn btn-primary" style="display: none"><i class="icon-plus"></i> <span class="hidden-xs">Добавить слово</span></button>
            </div>
        </form>
        <div style="position: relative;"><ul id="search_result" class="search_result" style="display: none"></ul></div>


    <div class="table-responsive mt-3">
    <table class="table table-striped table-hover table-bordered" id="table">
        <thead>
        <tr>
            <th>ID</th>
            <th></th>
            <th>слова</th>
            <th style="width: 50%">перевод</th>
            <th  style="width: 50%">пример</th>
            <th class="no-sort"></th>
            <th class="no-sort"></th>
        </tr>
        </thead>
        <tbody>

        @php $words = $group->words->sortDesc()@endphp
@foreach($words AS $word)
    <tr id="{{$word->id}}">
        <td>{{$word->id}}</td>
        <td><div class="audio-icon" data-audio="{{$word->audio}}" data-voice="f" onclick="playWord(this)"><i class="fas fa-play-circle"></i></div></td>
        <td><div class="fw-bold word">{{$word->word}}</div> <small class="text-muted ts">{{$word->ts}}</small></td>
        <td><small class="text-muted translate">{{$word->translate}}</small></td>
        <td><small class="example">{{$word->example}}</small></td>
        <td><a class="btn btn-dark" data-bs-toggle="modal" data-id="{{$word->id}}" data-bs-target="#winModal" onclick="editRow(this)"><i class="fas fa-pencil-alt"></i></a></td>
        <td><form method="post" action="{{route('wordlist.destroy',[$word->id])}}" onsubmit="return delItem(this)"> @csrf @method('delete')
                <input name="group_id" value="{{$group->id}}" type="hidden"><button type="submit" class="btn btn-danger fas fa-trash-alt"></button></form></td>
    </tr>
@endforeach
        </tbody>
    </table>



    </div>

        {{$group->content}}
        <p class="mt-3"><a class="btn btn-sm btn-dark" href="{{route('wordgroup.edit',$group->id)}}">изменить контент страницы</a></p>

    </div>
    <!-- Модальное окно -->
    <div class="modal fade" id="winModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content">
                <form id="saveWord" onsubmit="return saveWord(this)" method="post">
                    @csrf        <input type="hidden" name="_method" value="PUT">            <div class="modal-header">
                        <h5 class="modal-title" id="winModalLabel">Изменить слово</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><label class="form-label">Слово:</label><input class="form-control" id="inputwords" name="word" value="" minlength="2" maxlength="50" required=""></p>
                        <p><label class="form-label">Перевод:</label> <input class="form-control wintanslate" id="inputtranslate" name="translate" value="" minlength="2" maxlength="100" required=""></p>
                        <p><label>Транскрипция:</label><input class="form-control" id="inputts" name="ts" value="" maxlength="30"></p>
                        <p><label>Пример:</label><input class="form-control" id="inputexample" name="example" value="" maxlength="255"></p>

                    </div>

                    <div class="modal-footer">
                        <input name="id" id="inputid" type="hidden" value="">
                        <input name="group_id" type="hidden" value="{{$group->id}}">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Отменить</button>
                        <button class="btn btn-dark">Сохранить</button>
                    </div>
                </form>

            </div></div>
    </div>
        <audio id="audio"></audio>
    <style>

        table .btn {width: 2em;height: 2em; padding: 0; display: flex; align-items: center;justify-content: center }
        #translate{ flex-grow: 4}
    </style>
    <script>




        const sr = document.getElementById('search_result');

        let searchInput = document.getElementById('add_words');
        let inputEvent = function (e) {
            let w = this.value;
            if(this.value.length >= 2){
                let req = new XMLHttpRequest();req.open('GET','http://laravel.loc/dictonary/search?word='+w,false);req.send(null);
                let obj = JSON.parse(req.responseText);
                console.log(obj);
                let li = '';
                for (const k of Object.keys(obj)) {
                    let re = new RegExp(w,"g"); // search for all instances
                    let nw = obj[k].word.replace(re, '<span class="text-danger">'+w+'</span>');
                    let nt = obj[k].translate.replace(re, '<span class="text-danger">'+w+'</span>');
                    li += '<li class="addwоrd" data-id="'+obj[k].id+'" data-word="'+obj[k].word+'"><span onclick="selectWоrd(this)"><b id="'+obj[k].id+'">'+nw+'</b> <small>'+obj[k].ts+'</small> - <em>'+nt+'</em> <s class="d-none">'+obj[k].audio+'</s><i class="d-none">'+obj[k].wgroup+'</i></span></li>';
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




        function selectWоrd(e){
            let word = e.querySelector('b').innerText;
            let ts = e.querySelector('small').innerText;
            let translate = e.querySelector('em').innerText;
            let audio = e.querySelector('s').innerText;
            let wgroup = e.querySelector('i').innerText;
            let add_words = document.getElementById('add_words');
            let iword = document.getElementById('word');
            let its = document.getElementById('ts');
            let itranslate = document.getElementById('translate');
            let iaudio = document.getElementById('audiofile');
            let iwgroup = document.getElementById('wgroup');
            iword.style.display = 'block';
            iword.value = word;
            its.style.display = 'block';
            its.value = ts;
            itranslate.style.display = 'block';
            itranslate.value = translate;
            iaudio.value = audio;
            iwgroup.value = wgroup;
            add_words.style.display = 'none';
            add_words.value = '';
            document.getElementById('addword').style.display = 'block';
            sr.style.display = 'none';
            itranslate.focus();
        }

        function addRow(form){
            let add_word = document.getElementById('add_words').value;
            let formData = new FormData(form);
            console.log(formData);
            let url = form.getAttribute('action');



        }
        function editRow(e) {
            let tr = e.closest('tr');
            let id = e.getAttribute('data-id');
           document.getElementById('inputwords').value = tr.querySelector('.word').innerText;
           document.getElementById('inputtranslate').value = tr.querySelector('.translate').innerText;
            document.getElementById('inputts').value = tr.querySelector('.ts').innerText;
            document.getElementById('inputexample').value = tr.querySelector('.example').innerText;
           document.getElementById('inputid').value = id;
           document.getElementById('saveWord').setAttribute('action','/admin/wordlist/'+id);
        }


        function saveWord(form){
            const url = form.getAttribute('action');
            const formData = new FormData(form);
            let req = new XMLHttpRequest(); req.open("POST", url,false);req.send(formData);
            console.log(req);
            if(req.responseText){
                let tr = document.getElementById(formData.get('id'));
                tr.querySelector('.word').textContent = formData.get('word');
                tr.querySelector('.translate').textContent = formData.get('translate');
                tr.querySelector('.example').textContent = formData.get('example');
                tr.querySelector('.ts').textContent = formData.get('ts');
                form.querySelector('button[data-bs-dismiss="modal"]').click();
                messBlock('Слово <b>'+formData.get('word')+'</b> обновлено','success',2000);
            } else {
                document.querySelector('#winModal .modal-body').innerHTML = 'Что то пошло не так, одновите страницу';
            }
            return false;

        }




    function delItem(form){

        let tr = form.closest('tr');
        if(confirm('Вы действительно хотите удалить слово?') == true){
            return true;
        }
        return false;
    }



    </script>

    @if(session()->has('status'))
        <script>
            window.onload = function() {
            let mes = "{{ session()->get('status') }}";
            messBlock(mes,'success');
            };
        </script>
    @endif
@endsection
