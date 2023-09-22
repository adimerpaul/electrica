<?php

namespace App\Http\Controllers;

use App\Models\Tecnico;
use App\Http\Requests\StoreTecnicoRequest;
use App\Http\Requests\UpdateTecnicoRequest;

class TecnicoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Tecnico::all();
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
     * @param  \App\Http\Requests\StoreTecnicoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function listDep(){
        return DB::SELECT("SELECT distinct unidad from tecnicos");
    }

    public function store(StoreTecnicoRequest $request)
    {
        //
        $tecnico=new Tecnico();
        $tecnico->ci=strtoupper($request->ci);
        $tecnico->nombre=strtoupper($request->nombre);
        $tecnico->telefono=$request->telefono;
        $tecnico->unidad=strtoupper($request->unidad);
        $tecnico->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tecnico  $tecnico
     * @return \Illuminate\Http\Response
     */
    public function show(Tecnico $tecnico)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tecnico  $tecnico
     * @return \Illuminate\Http\Response
     */
    public function edit(Tecnico $tecnico)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTecnicoRequest  $request
     * @param  \App\Models\Tecnico  $tecnico
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTecnicoRequest $request, Tecnico $tecnico)
    {
        //
        $tecnico=Tecnico::find($request->id);
        $tecnico->ci=strtoupper($request->ci);
        $tecnico->nombre=strtoupper($request->nombre);
        $tecnico->telefono=$request->telefono;
        $tecnico->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tecnico  $tecnico
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tecnico $tecnico)
    {
        //
        $tecnico->delete();
    }
}
