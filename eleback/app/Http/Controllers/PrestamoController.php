<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Tecnico;
use App\Models\Boxtool;
use App\Models\Tool;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePrestamoRequest;
use App\Http\Requests\UpdatePrestamoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Prestamo::with('prestamodetalle')->with('tecnico')->get();
    }

    public function listPrestamo(Request $request){
      return Prestamo::with('tecnico')->with('prestamodetalle')
         ->whereDate('fecha','>=',$request->ini)->whereDate('fecha','<=',$request->fin)->get();
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
     * @param  \App\Http\Requests\StorePrestamoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePrestamoRequest $request)
    {
        //
        $tecnico=Tecnico::find($request->tecnico_id);
        $prestamo= new Prestamo;
        $prestamo->unidad=$tecnico->unidad;
        $prestamo->destino=$request->destino;
        $prestamo->fecha=$request->fecha;
        $prestamo->foto=$request->foto;
        $prestamo->hora=date('H:i:s');
        $prestamo->tiempo=$request->dias;
        $prestamo->retorno=date('Y-m-d', strtotime($request->fecha. ' + '.$request->dias.' days'));
        $prestamo->codigo=$request->codigo;
        $prestamo->material=$request->material;
        $prestamo->estado='PRESTAMO';
        $prestamo->observacion=$request->observacion;
        $prestamo->tool_id=$request->tool_id;
        $prestamo->tecnico_id=$request->tecnico_id;
        $prestamo->user_id=$request->user()->id;
        $prestamo->save();

        $tool=Tool::find($request->tool_id);
        $tool->estado='PRESTAMO';
        $tool->save();

        $boxtool=Boxtool::find($tool->boxtool_id);
        $boxtool->disponible=$boxtool->disponible - 1;
        $boxtool->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Prestamo  $prestamo
     * @return \Illuminate\Http\Response
     */
    public function show(Prestamo $prestamo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prestamo  $prestamo
     * @return \Illuminate\Http\Response
     */
    public function edit(Prestamo $prestamo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePrestamoRequest  $request
     * @param  \App\Models\Prestamo  $prestamo
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePrestamoRequest $request, Prestamo $prestamo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prestamo  $prestamo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prestamo $prestamo)
    {
        //
    }
}
