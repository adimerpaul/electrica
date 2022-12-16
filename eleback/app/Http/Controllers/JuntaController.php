<?php

namespace App\Http\Controllers;

use App\Models\Junta;
use App\Http\Requests\StoreJuntaRequest;
use App\Http\Requests\UpdateJuntaRequest;

class JuntaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Junta::all();
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
     * @param  \App\Http\Requests\StoreJuntaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreJuntaRequest $request)
    {
        //
        $junta= new Junta();
        $junta->nombre=$request->nombre;
        $junta->distrito=$request->distrito;
        $junta->zona=$request->zona;
        $junta->representante=$request->representante;
        $junta->celular=$request->celular;
        $junta->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Junta  $junta
     * @return \Illuminate\Http\Response
     */
    public function show(Junta $junta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Junta  $junta
     * @return \Illuminate\Http\Response
     */
    public function edit(Junta $junta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateJuntaRequest  $request
     * @param  \App\Models\Junta  $junta
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateJuntaRequest $request, Junta $junta)
    {
        //
        $junta=Junta::find($request->id);
        $junta->nombre=$request->nombre;
        $junta->distrito=$request->distrito;
        $junta->zona=$request->zona;
        $junta->representante=$request->representante;
        $junta->celular=$request->celular;
        $junta->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Junta  $junta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Junta $junta)
    {
        //
        $junta->delete();
    }
}
