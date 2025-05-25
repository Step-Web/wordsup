@extends('layouts.app')
@include('layouts.inc.meta',['title'=>'Английские фразы с произношением и переводом','mdesc'=>'Английские фразы с произношением и переводом','mkey'=>'Английские фразы с произношением и переводом'])

@section('content')
    <div class="container">
        <div class="block mb-4 pt-1">
            <div class="title"><h1>Английские фразы с произношением и переводом</h1> <span> Все фразы имеют перевод и произношение, которое озвучено носителями английского языка.</span></div>
            <form id="search"><div class="search-panel"><div class="long-search bg-primary pt-md-3 pb-md-3 ps-md-3 pe-md-3"> <div class="input-group input-group-lg">
                            <input type="text" id="add_words" name="word" value="" class="form-control" placeholder="Ведите слова для поиска" autocomplete="off">
                            <button class="input-group-text btn btn-info"><i class="fas fa-search"></i> <span class="d-none d-sm-inline">Поиск</span></button>
                        </div>
                    </div></div></form>
            <div class="position-relative"><ul id="search_result" class="search_result" style="display:none"></ul></div>
        </div>
       @foreach ($sentences as $key=>$s)
            <div class="block sentenece mb-4 p-3 pb-4 pb-4">
                <div class="num">#{{ $sentences->firstItem() + $key }}</div>
                <div class="d-flex">
                    <div class="wordblock fs-4"><div class="audio-icon" data-audio="{{($s->tID > 0)?$s->tID:'s'.$s->id}}" data-voice="f" onclick="playPhrase(this)"><i class="icon-play"></i></div></div>
                    <div>
                        <div class="fw-bold text-primary fs-4">{{$s->phrase}}</div>
                        <div class="text-muted fs-5">{{ $s->translate }}</div>
                    </div>
                </div>
                <div class="foot-icons"><i class="fas fa-graduation-cap text-primary" data-id="{{$s->id}}" data-bs-toggle="modal" data-bs-target="#winModal" onclick="addPhrase(this);"></i></div>
            </div>
        @endforeach

        <audio id="audio"></audio>
<div class="justify-content-center show-only-pagination">{{ $sentences->onEachSide(1)->links('pagination::bootstrap-5') }}</div>
</div>
    <div class="modal fade" id="winModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content"></div></div>
    </div>
    <script src="/assets/js/sentenece.js"></script>
@endsection
