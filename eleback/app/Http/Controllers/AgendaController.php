<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAgendaRequest;
use App\Http\Requests\UpdateAgendaRequest;
use Illuminate\Http\Request;

class AgendaController extends Controller
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

    public function listAgenda(Request $request){
        if($request->estado=='TODO')
            return Agenda::with('user')->whereDate('fecha','>=',$request->ini)->whereDate('fecha','<=',$request->fin)->get();
        else
            return Agenda::with('user')->where('estado',$request->estado)->whereDate('fecha','>=',$request->ini)->whereDate('fecha','<=',$request->fin)->get();

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
     * @param  \App\Http\Requests\StoreAgendaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAgendaRequest $request)
    {
        //
        $agenda= new Agenda;
        $agenda->distrito=strtoupper($request->distrito);
        $agenda->junta=strtoupper($request->junta);
        $agenda->dirigente=strtoupper($request->dirigente);
        $agenda->telefono=$request->telefono;
        $agenda->nueva=$request->nueva;
        $agenda->mtto=$request->mtto;
        $agenda->plantado=$request->plantado;
        $agenda->repot=$request->repot;
        $agenda->otros=$request->otros;
        $agenda->fecha=$request->fecha;
        $agenda->observacion=$request->observacion;
        $agenda->junta_id=$request->junta_id; 
        $agenda->save();
    }

    public function cambioFecha(Request $request){
        $agenda=Agenda::find($request->id);
        $agenda->fecha=$request->fecha;
        $agenda->save();
    }

    public function cambioEstado(Request $request){
        $agenda=Agenda::find($request->id);
        $agenda->estado=$request->estado;
        $agenda->save();
    }

    public function asignar(Request $request){
        $agenda=Agenda::find($request->id);
        $agenda->user_id=$request->user_id;
        $agenda->estado='EN PROCESO';
        $agenda->save();
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function show(Agenda $agenda)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function edit(Agenda $agenda)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAgendaRequest  $request
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAgendaRequest $request, Agenda $agenda)
    {
        //
        $agenda= Agenda::find($request->id);
        $agenda->distrito=strtoupper($request->distrito);
        $agenda->junta=strtoupper($request->junta);
        $agenda->dirigente=strtoupper($request->dirigente);
        $agenda->telefono=$request->telefono;
        $agenda->nueva=$request->nueva;
        $agenda->mtto=$request->mtto;
        $agenda->plantado=$request->plantado;
        $agenda->repot=$request->repot;
        $agenda->otros=$request->otros;
        $agenda->fecha=$request->fecha;
        $agenda->observacion=$request->observacion;
        $agenda->junta_id=$request->junta_id; 
        $agenda->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function destroy(Agenda $agenda)
    {
        //
    }
}
