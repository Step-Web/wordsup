@extends('layouts.app')
@include('layouts.inc.meta',['title'=>$page->mtitle,'mdesc'=>$page->mdesc,'mkey'=>$page->mkey,'index'=>$page->index])
@include('layouts.inc.breadcrumbs',['url'=>'sentence','title'=>'все фразы'])
@section('content')
    @php $total = $sentences->total() @endphp
    <div class="container">
        <div class="block mb-4 pt-1">
            <div class="title"><h1>Предложения с словом {{$w}}</h1> <span>Найдено <b>{{$total}}</b> примеров английских предложений с {{$w}}</span></div>
            <form id="search"><div class="search-panel"><div class="long-search bg-primary pt-md-3 pb-md-3 ps-md-3 pe-md-3"> <div class="input-group input-group-lg">
                            <input type="text" id="add_words" name="word" value="{{$w}}" class="form-control" placeholder="Ведите слова для поиска" autocomplete="off">
                            <button class="input-group-text btn btn-info"><i class="fas fa-search"></i> <span class="d-none d-sm-inline">Поиск</span></button>
                        </div>
                    </div></div></form>
            <div style="position: relative;"><ul id="search_result" class="search_result" style="display: none"></ul></div>
            <div class="info-word mt-1">
                @if(!empty($word->word))
                    <div class="wordblock mt-3"><div class="audio-icon" data-audio="{{$word->audio}}" data-voice="f" onclick="playWord(this)"><i class="icon-play"></i></div> <div><b class="text-info fs-5 text-uppercase">{{$word->word}}</b> <span class="text-secondary fs-5 fw-bold">{{($word->ts)?'/'.$word->ts.'/':''}}</span> <span> - {{$word->translate}}</span></div></div>
                @elseif(!empty($word))
                    @foreach($word AS $v)
                        <div class="wordblock mt-3"><div class="audio-icon" data-audio="{{$v->audio}}" data-voice="f" onclick="playWord(this)"><i class="icon-play"></i></div> <div><b class="text-info fs-5 text-uppercase">{{$v->word}}</b> <span class="text-secondary fs-5 fw-bold">{{($v->ts)?'/'.$v->ts.'/':''}}</span> <span> - {{$v->translate}}</span></div></div>
                    @endforeach
                @endif
            </div>
            @foreach ($more as $m)
                <div class="wordblock mt-3"><div class="audio-icon" data-audio="{{$m->audio}}" data-voice="m" onclick="playWord(this)"><i class="icon-play"></i></div> <div><a class="fs-5 fw-bold" href="/sentence/word/{{$m->word}}">{{$m->word}}</a> - {{$m->translate}} <small>{{$m->sentences}}</small></div></div>
            @endforeach

        </div>
        @if($total > 0)
       @foreach ($sentences as $key=>$s)
                   @php $str = '';
                      $original = explode(' ',$s->phrase);
                      $arr = explode(' ',strtolower(rtrim($s->phrase,'.')));
                      $aWord = explode(' ',strtolower($w));
                    @endphp
                       @foreach ($arr as $k=>$v)
                             @php
                             $str .= (in_array($v, $aWord))? '<span class="text-info">'.$original[$k].'</span> ':$original[$k].' ';
                             @endphp
                       @endforeach
                <div class="block sentenece mb-4 p-3 pb-4 pb-4">
                    <div class="num">#{{ $sentences->firstItem() + $key }}</div>
                    <div class="d-flex">
                        <div class="wordblock fs-4"><div class="audio-icon" data-audio="{{($s->tID > 0)?$s->tID:'s'.$s->id}}" data-voice="f" onclick="playPhrase(this)"><i class="icon-play"></i></div></div>
                        <div>
                            <div class="fw-bold text-primary fs-4">{!! $str !!}</div>
                            <div class="text-muted fs-5">{{ $s->translate }}</div>
                        </div>
                    </div>
                    <div class="foot-icons"><i class="fas fa-graduation-cap text-primary" data-id="{{$s->id}}" data-bs-toggle="modal" data-bs-target="#winModal" onclick="addPhrase(this);"></i></div>
                </div>
       @endforeach
        @else
           <span class="text-danger">Фразы со словом <b>{{$w}}</b> не найдены!</span> <span class="text-muted">Попробуйте ввести слово в другой форме.</span>
        @endif
        <audio id="audio"></audio>
<div class="justify-content-center show-only-pagination">{{ $sentences->onEachSide(1)->links('pagination::bootstrap-5') }}</div>
</div>
    <div class="modal fade" id="winModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content"></div></div>
    </div>
    <script src="/assets/js/sentenece.js"></script>
@endsection
