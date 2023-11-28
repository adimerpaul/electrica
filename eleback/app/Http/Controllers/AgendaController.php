<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Agendadetalle;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAgendaRequest;
use App\Http\Requests\UpdateAgendaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            return Agenda::with('user')->with('agendadetalles')->whereDate('fecha','>=',$request->ini)->whereDate('fecha','<=',$request->fin)->get();
        else
            return Agenda::with('user')->with('agendadetalles')->where('estado',$request->estado)->whereDate('fecha','>=',$request->ini)->whereDate('fecha','<=',$request->fin)->get();

    }

    public function listRep(){
        return DB::SELECT("SELECT dirigente,cargo,telefono from agendas group by dirigente,cargo,telefono order by id desc");
    } 

    public function listJunta(){
        return DB::SELECT("SELECT junta,distrito from agendas group by junta,distrito order by id desc");
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
        $agenda->cargo=strtoupper($request->cargo);
        $agenda->telefono=$request->telefono;
        $agenda->fecha=date('Y-m-d');
        $agenda->observacion=$request->observacion;
        $agenda->usuario=$request->user()->name;
        $agenda->save();
    }

    public function cambioFecha(Request $request){
        $agenda=Agenda::find($request->id);
        $agenda->fechaprog=$request->fechaprog;
        $agenda->estado='EN PROCESO';
        $agenda->observacion=$request->observacion;
        $agenda->save();
    }

    public function cambioEstado(Request $request){
        $agenda=Agenda::find($request->id);
        $agenda->estado=$request->estado;
        $agenda->observacion=$request->observacion;
        $agenda->save();
    }

    public function asignar(Request $request){
        $agenda=Agenda::find($request->id);
        $agenda->user_id=$request->user_id;
        $agenda->estado='REALIZADO';
        $agenda->observacion=$request->observacion;
        $agenda->save();
    }

    public function regLuminiaria(Request $request){
        DB::SELECT("DELETE from agendadetalles where agenda_id=$request->agenda_id");

        foreach ($request->detalle as $r) {
            # code...
            $agendadetalle=new Agendadetalle;
            $agendadetalle->cantidad=$r['cantidad'];
            $agendadetalle->tipo=$r['tipo'];
            $agendadetalle->potencia=$r['potencia'];
            $agendadetalle->agenda_id=$request->agenda_id;
            $agendadetalle->save();

        }
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
        $agenda->cargo=strtoupper($request->cargo);
        $agenda->telefono=$request->telefono;
        $agenda->observacion=$request->observacion;
        $agenda->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
        //
        DB::SELECT("DELETE from agendadetalles where agenda_id=$id");
        $agenda=Agenda::find($id);
        $agenda->delete();
    }
}
