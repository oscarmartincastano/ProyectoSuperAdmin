<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Instalacion;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewUser;
use DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $instalacion = Instalacion::create(['nombre' => $request->name, 'direccion' => $request->direccion, 'tlfno' => $request->tlfno, 'slug' => $request->slug]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => 'admin',
            'id_instalacion' => $instalacion->id
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function create_user_instalacion()
    {
        return view('auth.register_user_instalacion');
    }

    public function store_instalacion(Request $request)
    {


        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (isset($request->tlfno)) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'tlfno' => $request->tlfno,
                'direccion' => $request->direccion,
                'codigo_postal' => $request->codigo_postal,
                'rol' => 'user',
                'aprobado' => date('Y-m-d H:i:s'),
                'id_instalacion' => $instalacion->id
            ]);
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rol' => 'user',
                'tlfno' => $request->tlfno,
                'direccion' => $request->direccion,
                'codigo_postal' => $request->codigo_postal,
                'aprobado' => date('Y-m-d H:i:s'),
                'id_instalacion' => $instalacion->id
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        if(isset($_GET['evento'])){
            return redirect('/'.$request->slug_instalacion.'/evento/'.$_GET['evento']);

        }else{
            return redirect('/'.$request->slug_instalacion);
        }

        /* Mail::to('manuel@tallerempresarial.es')->send(new Newuser($user)); */

    }
}
