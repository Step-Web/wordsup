<input type="hidden" name="model" id="model" value="{{$model}}">
@if($type === 'word')
<div class="btn-group mb-2 align-items-center">
    <div class="pe-1 text-muted small">Режим<span class="d-none d-sm-inline"> изучения</span>:</div>
    <select class="form-select selectpicker" id="studymode" name="studymode" data-class="btn btn-info" style="display: none;">
        <option value="translate">Выбери перевод</option>
        <option value="reverse">Обратный перевод</option>
        <option value="write">Напиши слово</option>
        <option value="assemble">Собери слово</option>
        <option value="sprint">Спринт</option>
    </select>
    <span class="btn btn-danger" data-checked="false" data-bs-toggle="offcanvas" data-bs-target="#exerciseModal" aria-controls="exerciseModal">Учить слова</span>
</div>
@else
    <div class="btn-group mb-2 align-items-center">
        <div class="pe-1 text-muted small">Режим<span class="d-none d-sm-inline"> изучения</span>:</div>
        <select class="form-select selectpicker" id="studymode" name="studymode" data-class="btn btn-info" style="display: none;">
            <option value="collatewords">Собери фразу из слов</option>
            <option value="assemble">Собери фразу из букв</option>
            <option value="write">Напиши фразу</option>
        </select>
        <span class="btn btn-danger" data-checked="false" data-bs-toggle="offcanvas" data-bs-target="#exerciseModal" aria-controls="exerciseModal">Учить фразы</span>
    </div>
@endif
