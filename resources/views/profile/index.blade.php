@extends('layouts.app')

@section('jumbotron')
    @include('partials.jumbotron', ['title' => 'Configurar tu perfil', 'icon' => 'user-circle'])
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
@endpush

@section('content')
    <div class="pl-5 pr-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ __("Actualiza tus datos") }}
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update') }}" novalidate>
                            @csrf
                            @method('PUT')

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">
                                    {{ __("Correo electronico") }}
                                </label>
                                <div class="col-md-6">
                                    <input id="email"
                                           name="email"
                                           value="{{ old('email') ?: $user->email }}"
                                           type="email"
                                           readonly class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           required
                                           autofocus
                                    />

                                    @if($errors->has('email'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">
                                    {{ __("Contraseña") }}
                                </label>
                                <div class="col-md-6">
                                    <input id="password"
                                           name="password"
                                           type="password"
                                           class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                           required
                                    />

                                    @if($errors->has('password'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">
                                    {{ __("Confirma la contraseña") }}
                                </label>
                                <div class="col-md-6">
                                    <input id="password-confirm"
                                           name="password_confirmation"
                                           type="password"
                                           class="form-control"
                                           required
                                    />

                                    @if($errors->has('email'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __("Actualizar datos") }}
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
