<?php

namespace App\Http\Controllers;

use App\Models\Ordendetalle;
use App\Models\Ordencompra;
use App\Models\Logdetalle;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrdendetalleRequest;
use App\Http\Requests\UpdateOrdendetalleRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OrdendetalleController extends Controller
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

    public function listDetalle(Request $request){
        return Ordendetalle::with('logdetalle')->with('ordencompra')->whereRelation('ordencompra', 'fecha','>=', $request->ini)->whereRelation('ordencompra', 'fecha','<=', $request->fin)->get();
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
     * @param  \App\Http\Requests\StoreOrdendetalleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrdendetalleRequest $request)
    {
        //
        $ordendetalle= Ordendetalle::find($request->ordendetalle_id);        
        $ordendetalle->entregado=$ordendetalle->entregado + $request->cantidad;
        if($ordendetalle->entregado>=$ordendetalle->cantidad)
            $ordendetalle->estado='COMPLETADO';
        $ordendetalle->save();

        $logdetalle=new Logdetalle;
        $logdetalle->fecha=date('Y-m-d');
        $logdetalle->cantidad=$request->cantidad;
        $logdetalle->vale=$request->vale;
        $logdetalle->obs=$request->obs;
        $logdetalle->ordendetalle_id=$request->ordendetalle_id;
        $logdetalle->user_id=$request->user()->id;
        $logdetalle->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ordendetalle  $ordendetalle
     * @return \Illuminate\Http\Response
     */
    public function show(Ordendetalle $ordendetalle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ordendetalle  $ordendetalle
     * @return \Illuminate\Http\Response
     */
    public function edit(Ordendetalle $ordendetalle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrdendetalleRequest  $request
     * @param  \App\Models\Ordendetalle  $ordendetalle
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrdendetalleRequest $request, Ordendetalle $ordendetalle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ordendetalle  $ordendetalle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ordendetalle $ordendetalle)
    {
        //
    }
}
