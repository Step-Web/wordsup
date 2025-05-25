@if($messege)
    <div class="slider"><h4 class="text-warning">{{$messege}}</h4></div>
@else
<div id="myProgress"><div id="myBar" style="">0%</div></div>
<div class="slider">
    <div id="timebar" class="d-none">0</div>
    <div id="prestart"><div class="video-play-button" onclick="start()"><span></span></div><small class="p1">Не торопитесь мы даем время за правильный и снимаем за неправильный ответ.</small></div>
    <div class="slider__wrapper" id="slider">
        @php
            $words = $group['words'];
               $all = sizeof($words)-1;
               $last = '0';
            @endphp

        @foreach($words AS $k => $v)
            @php
            $items = array();
            $arr = explode(',',$v['translate']);
            $base = $arr[0];
            unset($arr[0]);
            $more = implode(', ',$arr);
            $rand = rand(0,1);
            if($rand == 0){
                $keys = array_rand($words,3);
                foreach ($keys AS $r){
                    if($words[$r]['word'] != $v['word']){
                        $word = $words[$r]['word'];
                    }
                }
                $btnNum = array(0,1);
                $trueVariant = $v['word'];
            } else {
                $word = $v['word'];
                $btnNum = array(1,0);
                $trueVariant = $word;
            }
            $last = ($all == $k)?1:0;
        @endphp

        <div class="slider__item" id="{{$v['id']}}" data-num="{{$k}}" data-last="{{$last}}" data-type="sprint" data-audio="{{$v['audio']}}">
            <span class="stat l{{$v['progress']??0}}" data-start="{{$v['progress']??0}}"></span>
            <div class="question mb-4">{{$base}}</div>
            <p class="answer mb-4">{{$word}}</p>
            <div class="trueVariant" style="display: none">{{$trueVariant}}</div>
            <div class="buttons"><span onclick="btnClick(this)" class="btn-answer btn btn-danger" data="{{$btnNum[1]}}">Не верно</span> <span class="btn-answer btn btn-success" onclick="btnClick(this)" data="{{$btnNum[0]}}">Верно</span></div>
        </div>
       @endforeach

        <div class="finish">
            <div class="row">
                <div id="cont" data-pct="100">
                    <svg id="svg"  width="200" height="200" viewport="0 0 100 100" version="1.1" xmlns="http://www.w3.org/2000/svg">
                        <circle r="90" cx="100" cy="100" fill="transparent" stroke-dasharray="565.48" stroke-dashoffset="0"></circle>
                        <circle id="bar" r="90" cx="100" cy="100" fill="transparent" stroke-dasharray="565.48" stroke-dashoffset="0"></circle>
                    </svg>
                </div>
                <div class="infofinish"><h3>Ваш результат</h3> <div class="p-info"><p class="seconds">Время: <b id="seconds" class="text-warning">0</b></p><p>Верно: <b id="answerTrue"></b></p><p>Ошибки: <b id="answerFalse"></b></p><p>Всего: <b id="answerTotal"></b></p></div>
                    <div class="btn-group mb-4"><button class="btn btn-primary" type="button" onclick="showDetail()">Посмотреть ответы</button><button type="button" class="btn btn-danger" data-bs-dismiss="offcanvas"><i class="fas fa-times"></i></button></div>
                </div>
            </div>
        </div>
    </div>
    <a class="slider__control slider__control_left d-none" href="#" role="button"><i class="fas fa-chevron-left"></i></a>
    <a class="slider__control slider__control_right d-none" href="#" role="button"><i class="fas fa-chevron-right"></i></a>
    <ul class="indicators"></ul>
</div>
<input type="hidden" id="trying" value="0">
<div id="showdetail" style="left:-100%; bottom: 11px"><span class="btn btn-primary position-fixed" style="bottom: 16px;margin-left:1em;" onclick="showDetail()"><i class="fas fa-chevron-circle-left"></i> Скрыть ответы</span>
    <div id="showAnswers"></div>
    <div class="text-center mt-4 mb-4"><a class="btn btn-danger" href="/words/errors"><i class="fas fa-exclamation-triangle"></i> Ваша история ошибок</a><br><br><br><br></div>
</div>
<span class="audio-icon" id="data-audio" data-audio="" data-voice="f" style="display: none" onclick="playWord(this)"></span>

<style>
    #prestart{ position: absolute; top: 0; left: 0; right: 0; bottom: 0; width: 100%; background: #f8fafc; z-index: 3; text-align: center;}
    #prestart span{ position: relative; top: 30%;  }

    .btn-answer{display: inline-block;position: relative; z-index:2;margin: 0.5em; min-width: 145px;}


    .video-play-button { cursor: pointer;
        position: absolute;
        z-index: 9;
        top: 50%;
        left: 50%;
        transform: translateX(-50%) translateY(-50%);
        box-sizing: content-box;
        display: block;
        border-radius: 50%;
        padding: 18px 20px 18px 28px;
    }
    .video-play-button:before {
        content: "";
        position: absolute;
        z-index: 0;
        left: 50%;
        top: 50%;
        transform: translateX(-50%) translateY(-50%);
        display: block;
        width: 80px;
        height: 80px;
        background: #0a84f8;
        border-radius: 50%;
        animation: pulse-border 1500ms ease-out infinite;
    }
    .video-play-button:after {
        content: "";
        position: absolute;
        z-index: 1;
        left: 50%;
        top: 50%;
        transform: translateX(-50%) translateY(-50%);
        display: block;
        width: 80px;
        height: 80px;
        background: #022d62;
        border-radius: 50%;
        transition: all 200ms;
    }

    .video-play-button:hover:after {
        background-color: #022d62;
    }
    .video-play-button span {
        display: block;
        position: relative;
        z-index: 3;
        width: 0;
        height: 0;
        border-left: 32px solid #fff;
        border-top: 22px solid transparent;
        border-bottom: 22px solid transparent;
    }

    #prestart small { color: grey;
        position: absolute;
        bottom:20%;
        margin: auto;
        left: 0;
        right: 0;
    }
</style>
@endif
