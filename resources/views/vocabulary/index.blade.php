@extends('layouts.app')
@include('layouts.inc.meta',['title'=>$page->mtitle])
@include('layouts.inc.breadcrumbs')

@section('content')
    <div class="container">
   <div class="block pt-1">
       <div class="title"><h1>{{$page->mtitle}}</h1></div>
       <div class="text-center text-muted small">время прохождения</div>
       <div id="timer"><span>00</span><b>:</b><span>00</span></div>

     @php


         $r = rand(0,3);
          $js = '';
          $trans = '';
          $max = 0;
          $result = 0;
          $all_words = [];
      @endphp
       <form id="form" action="{{route('vocabulary.store')}}" method="post">
           @csrf
       <div class="row" id="cbox">
           @foreach($words AS $w)
                @php
                   $verify = rand(0,4);
                    if($verify > 0){
                  $js .= '"'.$w->translate.'",';
              }
              $win = ($verify != 0)?'':'class="verify" ';
              $max += $w->wgroup;
               $all_words[] = $w->id;

              @endphp

           <div class="col-6 col-sm-4 col-md-3">
               <p class="checkbox d-flex">
                   <label>
                       <input {!!$win!!}type="checkbox" id="input{{$w->id}}" name="correct_words[{{$w->id}}]" value="{{$w->wgroup}}" data-id="{{$w->id}}" data-word="{{$w->word}}"  data-translate="{{$w->translate}}" onchange="checkWord(this)"><b id="word{{$w->id}}" class="rating{{rate($w->wgroup)}}"> {{$w->word}}</b>
                   </label> <label><span class="audio-icon" data-audio="{{$w->audio}}" data-voice="f" onclick="playWord(this)"><i class="fas fa-volume-down"></i></span></label>
               </p>
           </div>
               @endforeach
               <input type="hidden" name="lang" value="en">
               <input type="hidden" name="all_words" value="{{implode(',',$all_words)}}">
               <input type="hidden" name="maxscore" value="{{$max}}">
               <input type="hidden" id="cheat" name="cheat" value="0">
               <input type="hidden" id="time" name="time" value="0">
               <input type="hidden" id="startTimer" value="0">
           <p class="text-center mt-3"> <button id="endTest" class="btn btn-danger btn-lg d-none">Завершить тест</button></p>
       </div>
       </form>
   </div>
   </div>

    <div class="modal fade" data-bs-backdrop="static" id="checkModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
               <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title" id="exampleModalToggleLabel">Вы точно знаете слово?</h5> <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                    <div class="modal-body" id="wordid" data-id="0"></div>
                <input type="hidden" value="0">
                   <div class="modal-footer">
                       <button class="btn btn-danger" data-bs-toggle="modal" data-bs-dismiss="modal">Я не знаю</button>
                   </div>
                </div>
            </div></div>
    </div>
    <audio id="audio"></audio>
    <script>




        let sec = document.getElementById("time");
        let cheat = document.getElementById("cheat");
        let modal = document.getElementById('checkModal');
        let startTimer = document.getElementById("startTimer");
        let checkModal;
        const  arr = [{!! $js !!}];
        document.addEventListener("DOMContentLoaded", () => {
            checkModal = new bootstrap.Modal(modal);
        });
        function shuffle(array) {
            var currentIndex = array.length, temporaryValue, randomIndex;
            while (0 !== currentIndex) {
                randomIndex = Math.floor(Math.random() * currentIndex);
                currentIndex -= 1;
                temporaryValue = array[currentIndex];
                array[currentIndex] = array[randomIndex];
                array[randomIndex] = temporaryValue;
            }
            return array;
        }

        Array.prototype.rand = function() {
            return this[Math.floor(Math.random() * this.length)];
        }
      function checkWord(w){
          if(w.checked == true){
              if(startTimer.value == 0){ startTimer.value = 1; timer();}
              if(document.querySelectorAll('.checkbox input:checked').length > 9){ document.getElementById('endTest').classList.remove('d-none'); }
              let word = '';
              let translate =  w.dataset.translate;
             if(w.className){
              let words = shuffle([arr.rand(),arr.rand(),arr.rand(),translate]);
              for (let v of words) {
                  let check = (translate == v)?1:0;
                  word +=  '<p class="btn btn-primary btn-block" onclick="checkAnswer(this)" data-check="'+check+'">'+v+'</p>';
              }
                 document.querySelector('#checkModal .modal-body').innerHTML = '<p><b class="checkWord">'+w.dataset.word+'</b></p>'+word+'';
                 document.getElementById('wordid').dataset.id = w.dataset.id;
                 checkModal.show();
                 modal.querySelector('input').value = 0;
             }
          }
      }

function checkAnswer(btn){
    let cn = '';
    let answer = 0;
    let delay = 1500;
   if(btn.dataset.check > 0){
       cn = 'btn-success';
       answer = 1
       delay = 300;
   } else {
       cn =  'btn-danger';
   }
    btn.classList.add(cn);
    modal.querySelector('p[data-check="1"]').classList.add('btn-success');
    modal.querySelector('input').value = answer;
    let btns = modal.querySelectorAll('p.btn');
    for (let v of btns) { v.removeAttribute('onclick');}

    setTimeout('checkModal.hide()', delay);

}

        modal.addEventListener('hidden.bs.modal', function (e) {
            let id =  document.getElementById('wordid').dataset.id;
            let input = document.getElementById('input'+id);
            if(modal.querySelector('input').value == 0){
                input.setAttribute('disabled', 'disabled');
                document.getElementById('word'+id).className = 'text-secondary';
                cheat.value = Number(cheat.value) + 1;
            }
        })

        function timer(){
            let time = 0;
            let timerShow = document.getElementById("timer");

            if(timerShow){
                timeMinut = parseInt(time) * 60;
                timerun = setInterval(function () {
                    seconds = timeMinut%60;
                    minutes = Math.floor(timeMinut/60%60);
                    if(seconds < 10) seconds = '0'+seconds;
                    if(minutes < 10) minutes = '0'+minutes;
                    let strTimer = `<span>${minutes}</span><b>:</b><span>${seconds}</span>`;
                    timerShow.innerHTML = strTimer;
                    document.getElementById('time').value = time++;
                    ++timeMinut;
                }, 1000);

            }
        }
        document.addEventListener("DOMContentLoaded", function (){
            var uncheck=document.getElementsByTagName('input');
            for(var i=0;i<uncheck.length;i++)  {if(uncheck[i].type=='checkbox') uncheck[i].checked=false;}
            startTimer.value = 0;
            sec.value = 0;
            cheat.value = 0;
        });
    </script>
    <style>
        #checkModal p{ width: 100%; text-align: center}
        .checkWord{ font-size: 3em;}
        .verify + b{ background: #eee;}
        #timer{ display: flex; justify-content: center; align-items: center; font-weight: bold; margin-bottom: 20px;font-size: 0.85em}
        #timer span{position: relative; display:block;  margin:10px 5px;  color: #eee; border-radius: 4px; height:2em; width:2em; line-height: 2em; text-align: center; font-size: 2em; border-top: 1px solid grey; border-right: 1px solid grey;
            background: rgb(24,30,33);
            background: -moz-linear-gradient(0deg, rgba(24,30,33,1) 0%, rgba(41,50,55,1) 100%);
            background: -webkit-linear-gradient(0deg, rgba(24,30,33,1) 0%, rgba(41,50,55,1) 100%);
            background: linear-gradient(0deg, rgba(24,30,33,1) 0%, rgba(41,50,55,1) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#181e21",endColorstr="#293237",GradientType=1);
        }
        #timer span::after{content:"";display: block; opacity: 0.8; position: absolute; top: 1em;width: 100%; border-top:1px solid #000;border-bottom:1px solid #293237;  }
        #timer.red span{ color: #e04121}
        #timer b {font-size: 2em; color: #181e21;
            display: inline-block;
            margin: 0 1px;
            transition: 1s;
            animation: blink 0.5s 1;
            animation-delay: 0.6s;
        }
        .checkbox{ font-size: 1.1em}
        .checkbox .audio-icon{ border: none; width: 1em;height: 1em; left: 0.3em; top: 0.3em }
        .checkbox .audio-icon.play::after{width: 1.1em;height: 1.1em;}
        .checkbox .audio-icon i{ font-size: 1em }
        .audio-icon.play i{font-size: 0.5em}
    </style>
@endsection
