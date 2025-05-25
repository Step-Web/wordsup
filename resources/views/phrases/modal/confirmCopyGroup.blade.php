<div class="modal-content"><form data-url="{{route('group.copygroup',$group_id)}}?confirm=1" method="get" onsubmit="return copyGroup(this)">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalToggleLabel">Предупреждение</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mt-3 mb-3">{{$message}}</div>
        </div>
        <div class="modal-footer">
            <span class="btn btn-outline-primary" data-bs-dismiss="modal">Отмена</span>
            @if($btn_txt == 'В мои группы')
                <a href="/words/group/" class="btn btn-danger">{{$btn_txt}}</a>
            @elseif($btn_txt == 'В группу')
                <a href="/words/group/{{$group_id}}" class="btn btn-danger">{{$btn_txt}}</a>
            @else
                <button class="btn btn-danger">{{$btn_txt}}</button>
            @endif
        </div>
    </form>
</div>
