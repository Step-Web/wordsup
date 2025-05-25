
    <div class="modal-content">
    <form id="saveWord" onsubmit="return saveWord(this)" action="{{route('dictonary.update',$word->id)}}" method="post">
        @csrf
        @method('PUT')
        <div class="modal-header">
            <h5 class="modal-title" id="winModalLabel">Изменить слово</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <p><label class="form-label">Слово:</label><input class="form-control" id="inputwords" name="word" value="{{$word->word}}" minlength="2" maxlength="50" required=""></p>
            @php  $arr = explode(',',$word->translate);  @endphp


            @if($word->translate == 'нет перевода')
                <label class="form-label">Перевод: <span class="small text-warning">введите через запятую</span></label>
                <input class="form-control" id="inputtranslate" name="translate" value="" placeholder="Введите перевод для слова {{$word->word}}" minlength="2" maxlength="100" required="">
            @else
                <label class="form-label">Перевод: <span class="btn-link" data-target="wintanslate" onclick="showFieldTranslate(this)">изменить</span></label>
                <div id="wintanslate" class="sortable">
                    @foreach($arr AS $t)
                        <div>{{ $t }} <i class="fas fa-times" onclick="delTranslate(this)"></i></div>
                    @endforeach
                </div>
                <input class="form-control wintanslate" id="inputtranslate" name="translate" value="{{$word->translate}}" minlength="2" maxlength="100" style="display:none" required="">

            @endif

            <div class="mt-3">
                <a class="btn btn-outline-dark btn-sm" id="btnmore" data-bs-toggle="collapse" href="#collapseWord" role="button" aria-expanded="false" aria-controls="collapseExample" onclick="this.style.display='none'" style="display: inline;"><i class="icon-dots"></i> Eщё</a>
                <div class="collapse" id="collapseWord"><label>Транскрипция:</label><input class="form-control" id="input-tsr" name="ts" value="{{$word->ts}}" maxlength="30">
                    <label>Группа:</label><input class="form-control" id="input-wgroup" name="wgroup" value="{{$word->wgroup}}" maxlength="3">
                    <label>Популярность:</label><input class="form-control" id="input-freq" name="freq" value="{{$word->freq}}" maxlength="30">
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <input name="id" type="hidden" value="{{$word->id}}">
            <input name="user_id" type="hidden" value="{{$word->user_id}}">
            <input name="group_id" type="hidden" value="{{$word->group_id}}">
            <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Отменить</button>
            <button class="btn btn-dark">Сохранить</button>
        </div>

    </form>
    </div>
    <style>
        .sortable{ display: flex;flex-wrap: wrap;}
        .sortable > div{ background: #fff; border: 1px solid #999; padding:0.3rem 0.5rem; margin:0 3px 3px 0; cursor: ew-resize;  text-wrap: nowrap;  }
        .sortable  > div span::after{content: " ";padding:0.3em 0 0 0.2em; display: inline-block; width: 1em; height:1em;cursor: pointer; background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23000'%3e%3cpath d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/%3e%3c/svg%3e") center/1em auto no-repeat;   }
    </style>
