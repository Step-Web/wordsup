@extends('admin.layouts.app')

@section('content')
    <form method="POST" action="{{route('section.store')}}">
        @csrf
    <div class="container">

    <h3 class="mt-3 fw-bold">Добавить раздел</h3>

    <a class="btn btn-light mt-3" data-bs-toggle="collapse" href="#meta-collapse" role="button" aria-expanded="false" aria-controls="collapseExample"> <i class="fas fa-search" title="Поля мета-данных"></i></a>


        <div class="collapse" id="meta-collapse">
            <div class="card card-body mt-3">
                <div class="title"><h5>Мета данные</h5></div>
                <div class="p-3">
                    <label class="form-label">Title страницы:</label> <div class="input-group mb-3"><input id="mtitle" name="mtitle" value="" type="text" class="form-control"><span class="input-group-addon btn btn-default analysis" data-field="Title"><i class="fas fa-signal"></i></span></div>
                    <label class="form-label">Ключевые слова:</label><div class="input-group mb-3"><input id="mkey" name="mkey" value="" type="text" class="form-control"><span class="input-group-addon btn btn-default analysis" data-field="Keywords"><i class="fas fa-signal"></i></span></div>
                    <label class="form-label">Meta описание</label><div class="input-group mb-3"><input id="mdesc" name="mdesc" value="" type="text" class="form-control"><span class="input-group-addon btn btn-default analysis" data-field="Description"><i class="fas fa-signal"></i></span></div>
                </div>
            </div>
        </div>


        <div class="card card-body mt-3">

            <div class="mb-3 mt-4">
                <label for="title" class="form-label">Название</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">
                @error('title') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3 mt-4">
                <label for="title" class="form-label">Url</label>
                <div class="input-group validate_url mb-3">
                    <input type="text" name="url" id="url" class="form-control @error('url') is-invalid @enderror" value="{{ old('url') }}" >
                    @error('url') <div class="text-danger">{{ $message }}</div> @enderror
                    <span id="addurl" class="input-group-addon btn btn-light" data-sistem="0" data-ext=".html" onclick="makeUrl(this)"><i class="fa fa-link"></i></span>
                </div>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Описание</label>
                <textarea class="form-control @error('description') is-invalid @enderror" name="content" id="content">{{ old('content') }}</textarea>
                @error('content') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        <input type="hidden" name="id" value="0">

        </div>
    </div>
        <p class="text-center m-3"><button type="submit" class="btn btn-dark btn-lg">Сохранить</button></p>
    </form>
    <script src="/AdminLTE4/dist/js/create.js"></script>
@endsection
