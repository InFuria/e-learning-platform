<div class="col-2">
    @auth
        @can('opt_for_course', $course)
            @can('subscribe', App\Course::class)
                <a class="btn btn-subscribe btn-bottom btn-block" href="{{ route('subscriptions.plans') }}">
                    <i class="fa fa-bolt"></i> {{ __("Subscribirme") }}
                </a>
            @else
                @can('inscribe', $course)
                    <a class="btn btn-subscribe btn-bottom btn-block" href="{{ route('courses.inscribe', ['slug' => $course->slug]) }}">
                        <i class="fa fa-bolt"></i> {{ __("Inscribirme") }}
                    </a>
                @else
                    <a class="btn btn-subscribe btn-bottom btn-block" href="#">
                        <i class="fa fa-bolt"></i> {{ __("Inscripto") }}
                    </a>
                @endcan
            @endcan
        @else
            <a class="btn btn-subscribe btn-bottom btn-block" href="#">
                <i class="fa fa-user"></i> {{ __("Soy Autor") }}
            </a>
        @endcan
    @else
        <a class="btn btn-subscribe btn-bottom btn-block" href="{{ route('login') }}">
            <i class="fa fa-bolt"></i> {{ __("Acceder") }}
        </a>
    @endauth
</div>
