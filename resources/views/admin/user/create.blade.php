@extends('admin.layouts.app')

@section('content')
    <form method="POST" action="{{route('user.store')}}">
        @csrf
    <div class="container">

    <h3 class="mt-3 fw-bold">Добавить пользователя</h3>
        <div class="card card-body mt-3">
            <div class="row">

                <div class="col-sm-6 mb-3">
                    <label for="username" class="form-label">Никнайм</label>
                    <input type="text" name="username" class="form-control @error('title') is-invalid @enderror" value="{{old('username')}}" required>
                    @error('username') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="col-sm-6 mb-3">
                    <label for="name" class="form-label">Имя</label>
                    <input type="text" name="name" class="form-control @error('title') is-invalid @enderror" value="{{old('name')}}" required>
                    @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="col-sm-6 mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{old('email')}}" required>
                    @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="col-sm-6 mb-3">
                    <label for="title" class="form-label">Роль</label>
                    <select name="role" class="form-select">
                        @foreach($roles as $v)
                            <option value="{{$v}}">{{$v}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 mb-3">
                    <label for="password" class="form-label">Пароль</label>
                    <input type="text" name="password" class="form-control @error('password') is-invalid @enderror" value="{{old('password')}}" required>
                    @error('password') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="col-sm-6 mb-3">
                <div class="mb-3">
                    <label for="email_verified_at" class="form-label">Верификация E-mail</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="email_verified_at" value="{{date('Y-m-d h:i:s', time())}}" id="email_verified_at"> <label class="form-check-label" for="email_verified_at">E-mail проверен</label>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
        <p class="text-center m-3"><button type="submit" class="btn btn-dark btn-lg">Сохранить</button></p>
    </form>
@endsection
