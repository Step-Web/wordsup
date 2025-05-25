@section('breadcrumbs')
  <nav aria-label="breadcrumb">
        <ol class="breadcrumb container">
                  <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
 @if(!empty($url))<li class="breadcrumb-item"><a href="/{{$url}}">{{$title}}</a></li>@endif
                  <li class="breadcrumb-item"><a href="{{url()->current()}}">{{$page->title??$group->name}}</a></li>
        </ol>
    </nav>
@endsection
