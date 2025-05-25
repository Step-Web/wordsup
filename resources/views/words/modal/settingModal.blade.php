
<div class="modal-content">
    <form onsubmit="return setSetting(this)">
         @csrf
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalToggleLabel">Группы слов</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="d-flex justify-content-between">
            <div class="btn-group">
                <label class="btn btn-primary"><input type="checkbox" onchange="popular(this)" autocomplete="off" style="display: none"> <span>Популярные</span> </label>
            </div>

            <button class="btn btn-danger" id="save-top" style="display:none">Сохранить</button>
            </div>
            <hr>
            <span class="text-muted">Выбрано:</span>
            <span id="count"><b>0</b> <span class="text-muted">групп</span></span>
            <hr>
            <div class="row" id="cbox">
                @for ($i = 1; $i <= 100; $i++)
                    @php
    $checked = (in_array($i,$learngroups))?'checked':'' @endphp
                    <div class="col-6 col-sm-4 col-md-3">
                        <p class="checkbox">
                            <label>
                                <input type="checkbox" name="group_words[{{$i}}]" value="{{$i}}" class="rating" onchange="totals(this)" {{$checked}}> <b class="rating{{rate($i)}}">{{$i}} <small>группа</small></b>
                            </label>
                        </p>
                    </div>
                @endfor
            </div>
        </div>
        <div class="modal-footer">
            <span class="btn btn-outline-primary" data-bs-dismiss="modal">Отмена</span>
            <button class="btn btn-danger" id="save-foot" style="display:none">Сохранить</button>
        </div>
    </form>
</div>


