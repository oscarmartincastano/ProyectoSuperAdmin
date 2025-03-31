<?php


namespace App\Http\Controllers;


use App\Models\Pista;
use App\Models\Servicio;
use App\Models\Descuento;
use App\Models\Recibo;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bono;
use App\Models\Valor_campo_personalizado;
use App\Models\BonoUsuario;
use App\Models\Instalacion;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class BonosController extends Controller
{


    public function index(){

        $bonos= Bono::all();

        return view('instalacion.configuraciones.bonos.index')->with(compact('bonos'));

    }

    public function create(){

        return view('instalacion.configuraciones.bonos.create')/* ->with(compact('servicios')) */;


    }

    public function store(Request $request){
        $bono= new Bono();


        $bono->nombre=$request->nombre;
        $bono->precio= $request->precio;
        $bono->id_deporte=$request->espacio;
        $bono->descripcion=$request->contenido;
        $bono->num_usos=$request->usos;
        $bono->activado = $request->has('activo') ? 1 : 0;
        $bono->id_instalacion = auth()->user()->id_instalacion;
        $bono->save();

        return redirect(route('bonos',auth()->user()->instalacion->slug))->with('success', 'Bono creado correctamente');


    }

    public function edit(){
        $bono= Bono::find(request()->bono);
        $bonos= Bono::all();

        return view('instalacion.configuraciones.bonos.edit')->with(compact('bono','bonos'));
    }



    public function update(Request $request){


        $bono=Bono::find($request->bono);

        $bono->nombre=$request->nombre;
        $bono->precio= $request->precio;
        $bono->id_deporte=$request->espacio;
        $bono->descripcion=$request->contenido;
        $bono->num_usos=$request->usos;
        $bono->activado = $request->has('activo') ? 1 : 0;

        $bono->save();

        return redirect(route('bonos',auth()->user()->instalacion->slug))->with('success', 'Bono actualizado correctamente');


    }

    public function delete(){
        $bono=Bono::find(request()->bono);


        $bono->delete();
        return redirect(route('bonos',auth()->user()->instalacion->slug))->with('success', 'Bono eliminado correctamente');


    }

    public function contratar(){
        $bono = Bono::find(request()->bono);


        return view ('bono.reserva')->with(compact('bono'));
    }


    public function mis_bonos(Request $request){
        $user = User::find(auth()->user()->id);
        $bonos_usuario = BonoUsuario::where('id_usuario',$user->id)->get();
        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();
        return view('new.perfil.mis_bonos')->with(compact('instalacion','bonos_usuario'));
    }

}
