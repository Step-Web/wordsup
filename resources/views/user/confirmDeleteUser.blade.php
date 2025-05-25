<div class="modal-content"><form id="saveWord" action="{{route('deleteUser',$user->id)}}" method="post">
    @csrf
        @method('DELETE')
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalToggleLabel">Предупреждение</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="mt-3 mb-3">Вы действительно хотите удалить свой аккаунт?</div>
            <input name="user_id" type="hidden" value="{{$user->id}}">
        </div>
        <div class="modal-footer">
            <span class="btn btn-outline-primary" data-bs-dismiss="modal">закрыть</span>
            <button class="btn btn-danger">Удалить</button>
        </div>
    </form>
</div>
