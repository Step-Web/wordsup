@if($messege)
    <div class="slider"><h4 class="text-warning">{{$messege}}</h4></div>
@else
<div id="myProgress"><div id="myBar" style="">0%</div></div>
<div class="slider">
    <div class="slider__wrapper" id="slider">
       @php $wk=0; @endphp
        @foreach ($group['words'] AS $val)
        <div class="slider__item" id="{{$val['id']}}" data-num="{{$wk}}" data-type="reverse" data-audio="{{$val['audio']}}">
            <span class="stat l{{$val['progress']??0}}" data-start="{{$val['progress']??0}}"></span>
            <div class="question">{{$val['word']}}</div>
            <div class="answer trueVariant invisible">{{$val['translate']}}</div>
            <div class="variants">
                @foreach($val['variants'] AS $v)
                    <div class="variant" onclick="checkVaiant(this)">{{$v}}</div>
                @endforeach
            </div>
        </div>
            @php $wk++; @endphp
        @endforeach


        <div class="finish">
            <div class="row">
                <div id="cont" data-pct="100">
                    <svg id="svg"  width="200" height="200" viewport="0 0 100 100" version="1.1" xmlns="http://www.w3.org/2000/svg">
                        <circle r="90" cx="100" cy="100" fill="transparent" stroke-dasharray="565.48" stroke-dashoffset="0"></circle>
                        <circle id="bar" r="90" cx="100" cy="100" fill="transparent" stroke-dasharray="565.48" stroke-dashoffset="0"></circle>
                    </svg>
                </div>
                <div class="infofinish"><h3>Ваш результат</h3> <div class="p-info"><p>Верно: <b id="answerTrue"></b></p><p>Ошибки: <b id="answerFalse"></b></p><p>Всего: <b id="answerTotal"></b></p></div>
                    <div class="btn-group mb-4"><button class="btn btn-primary" type="button" onclick="showDetail()">Посмотреть ответы</button><button type="button" class="btn btn-danger" data-bs-dismiss="offcanvas"><i class="fas fa-times"></i></button></div>
                </div>
            </div>
        </div>
    </div>
    <a class="slider__control slider__control_left d-none" href="#" role="button"></a>
    <a class="slider__control slider__control_right d-none" href="#" role="button"></a>
    <ul class="indicators"></ul>
</div>
<input type="hidden" id="trying" value="0">
<div id="showdetail" style="left:-100%; bottom: 11px"><span class="btn btn-primary position-fixed" style="bottom: 16px;margin-left:1em;" onclick="showDetail()"><i class="fas fa-chevron-circle-left"></i> Скрыть ответы</span>
    <div id="showAnswers"></div>
    <div class="text-center mt-4 mb-4"><a class="btn btn-danger" href="/words/errors"><i class="fas fa-exclamation-triangle"></i> Ваша история ошибок</a><br><br><br><br></div>
</div>
<span class="audio-icon" id="data-audio" data-audio="" data-voice="f" style="display: none" onclick="playWord(this)"></span>
@endif
