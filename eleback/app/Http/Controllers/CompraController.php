<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Contenido;
use App\Models\Material;
use App\Models\Tienda;
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
        $compra->nrovale=$request->nrovale;
        $compra->fechacompra=$request->fechacompra;
        $compra->fecha=$request->fecha;
        $gestion=date("Y",strtotime($request->fecha));
        $compra->gestion=$gestion;
        $compra->tienda_id= $request->tienda_id;
        $compra->save();

        $tienda=Tienda::find($request->tienda_id);
        foreach ($request->contenido as $c) {
            # code...
            $cont=new Contenido();
            $cont->compra_id=$compra->id;
            $cont->material_id=$c['material']['id'];
            $cont->cantidad=$c['cantidad'];
            $cont->save();
            $material=Material::find($c['material']['id']);
            $material->stock=$material->stock + $cont->cantidad;
            $material->save();
            if($material->codificar=='SI'){
            for ($i=0; $i < $c['cantidad']; $i++) {
                # code...
                $invent=Inventario::where('material_id',$material->id)->where('gestion',$gestion)->where('letra',$tienda->codigo)->get();
                if(sizeof($invent)>0){
                    $numero=intval(Inventario::where('material_id',$material->id)->where('gestion',$gestion)->where('letra',$tienda->codigo)->max('num')) + 1;}
                else{ $numero=1;}
                $inv=new Inventario();
                $inv->cantidad=1;
                $inv->codigo=$gestion.'-'.$material->codigo.'-'.$numero.$tienda->codigo;
                $inv->num=$numero;
                $inv->letra=$tienda->codigo;
                $inv->gestion=$gestion;
                $inv->material_id=$material->id;
                $inv->compra_id=$compra->id;
                $inv->save();
            }
            }
            else{
                $inv=Inventario::where('gestion',$gestion)->where('material_id',$material->id)->get();
                if(sizeof($inv)>0)
                    {$inv2=Inventario::where('gestion',$gestion)->where('material_id',$material->id)->orderBy('id','desc')->first();
                    $inv2->cantidad=intval($inv2->cantidad) + intval( $c['cantidad']);
                    $inv2->save();
                }
                else{
                $inv=new Inventario();
                $inv->cantidad=$c['cantidad'];
                $inv->codigo=$gestion.'-'.$material->codigo;
                $inv->num=0;
                $inv->letra=$tienda->codigo;
                $inv->gestion=$gestion;
                $inv->material_id=$material->id;
                $inv->estado='ACTIVO';
                $inv->save();
            }
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
        $compra=Compra::find($request->id);
        $compra->nrocompra=$request->nrocompra;
        $compra->nrovale=$request->nrovale;
        $compra->fechacompra=$request->fechacompra;
        $compra->save();
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
        $compra->delete();
    }
}
