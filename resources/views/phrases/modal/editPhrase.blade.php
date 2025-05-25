<div class="modal-content">
    <form id="saveWord" onsubmit="return savePhrase(this)" action="{{route('userphrase.update',$item->id)}}" method="post">
@csrf
        @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="winModalLabel">{{$headTxt}} перевод</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p class="fw-bold fs-5">{{$item->phrase}}</p><input type="hidden" class="form-control" name="phrase" value="{{$item->phrase}}" minlength="5" maxlength="255" required="">
                       <p> <input class="form-control" id="inputtranslate" name="translate" value="{{$item->translate}}" placeholder="Введите перевод для фразы" minlength="5" maxlength="255" required=""></p>
            </div>
            <div class="modal-footer">
                <input name="id" type="hidden" value="{{$item->id}}">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Отменить</button>
                <button class="btn btn-dark">Сохранить</button>
            </div>
    </form>
</div>
