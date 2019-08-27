@extends('layouts.app')

@section('jumbotron')
    @include('partials.jumbotron', ['title' => 'Cursos a los que estas suscripto', 'icon' => 'table'])
@endsection

@section('content')
    <div class="pl-5 pr-5">
        <div class="row justify-content-center">
            @forelse($courses as $course)
                <div class="col-md-3">
                    <!-- No es necesario pasar el id ya que se esta recoriendo el array -->
                    @include('partials.courses.card_course')
                </div>
            @empty
                <div class="alert alert-dark">{{ __("Todavía no estas suscripto a ningún curso") }}</div>
            @endforelse
        </div>
    </div>
@endsection
