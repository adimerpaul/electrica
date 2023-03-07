<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Poste;
use App\Models\Reclamo;
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

    public function buscarPersona($ci){
        return Persona::where('ci',$ci)->first();
    }

    public function buscarReclamos(Request $request){
        return Persona::with('reclamos')->where('ci',$request->ci)->first();
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
    public function consultaReclamo($ci){
        if(Persona::where('ci',$ci)->with('reclamos')->count()>0){
            $persona=Persona::where('ci',$ci)->first();
            return Reclamo::with('persona')->with('poste')->where('persona_id',$persona->id)->get();
        }else{
            if(Poste::where('nroposte',$ci)->count()>0)
            {$poste=Poste::where('nroposte',$ci)->first();
                return Reclamo::with('persona')->with('poste')->where('poste_id',$poste->id)->get();
            }
        }
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
