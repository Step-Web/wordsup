
@extends('layouts.app')

@include('layouts.inc.meta',['title'=>$user->username.' профиль пользователя','index'=>'noindex'])
@section('content')
    <div class="container">
        @php  \Carbon\Carbon::setLocale('ru');
        @endphp
        <div class="block pt-1 mb-4">
                <div class="title" style="display:flex;justify-content:space-between;flex-wrap: wrap"><h1>{{$user->username}}</h1> <div><small class="text-muted">место в рейтинге:</small> <b>{{$user->position}}</b></div></div>
                     <div class="row flex-wrap">
                         <div class="col flex-grow-0"><img src="{{asset($user->userpic)}}" alt="" width="90" ></div>
                         <div class="col">
                             <div class="row mt-1">
                                 <div class="col-sm-6 col-md-4 mt-1 mb-1 text-nowrap"><small class="text-muted">Имя:</small> {{$user->name??'не указано'}}</div>
                                 <div class="col-sm-6 col-md-4 mt-1 mb-1 text-nowrap"><small class="text-muted">Тип:</small> {{$user->role}}</div>
                                 <div class="col-sm-6 col-md-4 mt-1 mb-1 text-nowrap"><small class="text-muted">Возраст:</small> {{($user->age)?\Carbon\Carbon::parse($user->age)->diff(\Carbon\Carbon::now())->format('%y'):'не указан' }}</div>
                                 <div class="col-sm-6 col-md-4 mt-1 mb-1 text-nowrap"><small class="text-muted">Город:</small> {{$user->city??'не указан'}}</div>
                                 <div class="col-sm-6 col-md-4 mt-1 mb-1 text-nowrap"><small class="text-muted">Заходил(а):</small> {{($user->last_login_at)?\Carbon\Carbon::parse($user->last_login_at)->diffForHumans():'нет данных'}}</div>
                             </div>

                         </div>
                     </div>
            </div>

        <div class="row text-center">
            <div class="col col-md-3 mb-4"> <div class="block pt-3 text-nowrap"><i class="icon-puzzle fs-2"></i><div><small class="text-muted">слов:</small> <b>{{$user->words}}</b></div></div></div>
            <div class="col col-md-3 mb-4"> <div class="block pt-3 text-nowrap"><i class="icon-puzzles fs-2"></i><div><small class="text-muted">фраз:</small> <b>{{$user->phrases}}</b></div></div></div>
            <div class="col col-md-3 mb-4"> <div class="block pt-3 text-nowrap"><i class="fas fa-coins fs-2"></i><div><small class="text-muted">монет:</small> <b>{{$user->score}}</b></div></div></div>
            <div class="col col-md-3 mb-4"> <div class="block pt-3 text-nowrap"><i class="fas fa-trophy fs-2"></i><div><small class="text-muted">место:</small> <b>{{$user->position}}</b></div></div></div>


        </div>
        </div>

@endsection
