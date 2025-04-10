<?php


namespace App\Http\Controllers;


use App\Models\Pista;
use App\Models\Servicio;
use App\Models\Descuento;
use App\Models\Recibo;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Valor_campo_personalizado;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class ServiciosController extends Controller
{


    public function index(){

        $servicios= Servicio::all();

        return view('instalacion.configuraciones.servicios.index')->with(compact('servicios'));

    }

    public function create(){

        $servicios= Servicio::all();
        return view('instalacion.configuraciones.servicios.create')->with(compact('servicios'));


    }


    public function store(){
        $servicio= new Servicio();


        $servicio->nombre=request()->nombre;
        $servicio->tipo= request()->tipo;
        $servicio->precio= request()->precio;
        $servicio->duracion= request()->duracion;
        $servicio->tipo_espacio=request()->espacio;
        $servicio->reservas=request()->reserva;
        $servicio->formapago = request()->formapago;
        $servicio->descripcion=request()->contenido;
        $servicio->id_tipo_participante=request()->tipos_participante;
        $servicio->instalacion_id=auth()->user()->id_instalacion;

        if(request()->deporte!=0){

            $servicio->pista_id=request()->deporte;
        }

        $servicio->save();
        if(request()->descuento){
            foreach (request()->descuento as $id => $precio) {
                $descuento = new Descuento();
                $descuento->id_servicio_padre = $servicio->id;
                $descuento->id_servicio_descuento = $id;
                $descuento->nuevo_precio = $precio;
                $descuento->save();
            }
        }
        return redirect(route('servicios',auth()->user()->instalacion->slug))->with('success', 'Servicio creado correctamente');


    }

    public function edit(){
        $servicio= Servicio::find(request()->servicio);
        $servicios= Servicio::all();

        return view('instalacion.configuraciones.servicios.edit')->with(compact('servicio','servicios'));
    }



    public function update(){


        $servicio=Servicio::find(request()->servicio);

        $servicio->nombre=request()->nombre;
        $servicio->tipo= request()->tipo;
        $servicio->precio= request()->precio;
        $servicio->duracion= request()->duracion;
        $servicio->tipo_espacio=request()->espacio;
        $servicio->reservas=request()->reserva;
        $servicio->descripcion=request()->contenido;
        $servicio->formapago = request()->formapago;
        if(request()->deporte!=0){

            $servicio->pista_id=request()->deporte;
        }else{
            $servicio->pista_id=null;
        }

        $servicio->save();

        Descuento::where('id_servicio_padre', $servicio->id)->delete();
        if(request()->descuento){
            foreach (request()->descuento as $id => $precio) {
                $descuento = new Descuento();
                $descuento->id_servicio_padre = $servicio->id;
                $descuento->id_servicio_descuento = $id;
                $descuento->nuevo_precio = $precio;
                $descuento->save();
            }
        }

        return redirect(route('servicios',auth()->user()->instalacion->slug))->with('success', 'Servicio actualizado correctamente');

    }



    public function delete(){
        $servicio=Servicio::find(request()->servicio);


        $servicio->delete();
        return redirect(route('servicios',auth()->user()->instalacion->slug))->with('success', 'Servicio eliminado correctamente');


    }

    public function exportar_abonados(Request $request){
        $participantes = unserialize($request->participantes);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $columnas = ["Usuario Comprador", "Datos Abonado", "Evento Inscrito"];
        foreach ($columnas as $key => $columna) {
            $sheet->setCellValueByColumnAndRow($key+1, 1, $columna);
        }

        foreach ($participantes as $key => $participante) {
            $usuario_nombre = User::find($participante['id_usuario'])->name;
            $sheet->setCellValueByColumnAndRow(1, $key+2, $usuario_nombre);
            $campos_valor = Valor_campo_personalizado::where('id_participante', $participante['id'])->get(); 
            $datos_abonado = "";
            foreach ($campos_valor as $campo_valor) {
                
                $datos_abonado .= $campo_valor->campo->label . ": " . $campo_valor->valor . PHP_EOL;
            }
            $sheet->setCellValueByColumnAndRow(2, $key+2, $datos_abonado);
            $servicio = Servicio::find($participante['id_servicio']);
            $sheet->setCellValueByColumnAndRow(3, $key+2, $servicio->nombre);
        }


        $sheet->getStyle('A1:C'.($key+2))->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:C'.($key+2))->getAlignment()->setVertical('top');
        // change width
        $sheet->getColumnDimension('B')->setWidth(60);
        

        $writer = new Xlsx($spreadsheet);
        $writer->save('abonados.xlsx');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="abonados.xlsx"');
        header('Cache-Control: max-age=0');
        // utf8
        // $writer->setUseBOM(true);
        $writer->save('php://output');


    }


    public function deporte()
    {

        return Pista::select('id', 'nombre')->where('id_deporte', request()->deporte)->get();
    }


    public function contratar(){
        $servicio = Servicio::find(request()->servicio);


        return view ('servicio.reserva')->with(compact('servicio'));
    }

    public function contratar_de_nuevo(){
        $servicio = Servicio::find(request()->servicio);

        return view ('servicio.nuevo_reserva')->with(compact('servicio'));

    }

    public function renovarservicio(Request $request){
        $servicio = Servicio::find(request()->servicio);
        $recibo = Recibo::find($request->recibo);

        return view ('servicio.renovacion')->with(compact('servicio','recibo'));

    }






}
