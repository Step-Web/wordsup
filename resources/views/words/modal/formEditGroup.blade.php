<div class="modal-content"><form action="{{route('group.update',$group->id)}}" method="post">
@csrf
        @if(isset($group->id)) @method('PUT') @endif

        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalToggleLabel">Изменить группу слов</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body"><br>
            <label>Название группы:</label>
            <div class="input-group">
                <input class="form-control" id="inputwords" name="name" placeholder="Введите название группы слов" value="{{$group->name ?? ''}}" required>
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" id="colorPalettePicker" data-bs-toggle="dropdown" aria-expanded="false">
                        <span style="background-color:{{$group->color ?? '#0c70e2'}}"></span> <b class="d-none d-sm-inline-block fw-normal">Цвет</b>
                    </button>   @if($group->owner_id != 1)
                    <ul class="dropdown-menu dropdown-menu-end colorPalettePicker" aria-labelledby="colorPalettePicker">
                        @foreach($colors AS $color)
                        <li><span {{(isset($group->color) && $color == $group->color) ? 'class="active"':''}} style="background-color:{{$color}}" onclick="checkColor(this,'{{$color}}')"></span></li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
            <input name="id" id="id" type="hidden" value="{{$group->id}}">
            <input name="type" type="hidden" value="{{$group->type}}">
            <input name="color" id="color" type="hidden" value="{{$group->color ?? '#0c70e2'}}">
            <input name="user_id" type="hidden" value="{{$user_id}}">
        </div>
        <div class="modal-footer">
            <span class="btn btn-outline-primary" data-bs-dismiss="modal">закрыть</span>
            <button class="btn btn-danger">Сохранить</button>
        </div>
    </form>
</div>
