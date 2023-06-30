<?php

namespace App\Http\Controllers;

use App\Models\Trabajo;
use App\Models\Trabajodetalle;
use App\Models\Bodega;
use App\Http\Requests\StoreTrabajoRequest;
use App\Http\Requests\UpdateTrabajoRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TrabajoController extends Controller
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

    public function listLugar(){
        return DB::SELECT("SELECT distinct(lugar) from trabajos");
    }

    public function listaTrabajos(Request $request){
        return Trabajo::with('user')->whereDate('fecha',$request->fecha)->where('user_id',$request->user()->id)->get();
    }

    public function reportJob(Request $request){
        if($request->id == 0 ){
            return Trabajo::with('user')->whereDate('fecha','>=',$request->ini)
            ->whereDate('fecha','<=',$request->fin)
            ->where('tipo',$request->tipo)
            ->get();
        }
        else{
            return Trabajo::with('user')->whereDate('fecha','>=',$request->ini)
            ->whereDate('fecha','<=',$request->fin)
            ->where('tipo',$request->tipo)
            ->where('user_id',$request->id)
            ->get();
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTrabajoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTrabajoRequest $request)
    {
        //
        $trabajo=new Trabajo();
        $trabajo->lat=$request->lat;
        $trabajo->lng=$request->lng;
        $trabajo->lugar=strtoupper($request->lugar);
        $trabajo->fecha=$request->fecha;
        $trabajo->hora=$request->hora;
        $trabajo->actividad=$request->actividad;
        $trabajo->tipo=$request->tipo;
        $trabajo->user_id=$request->user()->id;
        $trabajo->save();

        foreach ($request->detalle as $value) {
            # code...
            $bodega=Bodega::find($value['id']);
            if($bodega->saldo>0){
            $trabajodetalle= new Trabajodetalle();
            $trabajodetalle->cantidad=$value['uso'];
            $trabajodetalle->material=$value['material'];
            $trabajodetalle->trabajo_id=$trabajo->id;
            $trabajodetalle->bodega_id=$value['id'];
            $trabajodetalle->save();

            $bodega->saldo= intval($bodega->saldo) - intval($value['uso']);
            if($bodega->saldo<=0)
                $bodega->estado='AGOTADO';
            $bodega->save();
            }
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Trabajo  $trabajo
     * @return \Illuminate\Http\Response
     */
    public function show(Trabajo $trabajo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Trabajo  $trabajo
     * @return \Illuminate\Http\Response
     */
    public function edit(Trabajo $trabajo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTrabajoRequest  $request
     * @param  \App\Models\Trabajo  $trabajo
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTrabajoRequest $request, Trabajo $trabajo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Trabajo  $trabajo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trabajo $trabajo)
    {
        //
    }
}
