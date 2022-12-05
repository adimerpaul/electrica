<?php

namespace App\Http\Controllers;

use App\Models\Reclamo;
use App\Models\Persona;
use App\Http\Requests\StoreReclamoRequest;
use App\Http\Requests\UpdateReclamoRequest;
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

        $reclamo=new Reclamo();
        $reclamo->reclamo=$request->reclamo;
        $reclamo->fecha=$request->fecha;
        $reclamo->hora=$request->hora;
        $reclamo->persona_id=$persona->id;
        $reclamo->poste_id=$request->punto['id'];
        $reclamo->save();

        return Reclamo::with('persona')->where('id',$reclamo->id)->first();
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
