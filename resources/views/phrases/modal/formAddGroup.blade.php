<div class="modal-content"><form action="{{route('group.store')}}" method="post">
@csrf


        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalToggleLabel">Добавить группу фраз</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body"><br>
            <label>Название группы:</label>
            <div class="input-group">
                <input class="form-control" id="inputwords" name="name" placeholder="Введите название группы" value="" required>
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" id="colorPalettePicker" data-bs-toggle="dropdown" aria-expanded="false">
                        <span style="background-color:{{$group->color ?? '#0c70e2'}}"></span> <b class="d-none d-sm-inline-block fw-normal">Цвет</b>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end colorPalettePicker" aria-labelledby="colorPalettePicker">
                        @foreach($colors AS $color)
                        <li><span style="background-color:{{$color}}" onclick="checkColor(this,'{{$color}}')"></span></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <input name="id" id="id" type="hidden" value="0">
            <input name="type" type="hidden" value="{{$type}}">
            <input name="color" id="color" type="hidden" value="#0c70e2">
            <input name="user_id" type="hidden" value="{{$user_id}}">
        </div>
        <div class="modal-footer">
            <span class="btn btn-outline-primary" data-bs-dismiss="modal">закрыть</span>
            <button class="btn btn-danger">Сохранить</button>
        </div>
    </form>
</div>
