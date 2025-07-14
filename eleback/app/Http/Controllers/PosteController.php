<?php

namespace App\Http\Controllers;

use App\Models\Poste;
use App\Models\Reclamo;
use App\Http\Requests\StorePosteRequest;
use App\Http\Requests\UpdatePosteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Http;

class PosteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Poste::where('estado','ACTIVO')->get();
    }

    public function listaposte(Request $request){
        return Poste::where('distrito',$request->distrito)->where('estado','ACTIVO')->get();
    }

    public function calcularArea(Request $request){
        $distancia=$request->distancia;
        $lat1=$request->lat;
        $lng1=$request->lng;
        $puntos=Poste::where('estado','ACTIVO')->get();
        //return $puntos;
        $enviar=array();
        foreach($puntos as $p){
            $dist=$this->getDistanceBetweenPointsNew($lat1,$lng1,$p->lat,$p->lng,'kilometers');
            //return  $dist;
            if($dist<=$distancia){
                array_push($enviar,$p);
            }
        }
        return $enviar;

   }
   function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2, $unit = 'kilometers') {
    $theta = $longitude1 - $longitude2;
    $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
    $distance = acos($distance);
    $distance = rad2deg($distance);
    $distance = $distance * 60 * 1.1515;
    switch($unit) {
      case 'miles':
        break;
      case 'kilometers' :
        $distance = $distance * 1.609344 *1000;
    }
    return (round($distance,2));
  }

    public function tecMant(){
        return DB::SELECT("SELECT u.id,u.name from reclamos r inner join users u on r.user_id=u.id
        where r.tipo='MTTO' and r.estado='EN ESPERA' group by u.id,u.name" );
      }
        public function listmtto($id){
            if($id==0)
                return Reclamo::with('poste')->where('tipo','MTTO')->where('estado','EN ESPERA')->get();
            else
                return Reclamo::where('user_id',$id)->with('poste')->where('tipo','MTTO')->where('estado','EN ESPERA')->get();
        }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePosteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePosteRequest $request)
    {
        //
        $poste=new Poste;
        $poste->lat=$request->lat;
        $poste->lng=$request->lng;
        $poste->luminaria=$request->luminaria;
        $poste->nroposte=$request->nroposte;
        $poste->material=$request->material;
        $poste->cantidad=$request->cantidad;
        $poste->altura=$request->altura;
        $poste->distrito=$request->distrito;
        $poste->potencia=$request->potencia;
        $poste->observacion=$request->observacion;
        $poste->color=$request->color;
        $poste->junta='';
        $poste->tipo='NUEVO';
        $poste->fechaplan=$request->fechaplan;
        $poste->estado='ACTIVO';
        $poste->brazo=$request->brazo;
        $poste->comentario=$request->comentario;
        $poste->revisado=$request->revisado;
        return $poste->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Poste  $poste
     * @return \Illuminate\Http\Response
     */
    public function show(Poste $poste)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Poste  $poste
     * @return \Illuminate\Http\Response
     */
    public function edit(Poste $poste)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePosteRequest  $request
     * @param  \App\Models\Poste  $poste
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePosteRequest $request, Poste $poste)
    {
        //
        $poste=Poste::find($request->id);
        $poste->estado=$request->estado;
        return $poste->save();
    }

    public function updateEstado($id)
    {
        //
        $poste=Poste::find($id);
        $poste->estado='INACTIVO';
        return $poste->save();
    }

    public function upPoste(Request $request)
    {
        //
        $poste=Poste::find($request->id);
        $poste->lat=$request->lat;
        $poste->lng=$request->lng;
        $poste->luminaria=$request->luminaria;
        $poste->nroposte=$request->nroposte;
        $poste->material=$request->material;
        $poste->cantidad=$request->cantidad;
        $poste->altura=$request->altura;
        $poste->distrito=$request->distrito;
        $poste->junta=$request->junta;
        $poste->potencia=$request->potencia;
        $poste->observacion=$request->observacion;
        $poste->color=$request->color;
        $poste->fechaplan=$request->fechaplan;
        $poste->brazo=$request->brazo;
        $poste->comentario=$request->comentario;
        $poste->revisado=$request->revisado;
        return $poste->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Poste  $poste
     * @return \Illuminate\Http\Response
     */
    public function destroy(Poste $poste)
    {
        //
    }

    public function buscarPoste($nro){
        return Poste::where('nroposte',$nro)->where('estado','ACTIVO')->get();
    }

    public function listplan(Request $request){
        $anio=date("Y",strtotime($request->fecha));
        $mes=date("m",strtotime($request->fecha));
        //return $mes;
        return Poste::
       whereYear('fechaplan',$anio)
       ->whereMonth('fechaplan',$mes)
       ->where('estado','ACTIVO')
        ->get();
    }

    public function reporteCantidad(Request $request){
        $res1= DB::SELECT("SELECT p.*,count(*) cantidad from postes p inner join reclamo r on r.poste_id=p.id
        where r.estado='ATENDIDO' and p.estado='ACTIVO' and r.fechaman>='$request->ini' and r.fechaman<='$request->fin' group by p.id order by cantidad desc ");
        return $res1;
    }


    public function report2(Request $request){
        return Poste::where('id',$request->id)->with('reclamos')->first();
    }


    public function prediccion($id)
        {    
        // URL de tu servidor Flask
        $url = "http://127.0.0.1:5000/predecir?id=".$id;

        try {
            $response = Http::get($url);

            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json([
                    'error' => 'Error desde Flask',
                    'status' => $response->status(),
                    'body' => $response->body()
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No se pudo conectar con Flask',
                'mensaje' => $e->getMessage()
            ], 500);
        }
        
    }
}
