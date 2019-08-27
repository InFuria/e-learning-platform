<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class InvoiceController extends Controller
{
    public function admin()
    {
        $invoices = new Collection();
        // Si tiene un id de stripe esta suscripto y por lo tanto tiene facturas
        if (auth()->user()->stripe_id) {
            $invoices = auth()->user()->invoices();
        }
        return view('invoices.admin', compact('invoices'));
    }

    public function download(Request $request, $id)
    {
        return $request->user()->downloadInvoice($id, [
            'vendor' => 'Mi Empresa',
            'product' => __("Suscripcion"),
        ]);
    }
}
