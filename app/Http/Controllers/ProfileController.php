<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        // Se agrega la relacion al user para mostrar los datos de la red social del mismo
        $user = auth()->user()->load('socialAccount');

        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        //
    }
}
