
<div class="modal-content">
    @if(auth()->check())
    <form id="insertword" action="{{route('addPhraseByID',$phrase->id)}}" onsubmit="return insertPhrase(this)">
         @csrf
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalToggleLabel">Добавить фразу</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body pt-4">

            <h1 class="text-center"><b>{{$phrase->phrase}}</b></h1>
            <div id="wordtranslate" class="text-center mb-4">
                <div class="save" style="display: none;"><input type="text" class="form-control newtranslate" name="translate" value="{{$phrase->translate}}"> <span class="btn btn-primary" onclick="translateEdit(this)"><i class="fas fa-save" ></i></span></div>
                <div class="edit"><span class="text-center text-muted">{{$phrase->translate}}</span> <span class="btn" onclick="translateEdit(this)"><i class="fas fa-pencil-alt"></i></span></div>
            </div>

            @if($groups->count() > 0)
            <div class="mb-2"><label>Добавить фразу в:</label> <small class="mess"></small></div>
            <select class="form-select selectpicker" id="groupnew" name="group_id">
                 @foreach($groups AS $val)
                   <option value="{{$val['id']}}" data-subtext="{{$val['qty']}} фраз(ы)" data-color="{{$val['color']}}">{{$val['name']}}</option>
                 @endforeach
            </select>
                <p class="mt-3"><input type="checkbox" name="redictgroup" id="redictgroup" value="1"> <label for="redictgroup" class="text-muted">Перейти в группу после завершения</label></p>
            @else

                <div class="mb-2"><label>Название группы:</label> <small class="text-warning mess">У вас пока нет групп для фраз, давайте создадим её</small></div>
               <input name="name" class="form-control" value="Базовая группа фраз" placeholder="назовите группу" required>
                <input type="text" id="groupnew" name="group_id" value="0">
                <p class="mt-3"><input type="checkbox" name="redictgroup" id="redictgroup" value="1" class="d-none"></p>
            @endif

        </div>
        <div class="modal-footer">
            <input type="hidden" name="phrase" value="{{$phrase->phrase}}">
            <input type="hidden" name="user_id" value="{{session()->get('user.id')}}">
            <input type="hidden" name="type" value="phrases">
            <span class="btn btn-outline-primary" data-bs-dismiss="modal">Отмена</span>
            <button class="btn btn-danger">Добавить</button>
        </div>
    </form>
    @else
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalToggleLabel">Предупреждение</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <p>Только зарегистрированные пользователи могут добавлять слова в свой персональный словарь</p>
        </div>
    @endif
</div>
<style>
    #wordtranslate {display:flex;align-items: center;justify-content: center; }
    #wordtranslate > div{ display: flex; align-items: center; }
    #wordtranslate i{cursor: pointer}
    #wordtranslate .edit{padding-left: 1em}
    #wordtranslate  .edit .btn{ padding-left:4px }
</style>
