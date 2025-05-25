<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300;400;700&family=Roboto:ital,wght@0,100;0,300;0,400;0,700;1,100;1,300;1,400;1,500&display=swap" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" />
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
    @yield('meta')
</head><body>
<div class="wrap">
    <header id="topmenu"><div class="navbar" id="navbar">
            <div class="container">

                <div id="logo">
                    <div class="logotype" onclick="document.location='/'"><span>W</span><span>O</span><span>R</span><span>D</span><span>S</span></div>
                </div>

                <ul class="links">
                    <li><a href="/sentence/">Фразы</a></li>
                    <li><a href="/translate.html">Топики</a></li>
                    <li><a href="/translate.html">Видео</a></li>
                    <li><a class="dropdown-toggle" href="#" role="button" id="dropdownMore" data-bs-toggle="dropdown" aria-expanded="false">Слова</a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMore">
                            <li><a class="dropdown-item" href="/wordlist">Слова по темам</a></li>
                            <li><a class="dropdown-item" href="/learnword/levels">Слова по уровням</a></li>
                            <li><a class="dropdown-item" href=" /learnword/random">Случайные слова</a></li>

                        </ul>
                    </li>
                    <li><a class="dropdown-toggle" href="#" role="button" id="dropdownMore" data-bs-toggle="dropdown" aria-expanded="false">Тесты</a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMore">
                            <li><a class="dropdown-item" href="/test/vocabulary">Тест на словарный запас</a></li>
                        </ul>
                    </li>

                </ul>
                <div class="toggle">
                    <div class="line1"></div>
                    <div class="line2"></div>
                    <div class="line3"></div>
                </div>
                <div id="loginuser" class="dropdown">
                @auth

                        <div class="dropdown-toggle" id="dropdownLogin" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="imgaccount"><img src="{{asset(session()->get('user.userpic', '/storage/images/user/noimg.svg'))}}" alt="{{session()->get('user.username')}}"></span>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownLogin">
                            <li class="inaccount">
                                <a class="dropdown-item" href="/user/{{mb_strtolower(session()->get('user.username'),'UTF-8')}}"><i class="fas fa-user-circle"></i> <span>Мой профиль</span></a>
                                <a class="dropdown-item" href="/words/group"><i class="fas fa-th"></i> <span>Мои слова</span></a>
                             <a class="dropdown-item" href="/phrases/group"><i class="icon-puzzles"></i> <span>Мои фразы</span></a>
                                <a class="dropdown-item" href="{{route('userErrors','words')}}"><i class="fas fa-exclamation-triangle"></i> <span>Мои ошибки</span></a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                        @csrf  <button class="dropdown-item btn btn-link"><i class="fas fa-sign-out-alt"></i> Bыход</button>
                                    </form>
                        </ul>

                @else

                        <div class="dropdown-toggle" id="dropdownLogin" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false"><i class="fa-2x fas fa-user-circle"></i> </div>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownLogin">
                            <li><div class="navbar-login">
                                    <p class="text-muted small">Ведите данные для входа:</p>
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Ваш Email" autofocus>
                                        @error('email')
                                        <p class="invalid-feedback" role="alert">{{ $message }}</p>
                                        @enderror
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror mt-3 mb-3" name="password" placeholder="Ваш Пароль" required autocomplete="current-password">
                                        @error('password')
                                        <p class="invalid-feedback" role="alert">{{ $message }} </p>
                                        @enderror
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} checked>
                                            <label class="form-check-label" for="remember"> запомнить меня</label>
                                        </div>
                                        <button type="submit" class="btn btn-danger mb-2">Войти</button>
                                        @if (Route::has('password.request'))
                                            <p class="text-center">  <a rel="nofollow" class="pe-1" href="{{ route('register') }}">Регистрация</a> <a rel="nofollow" class="ps-1" href="{{ route('password.request') }}">Забыли пароль?</a></p>
                                        @endif
                                    </form>

                                </div>
                            </li>

                        </ul>

                    @endif
            </div>






            </div>
        </div>
    </header>
@yield('headinfo')
   @if(!request()->routeIs('home'))
        <div class="container">
            <div class="row bc mt-3">
                <div class="col"> @yield('breadcrumbs')</div>
                <div class="col text-end">  <i class="fas fa-coins text-warning"></i> <small class="fw-normal">Баллы:</small> <b id="myscore">{{session()->get('user.score', 0)}}</b></div>
            </div>
        </div>
   @endif


    <main class="content home">
            @yield('content')
    </main><!-- .content -->

</div>
<footer class="footer">

</footer>
<div class="copyright"></div>


<audio id="audio"></audio>
<div class="fixed-bottom"><div class="container"><div id="messblock"></div></div></div>
<link rel="stylesheet" type="text/css" href="/assets/css/icomoon/style.css">
<script src="/assets/js/general.js"></script>


</body></html>



