@extends('admin.layouts.app')

@section('content')
    <div class="container">
    <h3 class="mt-3 fw-bold">Группы слов</h3>
        <p class="text-right"><a class="btn btn-dark btn-sm" href="{{route('wordgroup.create')}}">Добавить</a></p>
    <div class="table-responsive">
    <table class="table table-striped table-hover table-bordered" id="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>фото</th>
            <th style="width: 100%">название</th>
            <th>статус</th>
            <th class="no-sort"></th>
            <th class="no-sort"></th>
        </tr>
        </thead>
        <tbody>
@foreach($pages AS $page)
    <tr>
        <td>{{$page->id}}</td>
        <td><img src="{{asset($page->image)}}?{{time()}}" class="img-fluid" alt=""></td>
        <td><div class="iconname"><a class="folderpage fas fa-folder" href="{{route('wordlist.index',['group'=>$page->id])}}"><small>{{$page->words_count}}</small></a> <div><div class="font-bold">{{$page->name}}</div> <a href="/wordlist/{{$page->url}}" class="small text-muted url">/wordlist/{{$page->url}}</a></div> </div></td>
        <td><i data-url="wordgroups/{{$page->id}}/is_public" class="setflag status{{$page->is_public}}" onclick="setFlag(this)">{{$page->is_public}}</i></td>
        <td><a class="btn btn-dark" href="{{route('wordgroup.edit',[$page->id])}}"><i class="fas fa-pencil-alt"></i></a></td>
        <td><form method="post" action="{{route('wordgroup.destroy',[$page->id])}}" onsubmit="return delItem(this)"> @csrf @method('delete')<button type="submit" class="btn btn-danger fas fa-trash-alt"></button></form></td>
    </tr>
@endforeach
        </tbody>
    </table>
    </div>
        <p class="text-right"><a class="btn btn-dark btn-sm" href="{{route('wordgroup.create')}}">Добавить</a></p>
        <input id="tab" type="hidden" value="{{$table}}">
    </div>


    <style>
        table td .status1 {
            background: #1b1b1b;
        }
        table td .setflag {
            width: 1em;
            height: 1em;
            display: block;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid #1b1b1b;
            text-indent: 9em;
            white-space: nowrap;
            margin: 0.5em auto 1em;
        }
        table .btn {width: 2em;height: 2em; padding: 0; display: flex; align-items: center;justify-content: center }
        .iconname {
            line-height: 1em;
            display: flex;
            align-items: center;
            font-weight: bold;
            text-align: left;
        }
        .iconname .folderpage {
            font-size: 2.4em;
            text-decoration: none;
            color: #feda00;
            position: relative;
            display: flex;
            align-items: center;
            margin-right: 0.2em;
        }
        .iconname .folderpage small {
            color: #000;
            font-weight: bold;
            font-size: 0.5em;
            position: absolute;
            left: 0;
            top: 0.6em;
            text-align: center;
            width: 100%;
        }

        .iconname .url{ display: inline-block; margin-top: .2em;
            font-weight: normal;
        }
    </style>
    <script>

        function setFlag(el){
            let url = el.dataset.url;
            let flag = (Number(el.innerText) == 1)?0:1;
            fetch('/admin/setflag/'+url+'/'+flag)
               .then(response => response.text())
                .then(function(res) {
                   if(res > 0){
                    el.className = 'setflag status'+flag;
                    el.innerText = flag;
                       messBlock('Статус был успешно обновлён','success')
                   }
                })
                .catch(error =>  messBlock(error,'danger'));
        }
    function delItem(form){
            let sistem = form.dataset.sistem;
            if(sistem > 0) { messBlock('Это системная страница её нельзя удалить'); return false}
        let tr = form.closest('tr');
        if(confirm('Вы действительно хотите удалить страницу?') == true){
            return true;
        }
        return false;
    }
    </script>
@endsection
