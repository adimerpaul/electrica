<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Http\Requests\StoreInventarioRequest;
use App\Http\Requests\UpdateInventarioRequest;
use App\Models\Bodega;
use App\Models\Compra;
use App\Models\Material;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Intervention\Image\ImageManagerStatic as Image;
use Dompdf\Dompdf;

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

    public function generatePdf(Request $request){
        $data=[];
        foreach ($request->all() as $value) {
//            $png = '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($value['ci'], $generator::TYPE_CODE_128)) . '">';
            $png = QrCode::format('png')->size(250)->generate($value['codigo']);
            $png = base64_encode($png);
            $value['qr']=$png;
            $data[]=$value;
        }
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('generar', ['inventarios' => $data]);
        $pdf->setPaper('letter', 'P');
        $pdf->save('qrs.pdf')->stream();
    }
    public function qrFile(){
        return response()->file('qrs.pdf');
    }
    public function create()
    {
        //
    }

    public function recuperaInv($cod){
        return Inventario::with('material')->with('compra')->where('codigo',$cod)->first();
    }

    public function consultaInv($cod){
        return Inventario::with('material')->where('codigo',$cod)->where('estado','ACTIVO')->first();
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

    public function devolucionMaterial(Request $request){
        $inv=Inventario::where('codigo',$request->codigo)->where('estado','AGOTADO')->first();
        if(!$inv)
            return 'Error';

        $bodega=Bodega::where('inventario_id',$inv->id)->where('estado','ACTIVO')->first();
        if(!$bodega)
            return 'Error';

        $material=Material::where('id',$inv->material_id)->first();
        if(!$material)
            return 'error';

        $material->stock=$material->stock + $bodega->saldo;
        $inv->cantidad=$inv->cantidad + $bodega->saldo;
        $material->save();

        $bodega->saldo=0;
        $bodega->estado='DEVUELTO';
        $bodega->save();

        $inv->estado='ACTIVO';
        $inv->save();

    }
}
