@extends('layouts.app')
@include('layouts.inc.meta',['title'=>'Бесплатные тренажер для изучения и запоминания английских слов','mdesc'=>'Бесплатные онлайн тренажеры для запоминания английских слов и изучения правил грамматики английского языка','mkey'=>'изучение слов,запомнить слова'])
@section('content')
    <div class="tophome">
        <div class="container pt-3 pb-3">
            <div class="row">
                <div class="col-md-7 col-lg-6" style="display: flex; align-items: center">
                    <div class="txt"> <div class="free">Быстрый <span>старт</span></div>
                    <p class="text-uppercase subtitle">В изучении английского языка</p>
                    <p class="desc">Бесплатные тренажеры и инструменты для изучения слов <br>и грамматики английского языка</p>
                        <div class="btns mt-4">
                            <a href="/test/vocabulary" class="btn btn-info">Тест на словарный запас</a>   <a href="/learnword/random" class="btn btn-danger">Учить слова</a>
                        </div>
                    </div>
                <br>
                </div>
                <div class="col-md-5 col-lg-6 text-center"><img src="{{asset('/storage/images/layouts/tophome.png')}}" alt=""></div>
            </div>
        </div>
    </div>
 <div class="container">
     <div class="title text-center pt-4"><h1>Тренажёр английских слов</h1></div>
     <p class="text-center mb-5"> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam deserunt eaque eos est illo, laborum odio odit pariatur perferendis possimus quas quod recusandae, totam vel voluptatum! Corporis delectus praesentium vitae</p>
          <div class="row info-blocks">
              <div class="col-sm-6 col-xl-3">
                  <div class="block"> <div class="icon"><img src="{{asset('/storage/images/icons/notepad.svg')}}" alt=""></div>
                      <b>Персональный словарь</b>
                  <p>Добавляйте слова в свой личный словарь, разбивайте их на группы и учите когда захотите</p>
                      <a href="/words/group" class="btn btn-primary">в словарь</a>
                  </div>
              </div>
              <div class="col-sm-6 col-xl-3">
                  <div class="block"> <div class="icon"><img src="{{asset('/storage/images/icons/cogs.svg')}}" alt=""></div>
                      <b>5 режимов изучения</b>
                      <p>Запоминайте слова при помощи нескольких режимов обучения, отрабатывая все свои навыки</p>
                      <a href="/words/group" class="btn btn-primary">в словарь</a>
                  </div>
              </div>
              <div class="col-sm-6 col-xl-3">
                  <div class="block"> <div class="icon"><img src="{{asset('/storage/images/icons/charts.svg')}}" alt=""></div>
                      <b>Отслеживание прогресса</b>
                      <p>Следите за своим прогрессом изучения новых слов и фраз соревнуясь с другими участниками</p>
                      <a href="/words/group" class="btn btn-primary">в словарь</a>
                  </div>
              </div>
              <div class="col-sm-6 col-xl-3">
                  <div class="block"> <div class="icon"><img src="{{asset('/storage/images/icons/errors.svg')}}" alt=""></div>
                      <b>Работа над ошибками</b>
                      <p>Отслеживайте слова которые вам сложно запомнить и где вы наиболее часто делаете ошибки</p>
                      <a href="/words/group" class="btn btn-outline-light">в словарь</a>
                  </div>
              </div>
          </div>
 </div>
    @if($wordgroups)
    <div class="bg-primary">
        <div class="container">
            <div class="title text-center mt-4 mb-4"><h2>Группы слов на английском</h2></div>
                <div class="row wordgroups">
                    <div class="col-6 col-md-4 col-lg-3 mb-4">
                        <div class="block h-100">
                            <div class="img">
                                <a href="/learnword/levels"><img class="img-fluid" src="/storage/images/wordgroup/1.png" alt="Слова по уровням"></a>
                            </div>
                            <div class="desc p-2 h-100"> <div class="lang"><img src="{{asset('/storage/images/icons/en.svg')}}" alt=""></div>
                                <div class="title text-center"><p><a href="/learnword/levels">Слова по уровням</a></p></div>
                                <div class="small text-muted">Слова по уровням</div>
                            </div>
                            <div class="foot">
                                <div>20 000 <small class="text-muted">слов</small></div>
                                <div> <div class="btn-group"><a href="/learnword/levels" class="btn btn-danger">учить новые слова</a></div></div>
                            </div>
                        </div></div>
                    @foreach($wordgroups AS $group)
                        <div class="col-6 col-md-4 col-lg-3 mb-4">
                            <div class="block h-100">
                                <div class="img">
                                    <a href="/wordlist/{{$group->url}}"><img class="img-fluid" src="{{asset($group->image)}}" alt="{{$group->name}}"></a>
                                </div>
                                <div class="desc p-2 h-100"> <div class="lang"><img src="{{asset('/storage/images/icons/'.$group->lang.'.svg')}}" alt=""></div>
                                    <div class="title text-center"><p><a href="/wordlist/{{$group->url}}">{{$group->name}}</a></p></div>
                                    <div class="small text-muted">{{$group->description}}</div>
                                </div>
                                <div class="foot">
                                    <div>{{$group->qty}} <small class="text-muted">{{ trans_choice('слово|слов|Публикаций', $group->qty)}}</small></div>
                                    <div> <div class="btn-group"><span class="btn btn-info" data-url="{{route('group.copygroup',$group->id)}}" onclick="copyGroup(this)" title="Добавить группу в свой словарь">в словарь</span> <a href="/wordlist/{{$group->url}}" class="btn btn-danger">учить</a></div></div>
                                </div>
                            </div></div>
                    @endforeach
                </div>

        </div>
    </div>
    @endif
     <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
     <div class="container">
  <div class="title"><h1>{{$page->title}}</h1></div>
    {{$page->content}}
 </div>
    <!-- Модальное окно -->
    <div class="modal fade" id="winModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content"></div></div>
    </div>
    <script>
        let winModal;

        document.addEventListener("DOMContentLoaded", () => {
            winModal = new bootstrap.Modal(document.getElementById('winModal'));
        });
        function copyGroup(btn){
            let url = btn.dataset.url+'?type=wordgroup';
            let req = new XMLHttpRequest();req.open('GET',url,false);req.send(null);
            document.querySelector('#winModal .modal-content').innerHTML = req.responseText;
            winModal.show();
            return false;
        }

    </script>
@endsection
