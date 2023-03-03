<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Contenido;
use App\Models\Material;
use App\Http\Requests\StoreCompraRequest;
use App\Http\Requests\UpdateCompraRequest;
use App\Models\Inventario;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Compra::with('contenidos')->with('tienda')->get();

    }

    public function valCompra($nro){
        return Compra::where('nrocompra',$nro)->get();
    }

    public function listCompra(Request $request){
        return Compra::with('contenidos')->with('tienda')->whereDate('fecha','>=',$request->ini)
        ->whereDate('fecha','<=',$request->fin)
        ->get();
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
     * @param  \App\Http\Requests\StoreCompraRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCompraRequest $request)
    {
        //
        //return $request->contenido[0]['material']['id'];
        /*if(Compra::where('nrocompra',$request->nrocompra)->count()>0)
            return false;
*/
        $compra=new Compra();
        $compra->nrocompra=$request->nrocompra;
        $compra->fecha=$request->fecha;
        $compra->tienda_id= $request->tienda_id;
        $compra->save();

        foreach ($request->contenido as $c) {
            # code...
            $cont=new Contenido();
            $cont->compra_id=$compra->id;
            $cont->material_id=$c['material']['id'];
            $cont->cantidad=$c['cantidad'];
            $cont->unitario=$c['unitario'];
            $cont->total=$c['total'];
            $cont->save();
            $material=Material::find($c['material']['id']);
            $material->stock=$material->stock + $cont->cantidad;
            $material->save();
            if($material->codificar=='SI'){
            for ($i=0; $i < $c['cantidad']; $i++) {
                # code...
                $invent=Inventario::where('material_id',$material->id)->get();
                if(sizeof($invent)>0){ $numero=intval(Inventario::where('material_id',$material->id)->max('num')) + 1;}
                else{ $numero=1;}
                $inv=new Inventario();
                $inv->cantidad=1;
                $inv->codigo=$compra->nrocompra.'-'.$material->codigo.'-'.$numero;
                $inv->num=$numero;
                $inv->material_id=$material->id;
                $inv->compra_id=$compra->id;
                $inv->save();
            }
            }
            else{
                $inv=new Inventario();
                $inv->cantidad=$c['cantidad'];
                $inv->codigo=$compra->nrocompra.'-'.$material->codigo;
                $inv->num=0;
                $inv->material_id=$material->id;
                $inv->compra_id=$compra->id;
                $inv->save();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function show(Compra $compra)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function edit(Compra $compra)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCompraRequest  $request
     * @param  \App\Models\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCompraRequest $request, Compra $compra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function destroy(Compra $compra)
    {
        //
    }
}
