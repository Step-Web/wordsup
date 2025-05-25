@extends('layouts.app')
@include('layouts.inc.meta',['title'=>'Вы вошли на сайт','index'=>'noindex'])
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }} </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                 Вы вошли на сайт
                    это шаблон user/USERHOME
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
