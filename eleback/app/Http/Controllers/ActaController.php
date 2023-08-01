<?php

namespace App\Http\Controllers;

use App\Models\Acta;
use App\Models\User;
use App\Http\Requests\StoreActaRequest;
use App\Http\Requests\UpdateActaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActaController extends Controller
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

    public function listActa(Request $request){
        return Acta::with('user')->whereDate('fecha','>=',$request->ini)->whereDate('fecha','<=',$request->fin)->get();
    }

    public function lugaresActa(){
        return DB::SELECT("SELECT distinct(lugar) from actas");
    }

    public function vecinoActa(){
        return DB::SELECT("SELECT distinct(vecino) from actas");
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
     * @param  \App\Http\Requests\StoreActaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreActaRequest $request)
    {
        //
        $acta=new Acta();
        $acta->fecha=$request->fecha;
        $acta->lugar=strtoupper($request->lugar);
        $acta->distrito=$request->distrito;
        $acta->tipo=$request->tipo;
        $acta->luminaria=$request->luminaria;
        $acta->cantidad=$request->cantidad;
        $acta->tecnico=strtoupper($request->tecnico);
        $acta->archivo=$request->archivo;
        $acta->vecino=strtoupper($request->vecino);
        $acta->observacion=$request->observacion;
        $acta->user_id=$request->user_id;
        $acta->save();
    }

    public function uparchivo(Request $request){

        $acta=Acta::find($request->id);
        $acta->archivo=$request->archivo;
        $acta->save();
     }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Acta  $acta
     * @return \Illuminate\Http\Response
     */
    public function show(Acta $acta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Acta  $acta
     * @return \Illuminate\Http\Response
     */
    public function edit(Acta $acta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateActaRequest  $request
     * @param  \App\Models\Acta  $acta
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateActaRequest $request, Acta $acta)
    {
        //
        $acta=Acta::find($request->id);
        $acta->fecha=$request->fecha;
        $acta->lugar=strtoupper($request->lugar);
        $acta->distrito=$request->distrito;
        $acta->tipo=$request->tipo;
        $acta->luminaria=$request->luminaria;
        $acta->cantidad=$request->cantidad;
        $acta->tecnico=strtoupper($request->tecnico);
        $acta->vecino=strtoupper($request->vecino);
        $acta->observacion=$request->observacion;
        $acta->user_id=$request->user_id;
        $acta->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Acta  $acta
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $acta=Acta::find($id);
        $acta->delete();
    }
}
