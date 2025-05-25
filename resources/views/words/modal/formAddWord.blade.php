
<div class="modal-content">
    @if(auth()->check())
    <form id="insertword" action="{{route('userword.store')}}" method="post" onsubmit="return insertWord(this)">
         @csrf
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalToggleLabel">Добавить слово</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body"><br>
            <h1 class="text-center"><b>{{$word->word}}</b></h1>
            <div id="wordtranslate" class="text-center mb-4">
                <div class="save" style="display: none;"><input type="text" class="form-control newtranslate" name="translate" value="{{$word->translate}}"> <span class="btn btn-primary" onclick="translateEdit(this)"><i class="fas fa-save" ></i></span></div>
                <div class="edit"><span class="text-center text-muted">{{$word->translate}}</span> <span class="btn" onclick="translateEdit(this)"><i class="fas fa-pencil-alt"></i></span></div></div>

            @if($groups->count() > 0)
                <div class="mb-2"><label>Добавить слово в:</label> <small class="mess"></small></div>
            <select class="form-select selectpicker" id="groupnew" name="group_id">
                 @foreach($groups AS $val)
                   <option value="{{$val['id']}}" data-subtext="{{$val['qty']}} слов(а)" data-color="{{$val['color']}}">{{$val['name']}}</option>
                 @endforeach
            </select>
            <p class="mt-3"><input type="checkbox" name="redictgroup" id="redictgroup" value="1"> <label for="redictgroup" class="text-muted">Перейти в группу после завершения</label></p>
            @else
                <div class="mb-2"><label>Название группы:</label> <small class="text-warning mess">У вас пока нет групп для слов, давайте создадим её</small></div>
               <input name="name" class="form-control" value="Базовая группа слов" placeholder="назовите группу" required>
                <p class="mt-3"><input type="checkbox" name="redictgroup" id="redictgroup" value="1" class="d-none"></p>
            @endif
        </div>
        <div class="modal-footer">
            <input type="hidden" name="word" value="{{$word->word}}">
            <input type="hidden" name="user_id" value="{{session()->get('user.id')}}">
            <input type="hidden" name="type" value="words">
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
