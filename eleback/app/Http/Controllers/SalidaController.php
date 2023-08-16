<?php

namespace App\Http\Controllers;

use App\Models\Salida;
use App\Http\Requests\StoreSalidaRequest;
use App\Http\Requests\UpdateSalidaRequest;
use App\Models\Elemento;
use App\Models\Inventario;
use App\Models\Material;
use App\Models\Bodega;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SalidaController extends Controller
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

    public function listsalida(Request $request){
        return Salida::with('elementos')->with('tecnico')->whereDate('fecha',$request->fecha)->get();
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSalidaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSalidaRequest $request)
    {
        //
        //return $request->detalle[0];
        $salida=new Salida();
        $salida->destino=$request->destino;
        $salida->motivo=$request->motivo;
        $salida->carro=$request->carro;
        $salida->fecha=date('Y-m-d');
        $salida->hora=date('H:s:i');
        $salida->tecnico_id=$request->tecnico;
        $salida->user_id=$request->user_id;
        $salida->save();

        foreach ($request->detalle as $val) {
            # code...
            $inventario=Inventario::find($val['id']);
            if($inventario->cantidad>0){

            $elemento=new Elemento();
            $elemento->cantidad=$val['cant'];
            $elemento->material_id=$val['material_id'];
            $elemento->material=$val['material']['nombre'];
            $elemento->salida_id=$salida->id;
            $elemento->inventario_id=$val['id'];
            $elemento->save();

            $material=Material::find($val['material_id']);
            $material->stock=floatval($material->stock) - floatval($val['cant']);
            $material->save();

            $inventario->cantidad = floatval($inventario->cantidad) - floatval($val['cant']);
            if($inventario->cantidad<=0){
                $inventario->estado='AGOTADO';
            }
            $inventario->save();
            if($material->codificar=='SI'){
            $bodega=new Bodega;
            $bodega->material=$elemento->material;
            $bodega->cantidad=$elemento->cantidad;
            $bodega->saldo=$elemento->cantidad;
            $bodega->material_id=$elemento->material_id;
            $bodega->inventario_id=$elemento->inventario_id;
            $bodega->user_id=$salida->tecnico_id;
            $bodega->save();}
            else{
                $valida=Bodega::where('material_id',$elemento->material_id)->where('user_id',$salida->tecnico_id)->where('inventario_id',$elemento->inventario_id)->get();
                if(sizeof($valida)<=0 )
                {
                    $bodega=new Bodega;
                    $bodega->material=$elemento->material;
                    $bodega->cantidad=$elemento->cantidad;
                    $bodega->saldo=$elemento->cantidad;
                    $bodega->material_id=$elemento->material_id;
                    $bodega->inventario_id=$elemento->inventario_id;
                    $bodega->user_id=$salida->tecnico_id;
                    $bodega->save();
                }
                else{
                    $bodega=Bodega::where('material_id',$elemento->material_id)->where('user_id',$salida->tecnico_id)->where('inventario_id',$elemento->inventario_id)->first();
                    $bodega->cantidad=$bodega->cantidad + $elemento->cantidad;
                    $bodega->saldo=$bodega->saldo + $elemento->cantidad;
                    $bodega->estado='ACTIVO';
                    $bodega->save();

                }
            }
         }
        }

        return Salida::with('tecnico')->with('elementos')->where('id',$salida->id)->first();
    }

    public function reporteMaterial(Request $request){
        return DB::SELECT("select e.material_id,(select codigo from materials where id=e.material_id) codigo,sum(e.cantidad) cantidad
        ,e.material 
        from salidas s inner join elementos e on s.id=e.salida_id 
        where s.fecha>='".$request->ini."' and s.fecha<='".$request->fin."'
        GROUP by e.material_id,e.material
        order by codigo;");
    } 
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Salida  $salida
     * @return \Illuminate\Http\Response
     */
    public function show(Salida $salida)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Salida  $salida
     * @return \Illuminate\Http\Response
     */
    public function edit(Salida $salida)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSalidaRequest  $request
     * @param  \App\Models\Salida  $salida
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSalidaRequest $request, Salida $salida)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Salida  $salida
     * @return \Illuminate\Http\Response
     */
    public function destroy(Salida $salida)
    {
        //
    }
}
