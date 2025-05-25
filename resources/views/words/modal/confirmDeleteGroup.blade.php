<div class="modal-content"><form id="saveWord" action="{{route('group.destroy',$group->id)}}" method="post">
    @csrf
        @method('DELETE')


        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalToggleLabel">Предупреждение</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="mt-3 mb-3">Вы действительно хотите удалить группу@if($group->qty > 0) и вложенные слова@endif?</div>
            <input name="user_id" type="hidden" value="{{$group->user_id}}">
            <input name="type" type="hidden" value="{{$group->type}}">
        </div>
        <div class="modal-footer">
            <span class="btn btn-outline-primary" data-bs-dismiss="modal">закрыть</span>
            <button class="btn btn-danger">Удалить</button>
        </div>
    </form>
</div>
