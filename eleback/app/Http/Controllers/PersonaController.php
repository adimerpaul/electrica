<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdatePersonaRequest;
use Illuminate\Support\Facades\Request;
use PhpParser\Node\Expr\FuncCall;

class PersonaController extends Controller
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

    public function buscarPersona(Request $request){
        return Persona::where('ci',$request->ci)->first();
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
     * @param  \App\Http\Requests\StorePersonaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePersonaRequest $request)
    {
        //
        $persona=new Persona();
        $persona->nombre=strtoupper($request->nombre);
        $persona->ci=$request->ci;
        $persona->telefono=$request->telefono;
        $persona->direccion=$request->direccion;
        $persona->zona=$request->zona;
        $persona->distrito=$request->distrito;
        $persona->junta_id=$request->junta_id;
        $persona->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Persona  $persona
     * @return \Illuminate\Http\Response
     */
    public function show(Persona $persona)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Persona  $persona
     * @return \Illuminate\Http\Response
     */
    public function edit(Persona $persona)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePersonaRequest  $request
     * @param  \App\Models\Persona  $persona
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePersonaRequest $request, Persona $persona)
    {
        //
        $persona=Persona::find($request->id);
        $persona->nombre=strtoupper($request->nombre);
        $persona->telefono=$request->telefono;
        $persona->direccion=$request->direccion;
        $persona->zona=$request->zona;
        $persona->distrito=$request->distrito;
        $persona->junta_id=$request->junta_id;
        $persona->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Persona  $persona
     * @return \Illuminate\Http\Response
     */
    public function destroy(Persona $persona)
    {
        //
    }
}
