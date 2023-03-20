<?php

namespace App\Http\Controllers;

use App\Models\Reclamo;
use App\Models\Persona;
use App\Models\Poste;
use App\Models\Detalle;
use App\Http\Requests\StoreReclamoRequest;
use App\Http\Requests\UpdateReclamoRequest;
use App\Models\Bodega;
use Illuminate\Http\Request;

use Mockery\Undefined;

class ReclamoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function reporteReclamo(Request $request){
        return Reclamo::with('persona')->with('poste')->where('estado','ATENDIDO')
        ->whereDate('fechaman','>=',$request->ini)
        ->whereDate('fechaman','<=',$request->fin)->get();
    }

    public function listAtencion(){
        return Reclamo::where('estado','EN ESPERA')->with('poste')->with('persona')->get();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreReclamoRequest  $request
     * @return \Illuminate\Http\Response
     */

     public function store(StoreReclamoRequest $request)
    {
        //
        //return $request->persona['id'];
        if($request->persona['id']==''){
            $persona = new Persona;
            $persona->ci=strtoupper($request->persona['ci']);
            $persona->nombre=strtoupper($request->persona['nombre']);
            $persona->telefono=$request->persona['telefono'];
            $persona->save();
        }
        else{
            $persona = Persona::find($request->persona['id']);
            $persona->nombre=strtoupper($request->persona['nombre']);
            $persona->telefono=$request->persona['telefono'];
            $persona->save();
        }

        if(Reclamo::where('poste_id',$request->punto['id'])->where('estado','EN ESPERA')->where('tipo','RECLAMO')->count()>0){
            return response()->json(['message' => 'Ya Existe un reclamo a este poste'], 400);
        }
        else{
        $reclamo=new Reclamo();
        $reclamo->reclamo=$request->reclamo;
        $reclamo->fecha=$request->fecha;
        $reclamo->hora=$request->hora;
        $reclamo->persona_id=$persona->id;
        $reclamo->poste_id=$request->punto['id'];
        $reclamo->save();

        return Reclamo::with('persona')->where('id',$reclamo->id)->first();
        }
    }

    public function matto(Request $request)
    {
        //
        //return $request->persona['id'];
        if(Reclamo::where('poste_id',$request->punto['id'])->where('estado','EN ESPERA')->where('tipo','MTTO')->count()>0){
            return response()->json(['message' => 'Ya Existe un reclamo a este poste'], 400);
        }
        else{
        $reclamo=new Reclamo();
        $reclamo->reclamo=$request->reclamo;
        $reclamo->fecha=$request->fecha;
        $reclamo->hora=$request->hora;
        $reclamo->tipo="MTTO";
        $reclamo->persona_id=null;
        $reclamo->poste_id=$request->punto['id'];
        $reclamo->save();
    }

    }

    public function registroMultiple(Request $request)
    {
        //
        //return $request->persona['id'];
        if($request->persona['id']==''){
            $persona = new Persona;
            $persona->ci=strtoupper($request->persona['ci']);
            $persona->nombre=strtoupper($request->persona['nombre']);
            $persona->telefono=$request->persona['telefono'];
            $persona->save();
        }
        else{
            $persona = Persona::find($request->persona['id']);
            $persona->nombre=strtoupper($request->persona['nombre']);
            $persona->telefono=$request->persona['telefono'];
            $persona->save();
        }

        foreach ($request->listado as $value) {
            # code...
            if(Reclamo::where('poste_id',$value['id'])->where('estado','EN ESPERA')->count()==0){

            $reclamo=new Reclamo();
            $reclamo->reclamo=$request->reclamo;
            $reclamo->fecha=$request->fecha;
            $reclamo->hora=$request->hora;
            $reclamo->persona_id=$persona->id;
            $reclamo->poste_id=$value['id'];
            $reclamo->save();
        }
        }
        return Reclamo::with('persona')->where('persona_id',$persona->id)->where('estado','EN ESPERA')->get();
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reclamo  $reclamo
     * @return \Illuminate\Http\Response
     */
    public function show(Reclamo $reclamo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reclamo  $reclamo
     * @return \Illuminate\Http\Response
     */
    public function edit(Reclamo $reclamo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateReclamoRequest  $request
     * @param  \App\Models\Reclamo  $reclamo
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateReclamoRequest $request, Reclamo $reclamo)
    {
        //
        $reclamo=Reclamo::find($request->id);
        $reclamo->fechaman=$request->fechaman;
        $reclamo->horaman=$request->horaman;
        $reclamo->estado=$request->estado;
        $reclamo->tecnico=$request->tecnico;
        $reclamo->supervisor=$request->supervisor;
        $reclamo->actividad=$request->actividad;
        $reclamo->l70=$request->l70;
        $reclamo->l150=$request->l150;
        $reclamo->l250=$request->l250;
        $reclamo->r70=$request->r70;
        $reclamo->r150=$request->r150;
        $reclamo->r250=$request->r250;
        $reclamo->foto=$request->foto;
        $reclamo->base=$request->base;
        $reclamo->ignitor=$request->ignitor;
        $reclamo->cable=$request->cable;
        $reclamo->contactor=$request->contactor;
        $reclamo->termico=$request->termico;
        $reclamo->soquet=$request->soquet;
        $reclamo->save();

        if($request->fechaplan!=null && $request->fechaplan!=''){
        $poste=Poste::find($reclamo->poste_id);
        $poste->fechaplan=$request->fechaplan;
        $poste->save();}
    }

    public function reclamofin(Request $request){
        //return $request->detalle[0]['cantidad'];
        $reclamo=Reclamo::find($request->punto['id']);
        $reclamo->fechaman=$request->punto['fechaman'];
        $reclamo->horaman=$request->punto['horaman'];
        $reclamo->estado='ATENDIDO';
        $reclamo->tecnico=$request->user()->name;
        //$reclamo->supervisor=$request->supervisor;
        $reclamo->actividad=$request->punto['actividad'];
        /*$reclamo->l70=$request->l70;
        $reclamo->l150=$request->l150;
        $reclamo->l250=$request->l250;
        $reclamo->r70=$request->r70;
        $reclamo->r150=$request->r150;
        $reclamo->r250=$request->r250;
        $reclamo->foto=$request->foto;
        $reclamo->base=$request->base;
        $reclamo->ignitor=$request->ignitor;
        $reclamo->cable=$request->cable;
        $reclamo->contactor=$request->contactor;
        $reclamo->termico=$request->termico;
        $reclamo->soquet=$request->soquet;*/
        $reclamo->save();

        foreach ($request->detalle as $d) {
            $detalle= new Detalle;
            $detalle->cantidad=$d['cantidad'];
            $detalle->material=$d['material'];
            $detalle->poste_id=$request->punto['id'];
            $detalle->reclamo_id=$reclamo->id;
            $detalle->bodega_id=$d['id'];
            $detalle->save();

            $bodega=Bodega::find($d['id']);
            $bodega->saldo= intval($bodega->saldo) - intval($d['cantidad']);
            if($bodega->saldo<=0)
                $bodega->estado='AGOTADO';
            $bodega->save();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reclamo  $reclamo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reclamo $reclamo)
    {
        //
    }
}
