<?php

namespace App\Http\Controllers;

use App\Models\Ordencompra;
use App\Models\Ordendetalle;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrdencompraRequest;
use App\Http\Requests\UpdateOrdencompraRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OrdencompraController extends Controller
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

    public function listOrden(Request $request){
        return Ordencompra::with('tienda')->with('ordendetalle')->wheredate('fecha','>=',$request->ini)->whereDate('fecha','<=',$request->fin)->get();
    }

    public function listUnid(){
        return DB::SELECT("SELECT distinct unidad from ordendetalles");

    }

    public function listMaterial(){
        return DB::SELECT("SELECT distinct material from ordendetalles");

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
     * @param  \App\Http\Requests\StoreOrdencompraRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrdencompraRequest $request)
    {
        //
        
        $orden = new Ordencompra;
        $orden->norden=$request->norden;
        $orden->fecha=$request->fecha;
        $orden->responsable=strtoupper($request->responsable);
        $orden->tienda_id=$request->tienda_id;
        $orden->user_id=$request->user()->id;
        $orden->save();

        foreach ($request->detalle as $d) {
            $det=new Ordendetalle;
            $det->cantidad=$d['cantidad'];
            $det->unidad=strtoupper($d['unidad']);
            $det->material=strtoupper($d['material']);
            $det->estado='PENDIENTE';
            $det->entregado=0;
            $det->ordencompra_id=$orden->id;
            $det->save();
        }
    }

    public function listResp(){
        return DB::SELECT("SELECT distinct responsable from ordencompras");
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ordencompra  $ordencompra
     * @return \Illuminate\Http\Response
     */
    public function show(Ordencompra $ordencompra)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ordencompra  $ordencompra
     * @return \Illuminate\Http\Response
     */
    public function edit(Ordencompra $ordencompra)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrdencompraRequest  $request
     * @param  \App\Models\Ordencompra  $ordencompra
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrdencompraRequest $request, Ordencompra $ordencompra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ordencompra  $ordencompra
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ordencompra $ordencompra)
    {
        //
    }
}
