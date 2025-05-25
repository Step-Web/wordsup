@extends('layouts.app')
@include('layouts.inc.meta',['title'=>'Рейтинг лучших за '.$aName[$period],'index'=>'noindex'])
@section('content')
    <link rel="stylesheet" type="text/css" href="/assets/css/mytable.css">
    <script src="/assets/js/mytable.js"></script>
<div class="container">
  <div class="block pt-1">

          <div class="title"><h1>Лучшие за {{$aName[$period]}}</h1>
              @if(empty(session()->get('user.id')))
                  <span>Только зарегистрированные пользователи отображаются в рейтинге</span>
              @elseif($pos != '')
                  <span> У Вас <b>{{$pos+1}}-e</b> место в рейтинге за {{$aName[$period]}}</span>
              @else
                  <span>Вы пока не попали в рейтинг@if($period == 'all'), выполняйте задания и вы тут появитесь@else за {{$aName[$period]}}@endif.</span>
              @endif
          </div>

      @if(!empty($users))
          <div class="table-responsive">
              <table id="oTable" class="table table-striped mytable">
                  <thead>
                  <tr>
                      <th>№</th>
                      <th>Фото</th>
                      <th class="w-100">Пользователь</th>
                      <th>Баллы</th>
                  </tr>
                  </thead>
                  <tbody>
                  @php $i=1 @endphp
                  @foreach($users AS $w)
                      <tr>
                          <td class="">{{$i++}}</td>
                          <td><a href="/user/{{mb_strtolower($w->username,'UTF-8')}}"><img width="40" src="{{asset($w->userpic??'/storage/images/user/noimg.svg')}}" alt="{{$w->username}}"></a></td>
                          <td><b>{{$w->username}}</b></td>
                          <td>{{$w->score}}</td>
                      </tr>
                  @endforeach
                  </tbody>
              </table>
          </div>

      @else

          <b>В рейтинге нет пользователей</b>

      @endif
</div>
</div>
<style>
    .buttons-list{ display: flex; list-style: none; margin: 0; padding: 0}
    .buttons-list span{display: none;}
</style>
    <script>
        option = {
            sorting:true,
            searching:true,
            search_column:[1,2],
            checkboxs:false,
            action:false,
            tophtml:'<div><ul class="buttons-list">@foreach($aName AS $k=>$p) <li><a class="btn {{($period == $k)?'active btn-info':'btn-primary'}}" href="/topuser/{{$k}}"><span>за </span>{{$p}}</a></li>@endforeach</ul></div>',
            bottomhtml:'<div></div>'
        }
        const oTable = new MyTable('#oTable',option);
    </script>
@endsection
