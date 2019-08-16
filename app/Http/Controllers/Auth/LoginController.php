<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Student;
use App\User;
use App\UserSocialAccount;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function logout(Request $request)
    {
        auth()->logout();
        session()->flush();
        return redirect('/login');
    }


    public function redirectToProvider(string $driver) //la variable $driver es el parametro enviado desde la ruta
    {
        // Socialite recibe el driver solicitado y se pasa al metodo drive y redirect para procesarlo
        // y redirecciona a la pagina solicitada
        return Socialite::driver($driver)->redirect();
    }

    public function handleProviderCallback(string $driver)
    {
        // Se valida el retorno de mensaje emergente de facebook
        if (!request()->has('code') || request()->has('denied')) {

            session()->flash('message', ['danger', __("Inicio de sesion cancelado")]);

            return redirect('login');
        }

        // Socialite devuelve el usuario devuelve la plataforma
        $socialUser = Socialite::driver($driver)->user();

        $user = null;
        $success = true;
        $email = $socialUser->email;
        $check = User::whereEmail($email)->first();
        if ($check) {
            $user = $check;
        } else {
            \DB::beginTransaction();
            try {

                $user = User::create([
                    "name" => $socialUser->name,
                    "email" => $email
                ]);

                UserSocialAccount::create([
                    "user_id" => $user->id,
                    "provider" => $driver,
                    "provider_uid" => $socialUser->id,
                ]);


                Student::create([
                    "user_id" => $user->id
                ]);

            } catch (\Exception $exception) {
                $success = $exception->getMessage();
                \DB::rollBack();
            }
        }


        if ($success == true) {
            \DB::commit();

            // Metodo para iniciar sesion
            auth()->loginUsingId($user->id);

            return redirect('/');
        }

        session()->flash('message', ['danger', $success]);
        return redirect('login');
    }
}
