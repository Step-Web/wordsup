<div class="modal-content">
    <form id="saveWord" onsubmit="return saveWord(this)" action="{{route('userword.update',$word->id)}}" method="post">
@csrf
        @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="winModalLabel">Добавьте перевод</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <input class="form-control" type="hidden" id="inputwords" name="word" value="{{$word->word}}" minlength="2" maxlength="50" required="">
                    <label class="form-label">Перевод для слова: <b>{{$word->word}}</b></label>
                        <input class="form-control" id="inputtranslate" name="translate" value="" placeholder="Введите свой перевод для слова" minlength="2" maxlength="100" required="">
                <div class="mt-3">
                    <div class="collapse" id="collapseWord">
                        <label>Транскрипция:</label><input class="form-control" id="input-tsr" name="ts" value="{{$word->ts}}" maxlength="30">
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <input name="id" type="hidden" value="{{$word->id}}">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Отменить</button>
                <button class="btn btn-dark">Сохранить</button>
            </div>
    </form>
</div>
