@if($messege)
    <div class="slider"><h4 class="text-warning">{{$messege}}</h4></div>
@else
<div id="myProgress"><div id="myBar" style="">0%</div></div>
<div class="slider">
    <div class="slider__wrapper" id="slider">
        @php
            $wk=0;
        @endphp
        @foreach($group['words'] AS $k=>$val)
            @php
            $items = array();
            $arr = explode(',',$val['translate']);
            $base = $arr[0];
            unset($arr[0]);
            $more = implode(', ',$arr);
            $item = mb_strtolower($val['word'], 'UTF-8');
            $arr = preg_split('//u',$item,-1,PREG_SPLIT_NO_EMPTY);
            $t = mb_strlen($item);
            $dots = '';
            for ($i = 0; $i < $t; $i++) {
                $dots .= ($item[$i] != ' ')?'<span class="emptyletter"></span>':' ';
                $char = mb_substr($item, $i, 1);
            }
            $arr = array_diff($arr, array(' '));
            shuffle($arr);
            $clickletters = array_count_values($arr);

            @endphp
            <div class="slider__item" id="{{$val['id']}}" data-num="{{$wk}}" data-type="asembler" data-audio="{{$val['audio']}}">
            <span class="stat l{{$val['progress']??0}}" data-start="{{$val['progress']??0}}"></span>
            <div class="falseVariant d-none"></div>
            <div class="question">{{$base}}</div>
            <div class="trueVariant p-3 h-100" data-true="{{$item}}">{!! $dots !!}</div>
            <div class="clickletters" data-words="{{$item}}">
                @foreach($clickletters AS $letter=>$badge)
                  @php  $n = ($badge > 1)?'<span class="badge">'.$badge.'</span>':'' @endphp
                <div onclick="clickLetter(this)" class="{{$letter}}">{{$letter}}{!! $n !!}</div>
                @endforeach
            </div>
        </div>
            @php
                $wk++;
            @endphp
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


    p.translate{ line-height: normal;}
    .words{ position: relative;}
    .checkquestion{ font-weight: bold; font-size: 1.4em}
    .red{ color: #df6055}
    .green{ color: #37b11b}
    .words{ position: relative}
    .word,.lastword{ display: inline-block; margin:0 0.5em;}

    .letter-tooltip{ opacity: 0; color: #fff; background: green; width: 30px; height: 30px; line-height: 30px; text-align: center; position: absolute;bottom:-31px; z-index: 99999; margin-top:6px; border-radius: 4px;
        animation-name: fade;
        animation-duration: 3.5s;
        animation-iteration-count: 1;
    }
    .letter-tooltip::before {
        content: '';
        display: inline-block;
        border-left: 7px solid transparent;
        border-right: 7px solid transparent;
        border-bottom: 7px solid #ccc;
        border-bottom-color: green;
        position: absolute;
        top: -6px;
        left: 8px;
    }



</style>
@endif
