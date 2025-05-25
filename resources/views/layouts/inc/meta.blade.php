@section('meta')
    @if($title) <title>{{$title}}</title>@endif
    @if(!empty($mdesc))<meta name="description" content="{{$mdesc}}">@endif
    @if(!empty($mkey))<meta name="keywords" content="{{$mkey}}">@endif
    @if(!empty($index))<meta name="robots" content="{{$index}},nofollow"/> @endif
    @if(!empty(request()->get('page')) && request()->get('page') == 1)<link rel="canonical" href="{{request()->url()}}"/> @endif
@endsection
