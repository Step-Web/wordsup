@extends('layouts.app')
@include('layouts.inc.meta',['title'=>'Ваш профиль','index'=>'noindex'])
@section('content')
<div class="container">
  <div class="block pt-1 mb-4">


          <div class="title d-flex justify-content-between flex-wrap"><h1>Ваш профиль</h1><div class="btn-group"><a class="btn btn-outline-primary btn-sm" href="/topuser/all"><small>место в рейтинге:</small> <b class="fs-6">{{$user->position}}</b></a></div></div>
          @if (session('status'))
              <div class="alert alert-success" role="alert"> {{ session('status') }}</div>
          @endif





      <div class="card bg-white">
          <div class="row g-0">
              <div class="col-md-4 p-3 text-center">
                  <img class="img-thumbnail"  src="{{asset($user->userpic??'/storage/images/user/noimg.svg')}}?uniqid={{rand()}}" alt="" width="90" >
                  <div class="mt-2">
                      <span class="badge bg-success">Online</span>
                  </div>
              </div>
              <div class="col-md-8">
                  <div class="card-body">
                      <h5 class="card-title d-flex justify-content-between align-items-center">
                          <div><b>{{$user->username}}</b></div>
                          <a class="btn btn-primary btn-sm" href="{{route('useredit',$user->id)}}">изменить</a>
                      </h5>

                      <div class="row mb-4">
                          <div class="col-sm-6 mt-2 mb-2"><p class="card-text"><small class="text-muted">Ваш ID:</small> <b>{{$user->id}}</b></p></div>
                          <div class="col-sm-6 mt-2 mb-2"><p class="card-text"><small class="text-muted">E-mail:</small> <b>{{$user->email}}</b></p></div>
                          <div class="col-sm-6 mt-2 mb-2"><p class="card-text"><small class="text-muted">Имя:</small> <b>{{$user->name}}</b></p></div>
                          <div class="col-sm-6 mt-2 mb-2"><p class="card-text"><small class="text-muted">Фамилия:</small> <b>{{$user->surname}}</b></p></div>
                          <div class="col-sm-6 mt-2 mb-2"><p class="card-text"><small class="text-muted">Возраст:</small> <b>{{\Carbon\Carbon::parse($user->age)->diff(\Carbon\Carbon::now())->format('%y') }}</b></p></div>

                          <div class="col-sm-6 mt-2 mb-2"><p class="card-text"><small class="text-muted">Уровень:</small> <b>{{$levels[$user->level]}}</b></p></div>
                      </div>

                      <div class="border-top pt-2">
                          <div class="row text-center">
                              <div class="col">
                                  <h6 class="small text-muted mt-2">Слов <span class="d-none d-lg-inline-block">в словаре</span></h6>
                                  <b>{{session()->get('user.words')}}</b> из  <b>{{session()->get('user.limit.words')}}</b>
                              </div>
                              <div class="col border-start">
                                  <h6 class="small text-muted mt-2">Фраз <span class="d-none d-lg-inline-block">в словаре</span></h6>
                                  <b>{{session()->get('user.phrases')}}</b> из  <b>{{session()->get('user.limit.phrases')}}</b>
                              </div>
                              <div class="col border-start">
                                  <h6 class="small text-muted mt-2">Ошибки</h6>
                                  <b>
                                      @if (!empty(Cookie::get('wordsErrors'))  || !empty(Cookie::get('phrasesErrors')))
                                          @php
                                              $werrors = sizeof((Cookie::get('wordsErrors'))?explode('::',Cookie::get('wordsErrors')):[]);
                                              $perrors = sizeof((Cookie::get('phrasesErrors'))?explode('::',Cookie::get('phrasesErrors')):[]);
                                          @endphp
                                           {{$werrors+$perrors}}
                                      @else
                                          0
                                      @endif
                                  </b>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="card-footer bg-white">
              <div class="d-flex justify-content-around">
                  <a href="/words/group" class="btn btn-info">
                      <i class="fas fa-th"></i> Мои слова
                  </a>
                  <a href="/phrases/group" class="btn btn-info">
                      <i class="fas fa-th-list"></i> Мои фразы
                  </a>
                  <a href="/words/errors" class="btn btn-danger">
                      <i class="fas fa-exclamation-triangle"></i> Мои ошибки
                  </a>
              </div>
          </div>
      </div>









</div>





    <div class="block pt-1">
        <div class="title">
            <h1>Статистика <b class="red" id="period"></b></h1>
            <p style="font-size:1em;margin-top: -10px;">
                <span class="text-muted">За <b id="totaldays">всё время</b> изучения вы получили: <b id="totalscore">0 баллов</b></span>
            </p>
        </div>
        <div id="btnPeriod">
            <button type="button" class="btn btn-sm" data-period="year" onclick="showChart(this)">за год</button>
            <button type="button" class="btn btn-sm" data-period="30" onclick="showChart(this)">за месяц</button>
            <button type="button" class="btn btn-sm" data-period="7" onclick="showChart(this)">за неделю</button>
        </div>



        <div id="chart-container">
            <canvas id="myChart"></canvas>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

</div>

    <script>
        function endWord(number, words) {return words[(number % 100 > 4 && number % 100 < 20) ? 2 : [2, 0, 1, 1, 1, 2][(number % 10 < 5) ? Math.abs(number) % 10 : 5]]; }

        window.onload = function() {
            const config =  {
                type: 'bar',
                data: {
                    datasets: [{
                        label: 'Заработанные балы',
                        backgroundColor: '#0b2242',
                        borderColor: '#0b2242',
                        hoverBackgroundColor: '#0c70e2',
                    }]
                },
                options: {
                    scales: {
                        x: {
                            ticks: {
                                maxRotation: 90,
                                minRotation: 0
                            }
                        }
                    }
                }
            };
            const ctx = document.getElementById('myChart').getContext('2d');
            window.myChart = new Chart(ctx, config);
            var items = document.querySelectorAll('#btnPeriod button');
            items[items.length-1].click();
        };



        function showChart(btn) {
            const date = new Date();
            const y =  date.getFullYear();
            const period = btn.dataset.period;
            const days = (period > 0)?'&period='+period:'';

            fetch('/user/statistic/{{$user->id}}?act=get&year='+y+days)
                .then(res => res.text())
                .then(function (data) {
                    const arr = JSON.parse(data);
                    console.log(arr);
                    data = arr.chart;
                    let added = [];
                    let score = [];
                    for (let i in data) {
                        added.push(data[i].added);
                        score.push(data[i].score);
                    }
                    let txtscore = (Number(arr.score) < 0)? arr.score * -1:arr.score;
                    document.getElementById('period').innerText = btn.innerText;
                    document.getElementById('totaldays').innerText = data.length +' '+ endWord(data.length,['день','дня','дней']);
                    document.getElementById('totalscore').innerText = arr.score +' '+ endWord(txtscore,['балл','балла','баллов']);
                    window.myChart.data.labels = added;
                    window.myChart.data.datasets[0].data = score;
                    window.myChart.update();
                });
            const btns = btn.parentNode;
            btns.querySelectorAll('button').forEach(function (b, i){  b.classList.remove('btn-primary');   });
            btn.classList.add('btn-primary');
        }
       // showChart();
    </script>
@endsection
