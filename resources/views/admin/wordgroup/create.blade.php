@extends('admin.layouts.app')
@section('content')
    <link rel="stylesheet" href="/assets/css/croppie.css" />
    <form method="POST" action="{{route('wordgroup.store')}}">
        @csrf

        <div class="container">
            <h3 class="mt-3 fw-bold">Изменить раздел</h3>
            <a class="btn btn-light mt-3" data-bs-toggle="collapse" href="#meta-collapse" role="button" aria-expanded="false" aria-controls="collapseExample"> <i class="fas fa-search" title="Поля мета-данных"></i></a>
            <div class="collapse" id="meta-collapse">
                <div class="card card-body mt-3">
                    <div class="title"><h5>Мета данные</h5></div>
                    <div class="p-3">
                        <label class="form-label">Title страницы:</label> <div class="input-group mb-3"><input id="mtitle" name="mtitle" value="{{old('mtitle')}}" type="text" class="form-control"><span class="input-group-addon btn btn-default analysis" data-field="Title"><i class="fas fa-signal"></i></span></div>
                        <label class="form-label">Ключевые слова:</label><div class="input-group mb-3"><input id="mkey" name="mkey" value="{{old('mkey',)}}" type="text" class="form-control"><span class="input-group-addon btn btn-default analysis" data-field="Keywords"><i class="fas fa-signal"></i></span></div>
                        <label class="form-label">Meta описание</label><div class="input-group mb-3"><input id="mdesc" name="mdesc" value="{{old('mdesc',)}}" type="text" class="form-control"><span class="input-group-addon btn btn-default analysis" data-field="Description"><i class="fas fa-signal"></i></span></div>
                    </div>
                </div>
            </div>
            <div class="card card-body mt-3 mb-4">

                <div class="row">
                    <div class="col-lg-4 text-center">

                            <div class="imgblock mt-4"><img class="img-fluid" src="{{asset('/storage/images/wordgroup/noimg.svg')}}" id="imagefile" alt=""></div>
                            <a id="btn-upload" class="btn file-btn btn-dark"><span class="text-light">Загрузить</span><input type="file" id="files" name="files" class="file-btn" accept="image/*"></a>
                            <span class="deleteBtn btn btn-danger" style="display:none">Удалить</span>

                        <div id="croppie" class="demo" style="display: none"></div>

                        <span id="btn-crop" class="btn btn-dark" style="display:none">Обрезать</span>
                        <input type="hidden" name="imagebase24" id="imagebase24">
                        <input type="hidden" name="image" id="image" value="">
                        <div id="configImg" data-id="" data-width="375" data-height="375" data-patch="wordgroup"></div>

                    </div>
                    <div class="col-lg-8">

                <div class="mb-3">
                    <label for="title" class="form-label">Название</label>
                    <input type="text" name="name" id="title" class="form-control @error('name') is-invalid @enderror" value="{{old('title')}}" required>
                    @error('title') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label">Url</label>
                    <div class="input-group">
                        <input type="text" name="url" id="url" data-sistem="0" class="form-control @error('url') is-invalid @enderror" value="{{old('url')}}" required>
                        <span id="addurl" class="input-group-addon btn btn-light" data-sistem="0" data-ext="" onclick="makeUrl(this)" ><i class="fa fa-link"></i></span>
                    </div>
                    @error('url') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Описание</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="content">{{old('description')}}</textarea>
                    @error('description') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <input type="hidden" name="id" value="">
                    </div>
            </div>

        </div>
            <div class="card card-body mt-3">
                <div class="mb-3">
                    <label for="content" class="form-label">Полное описание</label> <span class="btn btn-dark btn-sm" data-act="dark" onclick="showCode(this)"><i class="fas fa-code"></i></span>
                    <div class="mt-2"><textarea id="editor" class="form-control" name="content" rows="15"></textarea></div> <div id="code"></div>
                </div>

            </div>

            <input type="hidden" name="user_id" value="{{session()->get('user.id')}}">

        <p class="text-center m-3"><button type="submit" class="btn btn-dark btn-lg">Сохранить</button></p>
        </div>
    </form>
    <script src="/assets/js/croppie.js"></script>
    <script src="/assets/js/croppie-admin.js"></script>
    <script src="/AdminLTE4/dist/js/create.js"></script>
    <script>
        function showCode(btn){
            let editor = document.getElementById('editor');
            let code = document.getElementById('code');
            if(btn.dataset.act == 'dark'){
                btn.classList.remove('btn-dark');
                btn.dataset.act = '';
                code.innerHTML =  editor.value;
                editor.style.display = 'none';
            } else {
                btn.classList.add('btn-dark');
                btn.dataset.act = 'dark';
                code.innerHTML = '';
                editor.style.display = 'block';
            }

        }
    </script>
@endsection
