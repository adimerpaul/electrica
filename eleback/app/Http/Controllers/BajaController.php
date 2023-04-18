<?php

namespace App\Http\Controllers;

use App\Models\Baja;
use App\Models\Inventario;
use App\Models\Material;
use App\Http\Requests\StoreBajaRequest;
use App\Http\Requests\UpdateBajaRequest;
use Illuminate\Http\Request;

class BajaController extends Controller
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBajaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBajaRequest $request)
    {
        //
    }

    public function bajaInv(Request $request){
        $fec=date('Y-m-d');
        $hora=date('H:i:s');
        //return $request;
        foreach ($request->datos as $value) {
            $inv=Inventario::find($value['id']);
            if($inv->estado=='ACTIVO'){
            $baja=new Baja;
            $baja->motivo=$request->motivo;
            $baja->fecha=$fec;
            $baja->hora=$hora;
            $baja->inventario_id=$value['id'];
            $baja->save();
            //
            $inv->estado='INACTIVO';
            $inv->save();
            //
            $material=Material::find($inv->material_id);
            $material->stock = $material->stock - $inv->cantidad;
            $material->save();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Baja  $baja
     * @return \Illuminate\Http\Response
     */
    public function show(Baja $baja)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Baja  $baja
     * @return \Illuminate\Http\Response
     */
    public function edit(Baja $baja)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBajaRequest  $request
     * @param  \App\Models\Baja  $baja
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBajaRequest $request, Baja $baja)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Baja  $baja
     * @return \Illuminate\Http\Response
     */
    public function destroy(Baja $baja)
    {
        //
    }
}
