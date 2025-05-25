@extends('layouts.app')
@include('layouts.inc.meta',['title'=>'Мои группы фраз','index'=>'noindex'])

@section('content')
<div class="container">
   <div class="block pt-1"><div class="title"><h1>Мои группы фраз</h1> <span>Всего: <b>{{session()->get('user.phrases')}}</b> фраз Ваш лимит: <b>{{session()->get('user.limit.phrases')}}</b> фраз</span></div>
       <div class="row row-flex">
           <div class="col-sm-6 col-md-4 col-lg-3 item"><div data-url="{{route('group.create')}}" data-bs-toggle="modal" data-bs-target="#winModal"><div class="colorcard" style="background-color: #fff"><i class="fa-2x far fa-plus-square"></i><p>Добавить новый набор</p></div></div> </div>

       @foreach($groups AS $g)
               <div class="col-sm-6 col-md-4 col-lg-3 item">
                   <div class="actions">
                       <div class="dropdown-toggle" id="act2703" data-bs-toggle="dropdown" aria-expanded="false">
                           <i class="fas fa-ellipsis-v"></i>
                       </div>
                       <ul class="dropdown-menu dropdown-menu-end">
                           <li><span class="dropdown-item" data-url="{{route('group.edit',$g->id)}}" data-bs-toggle="modal" data-bs-target="#winModal"><i class="fas fa-edit"></i> Изменить</span></li>
                           <li><span class="dropdown-item" onclick="resetProgress({{$g->id}})"> <i class="fas fa-share-square"></i> Сброс прогресса</span></li>
                           <li><span class="dropdown-item" data-url="{{route('group.confirmDeleteGroup',$g->id)}}" data-bs-toggle="modal" data-bs-target="#winModal"><i class="fas fa-trash-alt"></i> Удалить</span></li>
                       </ul>
                   </div>
                   <a href="/{{$g->type}}/group/{{$g->id}}"><div class="colorcard" style="background-color:{{$g->color}}"><p class="wordsroup">{{$g->qty}}</p><small>фраз</small><br>{{$g->name}}</div></a>

               </div>

       @endforeach
       </div>
   </div>
</div>
    <!-- Модальное окно -->
    <div class="modal fade" id="winModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content"></div></div>
    </div>
   <link href="http://wordsup.loc/views/theme/css/user.css" rel="stylesheet">

   <script>
       const csrf_token = '{{csrf_token()}}';
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


       function checkColor(obj,color) {
           document.querySelector('#colorPalettePicker span').style.backgroundColor = color;
           document.getElementById('color').value = color;
           let spans = document.querySelectorAll('.colorPalettePicker span');
           spans.forEach(function(item) {item.classList.remove('active');});
           obj.classList.add('active');
       }




       function resetProgress(group_id){
           const formData = new FormData();
           formData.append('_token', csrf_token);
           formData.append('group_id', group_id);
           fetch("{{route('userphrase.resetProgress')}}", {
               method: "POST",
               body: formData
           }).then((res) => res.json())
               .then((response) => {
                   if(response > 0){
                       messBlock('Статистика успешно сброшена.','success');
                   } else {
                       messBlock('Не удалось обновить статистику, возможно она нулевая', 'warning');
                   }
               });

       }



   </script>
@endsection
