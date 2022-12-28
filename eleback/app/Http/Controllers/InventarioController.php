<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Http\Requests\StoreInventarioRequest;
use App\Http\Requests\UpdateInventarioRequest;
use App\Models\Compra;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Inventario::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function buscarInv(Request $request ){
        if($request->compra!=null && $request->compra!=''){
        $compra=Compra::where('nrocompra',$request->compra)->get();
        if(sizeof($compra)>0)
            return Inventario::with('material')->with('compra')->where('compra_id',$compra[0]->id)->get();
        else
            return [];
        }
        else{
        return Inventario::with('material')->with('compra')->where('material_id',$request->material_id)->get();
        }
    }

    public function credencialPdf(Request $request){
        $data=[];
        $generator = new BarcodeGeneratorPNG();
        foreach ($request->lista as $value) {
//            $png = '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($value['ci'], $generator::TYPE_CODE_128)) . '">';
            $value['qr']=base64_encode($generator->getBarcode($value['codigo']==null?'123':$value['codigo'], $generator::TYPE_CODE_128));
            $data[]=$value;
        }
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('generar', ['inventarios' => $data]);
        $pdf->setPaper('letter', 'landscape');
        $pdf->save('inventarios.pdf')->stream();
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreInventarioRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInventarioRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function show(Inventario $inventario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function edit(Inventario $inventario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateInventarioRequest  $request
     * @param  \App\Models\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInventarioRequest $request, Inventario $inventario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inventario $inventario)
    {
        //
    }
}
