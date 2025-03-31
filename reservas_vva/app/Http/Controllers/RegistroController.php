<?php


namespace App\Http\Controllers;


use App\Models\Registro;

class RegistrosController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }



    public function index(){
        if(auth()->user()->rol !="Admin"){

            return redirect()->route('home');
        }
       $registros= Registro::orderBy("id","desc")->get();
        return view ('registros')->with(compact('registros'));
    }

}