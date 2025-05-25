@extends('admin.layouts.app')

@section('content')
    <div class="container">
    <h3 class="mt-3 fw-bold">Страницы сайта</h3>
        <p class="text-right"><a class="btn btn-dark" href="{{route('page.create')}}">Добавить страницу</a></p>
    <div class="table-responsive">
    <table class="table table-striped table-hover table-bordered" id="table">
        <thead>
        <tr>
            <th>ID</th>
            <th style="width: 100%">название страниц</th>
            <th>индекс</th>
            <th>статус</th>
            <th class="no-sort"></th>
            <th class="no-sort"></th>
        </tr>
        </thead>
        <tbody>
@foreach($pages AS $page)
    <tr>
        <td>{{$page->id}}</td>
        <td><div class="fw-bold">{{$page->title}}</div><a href="/{{$section[$page->section_id]['url']}}/{{$page->url}}" class="small text-muted">/{{$section[$page->section_id]['url']}}/{{$page->url}}</a></td>
        <td><i data-url="{{$table}}/{{$page->id}}/is_index" class="setflag status{{$page->is_index}}" onclick="setFlag(this)">{{$page->is_index}}</i></td>
        <td><i data-url="{{$table}}/{{$page->id}}/is_public" class="setflag status{{$page->is_public}}" onclick="setFlag(this)">{{$page->is_public}}</i></td>
        <td><a class="btn btn-dark"><i class="fas fa-pencil-alt"></i></a></td>
        <td><form method="post" onsubmit="return delItem(this)" data-sistem="{{$page->is_sistem}}"> @csrf @method('delete')<button type="submit" class="btn btn-danger fas fa-trash-alt"></button></form></td>
    </tr>
@endforeach
        </tbody>
    </table>
    </div>
        <p class="text-right"><a class="btn btn-dark" href="{{route('page.create')}}">Добавить страницу</a></p>
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
    @if(session()->has('status'))
        <script>
            window.onload = function() {
            let mes = "{{ session()->get('status') }}";
            messBlock(mes,'success');
            };
        </script>
    @endif
@endsection
