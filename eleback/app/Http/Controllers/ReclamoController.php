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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreReclamoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReclamoRequest $request)
    {
        //
        if($request->persona['id']==''){
            $persona = new Persona;
            $persona->ci=$request->persona['ci'];
            $persona->nombre=$request->persona['nombre'];
            $persona->telefono=$request->persona['telefono'];
            $persona->distrito=$request->persona['distrito'];
            $persona->save();
        }
        else{
            $persona = Persona::find($request->persona['id']);
            $persona->nombre=$request->persona['nombre'];
            $persona->telefono=$request->persona['telefono'];
            $persona->distrito=$request->persona['distrito'];
            $persona->save();
        }

        $reclamo=new Reclamo();
        $reclamo->reclamo=$request->reclamo;
        $reclamo->fecha=$request->fecha;
        $reclamo->hora=$request->hora;
        $reclamo->persona_id=$persona->id;
        $reclamo->poste_id=$request->punto['id'];
        $reclamo->save();
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