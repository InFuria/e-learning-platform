<div class="col-12 pt-0 mt-0">
    <h2 class="text-muted">{{ __("Requisitos para tomar el curso") }}</h2>
    <hr/>
</div>
@forelse($requirements as $requirement)
    <div class="card bg-light p-3">
        <p class="mb-0">
            {{ $requirement->requirement }}
        </p>
    </div>
@empty
    <div class="alert alert-dark">
        <i class="fa fa-info-circle"></i>
        {{ __("No se hay requisitos para este curso") }}
    </div>
@endforelse
