@extends('layouts.app')
@include('layouts.inc.meta',['title'=>'Результат'])


@section('content')
    @php
        $vocab = ($data['vocabulary'] < 350)?350:$data['vocabulary'];

        @endphp
<div class="container">
<div class="block pt-1">
<div class="title"><h1>Результаты теста</h1></div>

<div class="text-center">
<p class="text-muted small">Ваш примерный словарный запас:</p>
<p class="text-primary" style="font-size: 4em; line-height: 1em"><b id="counter">{{$vocab}}</b> <small>слов</small></p>
<p>Вы заработали <i class="fas fa-coins text-warning"></i> <b>{{round($data['score']/200)}}</b> балов после прохождения теста</p>
<div class="moreinfo">
 <div><small class="text-muted">Время затрачено:</small> <b>{{$data['time']}}</b></div>
 @if($data['cheat'] > 0)<div><small class="text-muted">Попыток обмана:</small> <b class="red">{{$data['cheat']}}</b></div>@endif

</div>

<p class="text-center text-muted small">Ваш примерный уровень языка:</p>
<div class="levelline">
 <div id="myProgress"><div id="myBar"></div></div>
 @php

                            $lev = ($vocab < 500)?'Beginner':'Elementary';
                                $levels = array(
                                           array('A1',$lev,'500','6'),
                                           array('A2','Pre intermediate','1000','25'),
                                           array('B1','Intermediate','3000','40'),
                                           array('B2','Upper Intermediate','5000','58'),
                                           array('C1','Advanced','8000','75'),
                                           array('C2','Proficient','10000','95')
                                       );




               @endphp

               @foreach($levels AS $k=>$v)

                   @if($vocab < 500)
                       @php $active = 0 @endphp
                   @elseif($vocab > 10000)
                       @php $active = 5 @endphp
                   @elseif($vocab >= 500 && $vocab < 1000)
                       @php $active = 0 @endphp
                   @elseif($vocab >= 1000 && $vocab < 3000)
                       @php $active = 1 @endphp
                   @elseif($vocab >= 3000 && $vocab < 5000)
                       @php $active = 2 @endphp
                   @elseif($vocab >= 5000 && $vocab < 8000)
                       @php $active = 3 @endphp
                   @elseif($vocab >= 8000 && $vocab < 10000)
                       @php $active = 4 @endphp
                   @endif
                       <div id="level{{$v['3']}}" class="level"><span class="golink" data-link="/test-vocabulary/#proficiency-levels">{{$v['0']}}</span><small>{{$v['1']}}</small></div>
               @endforeach

           </div>

           <hr>
           <p class="text-center text-muted small">Ваши результаты по тесту:</p>
           <div class="columns row">
               <div class="col-sm-6 col-md-3"><div class="dial success" data-width="90" data-lineWidth="10" data-color="#008306">{{$data['correct']}}</div><span>Вы дали <b>{{$data['correct']}}</b> правильных ответа в тесте</span></div>
               <div class="col-sm-6 col-md-3"><div class="dial info" data-width="90" data-lineWidth="10" data-color="#0c70e2">{{($data['score']/$data['maxscore'])*100}}</div><span>Вы набрали <b>{{$data['score']}}</b> балов из <b>{{$data['maxscore']}}</b> возможных</span></div>
               <div class="col-sm-6 col-md-3"><div class="dial info" data-width="90" data-lineWidth="10" data-color="#0b2242">{{(10 - $data['cheat']) * 10}}</div><span>@if($data['cheat'] > 0)Вы не прошли проверку <b>{{$data['cheat']}} раз(a)</b>@elseВы были честны и прошли все проверки @endif </span></div>
               <div class="col-sm-6 col-md-3"><div class="dial danger" data-width="90" data-lineWidth="10" data-color="#dc3545">{{$morethen['percent']}}</div><span>У <b>{{$morethen['better']}}</b> из <b>{{$morethen['all']}}</b> человек результат был хуже</span></div>
           </div>
           <hr>
       </div>
       <style>
           .moreinfo{ text-align: left; margin: auto;width: 180px;}
           .moreinfo small{ min-width: 130px; display: inline-block}
       </style>
       <div class="title"><h2>Ваши ответы</h2></div>
       <div class="bordercolor" id="yourwords">
           <div class="row">
               @php

                   $correct_words = explode(',',$data['correct_words']);
               @endphp
               @foreach($words AS $val)

                   @php  $class = (in_array($val->id,$correct_words))?'check text-success':'times text-danger';  @endphp
                   <p class="col-6 col-sm-4 col-md-3"><i class="fas fa-{{$class}}"></i> <b onclick="popoverShow(this)" class="helper-word" data-audio="{{$val->audio}}" data-rating="{{rate($val->wgroup)}}">{{$val->word}}</b></p>


               @endforeach

           </div>
       </div>
       <hr>
   </div>


    @if(!empty($data['oldtests']))
        <div class="block pt-1 mt-4">
            <div class="title"><h2>Последние результаты</h2></div>
        <table class="table table-bordered table-striped table-hover">
        @foreach($data['oldtests'] AS $val)

            <tr>
                <td class="w-100">{{dateAgo($val['added'])}}</td>
                <td>{{$val['vocabulary']}}</td>
                <td>{{$val['score']}}</td>
                <td><i class="fas fa-times text-danger fa-2x cursor-pointer" data-id="{{$val['id']}}" onclick="remove(this)"></i></td>
            </tr>
        @endforeach
        </table>
        </div>
    @endif
    <div class="block pt-1 mt-4">
        <div class="title"><h2>Знания слов по уровням</h2></div>
        <table class="table table-bordered table-hover" style="font-family: Roboto, sans-serif">
            <thead>
            <tr><th>№</th><th class="text-left w-75">Уровень языка</th><th>слова</th></tr>
            </thead>
            <tbody>
            <tr class="table-success"><td>A1</td><td class="text-left">Beginner, Elementary</td><td>500 - 1000</td></tr>
            <tr class="table-success"><td>A2</td><td class="text-left">Pre-intermediate</td><td> 1000 - 3000</td></tr>
            <tr class="table-warning"><td>B1</td><td class="text-left">Intermediate</td><td>3000 - 5000</td></tr>
            <tr class="table-warning"><td>B2</td><td class="text-left">Upper-Intermediate</td><td>5000 - 8000</td></tr>
            <tr class="table-danger"><td>C1</td><td class="text-left">Advanced</td><td>8000 - 10000</td></tr>
            <tr class="table-danger"><td>C2</td><td class="text-left">Proficient</td><td class="text-nowrap">10000 и более</td></tr>
            </tbody></table>
    </div>


    </div>
    <!-- Модальное окно -->
    <div class="modal fade" id="winModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content"></div></div>
    </div>
    <script>
        function move(w) {
            let i = 0;
            if (i == 0) {
                i = 1;
                let elem = document.getElementById("myBar");
                let width = 1;
                let id = setInterval(frame,17);
                function frame() {
                    if (width >= w) {
                        clearInterval(id);
                        i = 0;
                    } else {
                        width++;
                        let element=document.getElementById('level'+width);
                        if(element){
                            element.previousElementSibling.classList.add('pre-current');
                            element.classList.add('current');
                        }
                        elem.style.width = width + "%";
                    }
                }
            }
        }
        move(<?=$levels[$active][3]?>);


        function popoverShow(el){
            let old = document.querySelectorAll('.popover');
            for (let v of old) { v.remove(); }
            let w = el.innerText;
            let req = new XMLHttpRequest();
            req.open('GET', '/dictonary/translate/?word='+w, false);
            req.send(null);
            let popover = new bootstrap.Popover(el, {
            trigger: 'focus',
            placement:'bottom',
            content:'content'

        })

            popover.show()
           // document.querySelector('.popover .popover-header').innerHTML = '<b>'+w+'</b>';
            document.querySelector('.popover .popover-body').innerHTML = '<p>'+ req.responseText+'</p><hr> <div class="popover-footer"><span class="btn btn-primary" data-word="'+w+'" onclick="addword(this)">На изучение</span><span class="btn btn-danger" onclick="popoverHide(this)">Закрыть</span></div>';
        }
        function popoverHide(el){
         el.closest('.popover').remove();
        }
        function addword(btn){
            document.querySelector('.popover').remove();
            const w = btn.dataset.word;
            let req = new XMLHttpRequest();req.open('GET','/dictonary/addword/'+w,false);req.send(null);
            document.querySelector('#winModal .modal-content').innerHTML = req.responseText;
            fakeSelect('#groupnew');
        }


        let upto = {{$vocab - 30}};
        let counts = setInterval(updated, 1);

        function updated() {
            let count = document.getElementById("counter");
            count.innerHTML = ++upto;
            if (upto === {{$vocab}}) {
                clearInterval(counts);
            }
        }












        let dials = document.querySelectorAll('.dial');
        // Перебираем все .dial и пихftvем туда canvas с графиком.
        for (i=0; i < dials.length; i++){
             let width = Math.round(dials[i].dataset.width);
             let procent =  Math.round(Number(dials[i].innerText) * 10)/10;
             if(procent == 0) procent = 100;
              let lineWidth = (typeof dials[i].dataset.lineWidth != 'undefined') ? Number(dials[i].dataset.lineWidth) : width / 10;
              if(lineWidth >= width) lineWidth = width+1;
             let size = width+lineWidth;
               let color = dials[i].dataset.color;
               dials[i].innerHTML ='<canvas id="dial' + i + '" width="' + size + '" height="' + size + '"></canvas><p>' + procent + '%</p>';
              let canvas = document.getElementById("dial" + i);
              let context = canvas.getContext("2d");
               // считаем по формуле радианы
               let radian = 2*Math.PI*procent/100;
             // рисуем круг для фона
              context.arc(width/2+lineWidth/2, width/2+lineWidth/2, width/2, 0, 2*Math.PI, false);
                context.lineWidth = lineWidth;
                context.strokeStyle = '#ddd';
                context.stroke();
              // рисуем круг с процентами
              context.beginPath();
              context.arc(width/2+lineWidth/2, width/2+lineWidth/2, width/2, 1.5 * Math.PI, radian+1.5 * Math.PI, false);
              context.strokeStyle = color;
              context.stroke();
        }


function remove(el){

    let id = el.dataset.id;
    let formData = new FormData();
    formData.append("_token",  document.head.querySelector("[name=csrf-token]").content);
    formData.append("_method","DELETE");
    formData.append("id",id);
    fetch("/test/vocabulary/"+id, {
        method: "POST",
        body:formData,
    }).then((res) => res.json())
      .then(function(data) { if(data == 1)  el.closest('tr').remove();  })


}
    </script>
    <style>
        .bordercolor{position: relative; padding-left: 20px}
        .bordercolor::before{ content: ""; width:8px; height:100%;left:0; top:0;position: absolute; background: red;
            background-image: linear-gradient(to bottom,#025000 0,#63b100 25%,#dbc100 50%,#ff4000 75%,#ff2000 100%);
        }
        .bordercolor .row p{ margin:10px 0}
        .popover-footer{ display: flex; justify-content: space-between; }




        #copybtn{cursor: pointer}
        #myProgress {width: 100%;background-color: #ddd; position: relative; margin:2em 0 0 0;}
        table td{}
        table .badge{position: relative;left: 3px; top: -5px; font-size: 0.8em; background: #008306  }
        #myBar { width: 1%; height: 3px;background-color: #23302c;
        }
        .levelline .level{float: left; width: 16.6%; text-align: center; position: relative; top: -0.8em; z-index: 1;  }
        .levelline .level:last-child::before,.levelline .level:nth-child(2)::before{content: ""; position:absolute; background: #fff; height:2em; width: 50%;}
        .levelline .level:last-child::before{right:0;}
        .levelline .level:nth-child(2)::before{left:0; }
        .levelline .level span{ padding: 6px; border:2px solid #ddd; background: #fff; color: #999; border-radius: 50%; position: relative;z-index: 2; }
        .levelline .level span:hover{color: #f90!important;}
        .levelline .level.current span{  padding:9px; border:none; background: #23302c ; color: #fff; }
        .levelline .level.pre-current span { border:2px solid #23302c!important; background-color: #fff;    color: #23302c; }
        .levelline .level small{ display: block; font-size: 0.7em; margin-top: 10px; color: #ccc}
        .levelline .level.current small{color: #23302c;}
        .levelline .level.pre-current small{color: #23302c; opacity: 0.8;}
        .bordercolor{position: relative; padding-left: 20px}
        .bordercolor::before{ content: ""; width:8px; height:100%;left:0; top:0;position: absolute; background: red;
            background-image: linear-gradient(to bottom,#025000 0,#63b100 25%,#dbc100 50%,#ff4000 75%,#ff2000 100%);
        }
        .bordercolor .row p{ margin:10px 0}
        td a:hover{ text-decoration: none;opacity: 0.8 }



        .dial { border-color: #fff; color: #fff;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;}

        .dial p { text-align: center; font-weight: bold; color: #23302c; white-space: nowrap; position: relative; overflow: hidden; z-index: 1; margin: 0; }

        .dial canvas { position: absolute; }

        /* разные цветовые схемы */
        .dial.danger { color: #ff1d25; }
        .dial.danger p { color: #ff1d25; }

        .dial.warning { color: #f90; }
        .dial.warning p{ color: #f90; }



        .dial.success { color: #008306;  }
        .dial.success p { color: #008306 ; }

        .dial.blue { color: #1390d4; }
        .dial.primary { color: #23302c; }

    </style>
@endsection
