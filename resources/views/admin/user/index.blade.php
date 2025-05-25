@extends('admin.layouts.app')
@section('content')
    <div class="container">
        <h3 class="mt-3 fw-bold">Пользователи</h3>

    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered" id="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Фото</th>
                <th style="width: 100%">Пользователь</th>
                <th>Роль</th>
                <th>Заходил</th>
                <th class="no-sort"></th>
                <th class="no-sort"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($users AS $user)
                <tr>
                    <td>{{$user->id}}</td>
                    <td></td>
                    <td><div class="fw-bold">{{$user->name}}</div><small class="small text-muted">{{$user->email}}</small></td>
                    <td><div class="fw-bold">{{$user->role}}</div></td>
                    <td><div>{{$user->updated_at}}</div></td>
                    <td><a class="btn btn-dark" href="{{route('user.edit',$user->id)}}"><i class="fas fa-pencil-alt"></i></a></td>
                    <td><form method="post" action="{{route('user.destroy',$user->id)}}" onsubmit="return delItem(this)"> @csrf @method('delete')<button type="submit" class="btn btn-danger fas fa-trash-alt"></button></form></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
        <p class="text-right"><a class="btn btn-dark" href="{{route('user.create')}}">Добавить</a></p>
    </div>
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


