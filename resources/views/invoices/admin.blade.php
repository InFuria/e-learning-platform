@extends('layouts.app')

@section('jumbotron')
    @include('partials.jumbotron', ['title' => 'Manejar mis facturas', 'icon' => 'archive'])
@endsection

@section('content')
    <div class="pl-5 pr-5">
        <div class="row justify-content-center">
            <table class="table table-hover table-dark">
                <thead>
                <tr>
                    <th>{{ __("Fecha de suscripcion") }}</th>
                    <th>{{ __("Coste de la suscripcion") }}</th>
                    <th>{{ __("Cupon") }}</th>
                    <th>{{ __("Descargar factura") }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($invoices as $invoice)
                    <tr>
                        {{--<td>{{ Carbon\Carbon::createFromTimestamp($invoice->asStripeInvoice()->created)->format('d/m/Y') }}</td>--}}
                        <td>{{ Carbon\Carbon::createFromTimestamp($invoice->asStripeInvoice()->created)->format('d/m/Y') }}</td>
                        <td>{{ $invoice->total() }}</td>
                        @if ($invoice->hasDiscount())
                            <td>{{ __("Cupon") }}: ({{ $invoice->coupon() }} / {{ $invoice->discount() }})</td>
                        @else
                            <td>{{ __("No se ha utilizado ningun cupon") }}</td>
                        @endif
                        <td>
                            <a class="btn btn-course" href="{{ route('invoices.download', ['id' => $invoice->id]) }}">
                                {{ __("Descargar") }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">{{ __("No hay ninguna factura disponible.") }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
