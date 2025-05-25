<div class="modal-content">
    <form id="transferForm" action="{{route('userphrase.transferWords')}}" method="post" onsubmit="return transferWords()">
         @csrf
        @php $txt = ($act=='cut') ? 'Перенести':'Копировать'; @endphp
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalToggleLabel">{{$txt}}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body"><br>

            <div class="mb-2"><label>{{$txt}} из <b>{{$groups[$group_id]['name']}}</b> в:</label></div>
            <select class="form-select selectpicker" id="groupnew" name="group_new">
                @foreach($groups AS $val)
                    @if($val['id'] != $group_id)
                   <option value="{{$val['id']}}" data-subtext="{{$val['qty']}} слов(а)" data-color="{{$val['color']}}">{{$val['name']}}</option>
                    @endif
                 @endforeach
            </select>
            <p class="mt-2"><input type="checkbox" name="redictgroup" id="redictgroup" value="{{$group_id}}"> <label for="redictgroup" class="text-muted">Перейти в группу после завершения</label></p>

        </div>
        <div class="modal-footer">
            <input type="hidden" name="group_old" value="{{$group_id}}">
            <input type="hidden" name="act" value="{{$act}}">
            <span class="btn btn-outline-primary" data-bs-dismiss="modal">Отмена</span>
            <button class="btn btn-danger">{{$txt}}</button>
        </div>
    </form>
</div>
