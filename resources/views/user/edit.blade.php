@extends('layouts.app')
@include('layouts.inc.meta',['title'=>'Изменить профиль','index'=>'noindex'])
@section('content')
    <link rel="stylesheet" href="/assets/css/croppie.css" />

<div class="container">
    <div class="container">
        <div class="block pt-1">
            <div class="title d-flex justify-content-between"><h1>Изменить профиль</h1> <div><i class="fas fa-user-times" role="button" data-url="{{route('confirmDestroyUser',$user->id)}}" data-bs-toggle="modal" data-bs-target="#winModal" title="Удалить профиль"></i></div></div>
    <form method="post" action="{{route('userupdate')}}">
        @csrf
        @method('PUT')

               <div class="row">
                   <div class="col-md-3 userpic">
                  @if($user->userpic)
                       <div class="imgblock mt-4"><img class="img-fluid" src="{{asset($user->userpic)}}" id="imagefile" alt=""> </div>
                       <a id="btn-upload" class="btn file-btn btn-dark" style="display:none"><span class="text-light">Загрузить</span><input type="file" id="files" name="files" class="file-btn" accept="image/*"></a>
                           <span class="deleteBtn btn btn-danger">Удалить</span>
                       @else
                       <div class="imgblock mt-4"><img class="img-fluid" src="{{asset('/storage/images/user/noimg.svg')}}" id="imagefile" alt=""></div>
                       <a id="btn-upload" class="btn file-btn btn-dark"><span class="text-light">Загрузить</span><input type="file" id="files" name="files" class="file-btn" accept="image/*"></a>
                           <span class="deleteBtn btn btn-danger" style="display: none">Удалить</span>
                      @endif
                      <div id="croppie" class="demo" style="display: none"></div>

                      <span id="btn-crop" class="btn btn-dark" style="display:none">Обрезать</span>
                       <input type="hidden" name="imagebase24" id="imagebase24">
                       <input type="hidden" name="image" id="image" value="{{$user->userpic}}">
                       <div id="configImg" data-id="{{$user->id}}" data-width="90" data-height="90" data-patch="user"></div>

                   </div>
                   <div class="col-md-9">
                       <div class="row">
                      <div class="col-sm-6 mb-3">
                          <label class="form-label fw-bold" for="username">Ник</label>
                          <input class="form-control" type="text" value="{{$user->username}}" disabled>
                      </div>
                      <div class="col-sm-6 mb-3">
                          <label class="form-label fw-bold" for="email">Е-mail</label>
                          <input class="form-control" type="text" name="email" value="{{old('email',$user->email)}}" required>
                      </div>
                       <div class="col-sm-6 mb-3">
                           <label class="form-label fw-bold" for="name">Имя</label>
                           <input class="form-control" type="text" name="name" value="{{old('name',$user->name)}}">
                       </div>
                       <div class="col-sm-6 mb-3">
                           <label class="form-label fw-bold" for="name">Фамилия</label>
                           <input class="form-control" type="text" name="surname" value="{{old('surname',$user->surname)}}">
                       </div>
                       <div class="col-sm-6 mb-3">
                           <label class="form-label fw-bold" for="name">День рожденья</label>
                           <input class="form-control" type="date" name="age" value="{{ date_format(date_create($user->age), 'Y-m-d') }}">
                       </div>
                       <div class="col-sm-6 mb-3">
                           <label class="form-label fw-bold" for="name">Город</label>
                           <input class="form-control" type="text" name="city" value="{{$user->city}}">
                       </div>
                       <div class="col-sm-6 mb-3">
                           <label class="form-label fw-bold" for="level">Ваш уровень</label>
                           <select name="level" class="form-select">
                               @foreach($levels AS $k=>$level)
                               <option {{($user->level == $k) ? 'selected':''}} value="{{$k}}">{{$level}}</option>
                               @endforeach
                           </select>
                       </div>
                          <div class="d-flex justify-content-between">



                           <div class="mb-3">

                           </div>

                          <div class="mb-3">
                              <input type="hidden" name="id" value="{{$user->id}}">
                              <button type="submit" class="btn btn-primary">Сохранить данные</button>

                          </div>
                          </div>
                       </div>
                   </div>

    </div>
    </form>

        </div>
</div>
    <script src="/assets/js/croppie.js"></script>
    <script src="/assets/js/croppie-user.js"></script>
    <!-- Модальное окно -->
    <div class="modal fade" id="winModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content"></div></div>
    </div>
    <link href="http://wordsup.loc/views/theme/css/user.css" rel="stylesheet">

    <script>
        let winModal = document.getElementById('winModal');
        winModal.addEventListener('show.bs.modal', function (event) {
            let btn = event.relatedTarget;
            let url = (btn.getAttribute('data-url'))?btn.getAttribute('data-url'):'';
            let content='';
            if(url){
                let req = new XMLHttpRequest();req.open('GET',url,false);req.send(null);
                content = req.responseText;
            } else {
                content = btn.getAttribute('data-info');
            }
            winModal.querySelector('.modal-content').innerHTML = content;
        });

        function confirmDestroyUser(){
            // winModal.querySelector('.modal-content').innerHTML = content;
        }




    </script>

@endsection
