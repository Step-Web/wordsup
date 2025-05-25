
    <div class="modal-content">
    <form id="saveWord" onsubmit="return savePhrase(this)" action="{{route('sentence.update',$item->id)}}" method="post">
        @csrf
        @method('PUT')
        <div class="modal-header">
            <h5 class="modal-title" id="winModalLabel">Изменить фразу</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <p><label class="form-label fw-bold">Фраза:</label><input class="form-control" id="phrase" name="phrase" value="{{$item->phrase}}" minlength="2" maxlength="50" required=""></p>
                <label class="form-label fw-bold">Перевод:</label>
                <input class="form-control" name="translate" value="{{$item->translate}}" minlength="2" maxlength="100" required="">
            <label class="form-label fw-bold mt-3">ID tatoeba.org:</label>
            <input class="form-control" type="number" name="tID" value="{{$item->tID??0}}" required="">
            <div class="mt-3">
                <a class="btn btn-outline-dark btn-sm" id="btnmore" data-bs-toggle="collapse" href="#collapseWord" role="button" aria-expanded="false" aria-controls="collapseExample" onclick="this.style.display='none'" style="display: inline;"><i class="icon-dots"></i> Eщё</a>
                <div class="collapse" id="collapseWord">
                    @php $file = 'sentence/en/'.mb_substr($item->id,-1).'/'.$item->id.'.mp3'; @endphp
                    <label class="form-label fw-bold">Путь до файла:</label><div class="input-group"><input class="form-control" id="pathfile" name="pathfile" value="{{$file}}" maxlength="30"><span class="input-group-btn"><span class="btn btn-dark" onclick="createAudioPhrase(this)">озвучить</span></span></div>
                    <label class="form-label fw-bold mt-3">Варианты:</label><input class="form-control" id="input-tsr" name="tsr" value="" maxlength="30">
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <input name="id" type="hidden" value="{{$item->id}}">
            <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Отменить</button>
            <button class="btn btn-dark">Сохранить</button>
        </div>
    </form>
    </div>
