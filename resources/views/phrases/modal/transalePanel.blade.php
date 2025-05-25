@if(!empty($word->id))
    <div class="panel-word"><div class="wordblock"><div class="audio-icon" data-audio="{{$word->audio}}" data-voice="f" onclick="playWord(this)"><i class="icon-play"></i></div> <span id="translate-word">{{$word->word}}</span>
            <span data-bs-toggle="modal" data-bs-target="#winModal"  class="btn btn-primary btn-sm" data-id="{{$word->id}}" data-word="{{$word->word}}" onclick="addword(this)">В словарь</span></div></div>
    <div class="text-desc">Транскрипция:</div>
    <i class="uk"></i> <small class="text-muted">[{{$word->ts}}]</small>     <i class="us"></i> <small class="text-muted">[{{$word->ts}}]</small>    <hr style="opacity: 0.1">
    <div class="text-desc">Перевод:</div>
    <p>{{$word->translate}}</p><br>
@else
    слово не найдено
@endif
