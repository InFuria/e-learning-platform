@include('partials.navigations._partial_menu')
<li><a class="nav-link" href="#">{{ __("Cursos desarrollados por mi") }}</a></li>
<li><a class="nav-link" href="{{ route('courses.create') }}">{{ __("Crear curso") }}</a></li>
@include('partials.navigations.logged')
